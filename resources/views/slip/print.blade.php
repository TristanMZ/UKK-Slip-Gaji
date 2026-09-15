@extends('layouts.app')

@section('title', 'Print Slip Gaji — Slip Gaji Karyawan')

@section('content')
    @php
        $selectedPeriodeAwal = $periodeAwal ?? null;
        $selectedPeriodeAkhir = $periodeAkhir ?? null;
    @endphp
    @if ($slipGaji->isEmpty())
        <div class="card">
            <div class="empty-state">
                <p>Tidak ada slip gaji yang cocok dengan filter periode.</p>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    @else
        <div class="slip-print-list no-print">
            <div class="section-heading">
                <div>
                    <h1>Print slip gaji</h1>
                    <div class="desc">Ringkasan slip gaji untuk periode yang dipilih.</div>
                </div>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">&larr; Kembali</a>
            </div>

            @if ($selectedPeriodeAwal || $selectedPeriodeAkhir)
                <div class="alert alert-info">
                    Menampilkan semua slip yang termasuk dalam rentang periode
                    {{ $selectedPeriodeAwal ? \Illuminate\Support\Carbon::parse($selectedPeriodeAwal)->format('d M Y') : '—' }}
                    sampai
                    {{ $selectedPeriodeAkhir ? \Illuminate\Support\Carbon::parse($selectedPeriodeAkhir)->format('d M Y') : '—' }}
                </div>
            @endif
        </div>

        <div class="card">
            <div class="slip-summary-grid">
                <div class="summary-box">
                    <div class="summary-box__label">Jumlah slip</div>
                    <div class="summary-box__value summary-box__value--large">{{ $jumlahSlip }}</div>
                </div>
                <div class="summary-box">
                    <div class="summary-box__label">Total penghasilan</div>
                    <div class="summary-box__value">Rp {{ number_format($totalPenghasilan, 0, ',', '.') }}</div>
                </div>
                <div class="summary-box">
                    <div class="summary-box__label">Total potongan</div>
                    <div class="summary-box__value">Rp {{ number_format($totalPotongan, 0, ',', '.') }}</div>
                </div>
                <div class="summary-box">
                    <div class="summary-box__label">Total gaji bersih</div>
                    <div class="summary-box__value">Rp {{ number_format($totalGajiBersih, 0, ',', '.') }}</div>
                </div>
            </div>

            <div class="table-wrap">
                <table class="ledger">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>Periode</th>
                            <th class="num">Penghasilan</th>
                            <th class="num">Potongan</th>
                            <th class="num">Gaji bersih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($slipGaji as $slip)
                            <tr>
                                <td>{{ $slip->karyawan->nama }}</td>
                                <td>{{ $slip->karyawan->nik }}</td>
                                <td>{{ $slip->periode_awal->format('d M Y') }} &ndash;
                                    {{ $slip->periode_akhir->format('d M Y') }}</td>
                                <td class="num">Rp {{ number_format($slip->total_penghasilan, 0, ',', '.') }}</td>
                                <td class="num">Rp {{ number_format($slip->total_potongan, 0, ',', '.') }}</td>
                                <td class="num">Rp {{ number_format($slip->gaji_bersih, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="slip-actions no-print">
            <button type="button" class="btn btn-primary" onclick="window.print()">Print semua</button>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Kembali</a>
        </div>
    @endif
@endsection
