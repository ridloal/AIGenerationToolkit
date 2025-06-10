@extends('layouts.admin') {{-- Menggunakan layout admin yang sudah kita buat --}}

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Overview')

@section('content')
<div class="row row-deck row-cards mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Welcome to AIGenerationToolkit!</h3>
            </div>
            <div class="card-body">
                <p>Your User ID: <strong>{{ Auth::id() }}</strong></p>
                <p>Your Roles: <strong>{{ Auth::user()->roles->pluck('name')->join(', ') }}</strong></p>

                @if(Auth::user()->hasRole('admin'))
                    <p class="text-success">You have Administrator privileges.</p>
                @endif

                @if(Auth::user()->hasRole('user'))
                    <p class="text-info">You have Standard User privileges.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Project Management Card --}}
    <div class="col-md-6 col-lg-4">
        <a href="{{ route('projects.index') }}" class="card card-link card-link-pop">
            <div class="card-body text-center">
                <div class="mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-folder" width="64" height="64" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                       <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                       <path d="M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2" />
                    </svg>
                </div>
                <h3 class="card-title">Project Management</h3>
                <p class="text-muted">View, edit, and manage all of your YouTube channel projects.</p>
            </div>
        </a>
    </div>

    {{-- Content Generator Card --}}
    <div class="col-md-6 col-lg-4">
        @php
            $activeProject = Auth::user()->projects()->where('is_active', true)->first();
        @endphp
        <a href="{{ $activeProject ? route('projects.contents.index', $activeProject) : '#' }}" 
           class="card card-link card-link-pop @if(!$activeProject) disabled @endif" 
           @if(!$activeProject) onclick="alert('Please select an active project first.'); return false;" @endif>
            <div class="card-body text-center">
                <div class="mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-text-ai" width="64" height="64" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M10 21h-3a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v3.5" /><path d="M9 9h1" /><path d="M9 13h2.5" /><path d="M9 17h1" /><path d="M14 21v-4a2 2 0 1 1 4 0v4" /><path d="M14 19h4" /><path d="M21 15v6" /></svg>
                </div>
                <h3 class="card-title">Content Generator</h3>
                <p class="text-muted">Plan and generate scripts for your active project's videos.</p>
            </div>
        </a>
    </div>

    {{-- Placeholder Card for future menu --}}
    <div class="col-md-6 col-lg-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-3 text-muted">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chart-bar" width="64" height="64" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                       <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                       <path d="M3 12m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                       <path d="M9 8m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                       <path d="M15 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                       <path d="M4 20l14 0" />
                    </svg>
                </div>
                <h3 class="card-title text-muted">Content Analytics</h3>
                <p class="text-muted">(Coming Soon) Track the performance of your videos and content ideas.</p>
            </div>
        </div>
    </div>

    {{-- Admin Only: Settings Card --}}
    @if(Auth::user()->hasRole('admin'))
    <div class="col-md-6 col-lg-4">
        <a href="{{ route('settings.ai.index') }}" class="card card-link card-link-pop">
            <div class="card-body text-center">
                <div class="mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-settings" width="64" height="64" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                       <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                       <path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" />
                       <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                    </svg>
                </div>
                <h3 class="card-title">AI Settings</h3>
                <p class="text-muted">Configure API keys and other AI-related settings.</p>
            </div>
        </a>
    </div>
    @endif
    
</div>
@endsection

@push('scripts')
    {{-- <script>
        // Custom JS untuk halaman dashboard bisa diletakkan di sini
        console.log('Dashboard loaded');
    </script> --}}
@endpush