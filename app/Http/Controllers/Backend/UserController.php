<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Service;
use App\Models\Handphone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('page.backend.user.index', compact('users'));
    }

    public function create()
    {
        return view('page.backend.user.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,technician,customer',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'is_active' => 'required|in:0,1', // fix boolean
        ]);

        // ubah string "1"/"0" jadi integer
        $validated['is_active'] = $validated['is_active'] == '1' ? 1 : 0;

        // Simpan gambar jika ada
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('users', 'public');
        }

        // Hash password
        $validated['password'] = Hash::make($validated['password']);

        // Buat user baru
        $user = User::create($validated);

        // Buat service otomatis hanya jika role customer
        if ($user->role === 'customer') {
            $handphone = Handphone::first();
            if ($handphone) {
                Service::create([
                    'no_invoice' => 'INV-' . strtoupper(uniqid()),
                    'customer_id' => $user->id,
                    'handphone_id' => $handphone->id,
                    'damage_description' => 'Belum ada keterangan',
                    'estimated_cost' => 0,
                    'status' => 'accepted',
                    'total_cost' => 0,
                    'other_cost' => 0,
                    'paid' => 0,
                    'change' => 0,
                    'paymentmethod' => null,
                    'status_paid' => 'unpaid',
                    'received_date' => now(),
                    'completed_date' => null,
                ]);
            }
        }

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        return view('page.backend.user.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('page.backend.user.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'role' => 'required|in:admin,technician,customer',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'is_active' => 'required|in:0,1',
        ]);

        $validated['is_active'] = $validated['is_active'] == '1' ? 1 : 0;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('users', 'public');
        }

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        Service::where('customer_id', $user->id)->delete();
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User dan data Service terkait berhasil dihapus.');
    }
}