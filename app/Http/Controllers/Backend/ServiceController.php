<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\User;
use App\Models\Handphone;
use App\Models\ServiceItem;
use PDF;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with(['customer', 'handphone', 'serviceItem'])->latest()->get();
        return view('page.backend.service.index', compact('services'));
    }

    public function create()
    {
        $lastService = Service::latest('id')->first();
        $no_invoice = 'INV-' . str_pad(($lastService->id ?? 0) + 1, 5, '0', STR_PAD_LEFT);

        $customers = User::where('role', 'customer')->get();
        $handphones = Handphone::all();
        $serviceItems = ServiceItem::all();

        return view('page.backend.service.create', compact('no_invoice', 'customers', 'handphones', 'serviceItems'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_invoice' => 'required|string',
            'customer_id' => 'required|integer',
            'handphone_id' => 'required|integer',
            'service_item_id' => 'required|integer',
            'damage_description' => 'required|string',
            'estimated_cost' => 'required|numeric',
            'other_cost' => 'nullable|numeric',
            'paymentmethod' => 'required|string',
            'status' => 'required|string',
            'received_date' => 'nullable|date',
            'completed_date' => 'nullable|date',
        ]);

        $validated['other_cost'] = $validated['other_cost'] ?? 0;
        $validated['status_paid'] = 'unpaid';
        $validated['paid'] = 0;
        $validated['total_cost'] = $validated['estimated_cost'] + $validated['other_cost'];

        Service::create($validated);

        return redirect()->route('service.index')->with('success', 'Transaksi service berhasil ditambahkan!');
    }

    public function show($id)
    {
        $service = Service::with(['customer', 'handphone', 'serviceItem'])->findOrFail($id);
        return view('page.backend.service.show', compact('service'));
    }

    public function edit($id)
    {
        $service = Service::findOrFail($id);
        $customers = User::where('role', 'customer')->get();
        $handphones = Handphone::all();
        $serviceItems = ServiceItem::all();

        return view('page.backend.service.edit', compact('service', 'customers', 'handphones', 'serviceItems'));
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $validated = $request->validate([
            'customer_id' => 'required|integer',
            'handphone_id' => 'required|integer',
            'service_item_id' => 'required|integer',
            'damage_description' => 'required|string',
            'estimated_cost' => 'required|numeric',
            'other_cost' => 'nullable|numeric',
            'paymentmethod' => 'required|string',
            'status' => 'required|string',
            'received_date' => 'nullable|date',
            'completed_date' => 'nullable|date',
        ]);

        $validated['other_cost'] = $validated['other_cost'] ?? 0;
        $validated['total_cost'] = $validated['estimated_cost'] + $validated['other_cost'];

        $service->update($validated);

        return redirect()->route('service.index')->with('success', 'Data service berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();
        return redirect()->route('service.index')->with('success', 'Data service berhasil dihapus!');
    }

    public function payment($id)
    {
        $service = Service::findOrFail($id);
        return view('page.backend.service.payment', compact('service'));
    }

    public function processPayment(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $validated = $request->validate([
            'amount_paid' => 'required|numeric|min:0',
        ]);

        $service->paid += $validated['amount_paid'];

        if ($service->paid >= $service->total_cost) {
            $service->status_paid = 'paid';
        } elseif ($service->paid > 0) {
            $service->status_paid = 'debt';
        } else {
            $service->status_paid = 'unpaid';
        }

        $service->save();

        return redirect()->route('service.index')->with('success', 'Pembayaran berhasil diproses!');
    }

    // CETAK STRUK SERVICE
    public function cetakStruk($id)
    {
        $service = Service::with(['customer', 'handphone', 'serviceItem'])->findOrFail($id);
        return view('page.backend.service.struk', compact('service'));
    }
}