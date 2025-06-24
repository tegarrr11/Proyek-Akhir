@extends('layouts.sidebar-mahasiswa')

@section('title', 'Dashboard Mahasiswa')

@section('content')
  {{-- Header komponen reusable --}}
  <x-header title="Dashboard" breadcrumb="Dashboard > Ruangan" />

  {{-- Kalender Mahasiswa --}}
  @include('components.kalender-mahasiswa', [
    'gedungs' => $gedungs,
    'selectedGedungId' => $selectedGedungId,
    'events' => $events
  ])
@endsection
