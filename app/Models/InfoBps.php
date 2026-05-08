<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoBps extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'judul',
        'foto',
    ];
}
