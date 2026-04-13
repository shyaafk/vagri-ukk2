@extends('layouts.app')

@section('title', 'dashboard')

@section('content')

@if (session('role') == 'admin')

     <div class="alert alert-primary">
        Dashboard admin
     </div>

@endif

@if (session('role') == 'petugas')

     <div class="alert alert-success">
        Dashboard Petugas
     </div>

@endif

@if (session('role') == 'owner')

     <div class="alert alert-warning">
        Dashboard Owner
     </div>

@endif

@endsection