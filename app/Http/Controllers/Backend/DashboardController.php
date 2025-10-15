<?php

namespace App\Http\Controllers\Backend;

use App\Models\Service;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index(){
    // Ambil semua total_cost dari service yang sudah "paid"
        $totalBalance = Service::where('status_paid', 'paid')->sum('total_cost');

        return view('page.backend.dashboard.index', compact('totalBalance'));
    }
}
