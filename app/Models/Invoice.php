<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoice';

    protected $fillable = [
        'device_id',
        'no_invoice',
        'nama_pemilik',
        'username',
        'jatuh_tempo',
        'harga',
        'qty',
        'jumlah',
        'keterangan'
    ];

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id', 'id');
    }
}
