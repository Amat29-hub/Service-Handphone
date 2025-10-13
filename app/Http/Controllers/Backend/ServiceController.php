<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\User;
use App\Models\Handphone;
use App\Models\ServiceItem;
use Carbon\Carbon;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with(['customer', 'handphone'])->latest()->get();
        return view('page.backend.service.index', compact('services'));
    }

    public function create()
    {
        $customers = User::where('role', 'customer')->get();
        $handphones = Handphone::all();
        $serviceItems = ServiceItem::all();
        return view('page.backend.service.create', compact('customers', 'handphones', 'serviceItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'handphone_id' => 'required',
            'service_item_id' => 'required',
            'damage_description' => 'required',
            'estimated_cost' => 'nullable|numeric',
            'other_cost' => 'nullable|numeric',
        ]);

        $total = ($request->estimated_cost ?? 0) + ($request->other_cost ?? 0);

        Service::create([
            'no_invoice' => 'INV-' . time(),
            'customer_id' => $request->customer_id,
            'handphone_id' => $request->handphone_id,
            'service_item_id' => $request->service_item_id,
            'damage_description' => $request->damage_description,
            'estimated_cost' => $request->estimated_cost ?? 0,
            'other_cost' => $request->other_cost ?? 0,
            'total_cost' => $total,
            'paymentmethod' => $request->paymentmethod ?? 'cash',
            'status' => $request->status ?? 'accepted',
            'status_paid' => 'unpaid',
            'received_date' => $request->received_date ?? Carbon::now(),
        ]);

        return redirect()->route('service.index')->with('success', 'Transaksi berhasil ditambahkan!');
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

        $request->validate([
            'customer_id' => 'required',
            'handphone_id' => 'required',
            'service_item_id' => 'required',
            'damage_description' => 'required',
            'estimated_cost' => 'nullable|numeric',
            'other_cost' => 'nullable|numeric',
            'paid' => 'nullable|numeric',
        ]);

        $estimated = $request->estimated_cost ?? 0;
        $other = $request->other_cost ?? 0;
        $total = $estimated + $other;
        $paid = $request->paid ?? 0;

        // Tentukan status pembayaran otomatis
        if ($paid <= 0) {
            $statusPaid = 'unpaid';
        } elseif ($paid < $total) {
            $statusPaid = 'debt';
        } else {
            $statusPaid = 'paid';
        }

        // Hitung kembalian
        $change = $paid > $total ? ($paid - $total) : 0;

        $service->update([
            'customer_id' => $request->customer_id,
            'handphone_id' => $request->handphone_id,
            'service_item_id' => $request->service_item_id,
            'damage_description' => $request->damage_description,
            'estimated_cost' => $estimated,
            'other_cost' => $other,
            'total_cost' => $total,
            'paid' => $paid,
            'change' => $change,
            'paymentmethod' => $request->paymentmethod,
            'status' => $request->status,
            'status_paid' => $statusPaid,
            'received_date' => $request->received_date,
            'completed_date' => $request->completed_date,
        ]);

        return redirect()->route('service.index')->with('success', 'Transaksi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();
        return redirect()->route('service.index')->with('success', 'Data berhasil dihapus!');
    }

    // Halaman pembayaran
    public function payment($id)
    {
        $service = Service::findOrFail($id);
        return view('page.backend.service.payment', compact('service'));
    }

    public function processPayment(Request $request, $id)
    {
        $service = Service::findOrFail($id);
    
        $paidBaru = $request->paid ?? 0;
        $total = $service->total_cost ?? 0;
        $paidTotal = $service->paid + $paidBaru; // total keseluruhan
        $change = $paidTotal > $total ? $paidTotal - $total : 0;
    
        if ($paidTotal <= 0) {
            $statusPaid = 'unpaid';
        } elseif ($paidTotal < $total) {
            $statusPaid = 'debt';
        } else {
            $statusPaid = 'paid';
        }
    
        $service->update([
            'paid' => $paidTotal,
            'change' => $change,
            'status_paid' => $statusPaid,
        ]);
    
        return redirect()->route('service.index')->with('success', 'Pembayaran berhasil diperbarui!');
    }
}