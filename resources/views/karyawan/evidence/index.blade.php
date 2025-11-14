<x-karyawan-layout>
    <style>
        /* === STYLE ASLI DARI KODE LU === */
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
        .btn-blue { background-color: #2563eb; } .btn-blue:hover { background-color: #1d4ed8; }
        .btn-red { background-color: #dc2626; } .btn-red:hover { background-color: #b91c1c; }
        .btn-lihat { background-color: #3b82f6; } .btn-lihat:hover { background-color: #2563eb; } 
        .btn-gray { background-color: #6b7280; } .btn-gray:hover { background-color: #4b5563; }

        /* === PAGINATION === */
        .pagination { margin-top: 16px; display: flex; justify-content: flex-end; align-items: center; }
        .pagination > div:not(nav):first-child { display: none !important; }
        .pagination nav { display: flex; gap: 4px; align-items: center; }
        .pagination nav a, .pagination nav span {
            padding: 8px 12px; border-radius: 6px; font-size: 0.85rem; font-weight: 500;
            text-decoration: none; transition: all 0.2s; border: 1px solid #d1d5db;
            white-space: nowrap; color: #1f2937; background-color: #f9fafb;
        }
        .pagination nav a:hover { background-color: #e5e7eb; border-color: #9ca3af; }
        .pagination nav span[aria-current="page"] {
            background-color: #3b82f6 !important; color: white !important; border-color: #3b82f6 !important; cursor: default;
        }
        .pagination nav svg { display: none !important; }
        .pagination nav a[rel="prev"]:before { content: "« Sebelumnya"; display: inline-block; }
        .pagination nav a[rel="next"]:after { content: "Selanjutnya »"; display: inline-block; }

        /* === MODAL === */
        .modal-overlay { position: fixed; inset: 0; background-color: rgba(0,0,0,0.6);
            display: flex; align-items: center; justify-content: center; z-index: 50; overflow-y: auto; padding: 20px; }
        .modal-content { background-color: #fff; padding: 24px; border-radius: 8px;
            max-width: 95%; width: 95%; max-height: 95%; display: flex; flex-direction: column; overflow-y: auto; }
        .modal-header-clean { display: flex; justify-content: space-between; align-items: center;
            padding-bottom: 15px; margin-bottom: 15px; border-bottom: 2px solid #3b82f6; }
        .modal-header-clean h3 { font-size: 1.5rem; font-weight: 700; color: #1f2937; }

        /* === GRID FOTO (6 PER BARIS) === */
        .modal-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 16px;
            overflow-y: auto;
            padding: 10px 5px;
        }
        .image-preview-item {
            background-color: #fff;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            padding: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
        }
        .image-preview-item img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-radius: 6px;
            margin-bottom: 8px;
        }
        .image-caption {
            font-size: 0.8rem;
            color: #374151;
            text-align: center;
            font-weight: 600;
        }
    </style>

    <div class="card" x-data="{ modalOpen: false, evidenceFiles: [], evidenceLocation: '' }">
        <h2 class="card-header">Riwayat Evidence Anda</h2>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-wrapper">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Waktu/Lokasi</th>
                        <th>Nomor PO</th>
                        <th>Tematik</th>
                        <th>Waspang</th>
                        <th>Foto</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($evidences as $evidence)
                        @php
                            $files = $evidence->file_path ?? [];
                            $filesJson = json_encode($files);
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $evidence->lokasi }}</strong><br>
                                <small>{{ $evidence->created_at->format('d M Y H:i') }}</small>
                            </td>
                            <td>{{ $evidence->po->no_po ?? 'N/A' }}</td>
                            <td style="color: #3b82f6;">{{ $evidence->tematik->nama_tematik ?? 'N/A' }}</td>
                            <td>{{ $evidence->pangwas->nama_pangwas ?? 'N/A' }}</td>
                            <td>
                                <button @click="modalOpen = true; evidenceFiles = {{ $filesJson }}; evidenceLocation = '{{ $evidence->lokasi }}';" 
                                    class="btn btn-lihat">
                                    <i class="fa-solid fa-folder-open mr-1"></i> Lihat ({{ count($files) }})
                                </button>
                            </td>
                            <td style="text-align:center;">
                                <span class="badge badge-{{ $evidence->status }}">{{ $evidence->status }}</span>
                                @if($evidence->status == 'rejected')
                                    <p title="{{ $evidence->catatan_admin }}" style="font-size: 0.75rem; color: #b91c1c;">
                                        Catatan: {{ Str::limit($evidence->catatan_admin, 20) }}
                                    </p>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($evidence->status != 'approved')
                                    <a href="{{ route('karyawan.evidence.edit', $evidence->id) }}" class="btn btn-blue">Edit</a>
                                    <form action="{{ route('karyawan.evidence.destroy', $evidence->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus evidence ini?');">
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
                        <tr><td colspan="7" style="text-align:center;">Anda belum memiliki riwayat evidence.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">{{ $evidences->links() }}</div>

        <!-- MODAL -->
        <div x-show="modalOpen" class="modal-overlay">
            <div class="modal-content" @click.away="modalOpen = false">
                <div class="modal-header-clean">
                    <h3 x-text="'Detail Evidence: ' + evidenceLocation"></h3>
                    <span style="font-size:0.9rem; color:#6b7280;">Total <span x-text="evidenceFiles.length"></span> Foto</span>
                </div>

                <!-- 🔥 GALERI FOTO DALAM GRID -->
                <div class="modal-gallery">
                    <template x-if="evidenceFiles && evidenceFiles.length > 0">
                        <template x-for="(file, index) in evidenceFiles" :key="index">
                            <div class="image-preview-item">
                                <img :src="'{{ asset('storage') }}/' + (file.path)" alt="">
                                <p class="image-caption" x-text="'Foto ' + (index + 1) + ': ' + (file.caption || 'Tanpa Keterangan')"></p>
                            </div>
                        </template>
                    </template>
                    <template x-if="!evidenceFiles || evidenceFiles.length === 0">
                        <p>Tidak ada file untuk ditampilkan.</p>
                    </template>
                </div>

                <div style="text-align: right; margin-top: 20px;">
                    <button @click="modalOpen = false" class="btn btn-red">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</x-karyawan-layout>
