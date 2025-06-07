<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ProjectController extends Controller
{
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
            // Ini adalah contoh prompt yang lebih terstruktur
            $fullPrompt = "Berdasarkan konteks proyek berikut:\n---" .
                          $request->input('context', 'Tidak ada konteks tambahan.') .
                          "\n---\n\n" .
                          "Tolong generate konten untuk permintaan ini:\n" .
                          $request->input('prompt');
            
            // DUMMY GEMINI API CALL
            // Di aplikasi nyata, Anda akan mengganti ini dengan panggilan API sesungguhnya.
            $responseText = "Ini adalah contoh respons yang dihasilkan oleh AI untuk prompt: '" . $request->input('prompt') . "'. Respons ini dibuat berdasarkan konteks yang Anda berikan untuk memastikan relevansi dan kualitas.";
            
            // Simulasi delay agar terlihat seperti panggilan API
            sleep(1); 

            return response()->json(['success' => true, 'text' => $responseText]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghasilkan konten: ' . $e->getMessage()], 500);
        }
    }
}
