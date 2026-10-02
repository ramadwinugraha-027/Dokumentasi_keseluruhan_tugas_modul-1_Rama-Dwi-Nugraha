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
        <p style="color: #000000;">
            <strong>Kapasitas Peserta:</strong> {{ $activity->registered_count }} / {{ $activity->capacity }} Terdaftar
            @if ($activity->registered_count >= $activity->capacity)
                <span style="color: #dc3545; font-weight: bold; margin-left: 6px;">(Kuota Penuh)</span>
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

            <form action="{{ route('activities.destroy', $activity) }}" method="POST" style="display: inline; margin: 0;" onsubmit="return confirm('Yakin ingin memindahkan kegiatan ini ke sampah?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
            </form>
        </div>
    </div>

    @if ($activity->status === 'published' && ! $activity->isPast() && $activity->hasCapacity())
        <div style="background: #ffffff; border: 1px solid #000000; padding: 16px; border-radius: 4px; margin-bottom: 20px;">
            <h4 style="margin-top: 0; color: #000000; margin-bottom: 12px;">Form Pendaftaran Peserta (Atomic Registration)</h4>
            <form action="{{ route('activities.registrations.store', $activity) }}" method="POST">
                @csrf
                <div style="display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 12px;">
                    <div style="flex: 1; min-width: 200px;">
                        <label for="participant_name" style="display: block; font-size: 13px; font-weight: bold; margin-bottom: 4px;">Nama Lengkap *</label>
                        <input type="text" id="participant_name" name="participant_name" class="form-control" placeholder="Nama peserta" required>
                    </div>

                    <div style="flex: 1; min-width: 200px;">
                        <label for="email" style="display: block; font-size: 13px; font-weight: bold; margin-bottom: 4px;">Email (Unique per Kegiatan) *</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="email@domain.com" required>
                    </div>

                    <div style="flex: 1; min-width: 160px;">
                        <label for="participant_phone" style="display: block; font-size: 13px; font-weight: bold; margin-bottom: 4px;">No. Telepon</label>
                        <input type="text" id="participant_phone" name="participant_phone" class="form-control" placeholder="08xxxxxxxx">
                    </div>
                </div>

                <button type="submit" class="btn btn-complete">Daftar Sekarang</button>
            </form>
        </div>
    @elseif ($activity->status !== 'published')
        <div style="background: #f9f9f9; border: 1px solid #000000; padding: 10px 14px; border-radius: 4px; margin-bottom: 20px; font-size: 13.5px;">
            <strong>Catatan:</strong> Pendaftaran hanya dibuka untuk kegiatan yang berstatus <strong>Published</strong> (status saat ini: <em>{{ $activity->status }}</em>).
        </div>
    @elseif ($activity->isPast())
        <div style="background: #f9f9f9; border: 1px solid #000000; padding: 10px 14px; border-radius: 4px; margin-bottom: 20px; font-size: 13.5px;">
            <strong>Catatan:</strong> Pendaftaran ditutup karena tanggal pelaksanaan kegiatan telah lewat.
        </div>
    @elseif (! $activity->hasCapacity())
        <div style="background: #f9f9f9; border: 1px solid #000000; padding: 10px 14px; border-radius: 4px; margin-bottom: 20px; font-size: 13.5px;">
            <strong>Catatan:</strong> Kuota pendaftaran untuk kegiatan ini telah penuh ({{ $activity->capacity }} / {{ $activity->capacity }} peserta).
        </div>
    @endif

    <h4 style="color: #000000; margin-bottom: 10px;">Daftar Peserta Terdaftar ({{ $activity->registrations->count() }} Orang)</h4>
    <table>
        <thead>
            <tr>
                <th style="width: 40px; text-align: center;">No</th>
                <th>Nama Peserta</th>
                <th>Email</th>
                <th>Telepon</th>
                <th style="width: 140px;">Waktu Daftar</th>
                <th style="width: 100px; text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($activity->registrations as $index => $registration)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $registration->participant_name }}</td>
                    <td>{{ $registration->email }}</td>
                    <td>{{ $registration->participant_phone ?? '-' }}</td>
                    <td>{{ $registration->registered_at ? $registration->registered_at->format('d/m/Y H:i') : '-' }}</td>
                    <td style="text-align: center;"><span class="badge">{{ $registration->status }}</span></td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #000000; padding: 16px;">Belum ada peserta yang mendaftar pada kegiatan ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection