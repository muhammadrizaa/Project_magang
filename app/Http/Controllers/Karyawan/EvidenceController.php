<?php
namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Evidence;
use App\Models\Waspang;
use App\Models\Tematik;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EvidenceController extends Controller
{
    public function index()
    {
        $evidences = Evidence::whereHas('assignment', function($q) {
                        $q->where('user_id', Auth::id());
                    })->latest()->paginate(10);
        return view('karyawan.evidence.index', compact('evidences'));
    }

    public function create()
    {
        $donePoIds = Evidence::where('status_laporan', 'approved')
                             ->pluck('po_id')
                             ->unique()
                             ->map(fn($id) => (int) $id)
                             ->toArray();

        $po_list      = PurchaseOrder::whereNotIn('id', $donePoIds)->orderBy('no_po', 'asc')->get();
        $waspang_list = Waspang::orderBy('nama_waspang', 'asc')->get();
        $tematik_list = Tematik::orderBy('nama_tematik', 'asc')->get();

        return view('karyawan.evidence.create', compact('waspang_list', 'tematik_list', 'po_list'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'deskripsi'  => ['nullable', 'string'],
            'file'       => ['required', 'array', 'min:1'],
            'file.*'     => ['image', 'mimes:jpeg,jpg,png'],
            'caption'    => ['nullable', 'array'],
            'caption.*'  => ['nullable', 'string', 'max:255'],
            'waspang_id' => ['required', 'integer', 'exists:waspang,id'],
            'tematik_id' => ['required', 'integer', 'exists:tematik,id'],
            'po_id'      => ['required', 'integer', 'exists:purchase_order,id'],
        ]);

        try {
            $fileData = [];
            $captions = $request->input('caption', []);

            if ($request->hasFile('file')) {
                foreach ($request->file('file') as $index => $file) {
                    $originalName = $file->getClientOriginalName();
                    $path = $file->storeAs('evidences/' . Auth::id(), $originalName, 'public');
                    $fileData[] = [
                        'path'    => $path,
                        'caption' => $captions[$index] ?? $originalName,
                    ];
                }
            }

            // Cari assignment milik user ini
            $assignment = \App\Models\Assignment::where('user_id', Auth::id())->first();

            Evidence::create([
                'assignment_id'  => $assignment ? $assignment->id : null,
                'po_id'          => $request->po_id,
                'waspang_id'     => $request->waspang_id,
                'tematik_id'     => $request->tematik_id,
                'deskripsi'      => $request->deskripsi,
                'file_path'      => $fileData,
                'status_laporan' => 'pending',
            ]);

            return response()->json([
                'success'      => true,
                'message'      => 'Evidence berhasil di-upload!',
                'total_files'  => count($fileData),
                'redirect'     => route('karyawan.evidence.index'),
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menyimpan data.',
                'errors'  => ['system' => $e->getMessage()],
            ], 500);
        }
    }

    public function edit(Evidence $evidence)
    {
        $waspang_list = Waspang::orderBy('nama_waspang', 'asc')->get();
        $tematik_list = Tematik::orderBy('nama_tematik', 'asc')->get();
        $po_list      = PurchaseOrder::orderBy('no_po', 'asc')->get();

        return view('karyawan.evidence.edit', compact('evidence', 'waspang_list', 'tematik_list', 'po_list'));
    }

    public function update(Request $request, Evidence $evidence)
    {
        $request->validate([
            'deskripsi'      => ['nullable', 'string'],
            'file'           => ['nullable', 'array'],
            'file.*'         => ['image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'deleted_files'  => ['nullable', 'string'],
            'waspang_id'     => ['required', 'integer', 'exists:waspang,id'],
            'tematik_id'     => ['required', 'integer', 'exists:tematik,id'],
            'po_id'          => ['required', 'integer', 'exists:purchase_order,id'],
        ]);

        try {
            $fileData = $evidence->file_path ?? [];

            if ($request->filled('deleted_files')) {
                $deletedIndexes = json_decode($request->deleted_files, true);
                if (is_array($deletedIndexes)) {
                    foreach ($deletedIndexes as $index) {
                        if (isset($fileData[$index]['path'])) {
                            Storage::disk('public')->delete($fileData[$index]['path']);
                        }
                        unset($fileData[$index]);
                    }
                    $fileData = array_values($fileData);
                }
            }

            if ($request->hasFile('file')) {
                foreach ($request->file('file') as $index => $file) {
                    $originalName = $file->getClientOriginalName();
                    $path = $file->storeAs('evidences/' . Auth::id(), $originalName, 'public');
                    $fileData[] = [
                        'path'    => $path,
                        'caption' => $originalName,
                    ];
                }
            }

            $evidence->update([
                'deskripsi'      => $request->deskripsi,
                'file_path'      => $fileData,
                'status_laporan' => 'pending',
                'waspang_id'     => $request->waspang_id,
                'tematik_id'     => $request->tematik_id,
                'po_id'          => $request->po_id,
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success'  => true,
                    'message'  => 'Evidence berhasil diperbarui!',
                    'redirect' => route('karyawan.evidence.index'),
                ], 200);
            }

            return redirect()->route('karyawan.evidence.index')->with('success', 'Evidence berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui evidence: ' . $e->getMessage());
        }
    }

    public function destroy(Evidence $evidence)
    {
        if (is_array($evidence->file_path)) {
            foreach ($evidence->file_path as $file) {
                if (is_array($file) && isset($file['path'])) {
                    Storage::disk('public')->delete($file['path']);
                }
            }
        }

        $evidence->delete();

        return redirect()->route('karyawan.evidence.index')->with('success', 'Evidence berhasil dihapus.');
    }
}