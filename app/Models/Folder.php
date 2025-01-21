<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Folder extends Model
{
    use HasFactory;

    protected $table = 'folders';

    protected $fillable = [
        'login',
        'nama',
        'perusahaan',
        'email',
        'kota',
        'alamat',
        'nohp',
        'expired_date',
        'status',
    ];

    public function devices()
    {
        return $this->hasMany(Device::class, 'folder_id', 'id');
    }
}
