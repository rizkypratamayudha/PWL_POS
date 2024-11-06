<form action="{{ url('/penjualan/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <!-- Penjualan Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Penjualan Body -->
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Pengguna</label>
                    <select name="user_id" id="user_id" class="form-control" required>
                        <option value="">- Pilih Pengguna -</option>
                        @foreach ($user as $l)
                            <option value="{{ $l->user_id }}">{{ $l->nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-user_id" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Pembeli</label>
                    <input type="text" name="pembeli" id="pembeli" class="form-control" required>
                    <small id="error-pembeli" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Penjualan Kode</label>
                    <input type="text" name="penjualan_kode" id="penjualan_kode" class="form-control" required>
                    <small id="error-penjualan_kode" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Penjualan Tanggal</label>
                    <input type="datetime-local" name="penjualan_tanggal" id="penjualan_tanggal" class="form-control"
                        required>
                    <small id="error-penjualan_tanggal" class="error-text form-text text-danger"></small>
                </div>
            </div>

            <!-- Detail Penjualan Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Detail Penjualan</h5>
            </div>

            <!-- Input for Number of Items -->
            <div class="modal-body">
                <div class="form-group">
                    <label>Jumlah Barang</label>
                    <input type="number" name="jumlah_barang" id="jumlah_barang" class="form-control" min="1"
                        required>
                    <small id="error-jumlah_barang" class="error-text form-text text-danger"></small>
                </div>
            </div>

            <!-- Detail Penjualan Body with Dynamic Table -->
            <div class="modal-body">
                <table class="table table-bordered" id="table-barang">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Gambar Barang</th>
                            <th>Harga Satuan</th>
                            <th>Stok</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody id="dynamic-inputs">
                    </tbody>
                </table>
                <div class="text-right mr-10">
                    <strong>Total Harga: </strong> <span id="total-harga">0</span>
                </div>
            </div>




            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        // Trigger input field generation based on jumlah_barang
        $('#jumlah_barang').on('input', function() {
            var count = $(this).val();
            generateInputFields(count);
        });

        // Function to generate dynamic input fields based on jumlah_barang
        function generateInputFields(count) {
            // Clear previous input rows
            $('#dynamic-inputs').empty();

            // Generate new input fields
            for (var i = 0; i < count; i++) {
                var row = `
                    <tr>
                        <td>
                            <select name="barang_id[]" class="form-control barang-select" required>
                                <option value="">- Pilih Barang -</option>
                                @foreach ($barang as $l)
                                    <option value="{{ $l->barang_id }}">{{ $l->barang_nama }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <img style="width: 50px;">
                        </td>
                        <td>
                            <input value="" type="number" name="harga_satuan[]" class="form-control harga-satuan" disabled>
                        </td>
                        <td>
                            <input value="" type="number" name="stok[]" class="form-control stok" disabled>
                        </td>
                        <td>
                            <input value="" type="number" name="jumlah[]" class="form-control jumlah" required>
                        </td>
                        <td>
                            <input value="" type="number" name="harga[]" class="form-control harga" required readonly>
                        </td>
                    </tr>
                `;
                $('#dynamic-inputs').append(row);
            }

            // Attach change event to new selects
            attachSelectEvent();
        }

        function attachSelectEvent() {
            // Change event for barang selection
            $('.barang-select').off('change').on('change', function() {
                var barangId = $(this).val();
                var hargaSatuanInput = $(this).closest('tr').find('input[name="harga_satuan[]"]');
                var stokInput = $(this).closest('tr').find('input[name="stok[]"]');
                var avatarImg = $(this).closest('tr').find('img');

                if (barangId) {
                    $.ajax({
                        url: 'penjualan/getHarga/' + barangId,
                        method: 'GET',
                        success: function(data) {
                            hargaSatuanInput.val(data.harga_jual);
                            calculateHarga($(this).closest(
                                'tr')); // Ensure this triggers calculation
                        }.bind(this), // Use .bind(this) to maintain context
                        error: function() {
                            hargaSatuanInput.val('');
                        }
                    });

                    $.ajax({
                        url: 'penjualan/getStok/' + barangId,
                        method: 'GET',
                        success: function(data) {
                            stokInput.val(data.stok_jumlah); // Masukkan stok ke input
                        },
                        error: function() {
                            stokInput.val('');
                        }
                    });
                    $.ajax({
                        url: 'penjualan/getAvatar/' + barangId,
                        method: 'GET',
                        success: function(data) {
                            avatarImg.attr('src', '{{ asset('images') }}/' + data.avatar);
                        },
                        error: function() {
                            avatarImg.attr('src',
                            '{{ asset("barang.png") }}');
                        }
                    });

                } else {
                    hargaSatuanInput.val('');
                    stokInput.val('');
                    avatarImg.attr('src', '{{ asset("barang.png") }}');
                }
            });

            // Input event for jumlah to calculate harga dynamically
            $('.jumlah').off('input').on('input', function() {
                calculateHarga($(this).closest('tr'));
                calculateTotalHarga();
            });

            // Input event for harga_satuan to calculate harga dynamically
            $('.harga-satuan').off('input').on('input', function() {
                calculateHarga($(this).closest('tr'));
                calculateTotalHarga();
            });
        }

        function calculateHarga(row) {
            var hargaSatuan = parseFloat(row.find('input[name="harga_satuan[]"]').val()) || 0;
            var jumlah = parseFloat(row.find('input[name="jumlah[]"]').val()) || 0;
            var harga = Math.floor(hargaSatuan * jumlah); // Use Math.floor to ensure no decimal places
            row.find('input[name="harga[]"]').val(harga); // Set harga without decimals
        }

        function calculateTotalHarga() {
            var totalHarga = 0;

            // Loop through each row to sum the prices
            $('input[name="harga[]"]').each(function() {
                var harga = parseFloat($(this).val()) || 0;
                totalHarga += harga;
            });

            // Update total harga in the div
            $('#total-harga').text(totalHarga);
        }

        // Call attachSelectEvent after generating inputs for the first time
        attachSelectEvent();

        // Form validation and submission
        $("#form-tambah").validate({
            rules: {
                user_id: {
                    required: true,
                    number: true
                },
                pembeli: {
                    required: true,
                    minlength: 3,
                    maxlength: 20
                },
                penjualan_kode: {
                    required: true,
                    minlength: 3,
                    maxlength: 100
                },
                penjualan_tanggal: {
                    required: true
                },
                jumlah_barang: {
                    required: true,
                    number: true,
                    min: 1
                },
                "barang_id[]": {
                    required: true,
                    number: true
                },
                "harga[]": {
                    required: true,
                    number: true
                },
                "jumlah[]": {
                    required: true,
                    number: true
                },
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
