<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // 🔍 Ambil parameter pencarian & filter dari request
        $search = $request->input('search');
        $role   = $request->input('role');
    
        // 🔧 Query dasar termasuk user yang di-soft delete
        $query = \App\Models\User::withTrashed();
    
        // 🔎 Jika ada keyword pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
    
        // 🎭 Jika ada filter role
        if ($role) {
            $query->where('role', $role);
        }
    
        // 🔽 Urutkan dari yang terbaru
        $users = $query->orderBy('id', 'desc')->get();
    
        // ✅ Arahkan ke view sesuai struktur kamu
        return view('page.backend.user.index', compact('users'));
    }

    public function create()
    {
        return view('page.backend.user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => [
                'required',
                'email',
                Rule::unique('users')->where(function ($query) use ($request) {
                    return $query->where('name', $request->name);
                }),
            ],
            'password'   => 'required|string|min:6',
            'role'       => 'required|in:admin,technician,customer',
            'is_active'  => 'required|in:active,nonactive',
            'image'      => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'email', 'role', 'is_active']);
        $data['password'] = Hash::make($request->password);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('users', 'public');
        }

        User::create($data);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
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
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id)->where(function ($query) use ($request) {
                    return $query->where('name', $request->name);
                }),
            ],
            'role'       => 'required|in:admin,technician,customer',
            'is_active'  => 'required|in:active,nonactive',
            'image'      => 'nullable|image|max:2048',
            'password'   => 'nullable|string|min:6',
        ]);

        $data = $request->only(['name', 'email', 'role', 'is_active']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('image')) {
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }
            $data['image'] = $request->file('image')->store('users', 'public');
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        if ($user->trashed()) {
            // Jika sudah di-soft delete, hapus permanen + hapus file gambar
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }
            $user->forceDelete();
            return redirect()->route('users.index')->with('success', 'User dihapus permanen!');
        } else {
            // Soft delete
            $user->delete();
            return redirect()->route('users.index')->with('success', 'User berhasil dihapus (soft delete)!');
        }
    }

    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('users.index')->with('success', 'User berhasil direstore!');
    }

    public function toggleStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'is_active' => 'required|in:active,nonactive',
        ]);

        $user->update(['is_active' => $request->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $user->is_active,
        ]);
    }
}
