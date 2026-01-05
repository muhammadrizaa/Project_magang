<x-karyawan-layout>
    <style>
        /* === STYLE ASLI DARI KODE LU === */
        .card { background-color: #fff; padding: 24px; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
        .card-header { font-size: 1.25rem; font-weight: 600; color: #1f2937; border-bottom: 1px solid #e5e7eb; padding-bottom: 16px; margin-bottom: 24px; }
        .alert-success { background-color: #d1fae5; border-left: 4px solid #34d399; color: #065f46; padding: 16px; margin-top: 16px; margin-bottom: 20px; border-radius: 8px; }
        .alert-success-custom {
            background-color: #ecfdf5;
            border-left: 4px solid #10b981;
            border-radius: 8px;
            padding: 14px 16px;
            margin-bottom: 20px;
            margin-top: 16px;
            color: #065f46;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.1);
            animation: slideInDown 0.4s ease-out;
            font-weight: 500;
            font-size: 0.95rem;
        }
        .table-wrapper { overflow-x: auto; margin-top: 24px; }
        .styled-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
        .styled-table thead tr { background-color: #f9fafb; text-align: left; color: #374151; text-transform: uppercase; font-size: 0.75rem; }
        .styled-table th, .styled-table td { padding: 12px 16px; border-bottom: 1px solid #e5e7eb; vertical-align: middle; }
        .styled-table tbody tr:hover { background-color: #f3f4f6; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; text-transform: capitalize; }
        .badge-pending { background-color: #fef9c3; color: #a16207; }
        .badge-approved { background-color: #dcfce7; color: #166534; }
        .badge-rejected { background-color: #fee2e2; color: #991b1b; }
        .btn { display: inline-flex; align-items: center; padding: 8px 14px; border-radius: 6px; font-weight: 500; font-size: 0.85rem; text-decoration: none; color: white; border: none; cursor: pointer; transition: all 0.2s; margin: 0 4px; }
        .btn-blue { background-color: #2563eb; } .btn-blue:hover { background-color: #1d4ed8; transform: translateY(-1px); box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3); }
        .btn-red { background-color: #ef4444; } .btn-red:hover { background-color: #dc2626; transform: translateY(-1px); box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3); }
        .btn-lihat { background-color: #3b82f6; } .btn-lihat:hover { background-color: #2563eb; transform: translateY(-1px); box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3); } 
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
            max-width: 95%; width: 95%; max-height: 90vh; display: flex; flex-direction: column; }
        .modal-header-clean { display: flex; justify-content: space-between; align-items: center;
            padding-bottom: 15px; margin-bottom: 15px; border-bottom: 2px solid #3b82f6; }
        .modal-header-clean h3 { font-size: 1.5rem; font-weight: 700; color: #1f2937; }

        /* === GRID FOTO === */
        .modal-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 16px;
            overflow-y: auto;
            max-height: calc(90vh - 200px);
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
            cursor: pointer;
            transition: transform 0.2s;
        }
        .image-preview-item img:hover {
            transform: scale(1.05);
        }
        .image-caption {
            font-size: 0.8rem;
            color: #374151;
            text-align: center;
            word-break: break-word;
        }
    </style>

    <div class="card" x-data="{ modalOpen: false, evidenceFiles: [], evidenceLocation: '' }">
        <h2 class="card-header">Riwayat Evidence Anda</h2>

        {{-- 🔥 NOTIFIKASI DARI SESSION (Flash Message dari Form) --}}
        @if(session('success'))
            <div class="alert-success-custom" id="success-alert">
                ✓ {{ session('success') }}
            </div>
        @endif
        
        {{-- 🔥 NOTIFIKASI DARI LOCALSTORAGE (untuk AJAX redirect) --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const successMessage = localStorage.getItem('successMessage');
                const totalFiles = localStorage.getItem('totalFiles');
                
                if (successMessage) {
                    const alertDiv = document.createElement('div');
                    alertDiv.id = 'success-alert-ajax';
                    alertDiv.className = 'alert-success-custom';
                    
                    let displayMessage = `✓ ${successMessage}`;
                    if (totalFiles && totalFiles !== 'null' && parseInt(totalFiles) > 0) {
                        displayMessage += ` (${totalFiles} foto)`;
                    }
                    
                    alertDiv.textContent = displayMessage;
                    
                    const cardHeader = document.querySelector('.card-header');
                    if (cardHeader) {
                        cardHeader.insertAdjacentElement('afterend', alertDiv);
                    }
                    
                    // Hapus dari localStorage
                    localStorage.removeItem('successMessage');
                    localStorage.removeItem('totalFiles');
                    
                    // Hilangkan notifikasi setelah 6 detik dengan efek fade-out
                    setTimeout(() => {
                        if (alertDiv && alertDiv.style) {
                            alertDiv.style.opacity = '0';
                            alertDiv.style.transition = 'opacity 0.10s ease';
                            setTimeout(() => {
                                if (alertDiv.parentNode) {
                                    alertDiv.remove();
                                }
                            }, 400);
                        }
                    }, 10000);
                }
                
                // Hilangkan notifikasi session setelah 6 detik
                const sessionAlert = document.getElementById('success-alert');
                if (sessionAlert) {
                    setTimeout(() => {
                        sessionAlert.style.opacity = '0';
                        sessionAlert.style.transition = 'opacity 0.10s ease';
                        setTimeout(() => {
                            if (sessionAlert.parentNode) {
                                sessionAlert.remove();
                            }
                        }, 400);
                    }, 10000);
                }
            });
        </script>
        
        <div class="table-wrapper">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Waktu/Lokasi</th>
                        <th>Nomor PO</th>
                        <th>Tematik</th>
                        <th>Waspang</th>
                        <th>Foto</th>
                        <th style="text-align: center;">Status</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($evidences as $evidence)
                        @php
                            $files = is_array($evidence->file_path) ? $evidence->file_path : [];
                            // Escape JSON dengan benar untuk Alpine.js
                            $filesJson = htmlspecialchars(json_encode($files), ENT_QUOTES, 'UTF-8');
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $evidence->lokasi }}</strong><br>
                                <small>{{ $evidence->created_at->format('d M Y H:i') }}</small>
                            </td>
                            <td>{{ $evidence->po->no_po ?? 'N/A' }}</td>
                            <td>{{ $evidence->tematik->nama_tematik ?? 'N/A' }}</td>
                            <td>{{ $evidence->pangwas->nama_pangwas ?? 'N/A' }}</td>
                            <td>
                                <button 
                                    onclick="openModal{{ $evidence->id }}()" 
                                    class="btn btn-lihat">
                                    <i class="fa-solid fa-folder-open mr-1"></i> Lihat ({{ count($files) }})
                                </button>
                                <script>
                                    function openModal{{ $evidence->id }}() {
                                        const data = {!! json_encode($files) !!};
                                        const component = document.querySelector('[x-data]').__x.$data;
                                        component.evidenceFiles = data;
                                        component.evidenceLocation = '{{ addslashes($evidence->lokasi) }}';
                                        component.modalOpen = true;
                                    }
                                </script>
                            </td>
                            <td style="text-align:center;">
                                <span class="badge badge-{{ $evidence->status }}">{{ $evidence->status }}</span>
                                @if($evidence->status == 'rejected' && $evidence->catatan_admin)
                                    <p title="{{ $evidence->catatan_admin }}" style="font-size: 0.75rem; color: #b91c1c; margin-top: 4px;">
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
        <div x-show="modalOpen" class="modal-overlay" x-cloak style="display: none;">
            <div class="modal-content" @click.away="modalOpen = false">
                <div class="modal-header-clean">
                    <h3 x-text="'Detail Evidence: ' + evidenceLocation"></h3>
                    <span style="font-size:0.9rem; color:#6b7280;">
                        Total <strong x-text="evidenceFiles.length"></strong> Foto
                    </span>
                </div>

                <!-- 🔥 GALERI FOTO DALAM GRID -->
                <div class="modal-gallery">
                    <template x-for="(file, index) in evidenceFiles" :key="index">
                        <div class="image-preview-item">
                            <img 
                                :src="'{{ asset('storage') }}/' + file.path" 
                                :alt="'Foto ' + (index + 1)"
                                @click="window.open('{{ asset('storage') }}/' + file.path, '_blank')">
                            <p class="image-caption" x-text="file.caption || ('Foto ' + (index + 1))"></p>
                        </div>
                    </template>
                </div>

                <div style="text-align: right; margin-top: 20px;">
                    <button @click="modalOpen = false" class="btn btn-red">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-karyawan-layout>