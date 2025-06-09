@extends('layouts.sidebar-admin')

@section('title', 'Dashboard Admin')

@section('content')
  {{-- Header komponen reusable --}}
  <x-header title="Dashboard" breadcrumb="Dashboard > Ruangan" />

  {{-- Kalender Mahasiswa --}}
  @include('components.kalender-mahasiswa')
@endsection
