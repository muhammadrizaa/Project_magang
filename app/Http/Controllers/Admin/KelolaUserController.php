<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class KelolaUserController extends Controller
{
    public function index()
    {
        $karyawan = User::whereIn('role', ['karyawan', 'team leader'])
                        ->latest('updated_at')
                        ->paginate(10);
        return view('admin.kelolauser.index', compact('karyawan'));
    }

    public function create()
    {
        return view('admin.kelolauser.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:' . User::class],
            'email'    => ['nullable', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role'     => ['required', Rule::in(['karyawan', 'team leader'])],
        ]);

        User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return redirect()->route('admin.kelolauser.index')->with('success', 'User baru berhasil ditambahkan.');
    }

    public function edit(User $kelolauser)
    {
        return view('admin.kelolauser.edit', compact('kelolauser'));
    }

    public function update(Request $request, User $kelolauser)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($kelolauser->id)],
            'email'    => ['nullable', 'string', 'email', 'max:255', Rule::unique('users')->ignore($kelolauser->id)],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role'     => ['required', Rule::in(['karyawan', 'team leader'])],
        ]);

        $kelolauser->update([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'role'     => $request->role,
        ]);

        if ($request->filled('password')) {
            $kelolauser->password = Hash::make($request->password);
            $kelolauser->save();
        }

        return redirect()->route('admin.kelolauser.index')->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy(User $kelolauser)
    {
        $kelolauser->delete();
        return redirect()->route('admin.kelolauser.index')->with('success', 'User berhasil dihapus.');
    }
}