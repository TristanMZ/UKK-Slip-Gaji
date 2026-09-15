<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlipGaji extends Model
{
    use HasFactory;

    protected $table = 'slip_gaji';

    protected $fillable = [
        'karyawan_id',
        'user_id',
        'periode_awal',
        'periode_akhir',
        'gaji_pokok',
        'lembur',
        'pinjaman_karyawan',
        'total_penghasilan',
        'total_potongan',
        'gaji_bersih',
    ];

    protected $casts = [
        'periode_awal' => 'date',
        'periode_akhir' => 'date',
        'gaji_pokok' => 'decimal:2',
        'lembur' => 'decimal:2',
        'pinjaman_karyawan' => 'decimal:2',
        'total_penghasilan' => 'decimal:2',
        'total_potongan' => 'decimal:2',
        'gaji_bersih' => 'decimal:2',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function dibuatOleh()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
