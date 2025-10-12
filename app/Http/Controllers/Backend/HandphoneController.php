<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Handphone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class HandphoneController extends Controller
{
    /**
     * Tampilkan daftar handphone
     */
    public function index()
    {
        $handphones = Handphone::latest()->get();
        return view('page.backend.handphone.index', compact('handphones'));
    }

    /**
     * Form tambah data
     */
    public function create()
    {
        return view('page.backend.handphone.create');
    }

    /**
     * Simpan data baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'release_year' => 'nullable|integer|min:2000|max:' . date('Y'),
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('handphones', 'public');
        }

        // default aktif saat dibuat
        $validated['is_active'] = 'active';

        Handphone::create($validated);

        return redirect()->route('handphone.index')->with('success', 'Data handphone berhasil ditambahkan.');
    }

    /**
     * Form edit
     */
    public function edit(Handphone $handphone)
    {
        return view('page.backend.handphone.edit', compact('handphone'));
    }

    /**
     * Update data
     */
    public function update(Request $request, Handphone $handphone)
    {
        $validated = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'release_year' => 'nullable|integer|min:2000|max:' . date('Y'),
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($handphone->image) {
                Storage::disk('public')->delete($handphone->image);
            }
            $validated['image'] = $request->file('image')->store('handphones', 'public');
        }

        // jika tidak ada perubahan status, biarkan defaultnya
        if (!isset($validated['is_active'])) {
            $validated['is_active'] = $handphone->is_active ?? 'active';
        }

        $handphone->update($validated);

        return redirect()->route('handphone.index')->with('success', 'Data handphone berhasil diperbarui.');
    }

    /**
     * Hapus data
     */
    public function destroy(Handphone $handphone)
    {
        if ($handphone->image) {
            Storage::disk('public')->delete($handphone->image);
        }

        $handphone->delete();
        return redirect()->route('handphone.index')->with('success', 'Data handphone berhasil dihapus.');
    }

    /**
     * Toggle status enum ('active' / 'nonactive')
     */
    public function toggleStatus($id)
    {
        try {
            $handphone = Handphone::findOrFail($id);

            // Ubah status
            if ($handphone->is_active === 'active') {
                $handphone->is_active = 'nonactive';
            } else {
                $handphone->is_active = 'active';
            }

            $handphone->save();

            return response()->json([
                'success' => true,
                'is_active' => $handphone->is_active,
                'message' => 'Status berhasil diperbarui',
            ], 200);

        } catch (\Throwable $e) {
            Log::error('Toggle handphone status error: ' . $e->getMessage(), [
                'id' => $id,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage(),
            ], 500);
        }
    }
}