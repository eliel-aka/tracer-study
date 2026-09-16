<?php

namespace App\Http\Controllers;

use App\Models\MasterJabatan;
use App\Models\MasterSatuanKerja;
use App\Models\MasterUnitKerja;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use App\Imports\MasterSatkerImport;
use App\Exports\MasterSatkerExport;

class ManajemenSatkerController extends Controller
{
    /**
     * Display a listing with tabs for Jabatan, Satuan Kerja, Unit Kerja.
     */
    public function index(Request $request)
    {
        $activeTab = $request->get('tab', 'jabatan');
        $search = $request->get('search', '');

        $jabatanQuery = MasterJabatan::query();
        $satuanKerjaQuery = MasterSatuanKerja::query();
        $unitKerjaQuery = MasterUnitKerja::query();

        if ($search) {
            $jabatanQuery->where('nama', 'like', '%' . $search . '%');
            $satuanKerjaQuery->where('nama', 'like', '%' . $search . '%');
            $unitKerjaQuery->where('nama', 'like', '%' . $search . '%');
        }

        $jabatan = $jabatanQuery->orderBy('nama')->paginate(10, ['*'], 'jabatan_page')->appends($request->query());
        $satuanKerja = $satuanKerjaQuery->orderBy('nama')->paginate(10, ['*'], 'satker_page')->appends($request->query());
        $unitKerja = $unitKerjaQuery->orderBy('nama')->paginate(10, ['*'], 'unitkerja_page')->appends($request->query());

        return view('admin.views.manajemen_satker.index', compact(
            'jabatan', 'satuanKerja', 'unitKerja', 'activeTab', 'search'
        ));
    }

    /**
     * Store a new master data entry.
     */
    public function store(Request $request)
    {
        $type = $request->input('type');
        $nama = trim($request->input('nama'));

        if (empty($nama)) {
            return redirect()->back()->with('error', 'Nama wajib diisi.')->withInput();
        }

        try {
            switch ($type) {
                case 'jabatan':
                    MasterJabatan::create(['nama' => $nama]);
                    break;
                case 'satuan_kerja':
                    MasterSatuanKerja::create(['nama' => $nama]);
                    break;
                case 'unit_kerja':
                    MasterUnitKerja::create(['nama' => $nama]);
                    break;
                default:
                    return redirect()->back()->with('error', 'Tipe data tidak valid.');
            }

            return redirect()->route('admin.manajemenSatker.index', ['tab' => $type])
                ->with('success', 'Data berhasil ditambahkan.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()
                    ->with('error', 'Data "' . $nama . '" sudah ada di sistem.')
                    ->withInput();
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update a master data entry.
     */
    public function update(Request $request, $id)
    {
        $type = $request->input('type');
        $nama = trim($request->input('nama'));

        if (empty($nama)) {
            return redirect()->back()->with('error', 'Nama wajib diisi.');
        }

        try {
            switch ($type) {
                case 'jabatan':
                    $item = MasterJabatan::findOrFail($id);
                    break;
                case 'satuan_kerja':
                    $item = MasterSatuanKerja::findOrFail($id);
                    break;
                case 'unit_kerja':
                    $item = MasterUnitKerja::findOrFail($id);
                    break;
                default:
                    return redirect()->back()->with('error', 'Tipe data tidak valid.');
            }

            $item->update(['nama' => $nama]);

            return redirect()->route('admin.manajemenSatker.index', ['tab' => $type])
                ->with('success', 'Data berhasil diperbarui.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()
                    ->with('error', 'Data "' . $nama . '" sudah ada di sistem.');
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Delete a master data entry.
     */
    public function destroy(Request $request, $id)
    {
        $type = $request->input('type');

        try {
            switch ($type) {
                case 'jabatan':
                    MasterJabatan::findOrFail($id)->delete();
                    break;
                case 'satuan_kerja':
                    MasterSatuanKerja::findOrFail($id)->delete();
                    break;
                case 'unit_kerja':
                    MasterUnitKerja::findOrFail($id)->delete();
                    break;
                default:
                    return redirect()->back()->with('error', 'Tipe data tidak valid.');
            }

            return redirect()->route('admin.manajemenSatker.index', ['tab' => $type])
                ->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Import master data from Excel.
     */
    public function import(Request $request, Excel $excel)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv',
                'type' => 'required|in:jabatan,satuan_kerja,unit_kerja',
            ]);

            $type = $request->input('type');
            $excel->import(new MasterSatkerImport($type), $request->file('file'));

            return redirect()->route('admin.manajemenSatker.index', ['tab' => $type])
                ->with('success', 'Data berhasil diimpor.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }

    /**
     * Export master data to Excel.
     */
    public function export(Request $request, Excel $excel)
    {
        $type = $request->get('type', 'jabatan');
        $filename = 'master_' . $type . '.xlsx';

        return $excel->download(new MasterSatkerExport($type), $filename);
    }

    /**
     * API endpoint for searchable dropdowns.
     */
    public function apiSearch(Request $request)
    {
        $type = $request->get('type');
        $search = $request->get('q', '');

        $query = match ($type) {
            'jabatan' => MasterJabatan::query(),
            'satuan_kerja' => MasterSatuanKerja::query(),
            'unit_kerja' => MasterUnitKerja::query(),
            default => null,
        };

        if (!$query) {
            return response()->json([]);
        }

        if ($search) {
            $query->where('nama', 'like', '%' . $search . '%');
        }

        $results = $query->orderBy('nama')->limit(50)->get(['id', 'nama']);

        return response()->json($results->map(fn($item) => [
            'id' => $item->nama,
            'text' => $item->nama,
        ]));
    }
}
