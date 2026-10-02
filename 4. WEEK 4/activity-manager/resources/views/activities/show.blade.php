@extends('layouts.app')

@section('content')
    <div style="margin-bottom: 15px;">
        <a href="{{ route('activities.index') }}" class="btn btn-secondary btn-sm">&larr; Kembali ke Daftar</a>
    </div>

    <div style="background: #fafafa; border: 1px solid #ddd; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
        <h3 style="margin-top: 0;">{{ $activity->title }}</h3>
        <p><strong>Kode Kegiatan:</strong> {{ $activity->code }}</p>
        <p><strong>Kategori:</strong> {{ $activity->category->name ?? '-' }}</p>
        <p><strong>Tanggal Kegiatan:</strong> {{ $activity->activity_date->format('d/m/Y') }}</p>
        <p><strong>Status:</strong> {{ $activity->status }}</p>
        <p><strong>Deskripsi:</strong> {{ $activity->description ?: '-' }}</p>

        <div style="margin-top: 15px;">
            <a href="{{ route('activities.edit', $activity) }}" class="btn btn-primary btn-sm">Edit</a>
            <form action="{{ route('activities.destroy', $activity) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
            </form>
        </div>
    </div>

    <h4>Daftar Peserta Terdaftar (Relasi Activity hasMany Registration)</h4>
    <table>
        <thead>
            <tr>
                <th style="width: 50px;">No</th>
                <th>Nama Peserta</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($activity->registrations as $index => $registration)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $registration->participant_name }}</td>
                    <td>{{ $registration->participant_email }}</td>
                    <td>{{ $registration->participant_phone ?? '-' }}</td>
                    <td>{{ $registration->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #777;">Belum ada peserta yang mendaftar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection