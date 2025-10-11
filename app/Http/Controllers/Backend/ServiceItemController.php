<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ServiceItem;
use Illuminate\Http\Request;

class ServiceItemController extends Controller
{
    /**
     * Menampilkan semua data Service Item.
     */
    public function index()
    {
        $service_items = ServiceItem::orderBy('created_at', 'desc')->get();
        return view('page.backend.serviceitem.index', compact('service_items'));
    }

    /**
     * Menampilkan form tambah Service Item.
     */
    public function create()
    {
        return view('page.backend.serviceitem.create');
    }

    /**
     * Menyimpan data Service Item baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'service_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'is_active' => 'required|in:active,inactive',
            'description' => 'nullable|string',
        ]);

        ServiceItem::create([
            'service_name' => $request->service_name,
            'price' => $request->price,
            'is_active' => $request->is_active,
            'description' => $request->description,
        ]);

        return redirect()->route('service-item.index')->with('success', 'Service item berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail Service Item.
     */
    public function show($id)
    {
        $service_item = ServiceItem::findOrFail($id);
        return view('page.backend.serviceitem.show', compact('service_item'));
    }

    /**
     * Menampilkan form edit Service Item.
     */
    public function edit($id)
    {
        $service_item = ServiceItem::findOrFail($id);
        return view('page.backend.serviceitem.edit', compact('service_item'));
    }

    /**
     * Memperbarui data Service Item.
     */
    public function update(Request $request, $id)
    {
        $service_item = ServiceItem::findOrFail($id);

        $request->validate([
            'service_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'is_active' => 'required|in:active,inactive',
            'description' => 'nullable|string',
        ]);

        $service_item->update([
            'service_name' => $request->service_name,
            'price' => $request->price,
            'is_active' => $request->is_active,
            'description' => $request->description,
        ]);

        return redirect()->route('service-item.index')->with('success', 'Data service item berhasil diperbarui!');
    }

    /**
     * Menghapus data Service Item.
     */
    public function destroy($id)
    {
        $service_item = ServiceItem::findOrFail($id);
        $service_item->delete();

        return redirect()->route('service-item.index')->with('success', 'Data service item berhasil dihapus!');
    }
}