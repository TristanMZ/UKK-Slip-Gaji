<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use Illuminate\Database\Seeder;

class KaryawanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nik' => '3273010101900001', 'nama' => 'Ahmad Fauzi', 'jabatan' => 'Staff Administrasi', 'gaji_pokok' => 4500000],
            ['nik' => '3273010101900002', 'nama' => 'Siti Nurhaliza', 'jabatan' => 'Staff Keuangan', 'gaji_pokok' => 5000000],
            ['nik' => '3273010101900003', 'nama' => 'Budi Santoso', 'jabatan' => 'Web Programmer', 'gaji_pokok' => 5500000],
            ['nik' => '3273010101900004', 'nama' => 'Dewi Lestari', 'jabatan' => 'HRD Manager', 'gaji_pokok' => 7000000],
        ];

        foreach ($data as $row) {
            Karyawan::firstOrCreate(['nik' => $row['nik']], $row);
        }
    }
}
