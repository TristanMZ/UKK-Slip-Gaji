<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('karyawan', function (Blueprint $table) {
            $table->decimal('gaji_pokok', 15, 2)->default(0)->change();
        });

        Schema::table('slip_gaji', function (Blueprint $table) {
            $table->decimal('gaji_pokok', 15, 2)->default(0)->change();
            $table->decimal('lembur', 15, 2)->default(0)->change();
            $table->decimal('pinjaman_karyawan', 15, 2)->default(0)->change();
            $table->decimal('total_penghasilan', 15, 2)->default(0)->change();
            $table->decimal('total_potongan', 15, 2)->default(0)->change();
            $table->decimal('gaji_bersih', 15, 2)->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('karyawan', function (Blueprint $table) {
            $table->decimal('gaji_pokok', 12, 2)->default(0)->change();
        });

        Schema::table('slip_gaji', function (Blueprint $table) {
            $table->decimal('gaji_pokok', 12, 2)->default(0)->change();
            $table->decimal('lembur', 12, 2)->default(0)->change();
            $table->decimal('pinjaman_karyawan', 12, 2)->default(0)->change();
            $table->decimal('total_penghasilan', 12, 2)->default(0)->change();
            $table->decimal('total_potongan', 12, 2)->default(0)->change();
            $table->decimal('gaji_bersih', 12, 2)->default(0)->change();
        });
    }
};