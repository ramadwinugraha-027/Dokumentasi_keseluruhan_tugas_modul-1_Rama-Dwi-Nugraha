@extends('layouts.app')

@section('content')
    <div style="margin-bottom: 14px;">
        <a href="{{ route('activities.index') }}" class="btn btn-secondary btn-sm">&larr; Kembali ke Daftar</a>
    </div>

    <div style="background: #ffffff; border: 1px solid #000000; padding: 16px; border-radius: 4px; margin-bottom: 20px;">
        <h3 style="margin-top: 0; color: #000000;">{{ $activity->title }}</h3>
        <p style="color: #000000;"><strong>Kode Kegiatan:</strong> {{ $activity->code }}</p>
        <p style="color: #000000;"><strong>Kategori:</strong> {{ $activity->category->name ?? '-' }}</p>
        <p style="color: #000000;"><strong>Tanggal Kegiatan:</strong> {{ $activity->activity_date ? $activity->activity_date->format('d/m/Y') : '-' }}</p>
        <p style="color: #000000;">
            <strong>Status:</strong>
            @if ($activity->status === 'draft')
                <span class="badge badge-draft">Draft</span>
            @elseif ($activity->status === 'published')
                <span class="badge badge-published">Published</span>
            @elseif ($activity->status === 'completed')
                <span class="badge badge-completed">Completed</span>
            @else
                <span class="badge">{{ $activity->status }}</span>
            @endif
        </p>
        <p style="color: #000000;"><strong>Deskripsi:</strong> {{ $activity->description ?: '-' }}</p>

        <div style="margin-top: 16px; display: flex; gap: 6px; align-items: center; flex-wrap: wrap;">
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

            <form action="{{ route('activities.destroy', $activity) }}" method="POST" style="display: inline; margin: 0;" onsubmit="return confirm('Yakin ingin menghapus kegiatan ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
            </form>
        </div>
    </div>

    <h4 style="color: #000000; margin-bottom: 10px;">Daftar Peserta Terdaftar (Relasi Activity hasMany Registration)</h4>
    <table>
        <thead>
            <tr>
                <th style="width: 50px; text-align: center;">No</th>
                <th>Nama Peserta</th>
                <th>Email</th>
                <th>Telepon</th>
                <th style="width: 120px; text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($activity->registrations as $index => $registration)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $registration->participant_name }}</td>
                    <td>{{ $registration->participant_email }}</td>
                    <td>{{ $registration->participant_phone ?? '-' }}</td>
                    <td style="text-align: center;"><span class="badge">{{ $registration->status }}</span></td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #000000; padding: 16px;">Belum ada peserta yang mendaftar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection