<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Models\Service;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use App\Models\ServiceDetail;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index(){
    // Ambil semua total_cost dari service yang sudah "paid"
        $totalBalance = Service::where('status_paid', 'paid')->sum('total_cost');

        // Total semua service
        $totalService = Service::count();

        // Total customer (misal role 'customer' disimpan di kolom 'role')
        $totalCustomer = User::where('role', 'customer')->count();

        $totalProductOrdered = ServiceDetail::sum('qty');

        return view('page.backend.dashboard.index', compact(
            'totalBalance',
            'totalService',
            'totalCustomer',
            'totalProductOrdered'
        ));
    }
}
