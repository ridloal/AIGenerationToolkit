<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Services\AiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ProjectController extends Controller
{
    public function __construct(private AiService $aiService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Auth::user()->projects()->latest()->paginate(10);
        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'channel_name_final' => 'required|string|max:255',
        ]);

        // Mengambil semua data dari request kecuali token CSRF
        $data = $request->except('_token');
        $data['user_id'] = Auth::id();

        // Laravel's model casting akan otomatis mengubah field array menjadi JSON
        $project = Project::create($data);

        // Nonaktifkan semua proyek lain milik user ini
        Auth::user()->projects()->where('id', '!=', $project->id)->update(['is_active' => false]);
        
        // Aktifkan proyek yang baru dibuat
        $project->update(['is_active' => true]);

        return redirect()->route('dashboard')->with('success', 'Proyek berhasil dibuat dan diaktifkan!');
    }

    /**
     * Display the specified resource.
     * Kita akan gunakan ini sebagai halaman detail nanti. Untuk sekarang, arahkan ke edit.
     */
    public function show(Project $project)
    {
        if ($project->user_id !== Auth::id()) abort(403);
        // Untuk saat ini, kita bisa langsung arahkan ke halaman edit.
        // Nanti bisa dibuat halaman detail terpisah.
        return redirect()->route('projects.edit', $project);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        if ($project->user_id !== Auth::id()) abort(403);
        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        if ($project->user_id !== Auth::id()) abort(403);
        $request->validate(['channel_name_final' => 'required|string|max:255']);
        $data = $request->except(['_token', '_method']);
        $project->update($data);
        return redirect()->route('projects.index')->with('success', 'Proyek berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        if ($project->user_id !== Auth::id()) abort(403);
        $projectName = $project->channel_name_final;
        $project->delete();
        return redirect()->route('projects.index')->with('success', "Proyek '{$projectName}' berhasil dihapus.");
    }

    /**
     * Set a project as active for the current user.
     */
    public function activate(Project $project)
    {
        // Pastikan user adalah pemilik proyek
        if ($project->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Nonaktifkan semua proyek lain
        Auth::user()->projects()->update(['is_active' => false]);

        // Aktifkan proyek yang dipilih
        $project->update(['is_active' => true]);

        return redirect()->back()->with('success', "Proyek '{$project->channel_name_final}' sekarang aktif.");
    }

    /**
     * Handle AI content generation request.
     */
    public function generate(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string',
            'context' => 'nullable|string',
        ]);

        try {
            $responseText = $this->aiService->generateText(
                $request->input('prompt'),
                $request->input('context', 'No additional context.')
            );
            return response()->json(['success' => true, 'text' => $responseText]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Generate dynamic suggestions for content creation form fields.
     */
    public function generateSuggestions(Project $project)
    {
        $this->authorizeOwnership($project);

        $context = "Channel Name: " . $project->channel_name_final . "\n" .
                   "Channel Description: " . $project->channel_description . "\n" .
                   "Primary Audience: " . $project->primary_audience_persona . "\n" .
                   "Content Pillars: " . $project->content_pillars . "\n" .
                   "Channel Missions: " . $project->channel_mission . "\n" .
                   "Unique Selling Proposition: " . $project->unique_selling_proposition;

        $prompt = "Based on the provided project context, generate suggestions for a new video content plan. I need suggestions for two specific fields:\n" .
                  "1. 'content_pillars': Generate 5 potential content pillar names this video could fall under. its possible defined in content pillars above please create summary in point\n" .
                  "2. 'main_goals': Generate 5 potential primary goals for a video on this channel.\n\n" .
                  "IMPORTANT: Return the result ONLY as a valid JSON object with two keys: 'pillars' and 'goals'. Each key should contain an array of strings. Example: {\"pillars\":[\"AI for Productivity\",\"AI Tool Reviews\"],\"goals\":[\"Get 100 subscribers\",\"Drive traffic to a blog post\"]}";

        try {
            $rawResponse = $this->aiService->generateText($prompt, $context);
            // Clean the response to ensure it's valid JSON
            $cleanedJson = preg_replace('/```json\s*|\s*```/', '', $rawResponse);
            $suggestions = json_decode($cleanedJson, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                // Fallback if AI doesn't return valid JSON
                throw new \Exception("AI returned an invalid format.");
            }
            
            return response()->json($suggestions);

        } catch (\Exception $e) {
            // Provide a generic fallback on error
            return response()->json([
                'pillars' => ['AI Tool Reviews', 'Productivity Hacks', 'AI Experiments'],
                'goals' => ['Increase Subscribers', 'Drive Website Traffic', 'Promote an Affiliate Product', 'Build Community Engagement']
            ]);
        }
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