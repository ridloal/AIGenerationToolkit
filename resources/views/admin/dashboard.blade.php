@extends('layouts.admin') {{-- Menggunakan layout admin yang sudah kita buat --}}

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Overview')

@section('content')
<div class="row row-deck row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Welcome to AIGenerationToolkit!</h3>
            </div>
            <div class="card-body">
                <p>This is your main dashboard. It's currently empty, but you can start adding widgets and content here.</p>
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
    {{-- Tambahkan card atau komponen Tabler lainnya di sini --}}
</div>
@endsection

@push('scripts')
    {{-- <script>
        // Custom JS untuk halaman dashboard bisa diletakkan di sini
        console.log('Dashboard loaded');
    </script> --}}
@endpush