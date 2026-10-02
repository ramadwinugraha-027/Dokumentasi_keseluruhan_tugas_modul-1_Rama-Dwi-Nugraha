<div class="form-group">
    <label for="code">Kode Kegiatan (Unique) *</label>
    <input type="text" id="code" name="code" class="form-control" value="{{ old('code', $activity->code ?? '') }}" placeholder="Contoh: ACT-001" required>
</div>

<div class="form-group">
    <label for="title">Judul Kegiatan *</label>
    <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $activity->title ?? '') }}" placeholder="Masukkan judul kegiatan" required>
</div>

<div class="form-group">
    <label for="category_id">Kategori *</label>
    <select id="category_id" name="category_id" class="form-control" required>
        <option value="">-- Pilih Kategori --</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected(old('category_id', $activity->category_id ?? '') == $category->id)>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="activity_date">Tanggal Kegiatan *</label>
    <input type="date" id="activity_date" name="activity_date" class="form-control" value="{{ old('activity_date', optional($activity->activity_date)->format('Y-m-d')) }}" required>
</div>

<div class="form-group">
    <label for="poster">Poster Kegiatan (Opsional, Max: 2 MB)</label>
    @if (!empty($activity->poster_path))
        <div style="margin-bottom: 8px;">
            <img src="{{ Storage::url($activity->poster_path) }}" alt="Poster Saat Ini" style="max-width: 140px; height: auto; border: 1px solid #000; border-radius: 3px; display: block;">
            <small style="color: #444;">Poster saat ini (unggah baru jika ingin mengganti)</small>
        </div>
    @endif
    <input type="file" id="poster" name="poster" class="form-control" accept="image/jpeg,image/png,image/jpg,image/webp">
</div>

<div class="form-group">
    <label for="description">Deskripsi</label>
    <textarea id="description" name="description" class="form-control" rows="4" placeholder="Deskripsi kegiatan...">{{ old('description', $activity->description ?? '') }}</textarea>
</div>