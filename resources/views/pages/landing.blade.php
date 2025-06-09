@extends('layouts.landing')

@section('title', 'Kalender Fasilitas PCR')

@section('content')
  @include('components.kalender-mahasiswa', [
    'selectedGedungId' => $selectedGedungId ?? null,
    'events' => $events ?? [],
    'gedungs' => $gedungs ?? [],
    'fromLanding' => true
  ])
@endsection
