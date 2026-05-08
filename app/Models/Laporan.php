<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $fillable = [
        'user_id', 'kegiatan_id', 'tanggal', 'deskripsi',
        'jam_mulai', 'jam_selesai', 'durasi_menit',
        'foto_mulai', 'foto_selesai', 'latitude',
        'longitude', 'lokasi_teks', 'keterangan', 'status',
    ];

    // Relasi: Laporan ini punya siapa?
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Laporan ini jenis kegiatannya apa?
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }
}
