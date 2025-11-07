<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Handphone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class HandphoneController extends Controller
{
    public function index()
    {
        $handphones = Handphone::withTrashed()->latest()->get();
        return view('page.backend.handphone.index', compact('handphones'));
    }

    public function create()
    {
        return view('page.backend.handphone.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'brand' => ['required', 'string', 'max:255'],
            'model' => [
                'required',
                'string',
                'max:255',
                Rule::unique('handphones')->where(fn($q) => $q->where('brand', $request->brand)->where('model', $request->model)),
            ],
            'release_year' => 'nullable|integer',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'required|in:active,nonactive',
        ], [
            'model.unique' => 'Kombinasi brand dan model sudah terdaftar.',
        ]);

        $data = $request->only(['brand', 'model', 'release_year', 'is_active']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('handphones', 'public');
        }

        Handphone::create($data);

        return redirect()->route('handphone.index')->with('success', 'Handphone berhasil ditambahkan!');
    }

    public function show(Handphone $handphone)
    {
        return view('page.backend.handphone.show', compact('handphone'));
    }

    public function edit(Handphone $handphone)
    {
        return view('page.backend.handphone.edit', compact('handphone'));
    }

    public function update(Request $request, Handphone $handphone)
    {
        $request->validate([
            'brand' => ['required', 'string', 'max:255'],
            'model' => [
                'required',
                'string',
                'max:255',
                Rule::unique('handphones')->ignore($handphone->id)->where(fn($q) => $q->where('brand', $request->brand)->where('model', $request->model)),
            ],
            'release_year' => 'nullable|integer',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['brand', 'model', 'release_year']);

        if ($request->hasFile('image')) {
            if ($handphone->image && Storage::disk('public')->exists($handphone->image)) {
                Storage::disk('public')->delete($handphone->image);
            }
            $data['image'] = $request->file('image')->store('handphones', 'public');
        }

        if ($request->has('is_active')) {
            $data['is_active'] = $request->is_active;
        }

        $handphone->update($data);

        return redirect()->route('handphone.index')->with('success', 'Handphone berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $handphone = Handphone::findOrFail($id);
        $handphone->delete();

        return redirect()->route('handphone.index')->with('success', 'Handphone berhasil dihapus (soft delete)!');
    }

    public function restore($id)
    {
        $handphone = Handphone::withTrashed()->findOrFail($id);
        $handphone->restore();

        return redirect()->route('handphone.index')->with('success', 'Handphone berhasil direstore!');
    }

    public function forceDelete($id)
    {
        $handphone = Handphone::withTrashed()->findOrFail($id);

        if ($handphone->image && Storage::disk('public')->exists($handphone->image)) {
            Storage::disk('public')->delete($handphone->image);
        }

        $handphone->forceDelete();

        return redirect()->route('handphone.index')->with('success', 'Handphone berhasil dihapus permanen!');
    }

    public function toggleStatus(Request $request, $id)
    {
        $handphone = Handphone::findOrFail($id);

        $request->validate(['is_active' => 'required|in:active,nonactive']);

        $handphone->update(['is_active' => $request->is_active]);

        return response()->json(['success' => true, 'is_active' => $handphone->is_active]);
    }
}