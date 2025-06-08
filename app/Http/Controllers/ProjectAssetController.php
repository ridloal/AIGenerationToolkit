<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // Import the Str class

class ProjectAssetController extends Controller
{
    /**
     * Display the assets page for a specific project.
     */
    public function index(Project $project)
    {
        $this->authorizeOwnership($project);

        $logo = $project->assets()->where('type', 'logo')->first();
        $banner = $project->assets()->where('type', 'banner')->first();
        $otherAssets = $project->assets()->where('type', 'other')->latest()->get();

        return view('projects.assets', compact('project', 'logo', 'banner', 'otherAssets'));
    }

    /**
     * Store a newly uploaded file for a project.
     */
    public function store(Request $request, Project $project)
    {
        $this->authorizeOwnership($project);

        $request->validate([
            'asset_file' => 'required|file|max:5120', // Max 5MB
            'asset_type' => 'required|in:logo,banner,other',
        ]);

        $file = $request->file('asset_file');
        $type = $request->input('asset_type');
        
        // --- FILENAME LOGIC ---
        $prefix = Str::slug($project->channel_name_final);
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        
        $newFileName = match ($type) {
            'logo' => "{$prefix}_logo.{$extension}",
            'banner' => "{$prefix}_banner.{$extension}",
            'other' => "{$prefix}_{$originalName}",
        };
        // --- END FILENAME LOGIC ---

        // Use storeAs to specify the filename
        $path = $file->storeAs("projects/{$project->id}/assets", $newFileName, 'public');

        // If uploading a logo or banner, delete the old one first
        if ($type === 'logo' || $type === 'banner') {
            $existingAsset = $project->assets()->where('type', 'type')->first();
            if ($existingAsset) {
                Storage::disk('public')->delete($existingAsset->file_path);
                $existingAsset->delete();
            }
        }

        ProjectAsset::create([
            'project_id' => $project->id,
            'user_id' => Auth::id(),
            'type' => $type,
            'file_name' => $newFileName, // Save the new filename
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);

        return redirect()->back()->with('success', ucfirst($type) . ' uploaded successfully!');
    }

    /**
     * Remove the specified asset from storage and database.
     */
    public function destroy(ProjectAsset $asset)
    {
        $this->authorizeOwnership($asset->project);

        Storage::disk('public')->delete($asset->file_path);
        $asset->delete();

        return redirect()->back()->with('success', 'Asset deleted successfully!');
    }
    
    /**
     * Helper to authorize that the user owns the project.
     */
    private function authorizeOwnership(Project $project)
    {
        if ($project->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}
