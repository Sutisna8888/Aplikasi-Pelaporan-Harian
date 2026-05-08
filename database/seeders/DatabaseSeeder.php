<?php

namespace Database\Seeders;

use App\Models\Kegiatan;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat User Admin untuk Uji Coba (Menggunakan updateOrCreate)
        User::updateOrCreate(
            ['nip' => '199001012024011001'], // Kunci pencarian
            [
                'username' => 'admin_bps',
                'email' => 'admin@bps.go.id',
                'password' => bcrypt('password123'),
                'role' => 'admin',
                'jabatan' => 'Pranata Komputer',
            ]
        );

        // 2. Akun Pegawai (User Biasa)
        User::updateOrCreate(
            ['nip' => '199505052024012002'], // Kunci pencarian
            [
                'username' => 'pegawai_bps',
                'email' => 'pegawai@bps.go.id',
                'password' => bcrypt('password123'),
                'role' => 'pegawai',
                'jabatan' => 'Statistisi Pertama',
            ]
        );

        // 3. Buat Daftar Kegiatan BPS (Menggunakan updateOrCreate agar tidak ganda)
        $kegiatan = [
            ['nama_kegiatan' => 'Pengolahan Data Survei'],
            ['nama_kegiatan' => 'Listing Lapangan'],
            ['nama_kegiatan' => 'Rapat Koordinasi Internal'],
            ['nama_kegiatan' => 'Diseminasi Statistik'],
        ];

        foreach ($kegiatan as $k) {
            Kegiatan::updateOrCreate(
                ['nama_kegiatan' => $k['nama_kegiatan']], // Kunci pencarian
                $k
            );
        }
    }
}
