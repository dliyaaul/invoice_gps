<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Device extends Model
{
    use HasFactory;

    protected $table = 'devices';

    protected $fillable = [
        'folder_id',
        'nama_nopol',
        'imei',
        'notlpn',
        'tgl_install'
    ];

    public function folder()
    {
        return $this->belongsTo(Folder::class, 'folder_id', 'id');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'device_id', 'id');
    }
}
