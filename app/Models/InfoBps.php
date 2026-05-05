<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InfoBps extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'judul',
        'foto',
    ];
}
