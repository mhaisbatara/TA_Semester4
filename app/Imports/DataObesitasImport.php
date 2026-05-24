<?php

namespace App\Imports;

use App\Models\DataObesitas;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Collection;

class DataObesitasImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    public int $insertedCount = 0;

    // Chunk kecil agar tidak timeout, tanpa transaction
    public function chunkSize(): int
    {
        return 500;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Normalisasi key: lowercase, trim, spasi/strip → underscore
            $raw = collect($row->toArray())->mapWithKeys(function ($value, $key) {
                $normalized = strtolower(trim((string) $key));
                $normalized = preg_replace('/[\s\-\.]+/', '_', $normalized);
                return [$normalized => $value];
            })->toArray();

            // Skip baris kosong
            if (empty(array_filter($raw, fn($v) => $v !== null && $v !== ''))) {
                continue;
            }

            // Gunakan insert langsung ke MongoDB (bypass Eloquent transaction)
            DataObesitas::getModel()->newQuery()->getConnection()
                ->getCollection('data_obesitas')
                ->insertOne([
                    'usia'                        => $raw['usia'] ?? null,
                    'jenis_kelamin'               => $raw['jenis_kelamin'] ?? null,
                    'tinggi_badan'                => $raw['tinggi_badan'] ?? null,
                    'berat_badan'                 => $raw['berat_badan'] ?? null,
                    'konsumsi_alkohol'            => $raw['konsumsi_alkohol'] ?? null,
                    'sering_makan_tinggi_kalori'  => $raw['sering_makan_tinggi_kalori'] ?? null,
                    'frekuensi_konsumsi_sayur'    => $raw['frekuensi_konsumsi_sayur'] ?? null,
                    'jumlah_makan_harian'         => $raw['jumlah_makan_harian'] ?? null,
                    'monitoring_kalori'           => $raw['monitoring_kalori'] ?? null,
                    'merokok'                     => $raw['merokok'] ?? null,
                    'konsumsi_air'                => $raw['konsumsi_air'] ?? null,
                    'riwayat_keluarga_overweight' => $raw['riwayat_keluarga_overweight'] ?? null,
                    'aktivitas_fisik'             => $raw['aktivitas_fisik'] ?? null,   // ← ditambahkan
                    'waktu_layar'                 => $raw['waktu_layar'] ?? null,        // ← ditambahkan
                    'kebiasaan_ngemil'            => $raw['kebiasaan_ngemil'] ?? null,   // ← ditambahkan
                    'transportasi'                => $raw['transportasi'] ?? null,
                    'kategori_obesitas'           => $raw['kategori_obesitas'] ?? null,
                    'created_at'                  => now(),
                    'updated_at'                  => now(),
                ]);

            $this->insertedCount++;
        }
    }
}
