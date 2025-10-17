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
        // Total uang masuk
        $totalBalance = Service::where('paid', '>', 0)->sum('paid');

        // Total semua service
        $totalService = Service::count();

        // Total customer
        $totalCustomer = User::where('role', 'customer')->count();

        // Total produk yang sudah diorder
        $totalProductOrdered = ServiceDetail::sum('qty');

        // Riwayat pemesanan (5 data terbaru)
        $recentServices = Service::with('customer')
            ->latest('received_date')
            ->take(5)
            ->get();

        return view('page.backend.dashboard.index', compact(
            'totalBalance',
            'totalService',
            'totalCustomer',
            'totalProductOrdered',
            'recentServices'
        ));
    }
}
