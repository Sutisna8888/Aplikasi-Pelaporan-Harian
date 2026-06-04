<?php

namespace Database\Seeders;

use App\Models\Kegiatan;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::updateOrCreate(
            ['nip' => '199001012024011001'], 
            [
                'username' => 'admin_bps',
                'email' => 'admin@bps.go.id',
                'password' => bcrypt('password123'),
                'role' => 'admin',
                'jabatan' => 'Kepala BPS Kota Sukabumi',
            ]
        );

        User::updateOrCreate(
            ['nip' => '199505052024012002'], 
            [
                'username' => 'pegawai_bps',
                'email' => 'pegawai@bps.go.id',
                'password' => bcrypt('password123'),
                'role' => 'pegawai',
                'jabatan' => 'Ketua tim humas',
            ]
        );

        $kegiatan = [
            ['nama_kegiatan' => 'Pengolahan Data Survei'],
            ['nama_kegiatan' => 'Rapat Koordinasi Internal'],
        ];

        foreach ($kegiatan as $k) {
            Kegiatan::updateOrCreate(
                ['nama_kegiatan' => $k['nama_kegiatan']],
                $k
            );
        }
    }
}
