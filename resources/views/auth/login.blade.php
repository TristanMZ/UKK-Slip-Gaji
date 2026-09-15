@extends('layouts.app')

@section('title', 'Masuk — Slip Gaji Karyawan')

@section('content')
<div class="login-shell">
    <div class="login-panel">
        <div>
            <div class="tag">Sistem Penggajian Internal</div>
            <h2>Kelola dan cetak slip gaji karyawan dengan rapi, dalam satu tempat.</h2>
        </div>
        <div class="foot">Junior Web Programmer &middot; Latihan Uji Kompetensi</div>
    </div>

    <div class="login-form-side">
        <div class="card">
            <h1>Masuk</h1>
            <p class="desc">Silakan masukkan username dan kata sandi Anda.</p>

            @if ($errors->any())
                <div class="alert alert-error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}">
                @csrf

                <div class="field @error('username') has-error @enderror">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" autofocus required>
                </div>

                <div class="field @error('password') has-error @enderror">
                    <label for="password">Kata sandi</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Masuk</button>
            </form>

            <p style="text-align:center; margin-top: 16px; font-size: 0.85rem;">
                <a href="#">Lupa kata sandi?</a>
            </p>

            <div class="login-hint">
                Akun contoh : username <strong>admin</strong>, kata sandi <strong>password123</strong>
            </div>
        </div>
    </div>
</div>
@endsection
