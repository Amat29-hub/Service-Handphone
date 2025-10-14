<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceDetail;
use App\Models\Serviceitem;
use App\Models\Customer;
use App\Models\Handphone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    /**
     * Tampilkan semua data service.
     */
    public function index()
    {
        $services = Service::with(['customer', 'handphone'])->latest()->get();
        return view('page.backend.service.index', compact('services'));
    }

    /**
     * Form create service baru.
     */
    public function create()
    {
        // Ambil data pelanggan
        $customers = \App\Models\User::where('role', 'customer')
            ->orWhere('role', 'user')
            ->get();
    
        // Ambil data handphone
        $handphones = \App\Models\Handphone::all();
    
        // Ambil data teknisi
        $technicians = \App\Models\User::where('role', 'technician')->get();
    
        // Ambil data service item (produk servis)
        $products = \App\Models\ServiceItem::where('is_active', 'active')->get();
    
        // Generate nomor invoice otomatis
        $lastService = \App\Models\Service::latest('id')->first();
        $nextId = $lastService ? $lastService->id + 1 : 1;
        $no_invoice = 'INV-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
    
        // Kirim semua data ke view
        return view('page.backend.service.create', compact(
            'customers',
            'handphones',
            'technicians',
            'products',
            'no_invoice'
        ));
    }

    /**
     * Simpan service baru beserta detailnya.
     */
    public function store(Request $request)
    {
        // Validasi request
        $request->validate([
            'no_invoice' => 'required|unique:services,no_invoice',
            'customer_id' => 'required|exists:users,id',
            'technician_id' => 'nullable|exists:users,id',
            'handphone_id' => 'required|exists:handphones,id',
            'damage_description' => 'nullable|string',
            'status' => 'nullable|string|in:accepted,process,finished,taken,cancelled',
            'products' => 'required|array',
            'products.*' => 'exists:serviceitems,id',
            'qty' => 'required|array',
            'qty.*' => 'numeric|min:1',
            'price' => 'required|array',
            'price.*' => 'numeric|min:0',
            'other_cost' => 'nullable|numeric|min:0',
        ]);
    
        // Hitung total biaya
        $totalService = 0;
        foreach ($request->products as $key => $productId) {
            $subtotal = $request->qty[$key] * $request->price[$key];
            $totalService += $subtotal;
        }
    
        $totalCost = $totalService + ($request->other_cost ?? 0);
    
        // Simpan data service
        $service = \App\Models\Service::create([
            'no_invoice' => $request->no_invoice,
            'customer_id' => $request->customer_id,
            'technician_id' => $request->technician_id,
            'handphone_id' => $request->handphone_id,
            'damage_description' => $request->damage_description,
            'status' => $request->status ?? 'accepted',
            'total_cost' => $totalCost,
            'other_cost' => $request->other_cost ?? 0,
            'paid' => 0,
            'change' => 0,
            'status_paid' => 'unpaid',
            'received_date' => $request->received_date ?? now(),
            'completed_date' => $request->completed_date ?? null,
        ]);
    
        // Simpan detail service
        foreach ($request->products as $key => $productId) {
            $service->details()->create([
                'serviceitem_id' => $productId,
                'qty' => $request->qty[$key],
                'price' => $request->price[$key],
                'subtotal' => $request->qty[$key] * $request->price[$key],
            ]);
        }
    
        return redirect()->route('service.index')->with('success', 'Transaksi service berhasil ditambahkan!');
    }

    /**
     * Tampilkan detail & form pembayaran.
     */
    public function payment($id)
    {
        $service = Service::with(['customer', 'handphone', 'details.serviceitem'])->findOrFail($id);
        return view('page.backend.service.payment', compact('service'));
    }

    /**
     * Proses pembayaran service.
     */
    public function processPayment(Request $request, $id)
    {
        $request->validate([
            'paid' => 'required|numeric|min:0',
            'paymentmethod' => 'required|string',
        ]);

        $service = Service::findOrFail($id);

        $total = $service->total_cost;
        $totalPaid = $service->paid + $request->paid;

        // Tentukan status_paid otomatis
        if ($totalPaid == 0) {
            $statusPaid = 'unpaid';
        } elseif ($totalPaid < $total) {
            $statusPaid = 'debt';
        } else {
            $statusPaid = 'paid';
        }

        // Tentukan status otomatis berdasarkan kondisi pembayaran
        $status = match ($statusPaid) {
            'paid' => 'finished',
            'debt' => 'process',
            default => $service->status,
        };

        $service->update([
            'paid' => min($totalPaid, $total),
            'status_paid' => $statusPaid,
            'status' => $status,
            'paymentmethod' => $request->paymentmethod,
            'completed_date' => $status === 'finished' ? now() : $service->completed_date,
        ]);

        return redirect()->route('service.index')->with('success', '✅ Pembayaran berhasil! Status: ' . strtoupper($statusPaid));
    }

    /**
     * Hapus data service dan semua detail terkait.
     */
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return redirect()->route('service.index')->with('success', '🗑️ Data service berhasil dihapus!');
    }

    /**
     * Tampilkan detail transaksi service
     */
    public function show($id)
    {
        // Ambil service beserta relasi customer, technician, handphone, dan detail->serviceitem
        $service = Service::with([
            'customer',
            'technician',
            'handphone',
            'details.serviceitem' // relasi pivot untuk qty, price, subtotal
        ])->findOrFail($id);
    
        return view('page.backend.service.show', compact('service'));
    }
}