<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Article extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'articles';

    protected $primaryKey = '_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'judul',
        'kategori',
        'penulis',
        'ringkasan',
        'tag',
        'status',
        'isi',
        'gambar',
        'slug',
        'views'
    ];
}
