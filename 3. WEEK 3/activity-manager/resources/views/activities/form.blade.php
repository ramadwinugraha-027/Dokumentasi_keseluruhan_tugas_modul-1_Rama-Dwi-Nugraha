<div class="form-group">
    <label for="status">Status</label>
    <select id="status" name="status">
        {{-- Jika status saat ini sudah 'Done', sembunyikan atau batasi opsi Planned --}}
        @if (isset($activity) && $activity->status === 'Done')
            <option value="Done" selected>Done</option>
        @else
            @foreach (['Planned', 'Ongoing', 'Done'] as $statusOption)
                <option value="{{ $statusOption }}" @selected(old('status', $activity->status ?? 'Planned') === $statusOption)>
                    {{ $statusOption }}
                </option>
            @endforeach
        @endif
    </select>
    @error('status')
        <div class="text-error">{{ $message }}</div>
    @enderror
</div>