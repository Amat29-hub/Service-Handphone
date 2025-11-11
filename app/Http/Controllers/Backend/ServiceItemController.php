<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ServiceItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServiceItemController extends Controller
{
    /**
     * Tampilkan semua service item (termasuk yang dihapus).
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
    
        $query = ServiceItem::query()->withTrashed()->latest();
    
        if ($search) {
            $query->where('service_name', 'like', "%{$search}%");
        }
    
        $serviceitems = $query->get();
    
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
     * Simpan data baru.
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
        ]);

        ServiceItem::create($validated);
        return redirect()->route('serviceitem.index')->with('success', 'Service item berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail.
     */
    public function show(ServiceItem $serviceitem)
    {
        return view('page.backend.serviceitem.show', compact('serviceitem'));
    }

    /**
     * Form edit.
     */
    public function edit(ServiceItem $serviceitem)
    {
        return view('page.backend.serviceitem.edit', compact('serviceitem'));
    }

    /**
     * Update data.
     */
    public function update(Request $request, ServiceItem $serviceitem)
    {
        $validated = $request->validate([
            'service_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('serviceitems', 'service_name')->ignore($serviceitem->id),
            ],
            'price' => 'required|numeric|min:0',
            'is_active' => 'required|in:active,nonactive',
        ]);

        $serviceitem->update($validated);
        return redirect()->route('serviceitem.index')->with('success', 'Service item berhasil diperbarui.');
    }

    /**
     * Soft delete (hapus sementara).
     */
    public function destroy($id)
    {
        $item = ServiceItem::withTrashed()->findOrFail($id);

        if ($item->trashed()) {
            // Jika sudah dihapus, hapus permanen
            $item->forceDelete();
            return redirect()->route('serviceitem.index')->with('success', 'Service item dihapus permanen.');
        } else {
            $item->delete();
            return redirect()->route('serviceitem.index')->with('success', 'Service item berhasil dihapus (soft delete).');
        }
    }

    /**
     * Restore dari soft delete.
     */
    public function restore($id)
    {
        $item = ServiceItem::onlyTrashed()->findOrFail($id);
        $item->restore();
        return redirect()->route('serviceitem.index')->with('success', 'Service item berhasil direstore.');
    }

    /**
     * Force delete permanen.
     */
    public function forceDelete($id)
    {
        $item = ServiceItem::onlyTrashed()->findOrFail($id);
        $item->forceDelete();
        return redirect()->route('serviceitem.index')->with('success', 'Service item berhasil dihapus permanen.');
    }

    /**
     * Toggle status aktif/nonaktif.
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