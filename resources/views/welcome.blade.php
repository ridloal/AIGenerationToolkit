<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AIGenerationToolkit</title>
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css" rel="stylesheet"/>
    <style>
        body { display: flex; flex-direction: column; justify-content: center; align-items: center; min-height: 100vh; background-color: #f8fafc; }
        .content { text-align: center; }
    </style>
</head>
<body>
    <div class="content">
        <h1 class="display-5 fw-bold">Welcome to AIGenerationToolkit</h1>
        <p class="fs-4">Your powerful assistant for AI content generation.</p>
        <div class="mt-4">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-lg">Go to Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg ms-2">Register</a>
                    @endif
                @endauth
            @endif
        </div>
    </div>
</body>
</html>