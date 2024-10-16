@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <div class="modal-body text-center">
            <div class="profile-info">
                <div>
                    <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('user.png') }}" alt="user avatar" style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%;">
                </div>
            </div>
        </div>
        <form action="{{url('/profile/update')}}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="username">Level Pengguna</label>
                <input type="hidden" name="level_id" class="form-control" value="{{ Auth::user()->level->level_id }}" readonly>
                <input type="text" name="level_nama" class="form-control" value="{{ Auth::user()->level->level_nama }}" readonly>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control" value="{{ Auth::user()->username }}">
            </div>
            <div class="form-group">
                <label for="username">Password</label>
                <input type="text" name="password" class="form-control" placeholder="********">
            </div>
            <div class="form-group">
                <label for="avatar">Foto Profil</label>
                <input type="file" name="avatar" class="form-control" accept="image/*">
            </div>
            <button type="submit" class="btn btn-sm btn-primary mt-1">
                Update Profil
            </button>
        </form>
    </div>
</div>
@endsection

@push('css')
@endpush
