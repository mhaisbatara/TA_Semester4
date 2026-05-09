<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MongoDB\Client;
use App\Imports\DataObesitasImport;
use App\Models\DataObesitas;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    // public function manajemenData()
    // {
    //     $totalData = DataObesitas::count();
    //     $data      = DataObesitas::orderBy('created_at', 'desc')->paginate(10);

    //     return view('auth.admin.manajemen_data', compact('data', 'totalData'));
    // }

    // // Upload & import file Excel ke MongoDB
    // public function uploadData(Request $request)
    // {
    //     $request->validate([
    //         'file' => 'required|mimes:xlsx,xls|max:10240',
    //     ], [
    //         'file.required' => 'Pilih file Excel terlebih dahulu.',
    //         'file.mimes'    => 'File harus berformat .xlsx atau .xls.',
    //         'file.max'      => 'Ukuran file maksimal 10MB.',
    //     ]);

    //     try {
    //         Excel::import(new DataObesitasImport, $request->file('file'));

    //         return redirect()->back()
    //             ->with('success', 'Data berhasil diupload dan disimpan ke database!');
    //     } catch (\Exception $e) {
    //         return redirect()->back()
    //             ->with('error', 'Gagal mengupload data: ' . $e->getMessage());
    //     }
    // }

    // // Hapus semua data obesitas
    // public function deleteAllData()
    // {
    //     try {
    //         DataObesitas::truncate();

    //         return redirect()->back()
    //             ->with('success', 'Semua data berhasil dihapus dari database.');
    //     } catch (\Exception $e) {
    //         return redirect()->back()
    //             ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
    //     }
    // }

    // Halaman manajemen data
    public function manajemenData()
    {
        $totalData = DataObesitas::count();
        $data      = DataObesitas::orderBy('created_at', 'desc')->paginate(10);

        return view('auth.admin.manajemen_data', compact('data', 'totalData'));
    }

    // Upload & import file Excel ke MongoDB
    public function uploadData(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:10240',
        ], [
            'file.required' => 'Pilih file Excel terlebih dahulu.',
            'file.mimes'    => 'File harus berformat .xlsx atau .xls.',
            'file.max'      => 'Ukuran file maksimal 10MB.',
        ]);

        try {
            $import = new DataObesitasImport;
            Excel::import($import, $request->file('file'));

            $count = $import->insertedCount;

            if ($count === 0) {
                return redirect()->back()
                    ->with('error', 'File berhasil dibaca tapi 0 baris tersimpan. Cek nama kolom di file Excel Anda, lalu lihat storage/logs/laravel.log untuk detail.');
            }

            return redirect()->back()
                ->with('success', "Berhasil! {$count} baris data disimpan ke database.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengupload data: ' . $e->getMessage());
        }
    }

    // Hapus semua data obesitas
    public function deleteAllData()
    {
        try {
            DataObesitas::truncate();

            return redirect()->back()
                ->with('success', 'Semua data berhasil dihapus dari database.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function index()
    {
        try {
            $client = new Client("mongodb://127.0.0.1:27017");
            $collection = $client->db_obesitas_workout->tingkatan_obesitas;

            $allData = $collection->find()->toArray();
            $recentData = collect(array_slice($allData, 0, 5));

            return view('auth.admin.dashboard', compact('allData', 'recentData'));

        } catch (\Exception $e) {
            $recentData = collect([]);
            $allData = [];
            return view('auth.admin.dashboard', compact('allData', 'recentData'));
        }
    }
}
