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
        ], [
            'email.unique' => 'Kombinasi nama dan email sudah digunakan pengguna lain.',
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
        ], [
            'email.unique' => 'Kombinasi nama dan email sudah digunakan pengguna lain.',
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

    public function destroy(User $user)
    {
        if ($user->image && Storage::disk('public')->exists($user->image)) {
            Storage::disk('public')->delete($user->image);
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
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