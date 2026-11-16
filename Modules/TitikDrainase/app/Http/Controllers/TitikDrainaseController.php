<?php

namespace Modules\TitikDrainase\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\TitikDrainase\Models\TitikDrainase;
use Modules\TitikDrainase\Http\Requests\StoreTitikRequest;
use Modules\TitikDrainase\Http\Requests\UpdateTitikRequest;

class TitikDrainaseController extends Controller
{

    public function index(Request $request)
    {
        // Query builder Eloquent dengan pencarian kondisional (when)
        $titik_drainases = TitikDrainase::query()
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_titik', 'like', "%{$search}%")
                        ->orWhere('kecamatan', 'like', "%{$search}%")
                        ->orWhere('kedalaman_cm', 'like', "%{$search}%");
                });
            })
            ->latest()            // Urutkan dari data terbaru
            ->paginate(5)         // Batasi 5 data per halaman (Pagination)
            ->withQueryString();  // Pertahankan parameter search di query URL saat ganti page

        // Return view Blade modul Buku (Modules/Buku/resources/views/index.blade.php)
        return view('TitikDrainase::index', compact('titik_drainases'));
    }

    /**
     * CREATE: Menampilkan form tambah buku baru.
     */
    public function create()
    {
        return view('titik_drainases::create');
    }

    /**
     * STORE: Memvalidasi & menyimpan data buku baru ke database.
     */
    public function store(StoreTitikRequest $request)
    {
        // Data yang sudah lolos validasi dari StoreBukuRequest
        $data = $request->validated();

        TitikDrainase::create([
            'nama_titik' => $data['nama_titik'],
            'kecamatan' => $data['kecamatan'],
            'deskripsi' => $data['deskripsi'],
            'kedalaman_cm' => $data['kedalaman_cm'],
        ]);

        // Redirect kembali ke halaman tabel dengan notifikasi flash session
        return redirect()
            ->route('titikdrainase.index')
            ->with('success', 'Buku baru berhasil ditambahkan.');
    }

    /**
     * EDIT: Menampilkan form ubah data buku (Route Model Binding).
     */
    public function edit(TitikDrainase $titikDrainase)
    {
        return view('TitikDrainase::edit', compact('titik_drainases'));
    }

    /**
     * UPDATE: Memvalidasi & memperbarui data buku di database.
     */
    public function update(UpdateTitikRequest $request, TitikDrainase $titikDrainase)
    {
        $data = $request->validated();

        $titikDrainase->update([
            'nama_titik' => $data['nama_titik'],
            'kecamatan' => $data['kecamatan'],
            'deskripsi' => $data['deskripsi'],
            'kedalaman_cm' => $data['kedalaman_cm'],
        ]);

        return redirect()
            ->route('titikDrainase.index')
            ->with('success', 'Data buku berhasil diperbarui.');
    }

    /**
     * DELETE: Menghapus data buku dari database.
     */
    public function destroy(TitikDrainase $titikDrainase)
    {
        $titikDrainase->delete();

        return redirect()
            ->route('titikDrainase.index')
            ->with('success', 'berhasil dihapus dari sistem.');
    }
}
