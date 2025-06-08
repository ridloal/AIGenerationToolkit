@extends('layouts.admin')

@section('title', 'Project Assets')
@section('page_title', 'Assets for: ' . $project->channel_name_final)

@push('styles')
<style>
    .asset-card { display: flex; flex-direction: column; height: 100%; }
    .asset-card-body { flex-grow: 1; }
    .asset-preview { max-height: 120px; object-fit: contain; margin: auto; display: block; }
    .asset-placeholder { width: 100%; height: 120px; background-color: #f8fafc; border: 2px dashed #e5e7eb; display: flex; align-items: center; justify-content: center; color: #9ca3af; }
    .ai-btn-container { position: relative; }
    .btn-ai-enhance { position: absolute; bottom: 10px; right: 10px; }
    /* AI Popup Styles */
    .ai-tooltip-popup { display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 500px; background-color: white; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 1050; padding: 20px; }
    .ai-tooltip-popup .form-group { margin-bottom: 1rem; }
    .ai-tooltip-popup-backdrop { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1040; }
</style>
@endpush

@section('content')
<div class="row g-4">
    {{-- Logo Card --}}
    <div class="col-lg-6">
        <div class="card asset-card">
            <div class="card-header">
                <h3 class="card-title">Channel Logo</h3>
            </div>
            <div class="card-body asset-card-body text-center">
                @if ($logo)
                    <img src="{{ Storage::url($logo->file_path) }}" alt="Channel Logo" class="asset-preview rounded mb-3">
                    <p class="text-muted">{{ $logo->file_name }} ({{ number_format($logo->size / 1024, 2) }} KB)</p>
                    <div class="d-flex justify-content-center">
                        <a href="{{ Storage::url($logo->file_path) }}" download="{{ $logo->file_name }}" class="btn btn-outline-primary me-2">Download</a>
                        <form action="{{ route('projects.assets.destroy', $logo) }}" method="POST" onsubmit="return confirm('Delete this logo?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">Delete</button>
                        </form>
                    </div>
                @else
                    <div class="asset-placeholder rounded mb-3"><span>No Logo Uploaded</span></div>
                    <form action="{{ route('projects.assets.store', $project) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="asset_type" value="logo">
                        <div class="input-group">
                            <input type="file" name="asset_file" class="form-control" required>
                            <button class="btn btn-primary" type="submit">Upload</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    {{-- Banner Card --}}
    <div class="col-lg-6">
        <div class="card asset-card">
            <div class="card-header">
                <h3 class="card-title">Channel Banner</h3>
            </div>
            <div class="card-body asset-card-body text-center">
                @if ($banner)
                    <img src="{{ Storage::url($banner->file_path) }}" alt="Channel Banner" class="asset-preview rounded mb-3">
                    <p class="text-muted">{{ $banner->file_name }} ({{ number_format($banner->size / 1024, 2) }} KB)</p>
                     <div class="d-flex justify-content-center">
                        <a href="{{ Storage::url($banner->file_path) }}" download="{{ $banner->file_name }}" class="btn btn-outline-primary me-2">Download</a>
                        <form action="{{ route('projects.assets.destroy', $banner) }}" method="POST" onsubmit="return confirm('Delete this banner?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">Delete</button>
                        </form>
                    </div>
                @else
                    <div class="asset-placeholder rounded mb-3"><span>No Banner Uploaded</span></div>
                     <form action="{{ route('projects.assets.store', $project) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="asset_type" value="banner">
                        <div class="input-group">
                            <input type="file" name="asset_file" class="form-control" required>
                            <button class="btn btn-primary" type="submit">Upload</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    {{-- AI Prompt Generation Card --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">AI Prompt Generation for Visuals</h3>
                <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
                    Back to Project List
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6 mb-3 mb-lg-0">
                        <label for="logo-prompt" class="form-label">Logo Prompt</label>
                        <div class="ai-btn-container">
                            <textarea id="logo-prompt" class="form-control" rows="5">{{ $project->logo_concept ?? 'A minimalist logo for this channel.' }}</textarea>
                            <button type="button" class="btn btn-secondary btn-ai-enhance" data-target="logo-prompt" 
                                    data-prompt-template="You are an expert prompt engineer for an image generation AI like Midjourney or DALL-E. Take the following basic idea for a LOGO and enhance it into a detailed, high-quality prompt. Add details about style (e.g., minimalist, vector, flat icon), composition, color palette, and mood. The original idea is: ">
                                Enhance Prompt
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <label for="banner-prompt" class="form-label">Banner Prompt</label>
                        <div class="ai-btn-container">
                            <textarea id="banner-prompt" class="form-control" rows="5">{{ $project->banner_concept ?? $project->logo_concept ?? 'A YouTube channel banner for a tech channel named [Channel Name]. The tagline is "[Tagline]".' }}</textarea>
                             <button type="button" class="btn btn-secondary btn-ai-enhance" data-target="banner-prompt"
                                    data-prompt-template="You are an expert prompt engineer for an image generation AI. Take the following basic idea for a YOUTUBE BANNER and enhance it into a detailed, high-quality prompt. Add details about visual elements, composition, style (e.g., futuristic, abstract, clean), typography, and color scheme. The original idea is: ">
                                Enhance Prompt
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Other Assets Card --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Other Project Assets</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('projects.assets.store', $project) }}" method="POST" enctype="multipart/form-data" class="mb-4">
                    @csrf
                    <input type="hidden" name="asset_type" value="other">
                    <label class="form-label">Upload New Asset</label>
                    <div class="input-group">
                        <input type="file" name="asset_file" class="form-control" required>
                        <button class="btn btn-primary" type="submit">Upload</button>
                    </div>
                </form>

                @if ($otherAssets->isEmpty())
                    <p class="text-center text-muted">No other assets have been uploaded yet.</p>
                @else
                    <div class="list-group">
                        @foreach ($otherAssets as $asset)
                            <div class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        {{-- Icon for file type --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /></svg>
                                    </div>
                                    <div class="col text-truncate">
                                        <div class="text-body d-block">{{ $asset->file_name }}</div>
                                        <div class="text-muted text-truncate mt-n1">{{ number_format($asset->size / 1024, 2) }} KB - {{ $asset->created_at->format('d M Y') }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <a href="{{ Storage::url($asset->file_path) }}" download="{{ $asset->file_name }}" class="btn btn-sm btn-outline-primary me-2">Download</a>
                                        <form class="d-inline" action="{{ route('projects.assets.destroy', $asset) }}" method="POST" onsubmit="return confirm('Delete this asset?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- AI Popup --}}
<div class="ai-tooltip-popup-backdrop" id="aiBackdrop"></div>
<div class="ai-tooltip-popup" id="aiPopup">
    <h4 id="aiPopupTitle">AI Assistant</h4>
    <div class="form-group">
        <label for="aiPrompt" class="form-label">Final Prompt for AI</label>
        <textarea id="aiPrompt" class="form-control" rows="8"></textarea>
    </div>
    <div class="d-flex justify-content-end mt-3">
        <button type="button" class="btn btn-secondary me-2" id="aiCancelBtn">Cancel</button>
        <button type="button" class="btn btn-primary" id="aiSubmitBtn">
            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true" id="aiSpinner"></span>
            Generate
        </button>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // --- Re-usable AI Popup Logic ---
    const aiPopup = document.getElementById('aiPopup');
    const aiBackdrop = document.getElementById('aiBackdrop');
    const aiPromptTextarea = document.getElementById('aiPrompt');
    const aiSubmitBtn = document.getElementById('aiSubmitBtn');
    const aiCancelBtn = document.getElementById('aiCancelBtn');
    const aiSpinner = document.getElementById('aiSpinner');
    let currentTargetId = null;

    function openAiPopup(finalPrompt, targetId) {
        currentTargetId = targetId;
        aiPromptTextarea.value = finalPrompt;
        aiPopup.style.display = 'block';
        aiBackdrop.style.display = 'block';
    }

    function closeAiPopup() {
        aiPopup.style.display = 'none';
        aiBackdrop.style.display = 'none';
        aiSpinner.classList.add('d-none');
        aiSubmitBtn.disabled = false;
    }

    aiCancelBtn.addEventListener('click', closeAiPopup);
    aiBackdrop.addEventListener('click', closeAiPopup);

    aiSubmitBtn.addEventListener('click', function() {
        if (!currentTargetId) return;

        aiSpinner.classList.remove('d-none');
        this.disabled = true;

        // The context is now built into the prompt itself by the 'Enhance' button logic
        // We can send a minimal context or none at all.
        const context = `Project Name: {{ $project->channel_name_final }}`;

        fetch('{{ route("ai.generate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                prompt: aiPromptTextarea.value,
                context: context
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(currentTargetId).value = data.text;
                closeAiPopup();
            } else {
                alert('Error: ' + (data.message || 'Unknown error'));
                closeAiPopup();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An unexpected error occurred.');
            closeAiPopup();
        });
    });

    // --- Enhance Button Logic ---
    document.querySelectorAll('.btn-ai-enhance').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.dataset.target;
            const promptTemplate = this.dataset.promptTemplate;
            const originalPrompt = document.getElementById(targetId).value;

            // Combine the template with the user's current text
            const finalPrompt = promptTemplate + `"${originalPrompt}"`;

            openAiPopup(finalPrompt, targetId);
        });
    });
});
</script>
@endpush

@endsection
