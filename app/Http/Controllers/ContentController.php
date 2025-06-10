<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContentController extends Controller
{
    /**
     * Display a listing of the content for a project.
     */
    public function index(Project $project)
    {
        $this->authorizeOwnership($project);
        $contents = $project->contents()->latest()->paginate(10);
        return view('contents.index', compact('project', 'contents'));
    }

    /**
     * Show the form for creating new content.
     */
    public function create(Project $project)
    {
        $this->authorizeOwnership($project);
        return view('contents.create', compact('project'));
    }

    /**
     * Store a newly created content resource in storage.
     */
    public function store(Request $request, Project $project)
    {
        $this->authorizeOwnership($project);
        $request->validate([
            'title' => 'required|string|max:255',
            'video_format' => 'required|in:long,short',
        ]);

        // Use ->all() to capture all submitted form data, including dynamic fields
        $data = $request->all();
        $data['user_id'] = Auth::id();

        // The model is unguarded, so it will correctly map all fields
        $project->contents()->create($data);

        return redirect()->route('projects.contents.index', $project)->with('success', 'Content plan created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Content $content)
    {
        $this->authorizeOwnership($content->project);
        $project = $content->project;
        return view('contents.edit', compact('project', 'content'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Content $content)
    {
        $this->authorizeOwnership($content->project);
        $request->validate([
            'title' => 'required|string|max:255',
            'video_format' => 'required|in:long,short',
        ]);

        // Use ->all() to capture all submitted form data
        $data = $request->all();

        // The update method will map all provided fields
        $content->update($data);
        
        return redirect()->route('projects.contents.index', $content->project)->with('success', 'Content plan updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Content $content)
    {
        $this->authorizeOwnership($content->project);
        $project = $content->project;
        $content->delete();

        return redirect()->route('projects.contents.index', $project)->with('success', 'Content plan deleted successfully!');
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
