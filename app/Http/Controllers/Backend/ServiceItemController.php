<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ServiceItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServiceItemController extends Controller
{
    /**
     * Tampilkan semua service item.
     */
    public function index()
    {
        $serviceitems = ServiceItem::latest()->get();
        return view('page.backend.serviceitem.index', compact('serviceitems'));
    }

    /**
     * Form tambah service item.
     */
    public function create()
    {
        return view('page.backend.serviceitem.create');
    }

    /**
     * Simpan data service item baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('serviceitems', 'service_name'),
            ],
            'price' => 'required|numeric|min:0',
            'is_active' => 'required|in:active,nonactive',
        ], [
            'service_name.unique' => 'Nama service sudah terdaftar.',
        ]);

        ServiceItem::create($validated);

        return redirect()->route('serviceitem.index')->with('success', 'Service item berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail service item.
     */
    public function show(ServiceItem $serviceitem)
    {
        return view('page.backend.serviceitem.show', compact('serviceitem'));
    }

    /**
     * Form edit service item.
     */
    public function edit(ServiceItem $serviceitem)
    {
        return view('page.backend.serviceitem.edit', compact('serviceitem'));
    }

    /**
     * Update data service item.
     */
    public function update(Request $request, ServiceItem $serviceitem)
    {
        $validated = $request->validate([
            'service_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('service_items', 'service_name')->ignore($serviceitem->id),
            ],
            'price' => 'required|numeric|min:0',
            'is_active' => 'required|in:active,nonactive',
        ], [
            'service_name.unique' => 'Nama service sudah terdaftar.',
        ]);

        $serviceitem->update($validated);

        return redirect()->route('serviceitem.index')->with('success', 'Service item berhasil diperbarui.');
    }

    /**
     * Hapus service item.
     */
    public function destroy(ServiceItem $serviceitem)
    {
        $serviceitem->delete();
        return redirect()->route('serviceitem.index')->with('success', 'Service item berhasil dihapus.');
    }

    /**
     * Toggle status aktif / tidak aktif.
     */
    public function toggleStatus($id)
    {
        $item = ServiceItem::find($id);

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan!'], 404);
        }

        $item->is_active = $item->is_active === 'active' ? 'nonactive' : 'active';
        $item->save();

        return response()->json([
            'success' => true,
            'is_active' => $item->is_active,
            'message' => 'Status berhasil diperbarui.'
        ]);
    }
}