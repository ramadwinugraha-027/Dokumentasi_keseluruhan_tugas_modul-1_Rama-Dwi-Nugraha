<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Kegiatan</title>
</head>
<body>
    <h1>Tambah Kegiatan Baru</h1>

    {{-- Pesan error validasi dari StoreActivityRequest --}}
    @if ($errors->any())
        <div style="color: red; margin-bottom: 15px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('activities.store') }}" method="POST">
        @csrf

        <p>
            <label>Judul Kegiatan:</label><br>
            <input type="text" name="title" value="{{ old('title') }}">
        </p>

        <p>
            <label>Kategori:</label><br>
            <input type="text" name="category" value="{{ old('category') }}">
        </p>

        <p>
            <label>Tanggal Kegiatan:</label><br>
            <input type="date" name="activity_date" value="{{ old('activity_date') }}">
        </p>

        <p>
            <label>Deskripsi (Opsional):</label><br>
            <textarea name="description">{{ old('description') }}</textarea>
        </p>

        <p>
            <label>Status:</label><br>
            <select name="status">
                <option value="Planned" @selected(old('status') === 'Planned')>Planned</option>
                <option value="Ongoing" @selected(old('status') === 'Ongoing')>Ongoing</option>
                <option value="Done" @selected(old('status') === 'Done')>Done</option>
            </select>
        </p>

        <button type="submit">Simpan Kegiatan</button>
    </form>

    <br>
    <a href="{{ route('activities.index') }}">Kembali ke Daftar</a>
</body>
</html>