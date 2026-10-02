<div class="form-group">
    <label for="title">Judul Kegiatan *</label>
    <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $activity->title ?? '') }}" required>
    @error('title')
        <div class="text-error">{{ $message }}</div>
    @enderror
</div>