@extends('layouts.admin')

@section('title', 'AI Settings')
@section('page_title', 'Artificial Intelligence Settings')

@section('content')
<div class="col-md-8">
    <form action="{{ route('settings.ai.store') }}" method="POST" class="card">
        @csrf
        <div class="card-header">
            <h3 class="card-title">Gemini API Configuration</h3>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">API Keys</label>
                <p class="card-subtitle">You can add multiple keys. The system will use them based on the selected strategy.</p>
                <div id="api-keys-container">
                    @forelse ($apiKeys as $key)
                        <div class="input-group mb-2">
                            <input type="password" name="api_keys[]" class="form-control" value="{{ $key }}" placeholder="Enter Gemini API Key">
                            <button class="btn btn-outline-danger" type="button" onclick="this.parentElement.remove()">Remove</button>
                        </div>
                    @empty
                        <div class="input-group mb-2">
                            <input type="password" name="api_keys[]" class="form-control" placeholder="Enter Gemini API Key">
                            <button class="btn btn-outline-danger" type="button" onclick="this.parentElement.remove()">Remove</button>
                        </div>
                    @endforelse
                </div>
                <button type="button" class="btn btn-secondary mt-2" id="add-key-btn">+ Add Another Key</button>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">API Key Strategy</label>
                    <select name="strategy" class="form-select">
                        <option value="random" @selected($strategy === 'random')>Random</option>
                        <option value="round-robin" @selected($strategy === 'round-robin')>Round Robin</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Generation Model</label>
                    <select name="model" class="form-select">
                        @foreach($availableModels as $availableModel)
                            <option value="{{ $availableModel }}" @selected($model === $availableModel)>{{ $availableModel }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="card-header border-top">
            <h3 class="card-title">Global Generation Settings</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="language-input" class="form-label">Output Language</label>
                    <p class="card-subtitle">The primary language for AI-generated content.</p>
                    <input class="form-control" list="language-options" id="language-input" name="language" value="{{ $language }}">
                    <datalist id="language-options">
                        @foreach($languageOptions as $option)
                            <option value="{{ $option }}">
                        @endforeach
                    </datalist>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="tone-input" class="form-label">Content Tone</label>
                    <p class="card-subtitle">The default tone of voice for generated text.</p>
                    <input class="form-control" list="tone-options" id="tone-input" name="tone" value="{{ $tone }}">
                    <datalist id="tone-options">
                        @foreach($toneOptions as $option)
                            <option value="{{ $option }}">
                        @endforeach
                    </datalist>
                </div>
            </div>
        </div>

        <div class="card-footer text-end">
            <button type="submit" class="btn btn-primary">Save Settings</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('add-key-btn').addEventListener('click', function() {
    const container = document.getElementById('api-keys-container');
    const newKeyInput = document.createElement('div');
    newKeyInput.className = 'input-group mb-2';
    newKeyInput.innerHTML = `
        <input type="password" name="api_keys[]" class="form-control" placeholder="Enter Gemini API Key">
        <button class="btn btn-outline-danger" type="button" onclick="this.parentElement.remove()">Remove</button>
    `;
    container.appendChild(newKeyInput);
});
</script>
@endpush
