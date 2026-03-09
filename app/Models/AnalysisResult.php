<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class AnalysisResult extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'analysis_results';

    protected $fillable = [
        'user_id',
        'bmi',
        'kategori',
        'bmr',
        'tdee',
        'rekomendasi'
    ];
}