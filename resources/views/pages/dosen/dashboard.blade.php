@extends('layouts.sidebar-dosen')

@section('title', 'Dashboard Staff')

@section('content')
  <x-header title="Dashboard" breadcrumb="Dashboard > Ruangan" />
  @include('components.kalender-mahasiswa', [
    'gedungs' => $gedungs,
    'selectedGedungId' => $selectedGedungId,
    'events' => $events
  ])
@endsection
