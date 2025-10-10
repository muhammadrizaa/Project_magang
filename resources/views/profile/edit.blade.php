{{-- Menentukan layout yang akan digunakan berdasarkan role user --}}
@if(auth()->user()->role == 'admin')
<x-admin-layout>
    <style>
        .profile-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
            gap: 24px;
        }

        .card {
            background-color: #fff;
            padding: 24px;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1),
                        0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .card-header {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1f2937;
        }

        .card-description {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 4px;
        }

        .form-group {
            margin-top: 1.25rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #374151;
        }

        .form-group input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            box-sizing: border-box;
        }

        .form-group input:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
            border-color: #ef4444;
        }

        .form-footer {
            margin-top: 1.5rem;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }

        .btn-red {
            background-color: #dc2626;
            color: white;
        }

        .btn-red:hover {
            background-color: #b91c1c;
        }

        .status-message {
            font-size: 0.875rem;
            font-weight: 500;
            color: #166534;
        }
    </style>

    <div class="profile-container">
        <!-- KARTU INFORMASI PROFIL -->
        <div class="card">
            <header>
                <h2 class="card-header">Informasi Profil</h2>
                <p class="card-description">Perbarui informasi profil dan alamat email akun Anda.</p>
            </header>

            <form method="post" action="{{ route('profile.update') }}">
                @csrf
                @method('patch')

                <div class="form-group">
                    <label for="name">Nama</label>
                    <input id="name" name="name" type="text"
                           value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input id="username" name="username" type="text"
                           value="{{ old('username', $user->username) }}" required autocomplete="username" />
                    @error('username')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email"
                           value="{{ old('email', $user->email) }}" required autocomplete="email" />
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-footer">
                    <button type="submit" class="btn btn-red">Simpan</button>
                    @if (session('status') === 'profile-updated')
                        <p class="status-message">Tersimpan.</p>
                    @endif
                </div>
            </form>
        </div>

        <!-- KARTU UPDATE PASSWORD -->
        <div class="card">
            <header>
                <h2 class="card-header">Update Password</h2>
                <p class="card-description">Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman.</p>
            </header>

            <form method="post" action="{{ route('password.update') }}">
                @csrf
                @method('put')

                <div class="form-group">
                    <label for="current_password">Password Saat Ini</label>
                    <input id="current_password" name="current_password" type="password" autocomplete="current-password" />
                    @error('current_password', 'updatePassword')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password Baru</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" />
                    @error('password', 'updatePassword')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password Baru</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" />
                    @error('password_confirmation', 'updatePassword')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-footer">
                    <button type="submit" class="btn btn-red">Simpan</button>
                    @if (session('status') === 'password-updated')
                        <p class="status-message">Tersimpan.</p>
                    @endif
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>

@else
{{-- BAGIAN KARYAWAN --}}
<x-karyawan-layout>
    <style>
        .profile-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
            gap: 24px;
        }

        .card {
            background-color: #fff;
            padding: 24px;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1),
                        0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .card-header {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1f2937;
        }

        .card-description {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 4px;
        }

        .form-group {
            margin-top: 1.25rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #374151;
        }

        .form-group input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            box-sizing: border-box;
        }

        .form-group input:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
            border-color: #ef4444;
        }

        .form-footer {
            margin-top: 1.5rem;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }

        .btn-red {
            background-color: #dc2626;
            color: white;
        }

        .btn-red:hover {
            background-color: #b91c1c;
        }

        .status-message {
            font-size: 0.875rem;
            font-weight: 500;
            color: #166534;
        }
    </style>

    <div class="profile-container">
        <!-- PROFIL -->
        <div class="card">
            <header>
                <h2 class="card-header">Informasi Profil</h2>
                <p class="card-description">Perbarui informasi profil dan alamat email akun Anda.</p>
            </header>

            <form method="post" action="{{ route('profile.update') }}">
                @csrf
                @method('patch')

                <div class="form-group">
                    <label for="name">Nama</label>
                    <input id="name" name="name" type="text"
                           value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input id="username" name="username" type="text"
                           value="{{ old('username', $user->username) }}" required autocomplete="username" />
                    @error('username')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email"
                           value="{{ old('email', $user->email) }}" required autocomplete="email" />
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-footer">
                    <button type="submit" class="btn btn-red">Simpan</button>
                    @if (session('status') === 'profile-updated')
                        <p class="status-message">Tersimpan.</p>
                    @endif
                </div>
            </form>
        </div>

        <!-- PASSWORD -->
        <div class="card">
            <header>
                <h2 class="card-header">Update Password</h2>
                <p class="card-description">Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman.</p>
            </header>

            <form method="post" action="{{ route('password.update') }}">
                @csrf
                @method('put')

                <div class="form-group">
                    <label for="current_password">Password Saat Ini</label>
                    <input id="current_password" name="current_password" type="password" autocomplete="current-password" />
                    @error('current_password', 'updatePassword')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password Baru</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" />
                    @error('password', 'updatePassword')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password Baru</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" />
                    @error('password_confirmation', 'updatePassword')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-footer">
                    <button type="submit" class="btn btn-red">Simpan</button>
                    @if (session('status') === 'password-updated')
                        <p class="status-message">Tersimpan.</p>
                    @endif
                </div>
            </form>
        </div>
    </div>
</x-karyawan-layout>
@endif
