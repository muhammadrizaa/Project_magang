<x-karyawan-layout>
    <style>
        .card { background-color: #fff; padding: 24px; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
        .card-header { font-size: 1.25rem; font-weight: 600; color: #1f2937; border-bottom: 1px solid #e5e7eb; padding-bottom: 16px; margin-bottom: 24px; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 500; color: #374151; }
        .form-group input, .form-group textarea { width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; }
        .form-footer { margin-top: 24px; display: flex; justify-content: flex-end; gap: 12px; }
        .btn { display: inline-flex; align-items: center; padding: 8px 16px; border-radius: 6px; font-weight: 600; text-decoration: none; border: none; cursor: pointer; }
        .btn-red { background-color: #dc2626; color: white; }
        .btn-red:hover { background-color: #b91c1c; }
        .btn-secondary { background-color: #e5e7eb; color: #1f2937; }
        .btn-secondary:hover { background-color: #d1d5db; }
        .file-list-current a { display: inline-block; margin-right: 10px; margin-bottom: 5px; color: #2563eb; font-size: 0.9rem; }
    </style>

    <div class="card">
        <h2 class="card-header">Edit Evidence</h2>
        <form action="{{ route('karyawan.evidence.update', $evidence->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="lokasi">Masukkan Lokasi Anda</label>
                <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi', $evidence->lokasi) }}" required>
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi (Opsional)</label>
                <textarea id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi', $evidence->deskripsi) }}</textarea>
            </div>

            <div class="form-group">
                <label>File Evidence Saat Ini</label>
                <div class="file-list-current">
                    {{-- Kode ini sekarang aman karena mengecek jika file_path adalah array --}}
                    @if(is_array($evidence->file_path))
                        @foreach($evidence->file_path as $index => $fileData)
                            @php
                                // Menangani format lama (string) dan baru (array of object)
                                $path = is_string($fileData) ? $fileData : ($fileData['path'] ?? null);
                            @endphp
                            @if($path)
                                <a href="{{ Storage::url($path) }}" target="_blank">File {{ $index + 1 }}</a>
                            @endif
                        @endforeach
                    @else
                        <p class="text-gray-500">Tidak ada file.</p>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label for="file">Upload File Baru (Opsional)</label>
                <input type="file" id="file" name="file">
                <small style="color: #6b7280;">Catatan: Mengupload file baru akan menghapus semua file lama dan status akan kembali "pending".</small>
            </div>
            <div class="form-footer">
                <a href="{{ route('karyawan.evidence.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-red">Update Evidence</button>
            </div>
        </form>
    </div>
</x-karyawan-layout>