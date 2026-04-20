<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Kategori extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'kategori';

    protected $fillable = ['nama_kategori'];
}