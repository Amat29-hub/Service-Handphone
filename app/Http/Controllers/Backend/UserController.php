<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Tampilkan daftar user.
     */
    public function index()
    {
        $users = User::orderBy('id', 'asc')->get();
        return view('page.backend.users.index', compact('users'));
    }

    /**
     * Tampilkan form tambah user.
     */
    public function create()
    {
        return view('page.backend.users.create');
    }

    /**
     * Simpan user baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required|string',
            'is_active'=> 'nullable|string',
            'image'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $validated['password'] = bcrypt($request->password);

        // Upload foto jika ada
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads/users', 'public');
            $validated['image'] = $path;
        }

        // Set default status
        $validated['is_active'] = $request->is_active ?? 'active';

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit user.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('page.backend.users.edit', compact('user'));
    }

    /**
     * Update data user.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|string',
            'is_active'=> 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Upload foto baru jika ada
        if ($request->hasFile('image')) {
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }

            $path = $request->file('image')->store('uploads/users', 'public');
            $validated['image'] = $path;
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Hapus user.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->image && Storage::disk('public')->exists($user->image)) {
            Storage::disk('public')->delete($user->image);
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }

    /**
     * Toggle status aktif/nonaktif (via AJAX)
     */
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = $user->is_active === 'active' ? 'nonactive' : 'active';
        $user->save();

        return response()->json([
            'success' => true,
            'status' => $user->is_active
        ]);
    }
}
