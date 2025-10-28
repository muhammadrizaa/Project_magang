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
        .pagination { margin-top: 16px; }

        /* 💡 PERUBAHAN GAYA MODAL (Grid Responsif) */
        .modal-overlay { 
            position: fixed; 
            top: 0; left: 0; right: 0; bottom: 0; 
            background-color: rgba(0, 0, 0, 0.7); 
            display: flex; 
            align-items: flex-start; 
            justify-content: center; 
            z-index: 50; 
            overflow-y: auto; 
            padding: 20px 0; 
        }
        .modal-content { 
            background-color: #fff; 
            padding: 24px; 
            border-radius: 8px; 
            max-width: 1200px; 
            width: 95%; 
            display: flex; 
            flex-direction: column; 
            margin-top: 50px; 
            margin-bottom: 50px;
        }
        .modal-content-body { 
            /* Logika Grid yang Responsif: Kolom dibuat sebanyak mungkin, min lebar 300px, mengisi ruang yang ada. */
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
            gap: 20px; 
            padding: 0; 
            flex-grow: 1; 
            margin-top: 20px;
        }
        @media (min-width: 1024px) {
            .modal-content-body {
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); 
            }
        }
        .image-preview-item { 
            margin-bottom: 0; 
            border: 1px solid #e5e7eb; 
            border-radius: 8px; 
            padding: 15px; 
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .image-preview-item img { 
            max-width: 100%; 
            height: auto; 
            max-height: 400px; 
            object-fit: contain; 
            display: block; 
            margin-bottom: 10px; 
            border-radius: 6px; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.05); 
        }
        .image-caption { 
            text-align: center; 
            margin-top: 10px; 
            font-style: italic; 
            color: #4b5563; 
            font-size: 0.9rem;
            word-break: break-word; 
        }
    </style>

    <div class="card" x-data="{ modalOpen: false, evidenceFiles: [] }">
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
                        <th>Lokasi</th>
                        <th>File Evidence</th>
                        <th style="text-align: center;">Status</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($evidences as $evidence)
                    @php
                        // Memastikan data file bisa diakses dan didecode dengan benar
                        $files = is_string($evidence->file_path) 
                            ? (json_decode($evidence->file_path, true) ?? [])
                            : ($evidence->file_path ?? ($evidence->files ?? [])); 
                        
                        // Menggunakan json_encode untuk Alpine.js
                        $filesJson = json_encode($files);
                    @endphp
                    <tr>
                        <td>
                            <div style="font-weight: 600; color: #1f2937;">{{ $evidence->lokasi }}</div>
                            <div style="font-size: 0.8rem; color: #6b7280;">{{ $evidence->created_at->format('d M Y') }}</div>
                        </td>
                        <td>
                            {{-- Button yang memicu Modal --}}
                            <button 
                                @click="modalOpen = true; evidenceFiles = {{ $filesJson }}" 
                                class="btn btn-lihat">
                                <i class="fa-solid fa-folder-open" style="margin-right: 6px;"></i> 
                                Lihat ({{ count($files) }}) Foto
                            </button>
                        </td>
                        <td style="text-align: center;">
                            <span class="badge badge-{{ $evidence->status }}">
                                {{ $evidence->status }}
                            </span>
                            @if($evidence->status == 'rejected')
                                {{-- Catatan admin hanya ditampilkan jika rejected --}}
                                <p style="font-size: 0.75rem; color: #b91c1c; margin-top: 4px; max-width: 200px; margin-left:auto; margin-right:auto;">
                                    Catatan: {{ $evidence->catatan_admin }}
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
                        <td colspan="4" style="text-align: center; padding: 16px;">Anda belum memiliki riwayat evidence.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            {{ $evidences->links() }}
        </div>

        {{-- 💡 MODAL UNTUK PREVIEW FOTO (Tampilan Grid Sudah Optimal) --}}
        <div x-show="modalOpen" class="modal-overlay" style="display: none;">
            <div class="modal-content" @click.away="modalOpen = false">
                <h3 class="card-header" style="margin-bottom: 0; padding-bottom: 12px;">Detail File Evidence (<span x-text="evidenceFiles.length"></span> Foto)</h3>
                
                <div class="modal-content-body">
                    <template x-if="evidenceFiles && evidenceFiles.length > 0">
                        <template x-for="(fileData, index) in evidenceFiles" :key="index">
                            <div class="image-preview-item">
                                {{-- Menghitung URL file --}}
                                <img :src="'{{ asset('storage') }}/' + (fileData.file_path || fileData.path || fileData)" alt="Evidence Photo" loading="lazy">
                                
                                {{-- Menampilkan Caption/Nama File --}}
                                <p class="image-caption" x-text="fileData.caption || (typeof fileData === 'string' ? 'Foto ' + (index + 1) : (fileData.caption || fileData.file_path || fileData.path))"></p>
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