<x-admin-layout>
    {{-- Ganti 'x-admin-layout' jika nama komponen layout Anda berbeda --}}
    
    <style>
        /* CSS KHUSUS UNTUK TAMPILAN TABEL (CSS MURNI) */
        .card-table { 
            background-color: #ffffff; 
            border-radius: 8px; 
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); /* Bayangan modern */
            overflow-x: auto; 
            border: 1px solid #e5e7eb; 
        }
        .table-data { 
            width: 100%; 
            border-collapse: collapse; 
            line-height: 1.5; 
        }
        .table-data thead tr { 
            background-color: #f9fafb; 
            border-bottom: 2px solid #e5e7eb; 
        }
        .table-data th { 
            padding: 12px 20px; 
            text-align: left; 
            font-size: 0.75rem; 
            font-weight: 600; 
            color: #4b5563; 
            text-transform: uppercase; 
            letter-spacing: 0.05em; 
        }
        .table-data td { 
            padding: 16px 20px; 
            border-bottom: 1px solid #e5e7eb; 
            font-size: 0.875rem; 
            color: #374151; 
        }
        .table-data tbody tr:hover { 
            background-color: #f3f4f6; 
        }
        .action-link { 
            text-decoration: none; 
            color: #4f46e5; /* Warna Edit */
            margin-right: 1rem; 
            font-weight: 500; 
            transition: color 0.15s; 
        }
        .action-link:hover { 
            color: #3730a3; 
        }
        .delete-btn { 
            color: #dc2626; /* Warna Hapus */
            font-weight: 500; 
            cursor: pointer; 
            border: none; 
            background: none; 
            padding: 0; 
            transition: color 0.15s; 
        }
        .delete-btn:hover { 
            color: #b91c1c; 
        }
        .btn-add { 
            background-color: #dc2626; 
            color: white; 
            font-weight: 600; 
            padding: 8px 16px; 
            border-radius: 8px; 
            box-shadow: 0 1px 3px 0 rgba(0,0,0,0.1); 
            text-decoration: none; 
            display: flex; 
            align-items: center; 
            transition: background-color 0.2s; 
        }
        .btn-add:hover { 
            background-color: #b91c1c; 
        }
        .alert-box { 
            background-color: #d1fae5; 
            border-left: 4px solid #10b981; 
            color: #065f46; 
            padding: 1rem; 
            margin-bottom: 1.5rem; 
        }
        .alert-error {
            background-color: #fee2e2; border-left: 4px solid #ef4444; color: #b91c1c; padding: 1rem; margin-bottom: 1.5rem;
        }
        /* Badge Status (Meskipun diganti, saya biarkan untuk jaga-jaga) */
        .badge { display: inline-block; padding: 4px 10px; border-radius: 4px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; }
        .badge-done { background-color: #dcfce7; color: #166534; border: 1px solid #16a34a; }
        .badge-progress { background-color: #fef9c3; color: #a16207; border: 1px solid #facc15; }
        
        /* Gaya Button Baru dari view Evidence */
        .btn {
            display: inline-flex;
            align-items: center;
            padding: 8px 14px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            color: white;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-blue { background-color: #2563eb; }
        .btn-blue:hover { background-color: #1d4ed8; }

        /* Gaya Modal Baru dari view Evidence */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.6);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 50;
        }
        .modal-content {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            width: 90%;
            max-width: 1000px; /* Batasi lebar modal */
            height: 90%;
            max-height: 700px; /* Batasi tinggi modal */
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        .modal-header-clean {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .modal-header-clean h3 {
            font-size: 1.4rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }
        .modal-content-body {
            flex-grow: 1;
            overflow-y: auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 10px;
        }
        .image-preview-item {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            padding: 10px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .image-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 220px;
            overflow: hidden;
        }
        .image-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            border-radius: 6px;
        }
        .image-caption {
            margin-top: 10px;
            text-align: center;
            font-size: 0.9rem;
            color: #374151;
            font-weight: 600;
            border-top: 1px dashed #e5e7eb;
            padding-top: 6px;
        }
    </style>
    
    <div class="container" style="max-width: 1000px; margin: 0 auto; padding: 20px;" x-data="{ modalOpen: false, evidenceFiles: [] }">
        <h1 style="font-size: 1.5rem; font-weight: 700; color: #1f2937; margin-bottom: 1.5rem;">Kelola Data Purchase Order</h1>

        @if (session('success'))
            <div class="alert-box" role="alert">
                <p style="font-weight: 700;">Berhasil! 🎉</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif
        @if (session('error'))
            <div class="alert-error" role="alert">
                <p style="font-weight: 700;">Gagal!</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div style="margin-bottom: 1.5rem; display: flex; justify-content: flex-end;">
            <a href="{{ route('admin.po.create') }}" class="btn-add">
                <i class="fa-solid fa-plus" style="margin-right: 0.5rem;"></i> Tambah Purchase Order Baru
            </a>
        </div>

        <div class="card-table">
            <table class="table-data">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Purchase Order (No. PO)</th>
                        <th>Detail Foto Evidence</th> {{-- DIGANTI DARI 'Status' --}}
                        <th>Dibuat Pada</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($po_list as $po)
                        @php
                            // Mengambil semua file evidence dari PO. 
                            // Asumsi: PO memiliki relasi 'evidences', dan setiap evidence memiliki 'file_path'
                            $allFiles = $po->evidences->flatMap(function($evidence) {
                                // Pastikan file_path adalah array yang dapat diulang
                                $files = is_array($evidence->file_path) ? $evidence->file_path : (is_string($evidence->file_path) ? json_decode($evidence->file_path, true) : []);
                                return collect($files)->map(function($file) use ($evidence) {
                                    // Menggabungkan data file dengan data evidence (misal: caption)
                                    return [
                                        'file_path' => $file['file_path'] ?? $file['path'] ?? $file, // Ambil path file
                                        'caption' => ($evidence->user->name ?? 'N/A') . ' - ' . ($evidence->tematik->nama_tematik ?? 'N/A') . ' (' . ($evidence->lokasi ?? 'N/A') . ')', // Caption yang lebih detail
                                    ];
                                });
                            })->all();
                            $filesJson = json_encode($allFiles);
                            $fileCount = count($allFiles);
                        @endphp
                        <tr>
                            <td>
                                {{ $po_list->firstItem() + $loop->index }} 
                            </td>
                            <td style="font-weight: 500; color: #1f2937;">
                                {{ $po->no_po }}
                            </td>
                            <td>
                                {{-- Tombol Detail Foto (Referensi dari View Evidence) --}}
                                <button
                                    @click="modalOpen = true; evidenceFiles = {{ $filesJson }}"
                                    class="btn btn-blue"
                                    @if($fileCount === 0) disabled style="opacity: 0.5; cursor: not-allowed;" @endif
                                >
                                    <i class="fa-solid fa-images" style="margin-right: 6px;"></i>
                                    Lihat ({{ $fileCount }})
                                </button>
                                @if($fileCount === 0)
                                    <div style="font-size: 0.75rem; color: #dc2626; margin-top: 4px;">Belum ada evidence</div>
                                @endif
                            </td> {{-- PENGGANTI KOLOM STATUS --}}
                            <td style="color: #6b7280;">
                                {{ $po->created_at ? $po->created_at->format('d M Y H:i') : '-' }}
                            </td>
                            <td style="text-align: center;">
                                <a href="{{ route('admin.po.edit', $po->id) }}" class="action-link">
                                    <i class="fa-solid fa-edit" style="margin-right: 4px;"></i> Edit
                                </a>

                                <form action="{{ route('admin.po.destroy', $po->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('APAKAH ANDA YAKIN INGIN MENGHAPUS DATA PO INI?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-btn">
                                        <i class="fa-solid fa-trash-alt" style="margin-right: 4px;"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: #6b7280;">
                                Data Purchase Order masih kosong. Silakan tambahkan Purchase Order baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if ($po_list->hasPages())
            <div style="margin-top: 1.5rem; text-align: center;">
                {{ $po_list->links() }}
            </div>
        @endif

        {{-- ===================== 🔹 MODAL DETAIL FOTO (Referensi dari View Evidence) ===================== --}}
        <div x-show="modalOpen" class="modal-overlay" style="display:none;">
            <div class="modal-content" @click.away="modalOpen = false">
                <div class="modal-header-clean">
                    <h3>Detail File Evidence dari PO {{ $po->no_po ?? 'Ini' }}</h3>
                    <span style="color:#6b7280;">Total <span x-text="evidenceFiles.length">0</span> Foto</span>
                </div>

                <div class="modal-content-body">
                    <template x-if="evidenceFiles && evidenceFiles.length > 0">
                        <template x-for="(fileData, index) in evidenceFiles" :key="index">
                            <div class="image-preview-item">
                                <div class="image-container">
                                    {{-- Sesuaikan path jika perlu, tergantung struktur file storage Anda --}}
                                    <img :src="'{{ asset('storage') }}/' + (fileData.file_path || fileData.path || fileData)" :alt="'Evidence ' + (index + 1)" loading="lazy">
                                </div>
                                <p class="image-caption" x-text="fileData.caption || (typeof fileData === 'string' ? 'Foto Ke-' + (index + 1) : (fileData.file_path || fileData.path))"></p>
                            </div>
                        </template>
                    </template>
                    <template x-if="!evidenceFiles || evidenceFiles.length === 0">
                        <p style="text-align:center; font-size:0.9rem; color:#6b7280;">Tidak ada file untuk ditampilkan.</p>
                    </template>
                </div>

                <div style="text-align:right; margin-top:15px;">
                    <button @click="modalOpen = false" class="btn btn-red">Tutup</button>
                </div>
            </div>
        </div>
        {{-- AKHIR MODAL --}}

    </div>
</x-admin-layout>