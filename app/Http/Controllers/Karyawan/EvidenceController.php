<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Evidence;
use App\Models\GlobalModel;
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
        // Validasi input
        $validatedData = $request->validate([
            'lokasi' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'file' => ['required', 'array', 'min:1'],
            'file.*' => ['image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'caption' => ['nullable', 'array'],
            'caption.*' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            $fileData = [];
            $captions = $request->input('caption', []);
            
            // Masukkan data project (Diasumsikan ini adalah langkah yang diperlukan)
            $id_project = GlobalModel::insertRecord('project',[
                'lokasi' => $request->lokasi,
                'deskripsi' => $request->deskripsi
            ]);
            
            // Proses upload semua file
            if ($request->hasFile('file')) {
                foreach ($request->file('file') as $index => $file) {
                    $path = $file->store('evidences/'.$id_project, 'public');
                    
                    $fileData[] = [
                        'path' => $path,
                        // Gunakan caption yang dikirim dari Dropzone, fallback ke lokasi
                        'caption' => $captions[$index] ?? $request->lokasi
                    ];
                }
            }

            // Buat record Evidence
            Evidence::create([
                'user_id' => auth()->id(),
                'project_id' => $id_project,
                'lokasi' => $request->lokasi,
                'deskripsi' => $request->deskripsi,
                'file_path' => $fileData, // Eloquent akan meng-JSON-encode karena Model Casting
                'status' => 'pending', 
            ]);

            // PENTING: Mengembalikan JSON response untuk Dropzone
            return response()->json([
                'success' => true, 
                'message' => 'Evidence berhasil di-upload.',
                'redirect' => route('karyawan.evidence.index') 
            ], 200);

        } catch (\Exception $e) {
            // Mengembalikan JSON error response untuk Dropzone
            return response()->json([
                'message' => 'Gagal menyimpan data.', 
                'errors' => $e->getMessage()
            ], 500); 
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

        // Logika penghapusan
        if ($request->has('deleted_files')) {
            foreach ($request->deleted_files as $pathToDelete) {
                Storage::disk('public')->delete($pathToDelete);
                // Filter array untuk menghapus data path yang sudah dihapus
                $files = array_filter($files, fn($file) => ($file['path'] ?? '') !== $pathToDelete);
            }
        }

        // Logika update caption
        if ($request->has('captions')) {
            foreach ($request->captions as $path => $caption) {
                foreach ($files as $key => $file) {
                    if (($file['path'] ?? '') === $path) {
                        $files[$key]['caption'] = $caption;
                    }
                }
            }
        }

        // Logika penambahan file baru
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

        // Hapus semua file yang terkait
        if (is_array($evidence->file_path)) {
            foreach ($evidence->file_path as $file) {
                if (is_array($file) && isset($file['path'])) {
                    Storage::disk('public')->delete($file['path']);
                }
            }
        }
        
        $evidence->delete();
        
        // Mengembalikan redirect standar karena ini adalah form submit biasa, bukan AJAX/Dropzone
        return redirect()->route('karyawan.evidence.index')->with('success', 'Evidence berhasil dihapus.');
    }
}