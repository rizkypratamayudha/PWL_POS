<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
                @empty($penjualan)
                    Kesalahan
                @else
                    Detail Data Penjualan
                @endempty
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            @empty($penjualan)
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan.
                </div>
                <a href="{{ url('/penjualan') }}" class="btn btn-warning">Kembali</a>
            @else
                <div class="alert alert-info">
                    <h5><i class="icon fas fa-info"></i> Detail Informasi</h5>
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

                <div class="alert alert-info">
                    <h5><i class="icon fas fa-info"></i> Detail Informasi Barang</h5>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Harga Satuan</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    @php
                        $totalHarga = 0; // Initialize total price
                    @endphp
                    @foreach ($detailpenjualan as $detail)
                        @php
                            $totalHarga += $detail->harga; // Add each item price to the total
                        @endphp
                        <tbody>
                            <tr>
                                <td class="col-9">{{ $detail->barang->barang_nama }}</td>
                                <td class="col-9">{{ $detail->barang->harga_jual }}</td>
                                <td class="col-9">{{ $detail->jumlah }}</td>
                                <td class="col-9">{{ $detail->harga }}</td>
                            </tr>
                        </tbody>
                    @endforeach
                </table>

                <!-- Display total harga -->
                <div class="text-right">
                    <strong>Total Harga: Rp</strong>
                    <span>{{ number_format($totalHarga, 2) }}</span>
                </div>
            @endempty
        </div>
    </div>
</div>
