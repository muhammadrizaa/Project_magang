<x-admin-layout>
    <style>
        .card { background-color: #fff; padding: 24px; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
        .card-header { display: flex; justify-content: space-between; align-items: center; padding-bottom: 16px; border-bottom: 1px solid #e5e7eb; }
        .card-title h2 { font-size: 1.25rem; font-weight: 600; color: #1f2937; }
        .card-title span { font-size: 0.875rem; color: #6b7280; }
        .btn { display: inline-flex; align-items: center; padding: 8px 16px; border-radius: 6px; font-weight: 600; font-size: 0.875rem; text-decoration: none; color: white; border: none; cursor: pointer; transition: background-color 0.2s; }
        .btn-red { background-color: #dc2626; }
        .btn-red:hover { background-color: #b91c1c; }
        .btn-blue { background-color: #2563eb; }
        .btn-blue:hover { background-color: #1d4ed8; }
        .btn-sm { padding: 5px 10px; font-size: 0.75rem; }
        .alert-success { background-color: #d1fae5; border-left: 4px solid #34d399; color: #065f46; padding: 16px; margin-top: 16px; }
        .table-wrapper { overflow-x: auto; margin-top: 24px; }
        .styled-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
        .styled-table thead tr { background-color: #f9fafb; text-align: left; color: #374151; text-transform: uppercase; font-size: 0.75rem; }
        .styled-table th, .styled-table td { padding: 12px 16px; border-bottom: 1px solid #e5e7eb; }
        .styled-table tbody tr:hover { background-color: #f3f4f6; }
        .pagination { margin-top: 16px; }
    </style>

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <h2>Daftar Karyawan</h2>
                <span>Total {{ $karyawan->total() }} karyawan ditemukan</span>
            </div>
            <div>
                <a href="{{ route('admin.karyawan.create') }}" class="btn btn-red">
                    + Tambah Baru
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-wrapper">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Tanggal Dibuat</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($karyawan as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                        <td style="text-align: center; white-space: nowrap;">
                             <a href="{{ route('admin.karyawan.edit', $user->id) }}" class="btn btn-blue btn-sm">Edit</a>
                             <form action="{{ route('admin.karyawan.destroy', $user->id) }}" method="POST" style="display: inline; margin-left: 8px;" onsubmit="return confirm('Yakin ingin menghapus karyawan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-red btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 16px;">Tidak ada data karyawan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            {{ $karyawan->links() }}
        </div>
    </div>
</x-admin-layout>