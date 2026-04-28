<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Waspang;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WaspangController extends Controller
{
    public function index()
    {
        $waspang_list = Waspang::oldest('created_at')->paginate(10);
        return view('admin.waspang.index', compact('waspang_list'));
    }

    public function create()
    {
        return view('admin.waspang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_waspang' => 'required|string|max:255|unique:waspang,nama_waspang',
            'nik_waspang'  => 'nullable|string|max:255',
        ]);

        Waspang::create([
            'nama_waspang' => $request->nama_waspang,
            'nik_waspang'  => $request->nik_waspang,
        ]);

        return redirect()->route('admin.waspang.index')
                         ->with('success', 'Data Waspang berhasil ditambahkan.');
    }

    public function show(Waspang $pangwas)
    {
        abort(404);
    }

    public function edit(Waspang $pangwas)
    {
        return view('admin.waspang.edit', compact('pangwas'));
    }

    public function update(Request $request, Waspang $pangwas)
    {
        $request->validate([
            'nama_waspang' => [
                'required', 'string', 'max:255',
                Rule::unique('waspang', 'nama_waspang')->ignore($pangwas->id),
            ],
            'nik_waspang' => 'nullable|string|max:255',
        ]);

        $pangwas->update([
            'nama_waspang' => $request->nama_waspang,
            'nik_waspang'  => $request->nik_waspang,
        ]);

        return redirect()->route('admin.waspang.index')
                         ->with('success', 'Data Waspang berhasil diperbarui.');
    }

    public function destroy(Waspang $pangwas)
    {
        if ($pangwas->evidence()->exists()) {
            return redirect()->route('admin.waspang.index')
                             ->with('error', 'Waspang tidak dapat dihapus karena sudah memiliki data Evidence terkait.');
        }

        try {
            $pangwas->delete();
            return redirect()->route('admin.waspang.index')
                             ->with('success', 'Data Waspang berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.waspang.index')
                             ->with('error', 'Gagal menghapus data Waspang. Error: ' . $e->getMessage());
        }
    }
}
