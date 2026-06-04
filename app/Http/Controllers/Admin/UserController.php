<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
        }
        $users = $query->orderBy('name')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ], [
            'email.unique'        => 'Email sudah dipakai akun lain.',
            'password.confirmed'  => 'Konfirmasi password tidak cocok.',
            'password.min'        => 'Password minimal 8 karakter.',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User admin berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ], [
            'email.unique' => 'Email sudah dipakai akun lain.',
        ]);

        $data = $request->only('name', 'email');

        // Password opsional: hanya diupdate jika diisi
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:8|confirmed',
            ], [
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
                'password.min'       => 'Password minimal 8 karakter.',
            ]);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Data user diperbarui.');
    }

    public function destroy(User $user)
    {
        // Cegah hapus diri sendiri
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        // Cegah hapus admin terakhir
        if (User::count() <= 1) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus user admin terakhir.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User admin dihapus.');
    }
}
