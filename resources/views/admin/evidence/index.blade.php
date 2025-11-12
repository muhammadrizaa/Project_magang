<x-admin-layout>
    <style>
        /* [Gaya CSS Umum dan Tabel - TIDAK BERUBAH] */
        .card { background-color: #fff; padding: 24px; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
        .card-header { display: flex; justify-content: space-between; align-items: center; padding-bottom: 16px; border-bottom: 1px solid #e5e7eb; }
        .card-title h2 { font-size: 1.25rem; font-weight: 600; color: #1f2937; }
        .card-title span { font-size: 0.875rem; color: #6b7280; }
        .alert-success { background-color: #d1fae5; border-left: 4px solid #34d399; color: #065f46; padding: 16px; margin-top: 16px; border-radius: 6px; }
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
        .btn-green { background-color: #16a34a; }
        .btn-green:hover { background-color: #15803d; }
        .btn-red { background-color: #dc2626; }
        .btn-red:hover { background-color: #b91c1c; }
        .btn-gray { background-color: #6b7280; }
        .btn-gray:hover { background-color: #4b5563; }
        .btn-blue { background-color: #3b82f6; } 
        .btn-blue:hover { background-color: #2563eb; } 
        .btn-secondary { background-color: #e5e7eb; color: #1f2937; }
        .btn-secondary:hover { background-color: #d1d5db; }
        
        /* [Gaya CSS Pagination - TIDAK BERUBAH] */
        .pagination { margin-top: 24px; display: flex; justify-content: flex-end; align-items: center; gap: 8px; }
        .pagination > div:not(nav):first-child { display: none !important; }
        .pagination nav { display: flex; gap: 8px; align-items: center; }
        .pagination nav a, .pagination nav span { padding: 8px 12px; border-radius: 6px; font-size: 0.85rem; font-weight: 500; text-decoration: none; transition: all 0.2s; border: 1px solid #d1d5db; white-space: nowrap; }
        .pagination nav a { color: #1f2937; background-color: #f9fafb; }
        .pagination nav a:hover { background-color: #e5e7eb; border-color: #9ca3af; }
        .pagination nav > div > a, .pagination nav > div > span { background-color: #dc2626 !important; color: white !important; border-color: #dc2626 !important; }
        .pagination nav > div > a:hover { background-color: #b91c1c !important; }
        .pagination nav > div > span { background-color: #fca5a5 !important; border-color: #fca5a5 !important; cursor: not-allowed; }
        .pagination nav span[aria-current="page"] { background-color: #3b82f6 !important; color: white !important; border-color: #3b82f6 !important; cursor: default; }
        .pagination nav svg { display: none !important; }

        /* 🔥 MODAL */
        .modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0, 0, 0, 0.6); display: flex; align-items: center; justify-content: center; z-index: 50; }
        .modal-content { 
            background-color: #fff; padding: 24px; border-radius: 8px; max-width: 90%; width: 95%; height: 90%; 
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
        .image-container {
            flex-grow: 1; display: flex; align-items: center; justify-content: center; padding-bottom: 10px;
        }
        .image-preview-item img { 
            max-width: 100%; height: auto; max-height: 65vh; display: block; margin: 0 auto; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); object-fit: contain;
        }
        .image-caption { 
            text-align: center; margin-top: 5px; font-style: normal; color: #374151; font-size: 0.875rem; font-weight: 600; padding-top: 8px;
            border-top: 1px dashed #e5e7eb; flex-shrink: 0;
        }
    </style>

    <div class="card" x-data="{ 
        modalOpen: false, 
        rejectModalOpen: false, 
        evidenceFiles: [], 
        rejectAction: '' 
    }">
        <div class="card-header">
            <div class="card-title">
                <h2>Kelola Evidence</h2>
                <span>Total {{ $evidences->total() }} data ditemukan</span>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left" style="margin-right: 6px;"></i> Kembali
            </a>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-wrapper">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th style="width: 15%;">Karyawan</th>
                        <th style="width: 10%;">No. PO</th>
                        <th style="width: 10%;">Tematik</th>
                        <th style="width: 10%;">Waspang</th>
                        <th style="width: 15%;">Lokasi/Tanggal</th>
                        <th style="width: 10%;">Detail Foto</th>
                        <th style="width: 10%; text-align: center;">Status</th>
                        <th style="width: 20%; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($evidences as $evidence)
                    @php
                        $files = $evidence->file_path ?? []; 
                        // Menggunakan json_encode untuk Alpine.js
                        $filesJson = json_encode($files);
                    @endphp
                    <tr>
                        <td>{{ $evidence->user->name ?? 'N/A' }}</td>
                        <td>
                            {{-- No. PO --}}
                            <div style="font-weight: 600;">{{ $evidence->po->no_po ?? 'N/A' }}</div>
                        </td>
                        <td>
                            {{-- Tematik --}}
                            <div>{{ $evidence->tematik->nama_tematik ?? 'N/A' }}</div>
                        </td>
                        <td>
                            {{-- Pangwas --}}
                            <div>{{ $evidence->pangwas->nama_pangwas ?? 'N/A' }}</div>
                        </td>
                        <td>
                            <div style="font-weight: 600;">{{ $evidence->lokasi }}</div>
                            <div style="font-size: 0.8rem; color: #6b7280;">{{ $evidence->created_at->format('d M Y H:i') }}</div>
                        </td>
                        <td>
                            <button 
                                @click="modalOpen = true; evidenceFiles = {{ $filesJson }}" 
                                class="btn btn-blue">
                                <i class="fa-solid fa-folder-open" style="margin-right: 6px;"></i> 
                                Lihat ({{ count($files) }})
                            </button>
                        </td>
                        <td style="text-align: center;">
                            <span class="badge badge-{{ $evidence->status }}">
                                {{ $evidence->status }}
                            </span>
                            @if($evidence->status == 'rejected' && $evidence->catatan_admin)
                                <p title="{{ $evidence->catatan_admin }}" style="font-size: 0.75rem; color: #991b1b; margin-top: 4px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; cursor: help;">
                                    Catatan: {{ Str::limit($evidence->catatan_admin, 15) }}
                                </p>
                            @endif
                        </td>
                        <td style="text-align: center; white-space: nowrap;">
                            @if($evidence->status == 'pending')
                                {{-- Tombol Approve --}}
                                <form action="{{ route('admin.evidence.approve', $evidence) }}" method="POST" style="display: inline;">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-green">Approve</button>
                                </form>
                                {{-- Tombol Reject (Memicu Modal) --}}
                                <button @click="rejectModalOpen = true; rejectAction = '{{ route('admin.evidence.reject', $evidence) }}'" class="btn btn-red" style="margin-left: 8px;">
                                    Reject
                                </button>
                            @else
                                <span class="btn btn-gray">Selesai</span>
                            @endif

                            {{-- Tombol Hapus Permanen --}}
                            <form action="{{ route('admin.evidence.destroy', $evidence) }}" method="POST" style="display: inline; margin-left: 8px;" onsubmit="return confirm('Yakin ingin MENGHAPUS PERMANEN evidence ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-red">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" style="text-align: center; padding: 16px;">Tidak ada data evidence yang perlu dikelola.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="pagination">{{ $evidences->links() }}</div>

        {{-- MODAL UNTUK PREVIEW FOTO --}}
        <div x-show="modalOpen" class="modal-overlay" style="display: none;">
            <div class="modal-content" @click.away="modalOpen = false">
                <div class="modal-header-clean">
                    <h3>Detail File Evidence</h3>
                    <div style="font-size: 1rem; color: #6b7280;">
                        Total <span x-text="evidenceFiles.length">0</span> Foto
                    </div>
                </div>

                <div class="modal-content-body">
                    <template x-if="evidenceFiles && evidenceFiles.length > 0">
                        <template x-for="(fileData, index) in evidenceFiles" :key="index">
                            <div class="image-preview-item">
                                <div class="image-container">
                                    <img :src="'{{ asset('storage') }}/' + (fileData.file_path || fileData.path || fileData)" :alt="'Evidence Photo ' + (index + 1)" loading="lazy">
                                </div>
                                <p class="image-caption" x-text="fileData.caption || (typeof fileData === 'string' ? 'ODP-' + (index + 1) : (fileData.file_path || fileData.path))"></p>
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

        {{-- Modal untuk Reject --}}
        <div x-show="rejectModalOpen" class="modal-overlay" style="display: none;">
            <div class="modal-content" @click.away="rejectModalOpen = false" style="width: auto; height: auto; max-width: 450px;">
                <h3 class="card-header" style="margin-bottom: 16px; padding-bottom: 12px;">Alasan Penolakan</h3>
                <form :action="rejectAction" method="POST">
                    @csrf
                    @method('PATCH')
                    <textarea name="catatan_admin" class="w-full border rounded p-2" style="border-color: #d1d5db; width: 100%; box-sizing: border-box;" rows="3" placeholder="Tulis alasan penolakan di sini..." required></textarea>
                    <div class="mt-4 flex justify-end gap-4" style="margin-top: 1rem; display: flex; justify-content: flex-end; gap: 1rem;">
                        <button type="button" @click="rejectModalOpen = false" class="btn btn-secondary">Batal</button>
                        <button type="submit" class="btn btn-red">Kirim Penolakan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>