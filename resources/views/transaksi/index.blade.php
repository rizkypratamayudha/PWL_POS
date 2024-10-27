@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{url('penjualan/import')}}')" class="btn btn-sm btn-info my-1">Import penjualan</button>
                <a href="{{url('/penjualan/export_excel')}}" class="btn btn-sm btn-primary mt-1"><i class="fa fa-file-excel"></i> Export penjualan</a>
                <a href="{{url('/penjualan/export_pdf')}}" class="btn btn-sm btn-warning mt-1"><i class="fa fa-file-pdf"></i> Export penjualan</a>
                <button onclick="modalAction('{{ url('penjualan/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah
                    Ajax</button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Filter:</label>
                        <div class="col-3">
                            <select class="form-control" id="user_id" name="user_id" required>
                                <option value="">- Semua -</option>
                                @foreach ($user as $item)
                                    <option value="{{ $item->user_id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Nama Pengguna</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover table-sm" id="table_penjualan">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Pengguna</th>
                            <th>Nama Pembeli</th>
                            <th>Penjualan Kode</th>
                            <th>Penjualan Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
@endpush

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }
        var datapenjualan;
        $(document).ready(function() {
            datapenjualan = $('#table_penjualan').DataTable({
                // serverSide: true, jika ingin menggunakan server side proses
                serverSide: true,
                ajax: {
                    "url": "{{ url('penjualan/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.user_id = $('#user_id').val();
                    }
                },
                columns: [{
                    data: "DT_RowIndex",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }, {
                    data: "user.nama",
                    className: "",
                    orderable: true,
                    searchable: true,
                }, {
                    data: "pembeli",
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "penjualan_kode",
                    className: "",
                    orderable: false,
                    searchable: true,
                },
                {
                    data: "penjualan_tanggal",
                    className: "",
                    orderable: true,
                    searchable: false
                },
                {
                    data: "aksi",
                    className: "",
                    orderable: false,
                    searchable: false
                }]
            });
            $('#user_id').on('change', function() {
                datapenjualan.ajax.reload();
            });
        });
    </script>
@endpush
