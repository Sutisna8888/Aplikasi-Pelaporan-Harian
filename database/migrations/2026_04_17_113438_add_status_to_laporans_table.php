<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('laporans', function (Blueprint $table) {
            // Menambahkan kolom status dengan nilai default 'berjalan'
            $table->enum('status', ['berjalan', 'selesai'])->default('berjalan')->after('lokasi_teks');

            // Memastikan kolom yang baru diisi saat selesai BOLEH KOSONG (nullable) saat pertama kali insert
            $table->time('jam_selesai')->nullable()->change();
            $table->string('foto_selesai')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->dropColumn('status');
            // (opsional) kembalikan ke not null jika diperlukan
        });
    }
};
