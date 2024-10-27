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

            <div class="row">


                <div class="col-md-7 ">
                    <form action="{{ url('/profile/update') }}" method="POST" id="formUpdate">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="username">Level Pengguna</label>
                            <input type="hidden" name="level_id" class="form-control"
                                value="{{ Auth::user()->level->level_id }}" readonly>
                            <input type="text" name="level_nama" class="form-control"
                                value="{{ Auth::user()->level->level_nama }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username" class="form-control"
                                value="{{ Auth::user()->username }}">
                        </div>
                        <div class="form-group">
                            <label for="username">Nama</label>
                            <input type="text" name="nama" id="nama" class="form-control"
                                value="{{ Auth::user()->nama }}">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="********">
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary mt-1">
                            Update Informasi
                        </button>
                    </form>
                </div>
                <div class="col-md-5 text-center mt-5">
                    <div class="profile-info">
                        <div>
                            <img src="{{ Auth::user()->avatar ? asset('images/' . Auth::user()->avatar) : asset('user.png') }}"
                                alt="user avatar"
                                style="width: 120px; height: 120px; object-fit: cover; border-radius: 50%;">
                        </div>
                    </div>
                    <form action="{{ url('/profile/update-avatar') }}" method="POST" enctype="multipart/form-data"
                        class="mt-3">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="avatar">{{ Auth::user()->nama }}</label>
                            <input type="file" name="avatar"
                                class="form-control form-control-sm file-input-small mx-auto" accept="image/*" required>
                        </div>
                        <button type="submit" class="btn btn-sm btn-success mt-1">
                            Update Foto
                        </button>
                        @if (auth()->user()->avatar != null)
                            <a href="{{ url('/profile/delete') }}" class="btn btn-sm btn-danger mt-1"
                                onclick="event.preventDefault();
                        document.getElementById('delete-avatar').submit()
                        ">Hapus
                                Foto</a>
                        @endif
                    </form>
                    <form action="{{ url('/profile/delete') }}" id="delete-avatar" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        /* Mengecilkan ukuran input file */
        .file-input-small {
            width: 50%;
            /* Ubah ini sesuai kebutuhan */
            padding: 3px;
            font-size: 12px;
            /* Sesuaikan ukuran font */
        }
    </style>
@endpush
@push('js')
    <script>
        $(document).ready(function() {
            dataProfil = $("#formUpdate").validate({
                rules: {
                    level_id: {
                        required: true,
                        number: true
                    },
                    username: {
                        required: false,
                        minlength: 3,

                    },
                    nama: {
                        required: false,
                        minlength: 3,
                        maxlength: 100,

                    },
                    password: {
                        required: false,

                    }
                },
                submitHandler: function(form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function(response) {
                            if (response.status) {
                                $('#myModal').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });
                                dataPtok.ajax.reload();
                            } else {
                                $('.error-text').text('');
                                $.each(response.msgField, function(prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
                                });
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: response.message
                                });
                            }
                        }
                    });
                    return false;
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
    </script>
@endpush
