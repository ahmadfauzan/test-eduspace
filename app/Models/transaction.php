<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id_peminjaman';
    protected $fillable =   [
        // 'id_peminjaman',
        'nama_mobil',
        'tanggal_peminjaman',
        'tanggal_pengembalian',
        'status_peminjaman',
    ];
}
