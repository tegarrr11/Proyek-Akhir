@extends('layouts.sidebar-bem')

@section('title', 'Dashboard Bem')

@section('content')
  <x-header title="Dashboard" breadcrumb="Dashboard > Ruangan" />

  {{-- Kalender Mahasiswa --}}
  @include('components.kalender-mahasiswa', [
    'gedungs' => $gedungs,
    'selectedGedungId' => $selectedGedungId,
    'events' => $events
  ])
@endsection
