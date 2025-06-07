<x-guest-layout>
    <div class="container container-tight py-4">
        <div class="text-center mb-4">
            <a href="{{ route('home') }}" class="navbar-brand navbar-brand-autodark">
                {{-- Anda bisa mengganti placeholder ini dengan logo Anda --}}
                <img src="https://placehold.co/110x32/206BC4/FFFFFF?text=AIGenKit" height="36" alt="AIGenerationToolkit">
            </a>
        </div>
        <div class="card card-md">
            <div class="card-body">
                <h2 class="h2 text-center mb-4">Login ke akun Anda</h2>

                <!-- Session Status (misal: notifikasi setelah reset password) -->
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" novalidate>
                    @csrf
                    
                    {{-- Input Email --}}
                    <div class="mb-3">
                        <label class="form-label">Alamat Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="your@email.com" value="{{ old('email') }}" required autofocus autocomplete="username">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Input Password --}}
                    <div class="mb-2">
                        <label class="form-label">
                            Password
                            @if (Route::has('password.request'))
                                <span class="form-label-description">
                                    <a href="{{ route('password.request') }}">Lupa password?</a>
                                </span>
                            @endif
                        </label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password Anda" required autocomplete="current-password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Checkbox "Remember me" --}}
                    <div class="mb-2">
                        <label class="form-check">
                            <input type="checkbox" class="form-check-input" name="remember"/>
                            <span class="form-check-label">Ingat saya di perangkat ini</span>
                        </label>
                    </div>

                    {{-- Tombol Submit --}}
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">Sign in</button>
                    </div>
                </form>
            </div>
        </div>
        @if (Route::has('register'))
            <div class="text-center text-muted mt-3">
                Belum punya akun? <a href="{{ route('register') }}" tabindex="-1">Daftar</a>
            </div>
        @endif
    </div>
</x-guest-layout>