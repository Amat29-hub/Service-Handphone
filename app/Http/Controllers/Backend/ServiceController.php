<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\User;
use App\Models\Handphone;
use App\Models\ServiceItem;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with(['customer', 'handphone', 'technician', 'serviceItem'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('page.backend.service.index', compact('services'));
    }

    public function create()
    {
        $last = Service::latest()->first();
        $nextNumber = $last ? intval(substr($last->no_invoice, -3)) + 1 : 1;
        $no_invoice = 'INV-' . date('Ymd') . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        $customers = User::where('role', 'customer')->orderBy('name')->get();
        $handphones = Handphone::orderBy('brand')->get();
        $serviceItems = ServiceItem::where('is_active', 'active')->orderBy('service_name')->get();

        return view('page.backend.service.create', compact('no_invoice', 'customers', 'handphones', 'serviceItems'));
    }

public function store(Request $request)
{
    // Validasi input
    $validated = $request->validate([
        'no_invoice' => 'required|unique:services,no_invoice',
        'customer_id' => 'required|exists:users,id',
        'handphone_id' => 'required|exists:handphones,id',
        'service_item_id' => 'required|exists:serviceitems,id',
        'damage_description' => 'required|string',
        'estimated_cost' => 'required|numeric',
        'other_cost' => 'nullable|numeric',
        'paymentmethod' => 'required|string',
        'status' => 'required|string',
        'status_paid' => 'required|string',
        'received_date' => 'required|date',
        'completed_date' => 'required|date',
        'paid' => 'required|numeric',
    ]);

    // Pastikan other_cost tidak null
    $validated['other_cost'] = $validated['other_cost'] ?? 0;

    // Hitung total_cost dan change
    $total = $validated['estimated_cost'] + $validated['other_cost'];
    $change = $validated['paid'] - $total;

    $validated['total_cost'] = $total;
    $validated['change'] = $change;
    $validated['technician_id'] = auth()->id();

    // Simpan data ke database
    Service::create($validated);

    return redirect()->route('service.index')->with('success', '✅ Data service berhasil ditambahkan!');
}

    public function edit($id)
    {
        $service = Service::findOrFail($id);
        $customers = User::where('role', 'customer')->orderBy('name')->get();
        $handphones = Handphone::orderBy('brand')->get();
        $serviceItems = ServiceItem::where('is_active', 'active')->orderBy('service_name')->get();

        return view('page.backend.service.edit', compact('service', 'customers', 'handphones', 'serviceItems'));
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $validated = $request->validate([
            'customer_id' => 'required|exists:users,id',
            'handphone_id' => 'required|exists:handphones,id',
            'service_item_id' => 'nullable|exists:serviceitems,id',
            'damage_description' => 'required|string',
            'estimated_cost' => 'nullable|numeric',
            'other_cost' => 'nullable|numeric',
            'paymentmethod' => 'required|string|in:cash,transfer',
            'status' => 'required|string|in:accepted,process,finished,taken,cancelled',
            'status_paid' => 'required|string|in:paid,debt,unpaid',
            'received_date' => 'required|date',
            'completed_date' => 'nullable|date',
            'paid' => 'nullable|numeric|min:0',
        ]);

        // Ambil biaya perkiraan dari ServiceItem jika ada
        if (!empty($validated['service_item_id'])) {
            $serviceItem = ServiceItem::find($validated['service_item_id']);
            if ($serviceItem) {
                $validated['estimated_cost'] = $serviceItem->price ?? 0;
            }
        }

        // Hitung ulang total & kembalian
        $total = ($validated['estimated_cost'] ?? 0) + ($validated['other_cost'] ?? 0);
        $paid = $validated['paid'] ?? 0;
        $change = max(0, $paid - $total);

        $validated['total_cost'] = $total;
        $validated['change'] = $change;

        $service->update($validated);

        return redirect()->route('service.index')->with('success', '✅ Data service berhasil diperbarui!');
    }

    public function show($id)
    {
        $service = Service::with(['customer', 'handphone', 'technician', 'serviceItem'])->findOrFail($id);
        return view('page.backend.service.show', compact('service'));
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return redirect()->route('service.index')->with('success', '🗑️ Data service berhasil dihapus!');
    }
}