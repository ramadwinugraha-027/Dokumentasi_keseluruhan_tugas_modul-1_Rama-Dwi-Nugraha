@extends('layouts.app')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h3 style="margin: 0;">Daftar Kegiatan</h3>
        <a href="{{ route('activities.create') }}" class="btn btn-primary">+ Tambah Kegiatan</a>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 40px;">No</th>
                <th style="width: 100px;">Kode</th>
                <th>Judul Kegiatan</th>
                <th>Kategori</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th style="width: 170px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($activities as $index => $activity)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $activity->code }}</strong></td>
                    <td>
                        <a href="{{ route('activities.show', $activity) }}" style="color: #0066cc; text-decoration: none;">
                            {{ $activity->title }}
                        </a>
                    </td>
                    <td>{{ $activity->category->name ?? '-' }}</td>
                    <td>{{ $activity->activity_date->format('d/m/Y') }}</td>
                    <td>{{ $activity->status }}</td>
                    <td>
                        <a href="{{ route('activities.show', $activity) }}" class="btn btn-secondary btn-sm">Detail</a>
                        <a href="{{ route('activities.edit', $activity) }}" class="btn btn-primary btn-sm">Edit</a>
                        <form action="{{ route('activities.destroy', $activity) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus kegiatan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #777;">Belum ada kegiatan yang terdaftar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection