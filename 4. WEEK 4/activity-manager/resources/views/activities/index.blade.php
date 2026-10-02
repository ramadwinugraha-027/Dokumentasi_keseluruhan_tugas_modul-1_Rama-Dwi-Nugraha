@extends('layouts.app')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 8px;">
        <h3 style="margin: 0; font-size: 17px; color: #000000;">Daftar Kegiatan</h3>
        <div style="display: flex; gap: 6px;">
            <a href="{{ route('activities.trash') }}" class="btn btn-secondary">Sampah ({{ $trashedCount ?? 0 }})</a>
            <a href="{{ route('activities.create') }}" class="btn btn-primary">+ Tambah Kegiatan</a>
        </div>
    </div>

    <div style="background: #ffffff; border: 1px solid #000000; padding: 14px; border-radius: 4px; margin-bottom: 18px;">
        <form action="{{ route('activities.index') }}" method="GET" style="display: flex; flex-wrap: wrap; gap: 10px; align-items: flex-end;">
            <div style="flex: 1.5; min-width: 180px;">
                <label for="search" style="display: block; font-size: 13px; font-weight: bold; margin-bottom: 4px; color: #000000;">Pencarian (Kode / Judul):</label>
                <input type="text" id="search" name="search" class="form-control" value="{{ request('search') }}" placeholder="Cari kode atau judul...">
            </div>

            <div style="flex: 1; min-width: 140px;">
                <label for="category_id" style="display: block; font-size: 13px; font-weight: bold; margin-bottom: 4px; color: #000000;">Kategori:</label>
                <select id="category_id" name="category_id" class="form-control">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="flex: 1; min-width: 120px;">
                <label for="status" style="display: block; font-size: 13px; font-weight: bold; margin-bottom: 4px; color: #000000;">Status:</label>
                <select id="status" name="status" class="form-control">
                    <option value="">Semua Status</option>
                    <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                    <option value="published" @selected(request('status') === 'published')>Published</option>
                    <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                </select>
            </div>

            <div style="flex: 1; min-width: 130px;">
                <label for="sort" style="display: block; font-size: 13px; font-weight: bold; margin-bottom: 4px; color: #000000;">Urutan Tanggal:</label>
                <select id="sort" name="sort" class="form-control">
                    <option value="latest" @selected(request('sort', 'latest') === 'latest')>Terbaru (Desc)</option>
                    <option value="oldest" @selected(request('sort') === 'oldest')>Terlama (Asc)</option>
                </select>
            </div>

            <div style="display: flex; gap: 5px;">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('activities.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 40px; text-align: center;">No</th>
                <th style="width: 95px;">Kode</th>
                <th>Judul Kegiatan</th>
                <th style="width: 130px;">Kategori</th>
                <th style="width: 100px;">Tanggal</th>
                <th style="width: 100px; text-align: center;">Status</th>
                <th style="width: 220px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($activities as $index => $activity)
                <tr>
                    <td style="text-align: center;">{{ $activities->firstItem() ? $activities->firstItem() + $index : $index + 1 }}</td>
                    <td><strong>{{ $activity->code }}</strong></td>
                    <td>
                        <a href="{{ route('activities.show', $activity) }}" style="color: #000000; text-decoration: underline; font-weight: bold;">
                            {{ $activity->title }}
                        </a>
                    </td>
                    <td>{{ $activity->category->name ?? '-' }}</td>
                    <td>{{ $activity->activity_date ? $activity->activity_date->format('d/m/Y') : '-' }}</td>
                    <td style="text-align: center;">
                        @if ($activity->status === 'draft')
                            <span class="badge badge-draft">Draft</span>
                        @elseif ($activity->status === 'published')
                            <span class="badge badge-published">Published</span>
                        @elseif ($activity->status === 'completed')
                            <span class="badge badge-completed">Completed</span>
                        @else
                            <span class="badge">{{ $activity->status }}</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 4px; justify-content: center; align-items: center; flex-wrap: wrap;">
                            <a href="{{ route('activities.show', $activity) }}" class="btn btn-secondary btn-sm">Detail</a>
                            <a href="{{ route('activities.edit', $activity) }}" class="btn btn-primary btn-sm">Edit</a>

                            @if ($activity->status === 'draft')
                                <form action="{{ route('activities.publish', $activity) }}" method="POST" style="display: inline; margin: 0;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-publish btn-sm">Publish</button>
                                </form>
                            @elseif ($activity->status === 'published')
                                <form action="{{ route('activities.complete', $activity) }}" method="POST" style="display: inline; margin: 0;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-complete btn-sm">Complete</button>
                                </form>
                            @endif

                            <form action="{{ route('activities.destroy', $activity) }}" method="POST" style="display: inline; margin: 0;" onsubmit="return confirm('Yakin ingin memindahkan kegiatan ini ke sampah?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #000000; padding: 20px;">Tidak ada kegiatan yang sesuai dengan kriteria filter/pencarian.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 16px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <div style="font-size: 13px; color: #000000; font-weight: 500;">
            Menampilkan {{ $activities->firstItem() ?? 0 }} sampai {{ $activities->lastItem() ?? 0 }} dari {{ $activities->total() }} total kegiatan
        </div>
        <div>
            {{ $activities->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection