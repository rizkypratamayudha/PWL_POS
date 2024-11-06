@empty($penjualan)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" arialabel="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/penjualan') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/penjualan/' . $penjualan->penjualan_id . '/delete_ajax') }}" method="POST" id="form-delete">
        @csrf
        @method('DELETE')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Hapus Data penjualan</h5>
                    <button type="button" class="close" data-dismiss="modal" arialabel="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-ban"></i> Konfirmasi !!!</h5>
                        Apakah Anda ingin menghapus data seperti di bawah ini?
                    </div>
                    <table class="table table-sm table-bordered table-striped">
                        <tr>
                            <th class="text-right col-3">Penjualan ID:</th>
                            <td class="col-9">{{ $penjualan->penjualan_id }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Nama Pengguna :</th>
                            <td class="col-9">{{ $penjualan->user->nama }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Pembeli :</th>
                            <td class="col-9">{{ $penjualan->pembeli }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Penjualan kode :</th>
                            <td class="col-9">{{ $penjualan->penjualan_kode }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Penjualan Tanggal :</th>
                            <td class="col-9">{{ $penjualan->penjualan_tanggal }}</td>
                        </tr>
                    </table>
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-warning"></i> Data Informasi detail penjualan</h5>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <th>Gambar Barang</th>
                            <th>Nama Barang</th>
                            <th>Harga Satuan</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                        </thead>
                        @php
                            $totalHarga = 0;
                        @endphp
                        @foreach ($detailpenjualan as $detail)
                            @php
                                $totalHarga += $detail->harga;
                            @endphp
                        <tbody>
                            <tr>
                                <td class="col-4">
                                    <img src="{{ $detail->barang->avatar ? asset('images/' . $detail->barang->avatar) : asset('barang.png') }}" alt="gambar barang" style="width: 50px;">
                                </td>
                                <td class="col-9">{{ $detail->barang->barang_nama }}</td>
                                <td class="col-9">{{ $detail->barang->harga_jual }}</td>
                                <td class="col-9">{{ $detail->jumlah }}</td>
                                <td class="col-9">{{ $detail->harga }}</td>
                            </tr>

                        </tbody>
                    @endforeach
                    </table>
                    <div class="text-right">
                        <strong>Total Harga: Rp</strong>
                        <span>{{ number_format($totalHarga, 2) }}</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                    <button type="submit" class="btn btn-primary">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </form>
    <script>
        $(document).ready(function() {
            $("#form-delete").validate({
                rules: {},
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
                                datapenjualan.ajax.reload();
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
@endempty
