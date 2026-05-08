<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $fillable = ['nama_kegiatan', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi: Satu jenis kegiatan bisa muncul di banyak laporan
    public function laporans()
    {
        return $this->hasMany(Laporan::class);
    }
}
