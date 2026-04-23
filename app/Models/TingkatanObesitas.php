<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class TingkatanObesitas extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'tingkatan_obesitas';

    protected $primaryKey = '_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kategori',
        'bmi_min',
        'bmi_max',
        'keterangan'
    ];

    // Optional: tambahkan casts untuk tipe data
    protected $casts = [
        'bmi_min' => 'float',
        'bmi_max' => 'float'
    ];
}
