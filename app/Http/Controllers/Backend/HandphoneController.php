<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Handphone;
use Illuminate\Http\Request;

class HandphoneController extends Controller
{
    /**
     * Tampilkan semua handphone.
     */
    public function index()
    {
        $handphones = Handphone::all();
        return view('page.backend.handphone.index', compact('handphones'));
    }

    /**
     * Form create handphone baru.
     */
    public function create()
    {
        return view('page.backend.handphone.create');
    }

    /**
     * Simpan handphone baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'release_year' => 'nullable|integer',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['brand', 'model', 'release_year']);
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('handphones', 'public');
        }

        $data['is_active'] = 'active';

        Handphone::create($data);

        return redirect()->route('handphone.index')->with('success', 'Handphone berhasil ditambahkan!');
    }

    /**
     * Tampilkan detail handphone.
     */
    public function show(Handphone $handphone)
    {
        return view('page.backend.handphone.show', compact('handphone'));
    }

    /**
     * Form edit handphone.
     */
    public function edit(Handphone $handphone)
    {
        return view('page.backend.handphone.edit', compact('handphone'));
    }

    /**
     * Update handphone.
     */
    public function update(Request $request, Handphone $handphone)
    {
        $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'release_year' => 'nullable|integer',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['brand', 'model', 'release_year']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('handphones', 'public');
        }

        $handphone->update($data);

        return redirect()->route('handphone.index')->with('success', 'Handphone berhasil diperbarui!');
    }

    /**
     * Hapus handphone.
     */
    public function destroy(Handphone $handphone)
    {
        $handphone->delete();
        return redirect()->route('handphone.index')->with('success', 'Handphone berhasil dihapus!');
    }

    /**
     * Toggle status active/inactive.
     */
    public function toggleStatus(Handphone $handphone)
    {
        $handphone->is_active = $handphone->is_active === 'active' ? 'inactive' : 'active';
        $handphone->save();

        return response()->json([
            'success' => true,
            'is_active' => $handphone->is_active,
        ]);
    }
}