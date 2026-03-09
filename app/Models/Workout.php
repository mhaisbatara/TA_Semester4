<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Workout extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'workouts';

    protected $fillable = [
        'nama_workout',
        'tipe',
        'durasi',
        'deskripsi'
    ];
}
