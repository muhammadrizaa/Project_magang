<x-admin-layout>
    {{-- CSS Khusus untuk Tampilan Tabel --}}
    <style>
        .card-table {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
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
            color: #4f46e5; 
            margin-right: 1rem;
            font-weight: 500;
            transition: color 0.15s;
        }
        .action-link:hover {
            color: #3730a3;
        }
        .delete-btn {
            color: #dc2626;
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
    </style>
    
    <div class="container" style="max-width: 1000px; margin: 0 auto; padding: 20px;">
        <h1 style="font-size: 1.5rem; font-weight: 700; color: #1f2937; margin-bottom: 1.5rem;">Kelola Data Waspang</h1>

        <!-- Alert Success -->
        @if (session('success'))
            <div id="alert-success" style="background-color: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; padding: 12px 16px; border-radius: 8px; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <i class="fa-solid fa-check-circle" style="margin-right: 8px;"></i>
                    <strong>Berhasil!</strong> 
                    @if (str_contains(session('success'), 'ditambahkan'))
                        Data Waspang berhasil ditambahkan.
                    @elseif (str_contains(session('success'), 'dihapus'))
                        Data Waspang berhasil dihapus.
                    @elseif (str_contains(session('success'), 'diubah'))
                        Data Waspang berhasil diubah.
                    @else
                        {{ session('success') }}
                    @endif
                </div>
                <button type="button" onclick="document.getElementById('alert-success').remove();" style="background: none; border: none; color: #065f46; cursor: pointer; font-size: 1.5rem;">×</button>
            </div>
        @endif

        <!-- Alert Error -->
        @if (session('error'))
            <div id="alert-error" style="background-color: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 12px 16px; border-radius: 8px; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <i class="fa-solid fa-exclamation-circle" style="margin-right: 8px;"></i>
                    <strong>Error!</strong> {{ session('error') }}
                </div>
                <button type="button" onclick="document.getElementById('alert-error').remove();" style="background: none; border: none; color: #991b1b; cursor: pointer; font-size: 1.5rem;">×</button>
            </div>
        @endif

        <!-- Tombol Tambah Pangwas -->
        <div style="margin-bottom: 1.5rem; display: flex; justify-content: flex-end;">
            <a href="{{ route('admin.pangwas.create') }}" class="btn-add">
                <i class="fa-solid fa-plus" style="margin-right: 0.5rem;"></i> Tambah Waspang Baru
            </a>
        </div>

        <!-- Tabel Data Pangwas -->
        <div class="card-table">
            <table class="table-data">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Waspang</th>
                        <th>Dibuat Pada</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pangwas_list as $pangwas)
                        <tr>
                            <td>
                                {{ $loop->index + 1 }} 
                            </td>
                            <td style="font-weight: 500; color: #1f2937;">
                                {{ $pangwas->nama_pangwas }}
                            </td>
                            <td style="color: #6b7280;">
                                {{ $pangwas->created_at ? $pangwas->created_at->format('d M Y H:i') : '-' }}
                            </td>
                            <td style="text-align: center;">
                                <!-- Tombol Edit -->
                                <a href="{{ route('admin.pangwas.edit', $pangwas->id) }}" class="action-link">
                                    <i class="fa-solid fa-edit" style="margin-right: 4px;"></i> Edit
                                </a>

                                <!-- Tombol Hapus (Form DELETE) - DIPERBAIKI -->
                                <form action="{{ route('admin.pangwas.destroy', $pangwas->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-btn" onclick="return confirm('APAKAH ANDA YAKIN INGIN MENGHAPUS DATA WASPANG INI?');">
                                        <i class="fa-solid fa-trash-alt" style="margin-right: 4px;"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 40px; color: #6b7280;">
                                Data Waspang masih kosong. Silakan tambahkan Waspang baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if ($pangwas_list->hasPages())
            <div style="margin-top: 2rem; display: flex; justify-content: center; gap: 0.5rem; flex-wrap: wrap;">
                <!-- Previous Page Link -->
                @if ($pangwas_list->onFirstPage())
                    <span style="padding: 8px 12px; color: #9ca3af; cursor: not-allowed; border: 1px solid #e5e7eb; border-radius: 4px;">← Previous</span>
                @else
                    <a href="{{ $pangwas_list->previousPageUrl() }}" style="padding: 8px 12px; color: #fff; background-color: #dc2626; text-decoration: none; border: 1px solid #dc2626; border-radius: 4px;">← Previous</a>
                @endif

                <!-- Pagination Elements -->
                @foreach ($pangwas_list->getUrlRange(1, $pangwas_list->lastPage()) as $page => $url)
                    @if ($page == $pangwas_list->currentPage())
                        <span style="padding: 8px 12px; color: #fff; background-color: #dc2626; border: 1px solid #dc2626; border-radius: 4px; font-weight: 600;">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" style="padding: 8px 12px; color: #4f46e5; background-color: #f3f4f6; text-decoration: none; border: 1px solid #e5e7eb; border-radius: 4px;">{{ $page }}</a>
                    @endif
                @endforeach

                <!-- Next Page Link -->
                @if ($pangwas_list->hasMorePages())
                    <a href="{{ $pangwas_list->nextPageUrl() }}" style="padding: 8px 12px; color: #fff; background-color: #dc2626; text-decoration: none; border: 1px solid #dc2626; border-radius: 4px;">Next →</a>
                @else
                    <span style="padding: 8px 12px; color: #9ca3af; cursor: not-allowed; border: 1px solid #e5e7eb; border-radius: 4px;">Next →</span>
                @endif
            </div>
        @endif

    </div>
</x-admin-layout>