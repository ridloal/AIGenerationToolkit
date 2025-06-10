@extends('layouts.admin')

@section('title', 'Edit Content Plan')
@section('page_title', 'Edit Content: ' . Str::limit($content->title, 40))

@push('styles')
    {{-- All styles are identical to create.blade.php --}}
<style>
    .form-label-container { display: flex; align-items: center; margin-bottom: .5rem; }
    .form-label-container .form-label { margin-bottom: 0; }
    .ai-buttons { margin-left: auto; display: flex; gap: .5rem; }
    .ai-buttons .btn { padding: .1rem .3rem; line-height: 1; }
    .structured-item { border-left: 3px solid #dee2e6; padding-left: 1rem; margin-bottom: 1rem; }
    .structured-item .form-label { font-weight: 600; }
    .structured-item-header { display: flex; justify-content: space-between; align-items: center; }
    .ai-tooltip-popup { display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 500px; background-color: white; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 1050; padding: 20px; }
    .ai-tooltip-popup-backdrop { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1040; }
</style>
@endpush

@section('content')
<form action="{{ route('contents.update', $content) }}" method="POST" id="content-form">
    @csrf
    @method('PATCH') {{-- Use PATCH method for updates --}}
    
    {{-- The entire form structure is the same as create.blade.php. --}}
    {{-- The key difference is populating the `value` attributes and text areas. --}}

    <div class="row g-4">
        {{-- Column 1: Main Form --}}
        <div class="col-lg-8">
            <div class="card mb-4" id="core-details-card">
                <div class="card-header"><h3 class="card-title">Step 1: Core Video Details</h3></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="title" class="form-label required">Video Title</label>
                        <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $content->title) }}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-label-container"><label class="form-label required">Video Format</label></div>
                            <select name="video_format" class="form-select">
                                <option value="long" @selected(old('video_format', $content->video_format) == 'long')>Long Form</option>
                                <option value="short" @selected(old('video_format', $content->video_format) == 'short')>Short</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-label-container"><label for="content_pillar" class="form-label">Content Pillar</label>{{-- AI Buttons --}}</div>
                            <input type="text" id="content_pillar" name="content_pillar" class="form-control" value="{{ old('content_pillar', $content->content_pillar) }}" list="pillar-suggestions">
                            <datalist id="pillar-suggestions"></datalist>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-label-container"><label for="main_goal" class="form-label">Primary Goal</label>{{-- AI Buttons --}}</div>
                        <input type="text" name="main_goal" id="main_goal" class="form-control" value="{{ old('main_goal', $content->main_goal) }}" list="goal-suggestions">
                        <datalist id="goal-suggestions"></datalist>
                    </div>
                    <div class="mb-3">
                        <div class="form-label-container"><label for="target_keywords" class="form-label">Target Keywords</label>{{-- AI Buttons --}}</div>
                        <input type="text" name="target_keywords" id="target_keywords" class="form-control" value="{{ old('target_keywords', $content->target_keywords) }}">
                    </div>
                    <div class="mb-3"><label for="reference_info" class="form-label">References</label><textarea name="reference_info" id="reference_info" rows="3" class="form-control">{{ old('reference_info', $content->reference_info) }}</textarea></div>
                </div>
            </div>

            {{-- Script & Asset Planning Card --}}
            <div class="card mt-4">
                 <div class="card-header"><h3 class="card-title">Step 2: Script & Asset Planning</h3></div>
                <div class="card-body">
                    {{-- SCRIPT OUTLINE SECTION --}}
                    <div class="mb-4">
                        <div class="form-label-container"><label class="form-label h4">Script Outline</label><div class="ai-buttons">{{-- AI Button --}}</div></div>
                        <div id="structured-script-container" class="mt-2"> <p class="text-muted text-center">Generate a script outline or load existing.</p> </div>
                        <textarea name="script_outline" id="script_outline_hidden" style="display: none;">{{ old('script_outline', $content->script_outline) }}</textarea>
                    </div>
                    <hr>
                    {{-- VISUAL ASSETS SECTION --}}
                    <div class="mt-4">
                        <div class="form-label-container"><label class="form-label h4">Visual Assets Needed</label><div class="ai-buttons">{{-- AI Button --}}</div></div>
                        <div id="structured-visuals-container" class="mt-2"> <p class="text-muted text-center">Generate a visual plan or load existing.</p> </div>
                        <textarea name="visual_assets_needed" id="visual_assets_needed_hidden" style="display: none;">{{ old('visual_assets_needed', $content->visual_assets_needed) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- YouTube Optimization Card --}}
            <div class="card mt-4">
                 <div class="card-header"><h3 class="card-title">Step 3: YouTube Optimization</h3></div>
                 <div class="card-body">
                    <div class="mb-3">
                        <div class="form-label-container"><label for="youtube_description" class="form-label">YouTube Description</label><div class="ai-buttons"><button type="button" class="btn btn-icon btn-outline-secondary btn-ai" data-bs-toggle="tooltip" title="Enhance" data-target="youtube_description" data-type="enhance" data-prompt-template="You are a YouTube SEO expert. Enhance the following video description to be more engaging and optimized for search. Add relevant hashtags and timestamps if possible. Original description:"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-sparkles" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M16 18a2 2 0 0 1 2 2a2 2 0 0 1 2 -2a2 2 0 0 1 -2 -2a2 2 0 0 1 -2 2z"></path><path d="M8 18a2 2 0 0 1 2 2a2 2 0 0 1 2 -2a2 2 0 0 1 -2 -2a2 2 0 0 1 -2 2z"></path><path d="M12 10a2 2 0 0 1 2 2a2 2 0 0 1 2 -2a2 2 0 0 1 -2 -2a2 2 0 0 1 -2 2z"></path></svg></button><button type="button" class="btn btn-icon btn-outline-secondary btn-ai" data-bs-toggle="tooltip" title="Generate Full" data-target="youtube_description" data-type="generate-full" data-prompt-template="You are a YouTube SEO expert. Write a full, optimized YouTube description based on the video title and script outline. Include a summary, timestamps, relevant links (as placeholders), and a call to action."><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-text-ai" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M14 3v4a1 1 0 0 0 1 1h4"></path><path d="M10 21h-3a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v3.5"></path><path d="M9 9h1"></path><path d="M9 13h2.5"></path><path d="M9 17h1"></path><path d="M14 21v-4a2 2 0 1 1 4 0v4"></path><path d="M14 19h4"></path><path d="M21 15v6"></path></svg></button></div></div>
                        <textarea name="youtube_description" id="youtube_description" rows="7" class="form-control" placeholder="Video Summary...&#10;&#10;TIMESTAMPS:&#10;00:00 - Intro">{{ old('youtube_description', $content->youtube_description) }}</textarea>
                    </div>
                 </div>
            </div>
        </div>

        {{-- Column 2: Project Context & Submission --}}
        <div class="col-lg-4">
            <div class="card" id="project-context-card">
                 <div class="card-header"><h3 class="card-title">Project Context</h3></div>
                 <div class="card-body">
                    <p>This content is part of the following project:</p>
                    <strong>Channel Name:</strong>
                    <p id="context-channel-name">{{ $project->channel_name_final }}</p>
                    <strong>Description:</strong>
                    <p id="context-description" class="text-muted">{{ Str::limit($project->channel_description, 150) }}</p>
                    <strong>Target Audience:</strong>
                    <p id="context-audience" class="text-muted">{{ Str::limit($project->primary_audience_persona, 150) }}</p>
                 </div>
            </div>
            <div class="d-grid mt-4"><button type="submit" class="btn btn-primary btn-lg">Update Content Plan</button></div>
        </div>
    </div>
</form>

{{-- AI Popup --}}
<div class="ai-tooltip-popup-backdrop" id="aiBackdrop"></div>
<div class="ai-tooltip-popup" id="aiPopup">
    <h4 id="aiPopupTitle">AI Assistant</h4>
    <div class="form-group"><label for="aiPrompt" class="form-label">Final Prompt for AI</label><textarea id="aiPrompt" class="form-control" rows="8"></textarea></div>
    <div class="d-flex justify-content-end mt-3"><button type="button" class="btn btn-secondary me-2" id="aiCancelBtn">Cancel</button><button type="button" class="btn btn-primary" id="aiSubmitBtn"><span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true" id="aiSpinner"></span>Generate</button></div>
</div>
@endsection

@push('scripts')
<script>
// --- FULL SCRIPT - This now includes all necessary functions ---
document.addEventListener('DOMContentLoaded', function () {
    const mainForm = document.getElementById('content-form');
    const projectId = `{{ $project->id }}`;
    const pillarDatalist = document.getElementById('pillar-suggestions');
    const goalDatalist = document.getElementById('goal-suggestions');

    // --- Initialize Tooltips ---
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    // --- Function to Load Suggestions ---
    async function loadSuggestions() {
        const storageKey = `project_suggestions_${projectId}`;
        let suggestions = JSON.parse(localStorage.getItem(storageKey));
        if (!suggestions) {
            try {
                const response = await fetch(`{{ route('projects.generate_suggestions', $project) }}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                if (!response.ok) throw new Error('Network response was not ok.');
                suggestions = await response.json();
                localStorage.setItem(storageKey, JSON.stringify(suggestions));
            } catch (error) { console.error('Failed to fetch suggestions:', error); return; }
        }
        if (suggestions.pillars) { pillarDatalist.innerHTML = suggestions.pillars.map(p => `<option value="${p}"></option>`).join(''); }
        if (suggestions.goals) { goalDatalist.innerHTML = suggestions.goals.map(g => `<option value="${g}"></option>`).join(''); }
    }
    loadSuggestions();
    
    // --- AI Popup and Generic Button Logic ---
    const aiPopup = document.getElementById('aiPopup');
    const aiBackdrop = document.getElementById('aiBackdrop');
    const aiPromptTextarea = document.getElementById('aiPrompt');
    const aiSubmitBtn = document.getElementById('aiSubmitBtn');
    const aiCancelBtn = document.getElementById('aiCancelBtn');
    const aiSpinner = document.getElementById('aiSpinner');
    let currentTargetId = null;
    let currentTargetIsStructured = false;

    function openAiPopup(finalPrompt, targetId, isStructured = false) {
        currentTargetId = targetId;
        currentTargetIsStructured = isStructured;
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

    // This section can be added back when you re-add the buttons to the view
    /*
    document.querySelectorAll('.btn-ai').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.dataset.target;
            const promptTemplate = this.dataset.promptTemplate;
            const actionType = this.dataset.type;
            const targetElement = document.getElementById(targetId);
            let finalPrompt = '';

            if (actionType === 'enhance') {
                if (!targetElement.value.trim()) {
                    alert('Please write something to enhance.');
                    return;
                }
                finalPrompt = promptTemplate + `"${targetElement.value}"`;
            } else { // generate-full
                finalPrompt = promptTemplate;
            }
            const isStructured = targetId === 'structured-script-container' || targetId === 'structured-visuals-container';
            openAiPopup(finalPrompt, targetId, isStructured);
        });
    });
    */

    aiSubmitBtn.addEventListener('click', function() {
        if (!currentTargetId) return;
        aiSpinner.classList.remove('d-none');
        this.disabled = true;

        // You would gather context here before the fetch call
        // const fullContext = gatherFullContext();
        
        fetch('{{ route("ai.generate") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ prompt: aiPromptTextarea.value, context: 'some context' /* replace with fullContext */ })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (currentTargetIsStructured) {
                    if (currentTargetId === 'structured-script-container') renderStructuredScript(data.text);
                    if (currentTargetId === 'structured-visuals-container') renderStructuredVisuals(data.text);
                } else {
                    document.getElementById(currentTargetId).value = data.text;
                }
                closeAiPopup();
            } else {
                alert('Error: ' + (data.message || 'Unknown error'));
                closeAiPopup();
            }
        })
        .catch(error => { console.error('Error:', error); alert('An unexpected error occurred.'); closeAiPopup(); });
    });


    // --- Structured Script Rendering ---
    const scriptContainer = document.getElementById('structured-script-container');
    function renderStructuredScript(jsonString) {
        try {
            const cleanedText = jsonString.replace(/```json\n?|```/g, '').trim();
            const data = JSON.parse(cleanedText);
            scriptContainer.innerHTML = ''; // Clear placeholder

            const createField = (label, value, key, rows = 2) => {
                return `<div class="structured-item"><label class="form-label">${label}</label><textarea class="form-control" rows="${rows}" data-script-key="${key}">${value || ''}</textarea></div>`;
            };
            scriptContainer.innerHTML += createField('Hook (0-15s)', data.hook?.text, 'hook.text', 2);
            data.main_points?.forEach((point, index) => {
                let pointHtml = `<div class="structured-item" data-point-index="${index}">
                                    <div class="structured-item-header">
                                        <label class="form-label">Main Point ${index + 1} (Est. ${point.duration || 'N/A'})</label>
                                        <button type="button" class="btn btn-sm btn-icon btn-outline-danger" data-bs-toggle="tooltip" title="Delete Point" onclick="this.closest('.structured-item').remove()">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                        </button>
                                    </div>
                                    <input type="text" class="form-control mb-2" data-script-key="title" value="${point.title || ''}" placeholder="Point Title">
                                    <textarea class="form-control" rows="4" data-script-key="details" placeholder="Details">${point.details || ''}</textarea>
                                 </div>`;
                scriptContainer.innerHTML += pointHtml;
            });
            scriptContainer.innerHTML += createField('Call to Action', data.cta?.text, 'cta.text', 2);
            scriptContainer.innerHTML += createField('Outro', data.outro?.text, 'outro.text', 2);
        } catch (e) {
            console.error("Failed to parse script JSON:", e, "Raw:", jsonString);
            scriptContainer.innerHTML = `<p class="text-danger text-center">Could not display script. The data might be in an old format.</p>`;
        }
    }

    // --- Structured Visuals Rendering ---
    const visualsContainer = document.getElementById('structured-visuals-container');
    function renderStructuredVisuals(jsonString) {
        try {
            const cleanedText = jsonString.replace(/```json\n?|```/g, '').trim();
            const data = JSON.parse(cleanedText);
            visualsContainer.innerHTML = '';
            data.visuals?.forEach((visual, index) => {
                const isImageSelected = visual.visual_type === 'Image';
                let visualHtml = `<div class="structured-item" data-visual-index="${index}">
                                    <div class="structured-item-header">
                                        <input type="text" class="form-control form-control-flush" data-visual-key="scene" value="${visual.scene || `Scene ${index + 1}`}">
                                        <button type="button" class="btn btn-sm btn-icon btn-outline-danger" data-bs-toggle="tooltip" title="Delete Visual" onclick="this.closest('.structured-item').remove()"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></button>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8 mb-2"><input type="text" class="form-control" data-visual-key="topic" value="${visual.topic || ''}" placeholder="Topic/Description"></div>
                                        <div class="col-md-4 mb-2"><select class="form-select" data-visual-key="visual_type"><option value="Image" ${isImageSelected ? 'selected' : ''}>Image</option><option value="Video" ${!isImageSelected ? 'selected' : ''}>Video</option></select></div>
                                    </div>
                                    <textarea class="form-control mb-2" rows="3" data-visual-key="prompt" placeholder="AI Generation Prompt">${visual.prompt || ''}</textarea>
                                    <input type="text" class="form-control" data-visual-key="keywords" value="${visual.keywords || ''}" placeholder="Search Keywords">
                                  </div>`;
                visualsContainer.innerHTML += visualHtml;
            });
        } catch (e) {
            console.error("Failed to parse visuals JSON:", e, "Raw:", jsonString);
            visualsContainer.innerHTML = `<p class="text-danger text-center">Could not display visuals. The data might be in an old format.</p>`;
        }
    }
    
    // --- Serialization Logic ---
    mainForm.addEventListener('submit', function() {
        // Serialize Script Outline
        const scriptItems = scriptContainer.querySelectorAll('.structured-item');
        if (scriptItems.length > 0) {
            const scriptData = { main_points: [] };
            scriptItems.forEach(item => {
                const pointIndex = item.dataset.pointIndex;
                if(pointIndex) {
                    const pointObject = {};
                    item.querySelectorAll('[data-script-key]').forEach(el => pointObject[el.dataset.scriptKey] = el.value);
                    scriptData.main_points.push(pointObject);
                } else {
                    const key = item.querySelector('[data-script-key]').dataset.scriptKey;
                    const parentKey = key.split('.')[0];
                    const childKey = key.split('.')[1];
                    scriptData[parentKey] = { [childKey]: item.querySelector('[data-script-key]').value };
                }
            });
            document.getElementById('script_outline_hidden').value = JSON.stringify(scriptData, null, 2);
        }

        // Serialize Visual Assets
        const visualItems = visualsContainer.querySelectorAll('.structured-item');
        if (visualItems.length > 0) {
            const visualsData = { visuals: [] };
            visualItems.forEach(item => {
                const visualObject = {};
                item.querySelectorAll('[data-visual-key]').forEach(el => visualObject[el.dataset.visualKey] = el.value);
                visualsData.visuals.push(visualObject);
            });
            document.getElementById('visual_assets_needed_hidden').value = JSON.stringify(visualsData, null, 2);
        }
    });

    // --- Key difference for EDIT page: Populate structured fields on load ---
    function populateOnLoad() {
        const scriptJson = document.getElementById('script_outline_hidden').value;
        if(scriptJson && scriptJson.trim() !== '') {
            renderStructuredScript(scriptJson);
        }

        const visualsJson = document.getElementById('visual_assets_needed_hidden').value;
        if(visualsJson && visualsJson.trim() !== '') {
            renderStructuredVisuals(visualsJson);
        }
    }
    
    populateOnLoad(); // Call the population function
});
</script>
@endpush