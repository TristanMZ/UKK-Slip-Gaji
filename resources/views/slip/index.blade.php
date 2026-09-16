@extends('layouts.app')

@section('title', 'Dashboard Slip Gaji Karyawan')

@section('content')
    <div class="section-heading">
        <div>
            <h1>Riwayat slip gaji</h1>
            <div class="desc">Daftar slip gaji yang sudah dibuat.</div>
        </div>
        <a href="{{ route('slip.create') }}" class="btn btn-primary">+ Buat slip gaji</a>
    </div>

    @if (session('sukses'))
        <div class="alert alert-success">{{ session('sukses') }}</div>
    @endif

    <div class="filter-bar no-print">
        <form method="GET" action="{{ route('dashboard') }}" class="filter-form">
            <div class="field-row">
                <div class="field">
                    <label for="periode_awal">Periode awal</label>
                    <input type="date" id="periode_awal" name="periode_awal" value="{{ request('periode_awal') }}">
                </div>
                <div class="field">
                    <label for="periode_akhir">Periode akhir</label>
                    <input type="date" id="periode_akhir" name="periode_akhir" value="{{ request('periode_akhir') }}">
                </div>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn btn-secondary">Filter</button>
                <a href="{{ route('slip.print', ['periode_awal' => request('periode_awal'), 'periode_akhir' => request('periode_akhir')]) }}"
                    class="btn btn-primary">Print</a>
            </div>
        </form>
    </div>

    <div class="card">
        @if ($slipGaji->isEmpty())
            <div class="empty-state">
                <div class="icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8">
                        <path d="M6 3h9l5 5v13a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1Z" />
                        <path d="M14 3v5h5" />
                    </svg>
                </div>
                <p>Belum ada slip gaji yang dibuat.</p>
                <a href="{{ route('slip.create') }}" class="btn btn-secondary">Buat slip gaji pertama</a>
            </div>
        @else
            <div class="table-wrap">
                <table class="ledger">
                    <thead>
                        <tr>
                            <th>Karyawan</th>
                            <th>Jabatan</th>
                            <th>Periode</th>
                            <th class="num">Gaji bersih</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($slipGaji as $slip)
                            <tr>
                                <td>{{ $slip->karyawan->nama }}</td>
                                <td>{{ $slip->karyawan->jabatan }}</td>
                                <td>{{ $slip->periode_awal->format('d M Y') }} &ndash;
                                    {{ $slip->periode_akhir->format('d M Y') }}</td>
                                <td class="num">Rp {{ number_format($slip->gaji_bersih, 0, ',', '.') }}</td>
                                <td><a href="{{ route('slip.show', $slip) }}">Lihat</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrap">
                {{ $slipGaji->links() }}
            </div>
        @endif
    </div>
@endsection
