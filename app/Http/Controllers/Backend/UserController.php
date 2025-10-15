<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Service;
use App\Models\Handphone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        return view('page.backend.user.index', compact('users'));
    }

    public function create()
    {
        return view('page.backend.user.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:6',
            'role'      => 'required|in:admin,technician,customer',
            'image'     => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'is_active' => 'required|in:active,nonactive',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('users', 'public');
        }

        $user = User::create($validated);

        if ($user->role === 'customer') {
            $handphone = Handphone::first();
            if ($handphone) {
                Service::create([
                    'no_invoice'          => 'INV-' . strtoupper(uniqid()),
                    'customer_id'         => $user->id,
                    'handphone_id'        => $handphone->id,
                    'damage_description'  => 'Belum ada keterangan',
                    'estimated_cost'      => 0,
                    'status'              => 'accepted',
                    'total_cost'          => 0,
                    'other_cost'          => 0,
                    'paid'                => 0,
                    'change'              => 0,
                    'paymentmethod'       => null,
                    'status_paid'         => 'unpaid',
                    'received_date'       => now(),
                ]);
            }
        }

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('page.backend.user.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('page.backend.user.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'password'  => 'nullable|min:6',
            'role'      => 'required|in:admin,technician,customer',
            'image'     => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'is_active' => 'required|in:active,nonactive',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($request->hasFile('image')) {
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }
            $validated['image'] = $request->file('image')->store('users', 'public');
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->image && Storage::disk('public')->exists($user->image)) {
            Storage::disk('public')->delete($user->image);
        }

        Service::where('customer_id', $user->id)->delete();
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }

    public function toggleStatus(Request $request, $id)
    {
        $request->validate([
            'is_active' => 'required|in:active,nonactive',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'is_active' => $request->is_active
        ]);

        return response()->json([
            'success'   => true,
            'is_active' => $user->is_active
        ]);
    }
}