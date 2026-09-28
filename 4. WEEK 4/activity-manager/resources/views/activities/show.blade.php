@extends('layouts.app')

@section('content')
    <h1>{{ $activity->title }}</h1>

    <p><strong>Deskripsi:</strong> {{ $activity->description }}</p>
    <p><strong>Tanggal:</strong> {{ $activity->activity_date }}</p>
    <p><strong>Kategori:</strong> {{ $activity->category }}</p>
    <p><strong>Status:</strong> {{ $activity->status }}</p>

    <a href="{{ route('activities.index') }}">Kembali ke Daftar</a>
@endsection