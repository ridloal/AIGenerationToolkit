@extends('layouts.admin')

@section('title', 'Create Content Plan')
@section('page_title', 'New Content Plan for: ' . $project->channel_name_final)

@push('styles')
<style>
    .form-label-container { display: flex; align-items: center; margin-bottom: .5rem; }
    .form-label-container .form-label { margin-bottom: 0; }
    .ai-buttons { margin-left: auto; display: flex; gap: .5rem; }
    .ai-buttons .btn { padding: .1rem .3rem; line-height: 1; }
    .structured-item { border-left: 3px solid #dee2e6; padding-left: 1rem; margin-bottom: 1rem; }
    .structured-item .form-label { font-weight: 600; }
    .structured-item-header { display: flex; justify-content: space-between; align-items: center; }
    /* AI Popup Styles */
    .ai-tooltip-popup { display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 500px; background-color: white; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 1050; padding: 20px; }
    .ai-tooltip-popup-backdrop { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1040; }
</style>
@endpush

@section('content')
<form action="{{ route('projects.contents.store', $project) }}" method="POST" id="content-form">
    @csrf
    <div class="row g-4">
        {{-- Column 1: Main Form --}}
        <div class="col-lg-8">
            {{-- Title Generation Card --}}
            <div class="card mb-4" id="title-generator-card">
                <div class="card-header"><h3 class="card-title">Step 0: Generate Video Title Ideas</h3></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3"><label for="title_keyword" class="form-label">Main Keyword</label><input type="text" id="title_keyword" class="form-control" placeholder="e.g., Best AI Tools"></div>
                        <div class="col-md-6 mb-3"><label for="title_style" class="form-label">Title Style</label><select id="title_style" class="form-select"><option value="Clickbait">Clickbait</option><option value="Informative">Informative</option><option value="Educative">Educative</option><option value="Tutorial">Tutorial</option><option value="Tips & Hacks">Tips & Hacks</option></select></div>
                    </div>
                    <button type="button" id="generate_titles_btn" class="btn btn-secondary"><span id="generate-titles-spinner" class="spinner-border spinner-border-sm d-none me-2" role="status"></span>Generate 5 Title Suggestions</button>
                    <div id="title-suggestions-container" class="mt-4 d-none"><label class="form-label fw-bold">Suggestions:</label><div id="title-suggestions-list" class="list-group"></div></div>
                </div>
            </div>

            {{-- Core Details Card --}}
            <div class="card mb-4" id="core-details-card">
                <div class="card-header"><h3 class="card-title">Step 1: Core Video Details</h3></div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="form-label-container"><label for="title" class="form-label required">Video Title</label><div class="ai-buttons" id="ai-buttons-title"></div></div>
                        <input type="text" id="title" name="title" class="form-control" placeholder="Generate or enter a catchy video title" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-label-container"><label class="form-label required">Video Format</label></div>
                            <select name="video_format" class="form-select"><option value="long">Long Form</option><option value="short">Short</option></select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-label-container"><label for="content_pillar" class="form-label">Content Pillar</label><div class="ai-buttons" id="ai-buttons-content_pillar"></div></div>
                            <input type="text" id="content_pillar" name="content_pillar" class="form-control" placeholder="e.g., AI for Productivity" list="pillar-suggestions">
                            <datalist id="pillar-suggestions"></datalist>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-label-container"><label for="main_goal" class="form-label">Primary Goal of This Video</label><div class="ai-buttons" id="ai-buttons-main_goal"></div></div>
                        <input type="text" name="main_goal" id="main_goal" class="form-control" placeholder="e.g., Get viewers to try a specific AI tool" list="goal-suggestions">
                        <datalist id="goal-suggestions"></datalist>
                    </div>
                    <div class="mb-3">
                        <div class="form-label-container"><label for="reference_info" class="form-label">References</label><div class="ai-buttons" id="ai-buttons-reference_info"></div></div>
                        <textarea name="reference_info" id="reference_info" rows="3" class="form-control" placeholder="Add any links, scripts, or text references here..."></textarea>
                    </div>
                </div>
            </div>

            {{-- Script & Asset Planning Card --}}
            <div class="card mt-4">
                 <div class="card-header"><h3 class="card-title">Step 2: Script & Asset Planning</h3></div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="form-label-container"><label class="form-label h4">Script Outline</label><div class="ai-buttons" id="ai-buttons-structured-script-container"></div></div>
                        <div id="structured-script-container" class="mt-2"><p class="text-muted text-center">Generate a script outline to begin.</p></div>
                        <textarea name="script_outline" id="script_outline_hidden" style="display: none;"></textarea>
                    </div>
                    <hr>
                    <div class="mt-4">
                        <div class="form-label-container"><label class="form-label h4">Visual Assets Needed</label><div class="ai-buttons" id="ai-buttons-structured-visuals-container"></div></div>
                        <div id="structured-visuals-container" class="mt-2"><p class="text-muted text-center">Generate a visual plan to begin.</p></div>
                        <textarea name="visual_assets_needed" id="visual_assets_needed_hidden" style="display: none;"></textarea>
                    </div>
                </div>
            </div>

            {{-- YouTube Optimization Card --}}
            <div class="card mt-4">
                 <div class="card-header"><h3 class="card-title">Step 3: YouTube Optimization</h3></div>
                 <div class="card-body">
                    <div class="mb-3">
                        <div class="form-label-container"><label for="youtube_description" class="form-label">YouTube Description</label><div class="ai-buttons" id="ai-buttons-youtube_description"></div></div>
                        <textarea name="youtube_description" id="youtube_description" rows="7" class="form-control" placeholder="Video Summary...&#10;&#10;TIMESTAMPS:&#10;00:00 - Intro"></textarea>
                    </div>
                    <div class="mb-3">
                        <div class="form-label-container"><label for="target_keywords" class="form-label">Tags / Keywords</label><div class="ai-buttons" id="ai-buttons-target_keywords"></div></div>
                        <textarea name="target_keywords" id="target_keywords" rows="3" class="form-control" placeholder="ai tools, productivity hacks, etc."></textarea>
                    </div>
                 </div>
            </div>
        </div>

        {{-- Column 2: Project Context & Submission --}}
        <div class="col-lg-4">
            <div class="card" id="project-context-card">
                 <div class="card-header"><h3 class="card-title">Project Context</h3></div>
                 <div class="card-body"><p>This content will be created based on the following project details:</p><strong id="context-channel-name-label">Channel Name:</strong><p id="context-channel-name">{{ $project->channel_name_final }}</p><strong id="context-description-label">Description:</strong><p id="context-description" class="text-muted">{{ Str::limit($project->channel_description, 150) }}</p><strong id="context-audience-label">Target Audience:</strong><p id="context-audience" class="text-muted">{{ Str::limit($project->primary_audience_persona, 150) }}</p></div>
            </div>
            <div class="d-grid mt-4"><button type="submit" class="btn btn-primary btn-lg">Save Content Plan</button></div>
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
// --- FULL SCRIPT WITH ALL FIXES AND FEATURES ---
document.addEventListener('DOMContentLoaded', function () {
    const mainForm = document.getElementById('content-form');
    if (!mainForm) return;

    // --- ELEMENT SELECTORS ---
    const projectId = `{{ $project->id }}`;
    const pillarDatalist = document.getElementById('pillar-suggestions');
    const goalDatalist = document.getElementById('goal-suggestions');
    const scriptContainer = document.getElementById('structured-script-container');
    const visualsContainer = document.getElementById('structured-visuals-container');
    const aiPopup = document.getElementById('aiPopup');
    const aiBackdrop = document.getElementById('aiBackdrop');
    const aiPromptTextarea = document.getElementById('aiPrompt');
    const aiSubmitBtn = document.getElementById('aiSubmitBtn');
    const aiCancelBtn = document.getElementById('aiCancelBtn');
    const aiSpinnerPopup = document.getElementById('aiSpinner');
    let currentTargetId = null;
    let currentTargetIsStructured = false;

    // --- UTILITY: AI Button HTML Generator ---
    function getAiButtonsHtml(targetId, options = {}) {
        const { showTranslate = true, showEnhance = true, showGenerate = true, enhancePrompt = '', generatePrompt = '' } = options;
        let buttons = '';
        if (showTranslate) buttons += `<button type="button" class="btn btn-icon btn-outline-secondary btn-ai" data-bs-toggle="tooltip" title="Translate to Indonesian" data-target="${targetId}" data-type="translate"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-language" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 5h7" /><path d="M9 3v2c0 4.418 -2.239 8 -5 8" /><path d="M5 9c0 2.144 2.952 3.908 6.7 4" /><path d="M12 20l4 -9l4 9" /><path d="M19.1 18h-6.2" /></svg></button>`;
        if (showEnhance) buttons += `<button type="button" class="btn btn-icon btn-outline-secondary btn-ai" data-bs-toggle="tooltip" title="Enhance" data-target="${targetId}" data-type="enhance" data-prompt-template="${enhancePrompt}"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-sparkles" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M16 18a2 2 0 0 1 2 2a2 2 0 0 1 2 -2a2 2 0 0 1 -2 -2a2 2 0 0 1 -2 2z" /><path d="M8 18a2 2 0 0 1 2 2a2 2 0 0 1 2 -2a2 2 0 0 1 -2 -2a2 2 0 0 1 -2 2z" /><path d="M12 10a2 2 0 0 1 2 2a2 2 0 0 1 2 -2a2 2 0 0 1 -2 -2a2 2 0 0 1 -2 2z" /></svg></button>`;
        if (showGenerate) buttons += `<button type="button" class="btn btn-icon btn-outline-secondary btn-ai" data-bs-toggle="tooltip" title="Generate" data-target="${targetId}" data-type="generate-full" data-prompt-template="${generatePrompt}"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-text-ai" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M10 21h-3a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v3.5" /><path d="M9 9h1" /><path d="M9 13h2.5" /><path d="M9 17h1" /><path d="M14 21v-4a2 2 0 1 1 4 0v4" /><path d="M14 19h4" /><path d="M21 15v6" /></svg></button>`;
        return buttons;
    }

    // --- INITIALIZATION ---
    function initializeTooltips() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) { return new bootstrap.Tooltip(tooltipTriggerEl); });
    }

    function setupAiButtons() {
        // This function dynamically injects the AI buttons into their placeholders.
        document.getElementById('ai-buttons-title').innerHTML = getAiButtonsHtml('title', { showEnhance: false, showGenerate: false });
        document.getElementById('ai-buttons-content_pillar').innerHTML = getAiButtonsHtml('content_pillar', { showEnhance: false, generatePrompt: 'Suggest a relevant content pillar for this video.' });
        document.getElementById('ai-buttons-main_goal').innerHTML = getAiButtonsHtml('main_goal', { enhancePrompt: 'Enhance the following video goal to be more specific, measurable, achievable, relevant, and time-bound (SMART). Original goal:', generatePrompt: 'Based on the video title, suggest a primary goal for this video (e.g., drive sign-ups, increase watch time, get comments).' });
        document.getElementById('ai-buttons-reference_info').innerHTML = getAiButtonsHtml('reference_info', { showGenerate: false, enhancePrompt: 'Summarize the key points from the following references:' });
        document.getElementById('ai-buttons-youtube_description').innerHTML = getAiButtonsHtml('youtube_description', { enhancePrompt: 'You are a YouTube SEO expert. Enhance the following video description to be more engaging and optimized for search. Add relevant hashtags and timestamps if possible. Original description:', generatePrompt: 'You are a YouTube SEO expert. Write a full, optimized YouTube description based on the video title and script outline. Include a summary, timestamps, relevant links (as placeholders), and a call to action.' });
        document.getElementById('ai-buttons-target_keywords').innerHTML = getAiButtonsHtml('target_keywords', { enhancePrompt: 'You are a YouTube SEO expert. Enhance and expand upon the following keywords. Add related long-tail keywords and synonyms. Original keywords:', generatePrompt: 'You are a YouTube SEO expert. Based on the video title and goal, generate a comma-separated list of 10-15 relevant keywords and tags.' });
        
        // FIX: Escape double quotes inside the JSON string with &quot;
        const scriptPrompt = 'You are a YouTube scriptwriting expert. Based on the provided context, create a complete script outline. IMPORTANT: Your response MUST be a valid JSON object with the following structure: {&quot;hook&quot;: {&quot;text&quot;: &quot;A captivating opening line...&quot;, &quot;duration&quot;: &quot;0-15s&quot;}, &quot;main_points&quot;: [{&quot;title&quot;: &quot;Main Point 1&quot;, &quot;details&quot;: &quot;Detailed explanation...&quot;, &quot;duration&quot;: &quot;1-2 min&quot;}], &quot;cta&quot;: {&quot;text&quot;: &quot;Like, comment, and subscribe!&quot;, &quot;duration&quot;: &quot;15s&quot;}, &quot;outro&quot;: {&quot;text&quot;: &quot;A concluding remark.&quot;, &quot;duration&quot;: &quot;30s&quot;}}';
        document.getElementById('ai-buttons-structured-script-container').innerHTML = getAiButtonsHtml('structured-script-container', { showTranslate: false, showEnhance: false, generatePrompt: scriptPrompt });
        
        const visualsPrompt = 'You are a visual director. Based on the script outline, create a plan for visual assets. IMPORTANT: Your response MUST be a valid JSON object with a single key &quot;visuals&quot; containing an array of objects. Each object should have this structure: {&quot;scene&quot;: &quot;Scene X&quot;, &quot;topic&quot;: &quot;Brief description&quot;, &quot;visual_type&quot;: &quot;Image&quot;, &quot;prompt&quot;: &quot;Detailed AI generation prompt&quot;, &quot;keywords&quot;: &quot;searchable, keywords, for, stock, sites&quot;}.';
        document.getElementById('ai-buttons-structured-visuals-container').innerHTML = getAiButtonsHtml('structured-visuals-container', { showTranslate: false, showEnhance: false, generatePrompt: visualsPrompt });

        initializeTooltips();
    }
    setupAiButtons();

    // --- DATA HANDLING & SUGGESTIONS ---
    async function loadSuggestions() {
        const storageKey = `project_suggestions_${projectId}`;
        let suggestions = JSON.parse(localStorage.getItem(storageKey));
        if (!suggestions) {
            try {
                const response = await fetch(`{{ route('projects.generate_suggestions', $project) }}`, {
                    method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
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

    function gatherFullContext() {
        // This function gathers all available information from the form to create a rich context for the AI.
        let context = "PROJECT CONTEXT:\n";
        context += `Channel Name: {{ $project->channel_name_final }}\n`;
        context += `Description: {{ $project->channel_description }}\n`;
        context += `Audience: {{ $project->primary_audience_persona }}\n\n`;
        context += "CURRENT VIDEO PLAN:\n";
        const fieldsToInclude = { 'title': 'Video Title', 'video_format': 'Video Format', 'content_pillar': 'Content Pillar', 'main_goal': 'Primary Goal', 'target_keywords': 'Tags / Keywords', 'reference_info': 'References', 'youtube_description': 'YouTube Description' };
        for (const [id, label] of Object.entries(fieldsToInclude)) {
            const element = document.getElementById(id);
            if (element && element.value.trim()) { context += `${label}: ${element.value.trim()}\n`; }
        }
        const videoFormat = document.querySelector('[name=video_format]').value;
        if (videoFormat === 'short') { context += "Target Video Length: 45-90 seconds.\n"; } else if (videoFormat === 'long') { context += "Target Video Length: Over 4 minutes.\n"; }
        
        const scriptJson = document.getElementById('script_outline_hidden').value;
        if (scriptJson && scriptJson.trim()) {
            try {
                const scriptData = JSON.parse(scriptJson);
                context += "\nSCRIPT OUTLINE SUMMARY:\n";
                if(scriptData.hook?.text) context += `- Hook: ${scriptData.hook.text}\n`;
                if(scriptData.main_points) { scriptData.main_points.forEach((point, index) => { context += `- Main Point ${index + 1}: ${point.title}\n`; }); }
                if(scriptData.cta?.text) context += `- CTA: ${scriptData.cta.text}\n`;
            } catch(e) { /* Ignore if JSON is invalid */ }
        }
        return context.trim();
    }
    
    // --- AI POPUP LOGIC ---
    function openAiPopup(prompt, targetId, isStructured = false) {
        currentTargetId = targetId;
        currentTargetIsStructured = isStructured;
        aiPromptTextarea.value = prompt;
        aiPopup.style.display = 'block';
        aiBackdrop.style.display = 'block';
    }
    function closeAiPopup() {
        aiPopup.style.display = 'none';
        aiBackdrop.style.display = 'none';
        aiSpinnerPopup.classList.add('d-none');
        aiSubmitBtn.disabled = false;
    }
    aiCancelBtn.addEventListener('click', closeAiPopup);
    aiBackdrop.addEventListener('click', closeAiPopup);

    // --- EVENT LISTENERS (Delegated) ---
    mainForm.addEventListener('click', function(e) {
        const aiButton = e.target.closest('.btn-ai');
        if (aiButton) {
            const targetId = aiButton.dataset.target;
            const targetElement = document.getElementById(targetId);
            const actionType = aiButton.dataset.type;
            let finalPrompt = '';

            if (actionType === 'translate') {
                if (!targetElement.value.trim()) { alert('Please enter text to translate.'); return; }
                finalPrompt = `Translate the following text to Indonesian: "${targetElement.value}"`;
            } else if (actionType === 'enhance') {
                if (!targetElement.value.trim()) { alert('Please write something to enhance.'); return; }
                finalPrompt = aiButton.dataset.promptTemplate + `"${targetElement.value}"`;
            } else { // generate-full
                finalPrompt = aiButton.dataset.promptTemplate;
            }
            
            const isStructured = targetId.includes('structured-');
            openAiPopup(finalPrompt, targetId, isStructured);
        }

        const titlesBtn = e.target.closest('#generate_titles_btn');
        if (titlesBtn) {
            const keyword = document.getElementById('title_keyword').value;
            const style = document.getElementById('title_style').value;
            if (!keyword) { alert('Please enter a main keyword.'); return; }
            const spinner = document.getElementById('generate-titles-spinner');
            spinner.classList.remove('d-none'); titlesBtn.disabled = true;
            const projectContext = `Channel Name: ${document.getElementById('context-channel-name').textContent.trim()}\nDescription: ${document.getElementById('context-description').textContent.trim()}`;
            const prompt = `You are a YouTube title expert. Based on the project context:\n---\n${projectContext}\n---\n\nGenerate exactly 5 video title suggestions for the keyword "${keyword}" with a "${style}" style. IMPORTANT: Return ONLY a valid JSON array of strings.`;
            
            fetch('{{ route("ai.generate") }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ prompt: prompt, context: projectContext })})
            .then(res => res.json()).then(data => {
                if(data.success) {
                    try {
                        const titles = parseAiJson(data.text);
                        const list = document.getElementById('title-suggestions-list');
                        list.innerHTML = '';
                        titles.forEach(title => {
                            const item = document.createElement('div');
                            item.className = 'list-group-item d-flex justify-content-between align-items-center';
                            item.textContent = title;
                            const btn = document.createElement('button');
                            btn.type = 'button'; btn.className = 'btn btn-sm btn-outline-primary'; btn.textContent = 'Use this';
                            btn.onclick = () => { document.getElementById('title').value = title; };
                            item.appendChild(btn);
                            list.appendChild(item);
                        });
                        document.getElementById('title-suggestions-container').classList.remove('d-none');
                    } catch (e) { alert('AI response format error.'); }
                } else { alert(`Error: ${data.message}`); }
            }).catch(err => alert('Request failed.'))
            .finally(() => { spinner.classList.add('d-none'); titlesBtn.disabled = false; });
        }
    });

    aiSubmitBtn.addEventListener('click', function() {
        if (!currentTargetId) return;
        aiSpinnerPopup.classList.remove('d-none'); this.disabled = true;
        const fullContext = gatherFullContext();
        
        fetch('{{ route("ai.generate") }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ prompt: aiPromptTextarea.value, context: fullContext })})
        .then(response => response.json()).then(data => {
            if (data.success) {
                if (currentTargetIsStructured) {
                    if (currentTargetId === 'structured-script-container') renderStructuredScript(data.text);
                    if (currentTargetId === 'structured-visuals-container') renderStructuredVisuals(data.text);
                } else { document.getElementById(currentTargetId).value = data.text; }
                closeAiPopup();
            } else { alert('Error: ' + (data.message || 'Unknown error')); closeAiPopup(); }
        }).catch(error => { console.error('Error:', error); alert('An unexpected error occurred.'); closeAiPopup(); });
    });

    // --- STRUCTURED DATA FUNCTIONS ---
    function parseAiJson(jsonString) {
        const match = /\{[\s\S]*\}|\[[\s\S]*\]/.exec(jsonString);
        if (match) {
            try { return JSON.parse(match[0]); } catch (e) { throw new Error("Could not parse extracted JSON."); }
        }
        throw new Error("No valid JSON found in response.");
    }

    function renderStructuredScript(jsonString) {
        try {
            const data = parseAiJson(jsonString); scriptContainer.innerHTML = '';
            const createField = (label, value, key, rows = 2) => `<div class="structured-item"><label class="form-label">${label}</label><textarea class="form-control" rows="${rows}" data-script-key="${key}">${value || ''}</textarea></div>`;
            scriptContainer.innerHTML += createField('Hook (0-15s)', data.hook?.text, 'hook.text', 2);
            data.main_points?.forEach((point, index) => {
                let pointHtml = `<div class="structured-item" data-point-index="${index}"><div class="structured-item-header"><label class="form-label">Main Point ${index + 1} (Est. ${point.duration || 'N/A'})</label><button type="button" class="btn btn-sm btn-icon btn-outline-danger" data-bs-toggle="tooltip" title="Delete Point" onclick="this.closest('.structured-item').remove()"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></button></div><input type="text" class="form-control mb-2" data-script-key="title" value="${point.title || ''}" placeholder="Point Title"><textarea class="form-control" rows="4" data-script-key="details" placeholder="Details">${point.details || ''}</textarea></div>`;
                scriptContainer.innerHTML += pointHtml;
            });
            scriptContainer.innerHTML += createField('Call to Action', data.cta?.text, 'cta.text', 2);
            scriptContainer.innerHTML += createField('Outro', data.outro?.text, 'outro.text', 2);
        } catch (e) { console.error("Failed to parse script JSON:", e, "Raw:", jsonString); scriptContainer.innerHTML = `<p class="text-danger text-center">Could not display script. The AI returned an invalid format.</p>`; }
    }

    function renderStructuredVisuals(jsonString) {
        try {
            const data = parseAiJson(jsonString); visualsContainer.innerHTML = '';
            data.visuals?.forEach((visual, index) => {
                const isImageSelected = visual.visual_type === 'Image';
                let visualHtml = `<div class="structured-item" data-visual-index="${index}"><div class="structured-item-header"><input type="text" class="form-control form-control-flush" data-visual-key="scene" value="${visual.scene || `Scene ${index + 1}`}"> <button type="button" class="btn btn-sm btn-icon btn-outline-danger" data-bs-toggle="tooltip" title="Delete Visual" onclick="this.closest('.structured-item').remove()"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></button></div><div class="row"><div class="col-md-8 mb-2"><input type="text" class="form-control" data-visual-key="topic" value="${visual.topic || ''}" placeholder="Topic/Description"></div><div class="col-md-4 mb-2"><select class="form-select" data-visual-key="visual_type"><option value="Image" ${isImageSelected ? 'selected' : ''}>Image</option><option value="Video" ${!isImageSelected ? 'selected' : ''}>Video</option></select></div></div><textarea class="form-control mb-2" rows="3" data-visual-key="prompt" placeholder="AI Generation Prompt">${visual.prompt || ''}</textarea><input type="text" class="form-control" data-visual-key="keywords" value="${visual.keywords || ''}" placeholder="Search Keywords"></div>`;
                visualsContainer.innerHTML += visualHtml;
            });
        } catch (e) { console.error("Failed to parse visuals JSON:", e, "Raw:", jsonString); visualsContainer.innerHTML = `<p class="text-danger text-center">Could not display visuals. The AI returned an invalid format.</p>`; }
    }
    mainForm.addEventListener('submit', function(e) {
        const scriptItems = scriptContainer.querySelectorAll('.structured-item');
        if (scriptItems.length > 0) {
            const scriptData = { main_points: [] };
            scriptItems.forEach(item => {
                const pointIndex = item.dataset.pointIndex;
                if(pointIndex) {
                    const pointObject = { title: item.querySelector('[data-script-key="title"]').value, details: item.querySelector('[data-script-key="details"]').value };
                    scriptData.main_points.push(pointObject);
                } else {
                    const key = item.querySelector('[data-script-key]').dataset.scriptKey;
                    const parentKey = key.split('.')[0]; const childKey = key.split('.')[1];
                    scriptData[parentKey] = { [childKey]: item.querySelector('[data-script-key]').value };
                }
            });
            document.getElementById('script_outline_hidden').value = JSON.stringify(scriptData, null, 2);
        } else { document.getElementById('script_outline_hidden').value = ''; }

        const visualItems = visualsContainer.querySelectorAll('.structured-item');
        if (visualItems.length > 0) {
            const visualsData = { visuals: [] };
            visualItems.forEach(item => {
                const visualObject = { scene: item.querySelector('[data-visual-key="scene"]').value, topic: item.querySelector('[data-visual-key="topic"]').value, visual_type: item.querySelector('[data-visual-key="visual_type"]').value, prompt: item.querySelector('[data-visual-key="prompt"]').value, keywords: item.querySelector('[data-visual-key="keywords"]').value };
                visualsData.visuals.push(visualObject);
            });
            document.getElementById('visual_assets_needed_hidden').value = JSON.stringify(visualsData, null, 2);
        } else { document.getElementById('visual_assets_needed_hidden').value = ''; }
    });
});
</script>
@endpush