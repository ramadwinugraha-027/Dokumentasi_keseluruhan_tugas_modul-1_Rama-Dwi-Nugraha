@extends('layouts.app')

@section('content')

    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Daftar Kegiatan</h1>
        <a href="{{ route('activities.create') }}" class="btn btn-primary">Tambah Kegiatan Baru</a>
    </div>

    @if (session('success'))
        <div style="color: green; margin-bottom: 15px;">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" action="{{ route('activities.index') }}" style="margin-bottom: 20px;">
        <label for="status">Filter Status:</label>
        <select name="status" id="status" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="Planned" {{ request('status') == 'Planned' ? 'selected' : '' }}>Planned</option>
            <option value="Ongoing" {{ request('status') == 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
            <option value="Done" {{ request('status') == 'Done' ? 'selected' : '' }}>Done</option>
        </select>
    </form>

    @forelse ($activities as $activity)

        <article class="card" style="margin-bottom: 15px; padding: 15px; border: 1px solid #ccc;">
            <h2>
                <a href="{{ route('activities.show', $activity) }}">
                    {{ $activity->title }}
                </a>
            </h2>
            <p>
                Tanggal: {{ $activity->activity_date->format('d M Y') }}
            </p>
            <p>
                Status: {{ $activity->status }}
            </p>

            <div style="margin-top: 10px;">
                <a href="{{ route('activities.edit', $activity) }}">Edit</a>

                <form action="{{ route('activities.destroy', $activity) }}" method="POST" style="display: inline; margin-left: 10px;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus kegiatan ini?')" style="color: red;">
                        Hapus
                    </button>
                </form>
            </div>
        </article>

    @empty
        <p>Belum ada kegiatan.</p>
    @endforelse

@endsection