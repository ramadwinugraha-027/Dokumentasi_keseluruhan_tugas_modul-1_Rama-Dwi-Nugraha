@extends('layouts.app')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
        <h3 style="margin: 0; font-size: 17px; color: #000000;">Daftar Sampah (Trash / Soft Deleted)</h3>
        <a href="{{ route('activities.index') }}" class="btn btn-secondary btn-sm">&larr; Kembali ke Daftar</a>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 40px; text-align: center;">No</th>
                <th style="width: 95px;">Kode</th>
                <th>Judul Kegiatan</th>
                <th style="width: 130px;">Kategori</th>
                <th style="width: 100px;">Tanggal</th>
                <th style="width: 140px;">Dihapus Pada</th>
                <th style="width: 180px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($activities as $index => $activity)
                <tr>
                    <td style="text-align: center;">{{ $activities->firstItem() ? $activities->firstItem() + $index : $index + 1 }}</td>
                    <td><strong>{{ $activity->code }}</strong></td>
                    <td style="font-weight: bold; color: #000000;">{{ $activity->title }}</td>
                    <td>{{ $activity->category->name ?? '-' }}</td>
                    <td>{{ $activity->activity_date ? $activity->activity_date->format('d/m/Y') : '-' }}</td>
                    <td>{{ $activity->deleted_at ? $activity->deleted_at->format('d/m/Y H:i') : '-' }}</td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 4px; justify-content: center; align-items: center; flex-wrap: wrap;">
                            <form action="{{ route('activities.restore', $activity->id) }}" method="POST" style="display: inline; margin: 0;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-complete btn-sm">Restore</button>
                            </form>

                            <form action="{{ route('activities.force-delete', $activity->id) }}" method="POST" style="display: inline; margin: 0;" onsubmit="return confirm('Yakin ingin menghapus kegiatan ini secara PERMANEN?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus Permanen</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #000000; padding: 24px;">Tidak ada data kegiatan di dalam sampah (Trash kosong).</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 16px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <div style="font-size: 13px; color: #000000; font-weight: 500;">
            Menampilkan {{ $activities->firstItem() ?? 0 }} sampai {{ $activities->lastItem() ?? 0 }} dari {{ $activities->total() }} data di sampah
        </div>
        <div>
            {{ $activities->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
