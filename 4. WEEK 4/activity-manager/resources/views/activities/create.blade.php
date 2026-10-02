@extends('layouts.app')

@section('content')
    <div style="margin-bottom: 15px;">
        <h3>Tambah Kegiatan Baru</h3>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('activities.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="code">Kode Kegiatan (Unique) *</label>
            <input type="text" id="code" name="code" class="form-control" value="{{ old('code') }}" placeholder="Contoh: ACT-001" required>
        </div>

        <div class="form-group">
            <label for="title">Judul Kegiatan *</label>
            <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" placeholder="Masukkan judul kegiatan" required>
        </div>

        <div class="form-group">
            <label for="category_id">Kategori *</label>
            <select id="category_id" name="category_id" class="form-control" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="activity_date">Tanggal Kegiatan *</label>
            <input type="date" id="activity_date" name="activity_date" class="form-control" value="{{ old('activity_date') }}" required>
        </div>

        <div class="form-group">
            <label for="poster">Poster Kegiatan (Opsional, Max: 2 MB)</label>
            <input type="file" id="poster" name="poster" class="form-control" accept="image/jpeg,image/png,image/jpg,image/webp">
        </div>

        <div class="form-group">
            <label for="description">Deskripsi</label>
            <textarea id="description" name="description" class="form-control" rows="4" placeholder="Deskripsi kegiatan...">{{ old('description') }}</textarea>
        </div>

        <div style="margin-top: 20px;">
            <button type="submit" class="btn btn-primary">Simpan Kegiatan</button>
            <a href="{{ route('activities.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
@endsection