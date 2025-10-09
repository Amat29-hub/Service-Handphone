<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Handphone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HandphoneController extends Controller
{
    public function index()
    {
        $handphones = Handphone::all();
        return view('page.backend.handphone.index', compact('handphones'));
    }

    public function create()
    {
        return view('page.backend.handphone.create');
    }

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

        Handphone::create($validated);

        return redirect()->route('handphone.index')->with('success', 'Data handphone berhasil ditambahkan.');
    }

    public function edit(Handphone $handphone)
    {
        return view('page.backend.handphone.edit', compact('handphone'));
    }

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

        $handphone->update($validated);

        return redirect()->route('handphone.index')->with('success', 'Data handphone berhasil diperbarui.');
    }

    public function destroy(Handphone $handphone)
    {
        if ($handphone->image) {
            Storage::disk('public')->delete($handphone->image);
        }

        $handphone->delete();
        return redirect()->route('handphone.index')->with('success', 'Data handphone berhasil dihapus.');
    }

    public function toggleStatus($id)
    {
        $handphone = Handphone::findOrFail($id);
        $handphone->is_active = $handphone->is_active === 'active' ? 'nonactive' : 'active';
        $handphone->save();

        return response()->json(['status' => $handphone->is_active]);
    }
}
