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

        @include('activities.form', ['activity' => new \App\Models\Activity()])

        <div style="margin-top: 20px;">
            <button type="submit" class="btn btn-primary">Simpan Kegiatan</button>
            <a href="{{ route('activities.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
@endsection