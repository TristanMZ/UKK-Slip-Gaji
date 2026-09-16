<?php

namespace App\Http\Controllers;

use App\Mail\SlipGajiMail;
use App\Models\Karyawan;
use App\Models\SlipGaji;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Throwable;

class SlipGajiController extends Controller
{
    /**
     * Dashboard: daftar riwayat slip gaji yang pernah dibuat.
     */
    public function index(Request $request)
    {
        $query = SlipGaji::with('karyawan')->latest();

        $periodeAwal = $request->input('periode_awal');
        $periodeAkhir = $request->input('periode_akhir');

        if ($periodeAwal || $periodeAkhir) {
            if (! empty($periodeAwal)) {
                $query->where('periode_awal', '>=', $periodeAwal);
            }

            if (! empty($periodeAkhir)) {
                $query->where('periode_akhir', '<=', $periodeAkhir);
            }
        }

        $slipGaji = $query->paginate(8)->appends($request->query());

        return view('slip.index', compact('slipGaji'));
    }

    /**
     * Form pembuatan slip gaji baru.
     */
    public function create()
    {
        return view('slip.create');
    }

    /**
     * Hitung & simpan slip gaji baru.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nik' => ['required', 'string', 'max:20'],
            'nama' => ['nullable', 'string', 'max:255'],
            'jabatan' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'periode_awal' => ['required', 'date'],
            'periode_akhir' => ['required', 'date', 'after_or_equal:periode_awal'],
            'gaji_pokok' => ['required', 'numeric', 'min:0'],
            'lembur' => ['nullable', 'numeric', 'min:0'],
            'pinjaman_karyawan' => ['nullable', 'numeric', 'min:0'],
            'captcha' => ['required', 'string', 'max:5'],
        ]);

        $captcha = strtoupper(trim((string) $data['captcha']));
        $captchaSession = strtoupper(trim((string) session('captcha_code', '')));

        if ($captcha !== $captchaSession) {
            throw ValidationException::withMessages([
                'captcha' => 'Kode captcha salah, silakan coba lagi.',
            ]);
        }

        $karyawan = Karyawan::where('nik', $data['nik'])->first();

        if (! $karyawan) {
            if (blank($data['nama'] ?? null)) {
                throw ValidationException::withMessages([
                    'nama' => 'Nama karyawan wajib diisi untuk membuat data karyawan baru.',
                ]);
            }

            $karyawan = Karyawan::create([
                'nik' => $data['nik'],
                'nama' => $data['nama'],
                'jabatan' => $data['jabatan'],
                'email' => filled($data['email'] ?? null) ? $data['email'] : null,
                'whatsapp' => filled($data['whatsapp'] ?? null) ? $data['whatsapp'] : null,
                'gaji_pokok' => 0,
            ]);
        } else {
            $karyawan->update([
                'nama' => $data['nama'] ?? $karyawan->nama,
                'jabatan' => $data['jabatan'],
                'email' => filled($data['email'] ?? null) ? $data['email'] : $karyawan->email,
                'whatsapp' => filled($data['whatsapp'] ?? null) ? $data['whatsapp'] : $karyawan->whatsapp,
            ]);
        }

        $gajiPokok = (float) $data['gaji_pokok'];
        $lembur = (float) ($data['lembur'] ?? 0);
        $pinjaman = (float) ($data['pinjaman_karyawan'] ?? 0);

        // Total Penghasilan = Gaji Pokok + Lembur
        $totalPenghasilan = $gajiPokok + $lembur;
        // Total Potongan = Pinjaman Karyawan
        $totalPotongan = $pinjaman;
        // Gaji Bersih = Total Penghasilan - Total Potongan
        $gajiBersih = $totalPenghasilan - $totalPotongan;

        $slip = SlipGaji::create([
            'karyawan_id' => $karyawan->id,
            'user_id' => auth()->id(),
            'periode_awal' => $data['periode_awal'],
            'periode_akhir' => $data['periode_akhir'],
            'gaji_pokok' => $gajiPokok,
            'lembur' => $lembur,
            'pinjaman_karyawan' => $pinjaman,
            'total_penghasilan' => $totalPenghasilan,
            'total_potongan' => $totalPotongan,
            'gaji_bersih' => $gajiBersih,
        ]);

        session()->forget('captcha_code');

        return redirect()->route('slip.show', $slip)->with('sukses', 'Slip gaji berhasil dibuat.');
    }

    /**
     * Tampilkan slip gaji (siap cetak).
     */
    public function show(SlipGaji $slipGaji)
    {
        $slipGaji->load('karyawan');

        return view('slip.show', compact('slipGaji'));
    }

    /**
     * Cetak beberapa slip gaji berdasarkan periode.
     */
    public function printByPeriode(Request $request)
    {
        $data = $request->validate([
            'periode_awal' => ['nullable', 'date'],
            'periode_akhir' => ['nullable', 'date', 'after_or_equal:periode_awal'],
        ]);

        $periodeAwal = $data['periode_awal'] ?? null;
        $periodeAkhir = $data['periode_akhir'] ?? null;

        $query = SlipGaji::with('karyawan')->orderBy('periode_awal');

        if ($periodeAwal || $periodeAkhir) {
            if (! empty($periodeAwal)) {
                $query->where('periode_awal', '>=', $periodeAwal);
            }

            if (! empty($periodeAkhir)) {
                $query->where('periode_akhir', '<=', $periodeAkhir);
            }
        }

        $slipGaji = $query->get();

        $jumlahSlip = $slipGaji->count();
        $totalPenghasilan = $slipGaji->sum('total_penghasilan');
        $totalPotongan = $slipGaji->sum('total_potongan');
        $totalGajiBersih = $slipGaji->sum('gaji_bersih');

        return view('slip.print', compact(
            'slipGaji',
            'periodeAwal',
            'periodeAkhir',
            'jumlahSlip',
            'totalPenghasilan',
            'totalPotongan',
            'totalGajiBersih'
        ));
    }

    /**
     * Halaman edit slip gaji.
     */
    public function edit(SlipGaji $slipGaji)
    {
        $slipGaji->load('karyawan');

        return view('slip.edit', compact('slipGaji'));
    }

    /**
     * Update slip gaji.
     */
    public function update(Request $request, SlipGaji $slipGaji)
    {
        $data = $request->validate([
            'nik' => ['required', 'string', 'max:20'],
            'nama' => ['nullable', 'string', 'max:255'],
            'jabatan' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'periode_awal' => ['required', 'date'],
            'periode_akhir' => ['required', 'date', 'after_or_equal:periode_awal'],
            'gaji_pokok' => ['required', 'numeric', 'min:0'],
            'lembur' => ['nullable', 'numeric', 'min:0'],
            'pinjaman_karyawan' => ['nullable', 'numeric', 'min:0'],
            'captcha' => ['required', 'string', 'max:5'],
        ]);

        $captcha = strtoupper(trim((string) $data['captcha']));
        $captchaSession = strtoupper(trim((string) session('captcha_code', '')));

        if ($captcha !== $captchaSession) {
            throw ValidationException::withMessages([
                'captcha' => 'Kode captcha salah, silakan coba lagi.',
            ]);
        }

        $karyawan = Karyawan::where('nik', $data['nik'])->first();

        if (! $karyawan) {
            if (blank($data['nama'] ?? null)) {
                throw ValidationException::withMessages([
                    'nama' => 'Nama karyawan wajib diisi untuk membuat data karyawan baru.',
                ]);
            }

            $karyawan = Karyawan::create([
                'nik' => $data['nik'],
                'nama' => $data['nama'],
                'jabatan' => $data['jabatan'],
                'email' => filled($data['email'] ?? null) ? $data['email'] : null,
                'whatsapp' => filled($data['whatsapp'] ?? null) ? $data['whatsapp'] : null,
                'gaji_pokok' => 0,
            ]);
        } else {
            $karyawan->update([
                'nama' => $data['nama'] ?? $karyawan->nama,
                'jabatan' => $data['jabatan'],
                'email' => filled($data['email'] ?? null) ? $data['email'] : $karyawan->email,
                'whatsapp' => filled($data['whatsapp'] ?? null) ? $data['whatsapp'] : $karyawan->whatsapp,
            ]);
        }

        $gajiPokok = (float) $data['gaji_pokok'];
        $lembur = (float) ($data['lembur'] ?? 0);
        $pinjaman = (float) ($data['pinjaman_karyawan'] ?? 0);

        $totalPenghasilan = $gajiPokok + $lembur;
        $totalPotongan = $pinjaman;
        $gajiBersih = $totalPenghasilan - $totalPotongan;

        $slipGaji->update([
            'karyawan_id' => $karyawan->id,
            'periode_awal' => $data['periode_awal'],
            'periode_akhir' => $data['periode_akhir'],
            'gaji_pokok' => $gajiPokok,
            'lembur' => $lembur,
            'pinjaman_karyawan' => $pinjaman,
            'total_penghasilan' => $totalPenghasilan,
            'total_potongan' => $totalPotongan,
            'gaji_bersih' => $gajiBersih,
        ]);

        session()->forget('captcha_code');

        return redirect()->route('slip.show', $slipGaji)->with('sukses', 'Slip gaji berhasil diperbarui.');
    }

    /**
     * Hapus slip gaji.
     */
    public function destroy(SlipGaji $slipGaji)
    {
        $slipGaji->delete();

        return redirect()->route('dashboard')->with('sukses', 'Slip gaji berhasil dihapus.');
    }

    public function sendEmail(SlipGaji $slipGaji)
    {
        $slipGaji->load('karyawan');

        if (blank($slipGaji->karyawan?->email)) {
            return back()->withErrors([
                'email' => 'Alamat email karyawan belum tersedia.',
            ]);
        }

        if (config('mail.default') !== 'smtp' || blank(config('mail.mailers.smtp.username')) || blank(config('mail.mailers.smtp.password'))) {
            return back()->withErrors([
                'email' => 'Konfigurasi SMTP belum lengkap. Isi MAIL_USERNAME dan MAIL_PASSWORD di file .env.',
            ]);
        }

        try {
            Mail::to($slipGaji->karyawan->email)->send(new SlipGajiMail($slipGaji));
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors([
                'email' => 'Email gagal dikirim. Periksa konfigurasi SMTP Gmail dan App Password.',
            ]);
        }

        return back()->with('sukses', 'Slip gaji berhasil dikirim ke email karyawan.');
    }

    public function sendWhatsapp(SlipGaji $slipGaji)
    {
        $slipGaji->load('karyawan');

        $message = urlencode(
            'Halo ' . $slipGaji->karyawan->nama . ', berikut rincian slip gaji Anda:' . "\n\n" .
            'Periode: ' . $slipGaji->periode_awal->format('d M Y') . ' - ' . $slipGaji->periode_akhir->format('d M Y') . "\n" .
            'Gaji pokok: Rp ' . number_format($slipGaji->gaji_pokok, 0, ',', '.') . "\n" .
            'Lembur: Rp ' . number_format($slipGaji->lembur, 0, ',', '.') . "\n" .
            'Total penghasilan: Rp ' . number_format($slipGaji->total_penghasilan, 0, ',', '.') . "\n" .
            'Pinjaman karyawan: Rp ' . number_format($slipGaji->pinjaman_karyawan, 0, ',', '.') . "\n" .
            'Total potongan: Rp ' . number_format($slipGaji->total_potongan, 0, ',', '.') . "\n" .
            'Gaji bersih: Rp ' . number_format($slipGaji->gaji_bersih, 0, ',', '.') . "\n\n" .
            'Terima kasih.'
        );

        return redirect()->away('https://wa.me/?text=' . $message);
    }

    /**
     * Cari data karyawan berdasarkan NIK (dipakai AJAX di form).
     */
    public function cariKaryawan(Request $request)
    {
        $request->validate(['nik' => 'required|string']);

        $karyawan = Karyawan::where('nik', $request->query('nik'))->first();

        if (! $karyawan) {
            return response()->json(['ditemukan' => false]);
        }

        return response()->json([
            'ditemukan' => true,
            'nama' => $karyawan->nama,
            'jabatan' => $karyawan->jabatan,
            'gaji_pokok' => (float) $karyawan->gaji_pokok,
        ]);
    }

    /**
     * Menghasilkan CAPTCHA SVG dan menyimpan kode ke session Laravel.
     */
    public function captcha()
    {
        $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $length = 5;
        $captchaCode = '';

        for ($i = 0; $i < $length; $i++) {
            $captchaCode .= $characters[random_int(0, strlen($characters) - 1)];
        }

        session(['captcha_code' => $captchaCode]);

        $width = 160;
        $height = 48;
        $bgColors = ['#0f172a', '#1e293b', '#111827'];
        $bg = $bgColors[array_rand($bgColors)];

        $svg = '<?xml version="1.0" encoding="UTF-8"?>';
        $svg .= '<svg xmlns="http://www.w3.org/2000/svg" width="' . $width . '" height="' . $height . '" viewBox="0 0 ' . $width . ' ' . $height . '">';
        $svg .= '<rect width="100%" height="100%" fill="' . $bg . '" rx="8"/>';

        for ($n = 0; $n < 5; $n++) {
            $svg .= '<line x1="' . random_int(0, $width) . '" y1="' . random_int(0, $height) . '" x2="' . random_int(0, $width) . '" y2="' . random_int(0, $height) . '" stroke="rgba(255,255,255,' . (random_int(10, 30) / 100) . ')" stroke-width="' . random_int(1, 2) . '"/>';
        }

        for ($d = 0; $d < 25; $d++) {
            $svg .= '<circle cx="' . random_int(0, $width) . '" cy="' . random_int(0, $height) . '" r="' . random_int(1, 2) . '" fill="rgba(255,255,255,0.2)"/>';
        }

        $charColors = ['#34d399', '#60a5fa', '#f472b6', '#fbbf24', '#a78bfa'];
        for ($i = 0; $i < $length; $i++) {
            $char = $captchaCode[$i];
            $x = 22 + ($i * 26);
            $y = random_int(30, 36);
            $rot = random_int(-15, 15);
            $color = $charColors[array_rand($charColors)];
            $svg .= '<text x="' . $x . '" y="' . $y . '" font-family="\'Courier New\', Courier, monospace" font-size="24" font-weight="bold" fill="' . $color . '" transform="rotate(' . $rot . ', ' . $x . ', ' . $y . ')">' . $char . '</text>';
        }

        $svg .= '</svg>';

        return response($svg, 200)->header('Content-Type', 'image/svg+xml');
    }
}
