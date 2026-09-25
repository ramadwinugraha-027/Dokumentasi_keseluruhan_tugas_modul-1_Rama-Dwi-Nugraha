<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Kegiatan</title>
</head>
<body>
    <h1>Edit Kegiatan</h1>

    {{-- Pesan error validasi & business rule --}}
    @if ($errors->any())
        <div style="color: red; margin-bottom: 15px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('activities.update', $activity) }}" method="POST">
        @csrf
        @method('PUT')

        <p>
            <label>Judul Kegiatan:</label><br>
            <input type="text" name="title" value="{{ old('title', $activity->title) }}">
        </p>

        <p>
            <label>Kategori:</label><br>
            <input type="text" name="category" value="{{ old('category', $activity->category) }}">
        </p>

        <p>
            <label>Tanggal Kegiatan:</label><br>
            <input type="date" name="activity_date" value="{{ old('activity_date', optional($activity->activity_date)->format('Y-m-d')) }}">
        </p>

        <p>
            <label>Deskripsi (Opsional):</label><br>
            <textarea name="description">{{ old('description', $activity->description) }}</textarea>
        </p>

        <p>
            <label>Status Saat Ini: <strong>{{ $activity->status }}</strong></label><br>
            <select name="status">
                {{-- Aturan UX Blade: Jika status Done, hanya tampilkan opsi Done --}}
                @if ($activity->status === 'Done')
                    <option value="Done" selected>Done</option>
                @else
                    <option value="Planned" @selected(old('status', $activity->status) === 'Planned')>Planned</option>
                    <option value="Ongoing" @selected(old('status', $activity->status) === 'Ongoing')>Ongoing</option>
                    <option value="Done" @selected(old('status', $activity->status) === 'Done')>Done</option>
                @endif
            </select>
        </p>

        <button type="submit">Simpan Perubahan</button>
    </form>

    <br>
    <a href="{{ route('activities.index') }}">Kembali ke Daftar</a>
</body>
</html>