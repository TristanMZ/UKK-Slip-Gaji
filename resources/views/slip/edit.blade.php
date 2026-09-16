@extends('layouts.app')

@section('title', 'Edit Slip Gaji - Slip Gaji Karyawan')

@section('content')
    <div class="section-heading">
        <div>
            <h1>Edit slip gaji</h1>
            <div class="desc">Perbarui data slip gaji dan karyawan jika diperlukan.</div>
        </div>
        <a href="{{ route('slip.show', $slipGaji) }}" class="btn btn-secondary">&larr; Kembali</a>
    </div>

    <div class="card">
        @if ($errors->any())
            <div class="alert alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('slip.update', $slipGaji) }}">
            @csrf
            @method('PUT')

            <div class="field @error('nik') has-error @enderror">
                <label for="nik">NIK karyawan</label>
                <input type="text" id="nik" name="nik" value="{{ old('nik', $slipGaji->karyawan->nik) }}"
                    data-search-url="{{ route('karyawan.cari') }}" required>
                <div class="hint">Ketik NIK untuk mengisi otomatis nama. Jika belum ada, isi nama di bawah untuk membuat
                    data karyawan baru.</div>
                <div id="karyawan-not-found" class="alert alert-error" style="display:none;">
                    NIK tidak ditemukan dalam data karyawan. Isi nama di bawah lalu simpan untuk membuat data karyawan baru.
                </div>
            </div>

            <div class="field @error('nama') has-error @enderror">
                <label for="nama">Nama karyawan</label>
                <input type="text" id="nama" name="nama" value="{{ old('nama', $slipGaji->karyawan->nama) }}"
                    placeholder="Isi nama lengkap jika karyawan baru">
            </div>

            <div class="field @error('jabatan') has-error @enderror">
                <label for="jabatan">Jabatan</label>
                <input type="text" id="jabatan" name="jabatan"
                    value="{{ old('jabatan', $slipGaji->karyawan->jabatan) }}" placeholder="Contoh: Staff Administrasi"
                    maxlength="100" required>
            </div>

            <div class="field-row">
                <div class="field @error('email') has-error @enderror">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email"
                        value="{{ old('email', $slipGaji->karyawan->email) }}" placeholder="contoh@email.com">
                </div>
                <div class="field @error('whatsapp') has-error @enderror">
                    <label for="whatsapp">WhatsApp</label>
                    <input type="text" id="whatsapp" name="whatsapp"
                        value="{{ old('whatsapp', $slipGaji->karyawan->whatsapp) }}" placeholder="08xxxxxxxxxx">
                </div>
            </div>

            <div id="karyawan-preview" class="alert alert-success" style="display:none;">
                <strong id="preview-nama"></strong> &middot; <span id="preview-jabatan"></span>
            </div>

            <div class="field-row">
                <div class="field @error('periode_awal') has-error @enderror">
                    <label for="periode_awal">Periode awal</label>
                    <input type="date" id="periode_awal" name="periode_awal"
                        value="{{ old('periode_awal', $slipGaji->periode_awal->format('Y-m-d')) }}" required>
                </div>
                <div class="field @error('periode_akhir') has-error @enderror">
                    <label for="periode_akhir">Periode akhir</label>
                    <input type="date" id="periode_akhir" name="periode_akhir"
                        value="{{ old('periode_akhir', $slipGaji->periode_akhir->format('Y-m-d')) }}" required>
                </div>
            </div>

            <div class="field @error('gaji_pokok') has-error @enderror">
                <label for="gaji_pokok">Gaji pokok</label>
                <div class="input-affix">
                    <span class="prefix">Rp</span>
                    <input type="number" min="0" step="1000" id="gaji_pokok" name="gaji_pokok"
                        value="{{ old('gaji_pokok', $slipGaji->gaji_pokok) }}" required>
                </div>
            </div>

            <div class="field-row">
                <div class="field @error('lembur') has-error @enderror">
                    <label for="lembur">Lembur</label>
                    <div class="input-affix">
                        <span class="prefix">Rp</span>
                        <input type="number" min="0" step="1000" id="lembur" name="lembur"
                            value="{{ old('lembur', $slipGaji->lembur) }}">
                    </div>
                </div>
                <div class="field @error('pinjaman_karyawan') has-error @enderror">
                    <label for="pinjaman_karyawan">Pinjaman karyawan</label>
                    <div class="input-affix">
                        <span class="prefix">Rp</span>
                        <input type="number" min="0" step="1000" id="pinjaman_karyawan" name="pinjaman_karyawan"
                            value="{{ old('pinjaman_karyawan', $slipGaji->pinjaman_karyawan) }}">
                    </div>
                </div>
            </div>

            <div class="field @error('captcha') has-error @enderror">
                <label for="captcha">Captcha</label>
                <div class="captcha-row">
                    <img src="{{ route('captcha') }}?v={{ time() }}" alt="Captcha" id="captcha-image"
                        class="captcha-image">
                    <button type="button" class="captcha-refresh" id="captcha-refresh-btn"
                        data-base-url="{{ route('captcha') }}" title="Ganti captcha">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M3 12a9 9 0 0 1 15.3-6.4L21 8" />
                            <path d="M21 3v5h-5" />
                            <path d="M21 12a9 9 0 0 1-15.3 6.4L3 16" />
                            <path d="M3 21v-5h5" />
                        </svg>
                    </button>
                    <input type="text" id="captcha" name="captcha" placeholder="Masukkan kode captcha"
                        maxlength="5" class="captcha-input" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Simpan perubahan</button>
        </form>
    </div>
@endsection
