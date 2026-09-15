# Slip Gaji Karyawan — Laravel + MySQL (XAMPP)

Aplikasi web untuk membuat dan mencetak slip gaji karyawan, dibuat berdasarkan
soal latihan UKK Junior Web Programmer 2026. Dibangun dengan **Laravel 10**
(PHP) dan **MySQL** (lewat XAMPP), tampilan minimalis modern tanpa perlu Node/npm
(CSS & JS polos, cukup PHP + Composer).

## Fitur

- **Login** petugas (session-based, bukan Laravel Breeze — ringan, mudah dipahami)
- **Cari karyawan otomatis berdasarkan NIK** (AJAX) untuk mengisi Nama & Jabatan
- **Captcha matematika sederhana** (bisa di-refresh tanpa reload halaman)
- **Hitung slip gaji otomatis**:
  - Total Penghasilan = Gaji Pokok + Lembur
  - Total Potongan = Pinjaman Karyawan
  - Gaji Bersih = Total Penghasilan − Total Potongan
- **Riwayat slip gaji** yang pernah dibuat (dengan paginasi)
- **Cetak slip gaji** (tampilan rapi khusus print, lewat `window.print()`)
- Desain minimalis modern: warna teal gelap sebagai aksen tunggal, angka rata kanan
  ala buku besar (ledger), tipografi Space Grotesk + Inter

## Struktur data

| Tabel        | Isi                                                              |
|--------------|-------------------------------------------------------------------|
| `users`      | Akun petugas/HRD yang login untuk membuat slip                   |
| `karyawan`   | Data master karyawan (NIK, nama, jabatan, gaji pokok)             |
| `slip_gaji`  | Slip gaji yang sudah dibuat, terhubung ke `karyawan` dan `users`  |

## Persiapan (di komputer Anda)

Sandbox tempat kode ini dibuat **tidak memiliki PHP, Composer, atau akses
internet**, jadi kode belum pernah benar-benar dijalankan/diuji di sini. Semua
file ditulis mengikuti struktur standar Laravel 10 — kemungkinan besar langsung
jalan, tapi tolong ikuti langkah di bawah dan kabari saya bila ada error, biar
bisa langsung diperbaiki.

### Yang dibutuhkan

- [XAMPP](https://www.apachefriends.org/) (Apache + MySQL + PHP ≥ 8.1)
- [Composer](https://getcomposer.org/)

### Langkah instalasi

1. **Salin folder proyek** ini ke dalam folder `htdocs` XAMPP, misalnya:
   ```
   C:\xampp\htdocs\slip-gaji
   ```

2. **Install dependency Laravel** lewat Composer (butuh koneksi internet, ini
   yang akan mengunduh framework Laravel-nya):
   ```bash
   cd C:\xampp\htdocs\slip-gaji
   composer install
   ```

3. **Buat file `.env`** dari contoh yang sudah disediakan:
   ```bash
   copy .env.example .env
   ```
   (di Mac/Linux pakai `cp .env.example .env`)

4. **Generate application key**:
   ```bash
   php artisan key:generate
   ```

5. **Nyalakan Apache & MySQL** dari XAMPP Control Panel.

6. **Buat database** bernama `slip_gaji` lewat phpMyAdmin
   (`http://localhost/phpmyadmin`) — cukup buat database kosong, tabelnya akan
   dibuat otomatis oleh migration di langkah berikutnya. `.env` sudah
   dikonfigurasi untuk `root` tanpa password (setelan default XAMPP); ubah
   `DB_USERNAME` / `DB_PASSWORD` di `.env` kalau setelan MySQL Anda berbeda.

7. **Jalankan migration & seeder** (membuat tabel + akun & data contoh):
   ```bash
   php artisan migrate --seed
   ```

8. **Jalankan aplikasi**:
   ```bash
   php artisan serve
   ```
   Buka `http://127.0.0.1:8000` di browser.

   *(Alternatif: akses lewat Apache XAMPP langsung ke
   `http://localhost/slip-gaji/public`, tapi `php artisan serve` lebih mudah
   untuk latihan/demo.)*

### Akun login contoh (dari seeder)

- **Username:** `admin`
- **Kata sandi:** `password123`

### Data karyawan contoh (dari seeder)

| NIK                | Nama            | Jabatan              | Gaji Pokok    |
|--------------------|-----------------|----------------------|---------------|
| 3273010101900001   | Ahmad Fauzi     | Staff Administrasi   | Rp 4.500.000  |
| 3273010101900002   | Siti Nurhaliza  | Staff Keuangan       | Rp 5.000.000  |
| 3273010101900003   | Budi Santoso    | Web Programmer       | Rp 5.500.000  |
| 3273010101900004   | Dewi Lestari    | HRD Manager          | Rp 7.000.000  |

## Alur pemakaian

1. Login dengan akun di atas.
2. Di dashboard, klik **"+ Buat slip gaji"**.
3. Ketik salah satu NIK contoh — nama & jabatan akan otomatis muncul.
4. Isi periode, gaji pokok (otomatis terisi, bisa diubah), lembur, dan pinjaman karyawan.
5. Jawab captcha, lalu klik **"Hitung & simpan slip gaji"**.
6. Slip gaji akan tampil rapi dan bisa langsung dicetak lewat tombol **"Cetak slip"**.

## Struktur folder penting

```
app/Http/Controllers/AuthController.php      -> login & logout
app/Http/Controllers/SlipGajiController.php  -> logika utama (hitung slip, captcha, cari NIK)
app/Models/                                  -> User, Karyawan, SlipGaji
database/migrations/                         -> struktur tabel
database/seeders/                            -> data contoh
resources/views/                             -> tampilan Blade
public/css/app.css                           -> semua styling (tanpa framework CSS eksternal)
public/js/app.js                             -> captcha refresh & pencarian NIK (AJAX)
routes/web.php                               -> daftar route
```

## Menyesuaikan ke soal aslinya

Soal UKK meminta juga tahap **wireframe & mockup** (Kelompok Pekerjaan 1)
sebelum coding. Aplikasi ini adalah hasil dari **Kelompok Pekerjaan 2**
(implementasi kode). Kalau Anda masih perlu membuat wireframe/mockup untuk
dikumpulkan terpisah, beri tahu saya — saya bisa bantu buatkan itu juga
(misalnya sebagai gambar/desain terpisah mengikuti contoh di soal).
