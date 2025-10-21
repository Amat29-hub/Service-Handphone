<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceDetail;
use App\Models\ServiceItem;
use App\Models\User;
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
        $customers = User::whereIn('role', ['customer', 'user'])->get();
        $handphones = Handphone::all();
        $technicians = User::where('role', 'technician')->get();
        $products = ServiceItem::where('is_active', 'active')->get();

        // Generate nomor invoice otomatis
        $lastService = Service::latest('id')->first();
        $nextId = $lastService ? $lastService->id + 1 : 1;
        $no_invoice = 'INV-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

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
        $request->validate([
            'no_invoice' => 'required|unique:services,no_invoice',
            'customer_id' => 'required|exists:users,id',
            'technician_id' => 'required|exists:users,id',
            'handphone_id' => 'required|exists:handphones,id',
            'damage_description' => 'required|string',
            'products' => 'required|array|min:1',
            'products.*' => 'exists:serviceitems,id',
            'price' => 'required|array|min:1',
            'price.*' => 'numeric|min:0',
            'other_cost' => 'nullable|numeric|min:0',
        ]);
    
        // Hitung total biaya service
        $totalService = array_sum($request->price);
        $totalCost = $totalService + ($request->other_cost ?? 0);
    
        // Simpan data service utama
        $service = \App\Models\Service::create([
            'no_invoice' => $request->no_invoice,
            'customer_id' => $request->customer_id,
            'technician_id' => $request->technician_id,
            'handphone_id' => $request->handphone_id,
            'damage_description' => $request->damage_description,
            'status' => 'accepted',
            'total_cost' => $totalCost,
            'other_cost' => $request->other_cost ?? 0,
            'paid' => 0,
            'change' => 0,
            'status_paid' => 'unpaid',
            'received_date' => now(),
            'completed_date' => null,
        ]);
    
        // Simpan detail service item
        foreach ($request->products as $index => $productId) {
            $service->details()->create([
                'serviceitem_id' => $productId,
                'qty' => 1, // karena qty dihapus dari form
                'price' => $request->price[$index],
                'subtotal' => $request->price[$index],
            ]);
        }
    
        return redirect()->route('service.index')->with('success', '✅ Transaksi service berhasil ditambahkan!');
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

        $statusPaid = $totalPaid == 0 ? 'unpaid' : ($totalPaid < $total ? 'debt' : 'paid');
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

        // Cegah bug: jangan hapus jika ini satu-satunya service terkait produk
        $remainingProducts = ServiceItem::count();
        if ($remainingProducts <= 1) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus produk terakhir untuk mencegah bug!');
        }

        $service->delete();
        return redirect()->route('service.index')->with('success', '🗑️ Data service berhasil dihapus!');
    }

    /**
     * Tampilkan detail transaksi service.
     */
    public function show($id)
    {
        $service = Service::with([
            'customer',
            'technician',
            'handphone',
            'details.serviceitem'
        ])->findOrFail($id);

        return view('page.backend.service.show', compact('service'));
    }
}