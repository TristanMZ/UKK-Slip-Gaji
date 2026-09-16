@extends('layouts.app')

@section('title', 'Slip Gaji - ' . $slipGaji->karyawan->nama)

@section('content')
    <div class="section-heading no-print">
        <div>
            <h1>Slip gaji</h1>
            <div class="desc">Slip gaji {{ $slipGaji->karyawan->nama }} berhasil dibuat.</div>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">&larr; Kembali ke riwayat</a>
    </div>

    @if (session('sukses'))
        <div class="alert alert-success no-print">{{ session('sukses') }}</div>
    @endif

    <div class="slip-sheet">
        <div class="slip-sheet__head">
            <h1>Slip Gaji Karyawan</h1>
            <div class="periode">
                Periode {{ $slipGaji->periode_awal->format('d F Y') }} &ndash;
                {{ $slipGaji->periode_akhir->format('d F Y') }}
            </div>
        </div>

        <div class="slip-sheet__body">
            <dl class="slip-meta">
                <dt>Nama</dt>
                <dd>{{ $slipGaji->karyawan->nama }}</dd>
                <dt>NIK</dt>
                <dd>{{ $slipGaji->karyawan->nik }}</dd>
                <dt>Jabatan</dt>
                <dd>{{ $slipGaji->karyawan->jabatan }}</dd>
                <dt>Email</dt>
                <dd>{{ $slipGaji->karyawan->email ?? '-' }}</dd>
                <dt>WhatsApp</dt>
                <dd>{{ $slipGaji->karyawan->whatsapp ?? '-' }}</dd>
            </dl>

            <div class="slip-columns">
                <div class="slip-col">
                    <h3>Penghasilan</h3>
                    <div class="slip-row">
                        <span class="label">Gaji pokok</span>
                        <span class="value">Rp {{ number_format($slipGaji->gaji_pokok, 0, ',', '.') }}</span>
                    </div>
                    <div class="slip-row">
                        <span class="label">Lembur</span>
                        <span class="value">Rp {{ number_format($slipGaji->lembur, 0, ',', '.') }}</span>
                    </div>
                    <div class="slip-row total">
                        <span class="label">Total penghasilan</span>
                        <span class="value">Rp {{ number_format($slipGaji->total_penghasilan, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="slip-col">
                    <h3>Potongan</h3>
                    <div class="slip-row">
                        <span class="label">Pinjaman karyawan</span>
                        <span class="value">Rp {{ number_format($slipGaji->pinjaman_karyawan, 0, ',', '.') }}</span>
                    </div>
                    <div class="slip-row total">
                        <span class="label">Total potongan</span>
                        <span class="value">Rp {{ number_format($slipGaji->total_potongan, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="slip-bersih">
                <span class="label">Gaji bersih</span>
                <span class="value">Rp {{ number_format($slipGaji->gaji_bersih, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="slip-sheet__foot no-print">
            <button type="button" id="print-btn" class="btn btn-primary">Cetak slip</button>
            <a href="{{ route('slip.create') }}" class="btn btn-secondary">Buat slip lain</a>
            <a href="{{ route('slip.edit', $slipGaji) }}" class="btn btn-secondary">Edit</a>
            <form method="POST" action="{{ route('slip.email', $slipGaji) }}">
                @csrf
                <button type="submit" class="btn btn-secondary icon-btn" aria-label="Kirim email" title="Kirim email">
                    <svg viewBox="0 0 24 24" aria-hidden="true" width="18" height="18" fill="none"
                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M4 6.75A2.75 2.75 0 0 1 6.75 4h10.5A2.75 2.75 0 0 1 20 6.75v10.5A2.75 2.75 0 0 1 17.25 20H6.75A2.75 2.75 0 0 1 4 17.25V6.75Z" />
                        <path d="M4.5 7.5 12 12.75 19.5 7.5" />
                    </svg>
                </button>
            </form>
            <a href="{{ route('slip.whatsapp', $slipGaji) }}" class="btn btn-secondary icon-btn" target="_blank"
                rel="noopener noreferrer" aria-label="Kirim WhatsApp" title="Kirim WhatsApp">
                <svg viewBox="0 0 24 24" aria-hidden="true" width="18" height="18" fill="currentColor">
                    <path
                        d="M12.04 2.02c-5.52 0-10 4.35-10 9.71 0 1.82.5 3.56 1.36 5.08L2 22l5.33-1.4a9.74 9.74 0 0 0 4.71 1.18h.01c5.51 0 9.98-4.35 9.98-9.71 0-5.36-4.47-9.71-9.99-9.71Zm5.34 13.45c-.14.4-.82.76-1.14.81-.29.05-.68.07-1.1-.08-.25-.08-.58-.19-.99-.38-1.74-.75-2.88-2.57-2.97-2.68-.09-.11-.77-1.04-.77-1.98s.49-1.4.66-1.6c.17-.2.37-.25.5-.25h.37c.12 0 .29.01.45.34.2.42.69 1.52.75 1.63.06.11.11.25.02.4-.09.15-.14.25-.27.39-.13.14-.28.31-.39.42-.13.13-.27.27-.12.52.15.25.69 1.12 1.49 1.81.99.9 1.84 1.19 2.1 1.32.26.14.4.11.54-.07.15-.18.64-.74.82-1 .17-.26.34-.22.57-.14.23.08 1.45.68 1.7.8.25.12.4.18.45.28.05.1.05.64-.09 1.04Z" />
                </svg>
            </a>
            <form method="POST" action="{{ route('slip.destroy', $slipGaji) }}"
                onsubmit="return confirm('Yakin ingin menghapus slip gaji ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Hapus</button>
            </form>
        </div>
    </div>
@endsection
