<x-admin-layout>
    <style>
        .card-table { background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); overflow-x: auto; border: 1px solid #e5e7eb; }
        .table-data { width: 100%; border-collapse: collapse; line-height: 1.5; }
        .table-data thead tr { background-color: #f9fafb; border-bottom: 2px solid #e5e7eb; }
        .table-data th { padding: 12px 20px; text-align: left; font-size: 0.75rem; font-weight: 600; color: #4b5563; text-transform: uppercase; letter-spacing: 0.05em; }
        .table-data td { padding: 16px 20px; border-bottom: 1px solid #e5e7eb; font-size: 0.875rem; color: #374151; }
        .table-data tbody tr:hover { background-color: #f3f4f6; }
        .action-link { text-decoration: none; color: #4f46e5; margin-right: 1rem; font-weight: 500; transition: color 0.15s; }
        .action-link:hover { color: #3730a3; }
        .delete-btn { color: #dc2626; font-weight: 500; cursor: pointer; border: none; background: none; padding: 0; transition: color 0.15s; }
        .delete-btn:hover { color: #b91c1c; }
        .btn-add { background-color: #dc2626; color: white; font-weight: 600; padding: 8px 16px; border-radius: 8px; box-shadow: 0 1px 3px 0 rgba(0,0,0,0.1); text-decoration: none; display: flex; align-items: center; transition: background-color 0.2s; }
        .btn-add:hover { background-color: #b91c1c; }
    </style>

    <div class="container" style="max-width: 1000px; margin: 0 auto; padding: 20px;">
        <h1 style="font-size: 1.5rem; font-weight: 700; color: #1f2937; margin-bottom: 1.5rem;">Kelola Data Waspang</h1>

        @if (session('success'))
            <div id="alert-success" style="background-color: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; padding: 12px 16px; border-radius: 8px; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                <div><i class="fa-solid fa-check-circle" style="margin-right: 8px;"></i><strong>Berhasil!</strong> {{ session('success') }}</div>
                <button type="button" onclick="document.getElementById('alert-success').remove();" style="background: none; border: none; color: #065f46; cursor: pointer; font-size: 1.5rem;">×</button>
            </div>
        @endif

        @if (session('error'))
            <div id="alert-error" style="background-color: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 12px 16px; border-radius: 8px; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                <div><i class="fa-solid fa-exclamation-circle" style="margin-right: 8px;"></i><strong>Error!</strong> {{ session('error') }}</div>
                <button type="button" onclick="document.getElementById('alert-error').remove();" style="background: none; border: none; color: #991b1b; cursor: pointer; font-size: 1.5rem;">×</button>
            </div>
        @endif

        <div style="margin-bottom: 1.5rem; display: flex; justify-content: flex-end;">
            <a href="{{ route('admin.waspang.create') }}" class="btn-add">
                <i class="fa-solid fa-plus" style="margin-right: 0.5rem;"></i> Tambah Waspang Baru
            </a>
        </div>

        <div class="card-table">
            <table class="table-data">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Waspang</th>
                        <th>NIK Waspang</th>
                        <th>Dibuat Pada</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($waspang_list as $waspang)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td style="font-weight: 500; color: #1f2937;">{{ $waspang->nama_waspang }}</td>
                            <td>{{ $waspang->nik_waspang ?? '-' }}</td>
                            <td style="color: #6b7280;">{{ $waspang->created_at ? $waspang->created_at->format('d M Y H:i') : '-' }}</td>
                            <td style="text-align: center;">
                                <a href="{{ route('admin.waspang.edit', $waspang->id) }}" class="action-link">
                                    <i class="fa-solid fa-edit" style="margin-right: 4px;"></i> Edit
                                </a>
                                <form action="{{ route('admin.waspang.destroy', $waspang->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-btn" onclick="return confirm('Yakin ingin menghapus data Waspang ini?');">
                                        <i class="fa-solid fa-trash-alt" style="margin-right: 4px;"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: #6b7280;">
                                Data Waspang masih kosong. Silakan tambahkan Waspang baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($waspang_list->hasPages())
            <div style="margin-top: 2rem; display: flex; justify-content: center; gap: 0.5rem; flex-wrap: wrap;">
                @if ($waspang_list->onFirstPage())
                    <span style="padding: 8px 12px; color: #9ca3af; cursor: not-allowed; border: 1px solid #e5e7eb; border-radius: 4px;">← Previous</span>
                @else
                    <a href="{{ $waspang_list->previousPageUrl() }}" style="padding: 8px 12px; color: #fff; background-color: #dc2626; text-decoration: none; border: 1px solid #dc2626; border-radius: 4px;">← Previous</a>
                @endif

                @foreach ($waspang_list->getUrlRange(1, $waspang_list->lastPage()) as $page => $url)
                    @if ($page == $waspang_list->currentPage())
                        <span style="padding: 8px 12px; color: #fff; background-color: #dc2626; border: 1px solid #dc2626; border-radius: 4px; font-weight: 600;">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" style="padding: 8px 12px; color: #4f46e5; background-color: #f3f4f6; text-decoration: none; border: 1px solid #e5e7eb; border-radius: 4px;">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($waspang_list->hasMorePages())
                    <a href="{{ $waspang_list->nextPageUrl() }}" style="padding: 8px 12px; color: #fff; background-color: #dc2626; text-decoration: none; border: 1px solid #dc2626; border-radius: 4px;">Next →</a>
                @else
                    <span style="padding: 8px 12px; color: #9ca3af; cursor: not-allowed; border: 1px solid #e5e7eb; border-radius: 4px;">Next →</span>
                @endif
            </div>
        @endif
    </div>
</x-admin-layout>