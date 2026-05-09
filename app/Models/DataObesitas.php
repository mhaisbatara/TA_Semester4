<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class DataObesitas extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'data_obesitas';

    protected $fillable = [
        'usia',
        'jenis_kelamin',
        'tinggi_badan',
        'berat_badan',
        'konsumsi_alkohol',
        'sering_makan_tinggi_kalori',
        'frekuensi_konsumsi_sayur',
        'jumlah_makan_harian',
        'monitoring_kalori',
        'merokok',
        'konsumsi_air',
        'riwayat_keluarga_overweight',
        'aktivitas_fisik',
        'waktu_layar',
        'kebiasaan_ngemil',
        'transportasi',
        'kategori_obesitas',
    ];
}
