<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Handphone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class HandphoneController extends Controller
{
    /**
     * 🔍 Tampilkan daftar handphone (dengan search dan soft delete)
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Ambil semua termasuk yang dihapus (soft delete)
        $query = Handphone::query()->withTrashed()->latest();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('release_year', 'like', "%{$search}%");
            });
        }

        $handphones = $query->get();

        return view('page.backend.handphone.index', compact('handphones'));
    }

    /**
     * 🆕 Form tambah handphone
     */
    public function create()
    {
        return view('page.backend.handphone.create');
    }

    /**
     * 💾 Simpan data baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'brand' => ['required', 'string', 'max:255'],
            'model' => [
                'required',
                'string',
                'max:255',
                Rule::unique('handphones')->where(fn($q) =>
                    $q->where('brand', $request->brand)
                      ->where('model', $request->model)
                ),
            ],
            'release_year' => 'nullable|integer',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'required|in:active,nonactive',
        ], [
            'model.unique' => 'Kombinasi brand dan model sudah terdaftar.',
        ]);

        $data = $request->only(['brand', 'model', 'release_year', 'is_active']);

        // Simpan gambar jika ada
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('handphones', 'public');
        }

        Handphone::create($data);

        return redirect()->route('handphone.index')->with('success', 'Handphone berhasil ditambahkan!');
    }

    /**
     * 👁️ Tampilkan detail handphone
     */
    public function show(Handphone $handphone)
    {
        return view('page.backend.handphone.show', compact('handphone'));
    }

    /**
     * ✏️ Form edit
     */
    public function edit(Handphone $handphone)
    {
        return view('page.backend.handphone.edit', compact('handphone'));
    }

    /**
     * 🔄 Update data handphone
     */
    public function update(Request $request, Handphone $handphone)
    {
        $request->validate([
            'brand' => ['required', 'string', 'max:255'],
            'model' => [
                'required',
                'string',
                'max:255',
                Rule::unique('handphones')
                    ->ignore($handphone->id)
                    ->where(fn($q) =>
                        $q->where('brand', $request->brand)
                          ->where('model', $request->model)
                    ),
            ],
            'release_year' => 'nullable|integer',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'required|in:active,nonactive',
        ]);

        $data = $request->only(['brand', 'model', 'release_year', 'is_active']);

        // Ganti gambar jika ada
        if ($request->hasFile('image')) {
            if ($handphone->image && Storage::disk('public')->exists($handphone->image)) {
                Storage::disk('public')->delete($handphone->image);
            }
            $data['image'] = $request->file('image')->store('handphones', 'public');
        }

        $handphone->update($data);

        return redirect()->route('handphone.index')->with('success', 'Data handphone berhasil diperbarui!');
    }

    /**
     * 🗑️ Soft delete
     */
    public function destroy($id)
    {
        $handphone = Handphone::findOrFail($id);
        $handphone->delete();

        return redirect()->route('handphone.index')->with('success', 'Handphone berhasil dihapus (soft delete)!');
    }

    /**
     * ♻️ Restore dari soft delete
     */
    public function restore($id)
    {
        $handphone = Handphone::withTrashed()->findOrFail($id);
        $handphone->restore();

        return redirect()->route('handphone.index')->with('success', 'Handphone berhasil direstore!');
    }

    /**
     * ❌ Hapus permanen
     */
    public function forceDelete($id)
    {
        $handphone = Handphone::withTrashed()->findOrFail($id);

        if ($handphone->image && Storage::disk('public')->exists($handphone->image)) {
            Storage::disk('public')->delete($handphone->image);
        }

        $handphone->forceDelete();

        return redirect()->route('handphone.index')->with('success', 'Handphone berhasil dihapus permanen!');
    }

    /**
     * ⚙️ Toggle status aktif/nonaktif (AJAX)
     */
    public function toggleStatus(Request $request, $id)
    {
        $request->validate(['is_active' => 'required|in:active,nonactive']);

        $handphone = Handphone::findOrFail($id);
        $handphone->update(['is_active' => $request->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $handphone->is_active
        ]);
    }
}