<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Evidence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EvidenceController extends Controller
{
    /**
     * Menampilkan halaman riwayat evidence milik karyawan.
     */
    public function index()
    {
        $evidences = Evidence::where('user_id', Auth::id())->latest()->paginate(10);
        return view('karyawan.evidence.index', compact('evidences'));
    }

    /**
     * Menampilkan form untuk membuat evidence baru.
     */
    public function create()
    {
        return view('karyawan.evidence.create');
    }

    /**
     * Menyimpan evidence baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'lokasi' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'file' => ['required', 'array', 'min:1'],
            'file.*' => ['image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'caption' => ['nullable', 'array'],
            'caption.*' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            $fileData = [];
            if ($request->hasFile('file')) {
                foreach ($request->file('file') as $index => $file) {
                    $path = $file->store('evidences', 'public');
                    $fileData[] = [
                        'path' => $path,
                        'caption' => $request->caption[$index] ?? $request->lokasi
                    ];
                }
            }

            Evidence::create([
                'user_id' => auth()->id(),
                'lokasi' => $request->lokasi,
                'deskripsi' => $request->deskripsi,
                'file_path' => $fileData, // Langsung kirim array, Eloquent yang urus
            ]);

            return redirect()->route('karyawan.evidence.index')->with('success', 'Evidence berhasil di-upload.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form untuk mengedit evidence.
     */
    public function edit(Evidence $evidence)
    {
        if ($evidence->user_id !== Auth::id()) {
            abort(403, 'AKSES DITOLAK.');
        }
        return view('karyawan.evidence.edit', compact('evidence'));
    }

    /**
     * Memperbarui data evidence di database.
     */
    public function update(Request $request, Evidence $evidence)
    {
        if ($evidence->user_id !== Auth::id()) {
            abort(403, 'AKSES DITOLAK.');
        }

        $request->validate([
            'lokasi' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'captions' => ['nullable', 'array'],
            'captions.*' => ['nullable', 'string', 'max:255'],
            'files' => ['nullable', 'array'],
            'files.*' => ['image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'deleted_files' => ['nullable', 'array'],
        ]);

        $files = $evidence->file_path;

        if ($request->has('deleted_files')) {
            foreach ($request->deleted_files as $pathToDelete) {
                Storage::disk('public')->delete($pathToDelete);
                $files = array_filter($files, fn($file) => $file['path'] !== $pathToDelete);
            }
        }

        if ($request->has('captions')) {
            foreach ($request->captions as $path => $caption) {
                foreach ($files as $key => $file) {
                    if ($file['path'] === $path) {
                        $files[$key]['caption'] = $caption;
                    }
                }
            }
        }

        if ($request->hasFile('files')) {
            foreach($request->file('files') as $file) {
                $path = $file->store('evidences', 'public');
                $files[] = [
                    'path' => $path,
                    'caption' => $request->lokasi
                ];
            }
        }

        $evidence->update([
            'lokasi' => $request->lokasi,
            'deskripsi' => $request->deskripsi,
            'file_path' => array_values($files),
            'status' => 'pending',
        ]);

        return redirect()->route('karyawan.evidence.index')->with('success', 'Evidence berhasil diperbarui.');
    }

    /**
     * Menghapus evidence.
     */
    public function destroy(Evidence $evidence)
    {
        if ($evidence->user_id !== Auth::id()) {
            abort(403, 'AKSES DITOLAK.');
        }

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