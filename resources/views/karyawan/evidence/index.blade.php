<x-karyawan-layout>
    <style>
        /* [Gaya Umum Tabel dan Card] */
        .card { background-color: #fff; padding: 24px; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
        .card-header { font-size: 1.25rem; font-weight: 600; color: #1f2937; border-bottom: 1px solid #e5e7eb; padding-bottom: 16px; margin-bottom: 24px; }
        .alert-success { background-color: #d1fae5; border-left: 4px solid #34d399; color: #065f46; padding: 16px; margin-top: 16px; }
        .table-wrapper { overflow-x: auto; margin-top: 24px; }
        .styled-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
        .styled-table thead tr { background-color: #f9fafb; text-align: left; color: #374151; text-transform: uppercase; font-size: 0.75rem; }
        .styled-table th, .styled-table td { padding: 12px 16px; border-bottom: 1px solid #e5e7eb; vertical-align: middle; }
        .styled-table tbody tr:hover { background-color: #f3f4f6; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; text-transform: capitalize; }
        .badge-pending { background-color: #fef9c3; color: #a16207; }
        .badge-approved { background-color: #dcfce7; color: #166534; }
        .badge-rejected { background-color: #fee2e2; color: #991b1b; }
        .btn { display: inline-flex; align-items: center; padding: 6px 12px; border-radius: 6px; font-weight: 500; font-size: 0.8rem; text-decoration: none; color: white; border: none; cursor: pointer; transition: background-color 0.2s; }
        .btn-blue { background-color: #2563eb; }
        .btn-blue:hover { background-color: #1d4ed8; }
        .btn-red { background-color: #dc2626; }
        .btn-red:hover { background-color: #b91c1c; }
        .btn-lihat { background-color: #3b82f6; } 
        .btn-lihat:hover { background-color: #2563eb; } 
        .btn-gray { background-color: #6b7280; }
        .btn-gray:hover { background-color: #4b5563; }
        
        /* 🔥 PENGATURAN PAGINATION TERAKHIR */
        .pagination { 
            margin-top: 16px; 
            display: flex; 
            justify-content: flex-end; 
            align-items: center; 
        }
        .pagination > div:not(nav):first-child {
            display: none !important;
        }
        .pagination nav {
            display: flex;
            gap: 4px;
            align-items: center;
        }
        .pagination nav a, 
        .pagination nav span {
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            border: 1px solid #d1d5db;
            white-space: nowrap;
            color: #1f2937;
            background-color: #f9fafb; 
        }
        .pagination nav a:hover {
            background-color: #e5e7eb;
            border-color: #9ca3af;
        }
        .pagination nav span[aria-current="page"] {
            background-color: #3b82f6 !important; 
            color: white !important; 
            border-color: #3b82f6 !important;
            cursor: default;
        }
        .pagination nav svg {
            display: none !important;
        }
        .pagination nav a[rel="prev"]:before {
            content: "« Sebelumnya";
            display: inline-block;
        }
        .pagination nav a[rel="next"]:after {
            content: "Selanjutnya »";
            display: inline-block;
        }
        
        /* 🔥 MODAL */
        .modal-overlay { 
            position: fixed; top: 0; left: 0; right: 0; bottom: 0; 
            background-color: rgba(0, 0, 0, 0.6); 
            display: flex; align-items: center; justify-content: center; 
            z-index: 50; overflow-y: hidden; padding: 20px 0; 
        }
        .modal-content { 
            background-color: #fff; padding: 24px; border-radius: 8px; max-width: 95%; max-height: 95%; width: 95%; height: 95%; 
            display: flex; flex-direction: column;
        }
        .modal-header-clean {
            display: flex; justify-content: space-between; align-items: center; padding-bottom: 15px; margin-bottom: 15px; border-bottom: 2px solid #3b82f6; 
        }
        .modal-header-clean h3 { font-size: 1.5rem; font-weight: 700; color: #1f2937; }
        .modal-content-body { 
            overflow: hidden; flex-grow: 1; padding: 0 10px; display: flex; align-items: stretch; justify-content: space-around; gap: 15px; 
        }
        .image-preview-item { 
            flex: 1 1 0; min-width: 100px; max-width: 350px; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 6px; padding: 10px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); height: 100%; box-sizing: border-box;
            display: flex; flex-direction: column; justify-content: space-between;
        }
        .image-container { flex-grow: 1; display: flex; align-items: center; justify-content: center; padding-bottom: 10px; }
        .image-preview-item img { 
            max-width: 100%; height: auto; max-height: 65vh; display: block; margin: 0 auto; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); object-fit: contain;
        }
        .image-caption { 
            text-align: center; margin-top: 5px; font-style: normal; color: #374151; font-size: 0.875rem; font-weight: 600; padding-top: 8px;
            border-top: 1px dashed #e5e7eb; flex-shrink: 0; width: 100%;
        }
    </style>

    <div class="card" x-data="{ modalOpen: false, evidenceFiles: [], evidenceId: null, evidenceLocation: '' }">
        <h2 class="card-header">Riwayat Evidence Anda</h2>
        
        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-wrapper">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th style="width: 15%;">Waktu/Lokasi</th>
                        <th style="width: 15%;">Nomor PO</th>
                        <th style="width: 15%;">Tematik</th>
                        <th style="width: 15%;">Pengawas</th>
                        <th style="width: 10%;">Foto</th>
                        <th style="width: 15%; text-align: center;">Status</th>
                        <th style="width: 15%; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($evidences as $evidence)
                    @php
                        // Memastikan data file bisa diakses dan didecode dengan benar (Dari Model Casting)
                        $files = $evidence->file_path ?? []; 
                        $filesJson = json_encode($files);
                    @endphp
                    <tr>
                        <td>
                            <div style="font-weight: 600; color: #1f2937;">{{ $evidence->lokasi }}</div>
                            <div style="font-size: 0.8rem; color: #6b7280;">{{ $evidence->created_at->format('d M Y H:i') }}</div>
                        </td>
                        <td>
                            {{-- PO (Purchase Order) --}}
                            <div style="font-weight: 600;">{{ $evidence->po->no_po ?? 'N/A' }}</div>
                        </td>
                        <td>
                            {{-- Tematik --}}
                            <div style="font-weight: 500; color: #3b82f6;">{{ $evidence->tematik->nama_tematik ?? 'N/A' }}</div>
                        </td>
                        <td>
                            {{-- Pangwas --}}
                            <div style="font-weight: 500;">{{ $evidence->pangwas->nama_pangwas ?? 'N/A' }}</div>
                        </td>
                        <td>
                            {{-- Button yang memicu Modal --}}
                            <button 
                                @click="modalOpen = true; evidenceFiles = {{ $filesJson }}; evidenceId = {{ $evidence->id }}; evidenceLocation = '{{ $evidence->lokasi }}';" 
                                class="btn btn-lihat">
                                <i class="fa-solid fa-folder-open" style="margin-right: 6px;"></i> 
                                Lihat ({{ count($files) }})
                            </button>
                        </td>
                        <td style="text-align: center;">
                            <span class="badge badge-{{ $evidence->status }}">
                                {{ $evidence->status }}
                            </span>
                            @if($evidence->status == 'rejected')
                                {{-- Catatan admin hanya ditampilkan jika rejected --}}
                                <p title="{{ $evidence->catatan_admin }}" style="font-size: 0.75rem; color: #b91c1c; margin-top: 4px; max-width: 120px; margin-left:auto; margin-right:auto; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; cursor: help;">
                                    Catatan: **{{ Str::limit($evidence->catatan_admin, 15) }}**
                                </p>
                            @endif
                        </td>
                        <td style="text-align: center; white-space: nowrap;">
                            @if($evidence->status != 'approved')
                                <a href="{{ route('karyawan.evidence.edit', $evidence->id) }}" class="btn btn-blue">Edit</a>
                                <form action="{{ route('karyawan.evidence.destroy', $evidence->id) }}" method="POST" style="display: inline; margin-left: 8px;" onsubmit="return confirm('Yakin ingin menghapus evidence ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-red">Hapus</button>
                                </form>
                            @else
                                <span class="btn btn-gray">Terkunci</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 16px;">Anda belum memiliki riwayat evidence.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- 🔥 Ini adalah elemen Pagination yang benar-benar ditampilkan di bawah tabel --}}
        <div class="pagination">
            {{ $evidences->links() }}
        </div>

        {{-- MODAL UNTUK PREVIEW FOTO --}}
        <div x-show="modalOpen" class="modal-overlay" style="display: none;">
            <div class="modal-content" @click.away="modalOpen = false">
                
                {{-- 🔥 HEADER YANG DIRAPIKAN --}}
                <div class="modal-header-clean">
                    <h3 x-text="'Detail Evidence: ' + evidenceLocation">Detail Evidence</h3>
                    <div style="font-size: 1rem; color: #6b7280;">
                        Total <span x-text="evidenceFiles.length">0</span> Foto
                    </div>
                </div>
                
                <div class="modal-content-body">
                    <template x-if="evidenceFiles && evidenceFiles.length > 0">
                        <template x-for="(fileData, index) in evidenceFiles" :key="index">
                            <div class="image-preview-item">
                                <div class="image-container">
                                    {{-- Menghitung URL file --}}
                                    {{-- Menggunakan asset('storage') dan memastikan path diambil dari object fileData --}}
                                    <img :src="'{{ asset('storage') }}/' + (fileData.path)" alt="Evidence Photo" loading="lazy">
                                </div>
                                
                                {{-- Menampilkan Caption/Nama File --}}
                                <p class="image-caption" x-text="'Foto ' + (index + 1) + ': ' + (fileData.caption || 'Tanpa Keterangan')"></p>
                            </div>
                        </template>
                    </template>
                    <template x-if="!evidenceFiles || evidenceFiles.length === 0">
                        <p>Tidak ada file untuk ditampilkan.</p>
                    </template>
                </div>
                
                <div style="text-align: right; margin-top: 20px; padding-top: 10px; border-top: 1px solid #e5e7eb;">
                    <button @click="modalOpen = false" class="btn btn-red">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</x-karyawan-layout>