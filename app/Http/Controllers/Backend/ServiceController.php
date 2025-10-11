<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\User;
use App\Models\Handphone;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with(['customer', 'technician', 'handphone'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('page.backend.service.index', compact('services'));
    }

    public function create()
    {
        // Ambil user yang aktif berdasarkan role
        $customers = User::where('role', 'customer')
            ->where('is_active', 1)
            ->get();

        $technicians = User::where('role', 'technician')
            ->where('is_active', 1)
            ->get();

        // Ambil handphone aktif
        $handphones = Handphone::where('is_active', 1)->get();

        return view('page.backend.service.create', compact('customers', 'technicians', 'handphones'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_invoice' => 'required|unique:services,no_invoice',
            'customer_id' => 'required|exists:users,id',
            'technician_id' => 'required|exists:users,id',
            'handphone_id' => 'required|exists:handphones,id',
            'damage_description' => 'required|string',
            'estimated_cost' => 'nullable|numeric',
            'other_cost' => 'nullable|numeric',
            'paymentmethod' => 'required|string|in:cash,transfer',
            'status' => 'required|string|in:accepted,process,finished,taken,cancelled',
            'status_paid' => 'required|string|in:paid,debt,unpaid',
            'received_date' => 'required|date',
            'completed_date' => 'nullable|date',
        ]);

        Service::create($validated);

        return redirect()->route('service.index')->with('success', 'Data service berhasil ditambahkan!');
    }

    public function show($id)
    {
        $service = Service::with(['customer', 'technician', 'handphone'])->findOrFail($id);
        return view('page.backend.service.show', compact('service'));
    }
}