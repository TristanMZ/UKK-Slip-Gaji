<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji</title>
</head>

<body style="margin:0; padding:24px; font-family:Arial, sans-serif; background:#f5f7fb; color:#1f2937;">
    <div
        style="max-width:720px; margin:0 auto; background:#ffffff; border-radius:12px; border:1px solid #dfe7f1; padding:24px;">
        <h2 style="margin:0 0 16px; color:#111827;">Slip Gaji Karyawan</h2>
        <p style="margin:0 0 12px;">Halo {{ $slipGaji->karyawan->nama }},</p>
        <p style="margin:0 0 18px;">Berikut slip gaji Anda untuk periode {{ $slipGaji->periode_awal->format('d M Y') }} -
            {{ $slipGaji->periode_akhir->format('d M Y') }}.</p>

        <table cellpadding="8" cellspacing="0"
            style="width:100%; border-collapse:collapse; border:1px solid #e5e7eb; margin-bottom:18px;">
            <tr style="background:#f8fafc;">
                <td style="border:1px solid #e5e7eb; font-weight:bold; width:180px;">Nama</td>
                <td style="border:1px solid #e5e7eb;">{{ $slipGaji->karyawan->nama }}</td>
            </tr>
            <tr>
                <td style="border:1px solid #e5e7eb; font-weight:bold;">NIK</td>
                <td style="border:1px solid #e5e7eb;">{{ $slipGaji->karyawan->nik }}</td>
            </tr>
            <tr style="background:#f8fafc;">
                <td style="border:1px solid #e5e7eb; font-weight:bold;">Jabatan</td>
                <td style="border:1px solid #e5e7eb;">{{ $slipGaji->karyawan->jabatan }}</td>
            </tr>
            <tr>
                <td style="border:1px solid #e5e7eb; font-weight:bold;">Gaji Pokok</td>
                <td style="border:1px solid #e5e7eb;">Rp {{ number_format($slipGaji->gaji_pokok, 0, ',', '.') }}</td>
            </tr>
            <tr style="background:#f8fafc;">
                <td style="border:1px solid #e5e7eb; font-weight:bold;">Lembur</td>
                <td style="border:1px solid #e5e7eb;">Rp {{ number_format($slipGaji->lembur, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="border:1px solid #e5e7eb; font-weight:bold;">Pinjaman</td>
                <td style="border:1px solid #e5e7eb;">Rp {{ number_format($slipGaji->pinjaman_karyawan, 0, ',', '.') }}
                </td>
            </tr>
            <tr style="background:#f8fafc;">
                <td style="border:1px solid #e5e7eb; font-weight:bold;">Total Penghasilan</td>
                <td style="border:1px solid #e5e7eb;">Rp {{ number_format($slipGaji->total_penghasilan, 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td style="border:1px solid #e5e7eb; font-weight:bold;">Total Potongan</td>
                <td style="border:1px solid #e5e7eb;">Rp {{ number_format($slipGaji->total_potongan, 0, ',', '.') }}
                </td>
            </tr>
            <tr style="background:#eefbf4;">
                <td style="border:1px solid #cfe6de; font-weight:bold;">Gaji Bersih</td>
                <td style="border:1px solid #cfe6de; font-weight:bold; color:#1f7a52;">Rp
                    {{ number_format($slipGaji->gaji_bersih, 0, ',', '.') }}</td>
            </tr>
        </table>

        <p style="margin:0; color:#4b5563;">Terima kasih.</p>
    </div>
</body>

</html>
