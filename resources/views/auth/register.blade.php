<x-guest-layout>
    <div class="container container-tight py-4">
        <div class="text-center mb-4">
            <a href="{{ route('home') }}" class="navbar-brand navbar-brand-autodark">
                <img src="https://placehold.co/110x32/206BC4/FFFFFF?text=AIGenKit" height="36" alt="AIGenerationToolkit">
            </a>
        </div>
        <div class="card card-md">
            <div class="card-body">
                <h2 class="h2 text-center mb-4">Buat akun baru</h2>
                <form method="POST" action="{{ route('register') }}" novalidate>
                    @csrf

                    {{-- Input Nama --}}
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Masukkan nama Anda" value="{{ old('name') }}" required autofocus autocomplete="name">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Input Email --}}
                    <div class="mb-3">
                        <label class="form-label">Alamat Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="your@email.com" value="{{ old('email') }}" required autocomplete="username">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    {{-- Input Activation Code --}}
                    <div class="mb-3">
                        <label class="form-label">Kode Aktivasi</label>
                        <input type="text" name="activation_code" class="form-control @error('activation_code') is-invalid @enderror" placeholder="Masukkan kode aktivasi Anda" value="{{ old('activation_code') }}" required>
                        @error('activation_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Input Password --}}
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password Anda" required autocomplete="new-password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Input Konfirmasi Password --}}
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ketik ulang password Anda" required autocomplete="new-password">
                    </div>

                    <div class="mb-3">
                        <label class="form-check">
                            <input type="checkbox" class="form-check-input" required/>
                            <span class="form-check-label">Setuju dengan <a href="#" tabindex="-1">Syarat & Ketentuan</a>.</span>
                        </label>
                    </div>
                    
                    {{-- Tombol Submit --}}
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">Buat Akun</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="text-center text-muted mt-3">
            Sudah punya akun? <a href="{{ route('login') }}" tabindex="-1">Login</a>
        </div>
    </div>
</x-guest-layout>