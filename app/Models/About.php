<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    protected $fillable = [
        'title',
        'description',
        'address',
        'email',
        'phone',
        'photo',
        'fb',
        'twitter',
        'instagram',
        'linkedin',
        'deskripsi_agen',
        'gambar',
        'keterangan_blog'
    ];
}
