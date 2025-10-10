<x-admin-layout>
    <style>
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
        .btn-secondary { background-color: #e5e7eb; color: #1f2937; }
        .btn-secondary:hover { background-color: #d1d5db; }
        .pagination { margin-top: 16px; }
        .modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0, 0, 0, 0.5); display: flex; align-items: center; justify-content: center; z-index: 50; }
        .modal-content { background-color: #fff; padding: 24px; border-radius: 8px; max-width: 500px; width: 100%; }
        .modal-file-list a { display: block; padding: 8px; border-radius: 6px; text-decoration: none; color: #2563eb; }
        .modal-file-list a:hover { background-color: #f3f4f6; }
    </style>

    <div class="card" x-data="{ modalOpen: false, rejectModalOpen: false, evidenceFiles: [], rejectAction: '' }">
        <div class="card-header">
            <div class="card-title">
                <h2>Kelola Evidence</h2>
                <span>Total {{ $evidences->total() }} data ditemukan</span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-wrapper">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Karyawan</th>
                        <th>Lokasi</th>
                        <th>Tanggal Upload</th>
                        <th style="text-align: center;">Status</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($evidences as $evidence)
                    <tr>
                        <td>{{ $evidence->user->name }}</td>
                        <td>{{ $evidence->lokasi }}</td>
                        <td>{{ $evidence->created_at->format('d M Y') }}</td>
                        <td style="text-align: center;">
                            <span class="badge badge-{{ $evidence->status }}">
                                {{ $evidence->status }}
                            </span>
                        </td>
                        <td style="text-align: center; white-space: nowrap;">
                            
                            <button @click="modalOpen = true; evidenceFiles = @json($evidence->file_path ?? [])" class="btn btn-gray">
                                Lihat ({{ is_array($evidence->file_path) ? count($evidence->file_path) : 0 }})
                            </button>
                            
                            @if($evidence->status == 'pending')
                                <form action="{{ route('admin.evidence.approve', $evidence) }}" method="POST" style="display: inline; margin-left: 8px;">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-green">Approve</button>
                                </form>
                                <button @click="rejectModalOpen = true; rejectAction = '{{ route('admin.evidence.reject', $evidence) }}'" class="btn btn-red" style="margin-left: 8px;">
                                    Reject
                                </button>
                            @endif

                            <form action="{{ route('admin.evidence.destroy', $evidence) }}" method="POST" style="display: inline; margin-left: 8px;" onsubmit="return confirm('Yakin ingin MENGHAPUS PERMANEN evidence ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-gray">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align: center; padding: 16px;">Tidak ada data evidence.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $evidences->links() }}</div>

        {{-- Modal untuk Lihat File --}}
        <div x-show="modalOpen" class="modal-overlay" style="display: none;">
            <div class="modal-content" @click.away="modalOpen = false">
                <h3 class="card-header" style="margin-bottom: 16px; padding-bottom: 12px;">Detail File Evidence</h3>
                <div class="modal-file-list">
                    <template x-if="evidenceFiles && evidenceFiles.length > 0">
                        <template x-for="(fileData, index) in evidenceFiles" :key="index">
                            <a :href="'/storage/' + (typeof fileData === 'string' ? fileData : fileData.path)" target="_blank">
                                <i class="fa-solid fa-file-arrow-down" style="margin-right: 8px;"></i>
                                <span x-text="typeof fileData === 'string' ? 'File ' + (index + 1) : (fileData.caption || 'File ' + (index + 1))"></span>
                            </a>
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

        {{-- Modal untuk Reject --}}
        <div x-show="rejectModalOpen" class="modal-overlay" style="display: none;">
            <div class="modal-content" @click.away="rejectModalOpen = false">
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