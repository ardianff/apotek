@extends('layouts.master')

@section('title')
    Daftar Pembelian
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Pembelian</li>
@endsection

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            Input Pembelian
        </div>
        <div class="card-body">
            <form id="form-pembelian">
                <div class="form-group">
                    <label for="supplier_id">Supplier</label>
                    <select id="supplier_id" class="form-control">
                        @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="no_faktur">No Faktur</label>
                    <input type="text" id="no_faktur" class="form-control">
                </div>
                <div class="form-group">
                    <label for="tgl_faktur">Tanggal Faktur</label>
                    <input type="date" id="tgl_faktur" class="form-control">
                </div>
                <button type="button" id="btn-simpan-pembelian" class="btn btn-primary">Simpan</button>
            </form>
            <hr>
            <h5>Detail Pembelian</h5>
            <form id="form-detail">
                <div class="form-group">
                    <label for="produk_id">Produk</label>
                    <select id="produk_id" class="form-control select2">
                        @foreach ($produk as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="button" id="btn-tambah-produk" class="btn btn-success">Tambah</button>
            </form>
            <table class="table mt-3" id="table-detail">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Isi</th>
                        <th>HB Grosir</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">
        <h5>Ringkasan Pembelian</h5>
        <table class="table">
            <tr>
                <th>Total Item</th>
                <td id="total-item">0</td>
            </tr>
            <tr>
                <th>Total Subtotal</th>
                <td id="total-subtotal">0</td>
            </tr>
            <tr>
                <th>Diskon (%)</th>
                <td><input type="number" id="diskon-pembelian" class="form-control" value="0"></td>
            </tr>
            <tr>
                <th>Potongan</th>
                <td><input type="number" id="potongan-pembelian" class="form-control" value="0"></td>
            </tr>
            <tr>
                <th>Total Bayar</th>
                <td id="total-bayar">0</td>
            </tr>
        </table>
    </div>
    
</div>

<script>
$(document).ready(function () {
    let pembelianId = null;

    // Simpan pembelian
    $('#btn-simpan-pembelian').on('click', function () {
        const data = {
            supplier_id: $('#supplier_id').val(),
            no_faktur: $('#no_faktur').val(),
            tgl_faktur: $('#tgl_faktur').val(),
        };

        $.post('{{ route("pembelian.store") }}', data, function (response) {
            pembelianId = response.data.id;
            alert('Pembelian berhasil disimpan.');
        });
    });

    // Tambah detail pembelian
    $('#btn-tambah-produk').on('click', function () {
        if (!pembelianId) {
            alert('Simpan pembelian terlebih dahulu.');
            return;
        }

        const data = {
            pembelian_id: pembelianId,
            produk_id: $('#produk_id').val(),
        };

        $.post('{{ route("pembelian-detail.store") }}', data, function (response) {
            const detail = response.data;
            $('#table-detail tbody').append(`
                <tr data-id="${detail.id}">
                    <td>${detail.produk_id}</td>
                    <td><input type="number" class="form-control jumlah" value="${detail.jumlah}"></td>
                    <td><input type="number" class="form-control isi" value="${detail.isi}"></td>
                    <td><input type="number" class="form-control hb_grosir" value="${detail.hb_grosir}"></td>
                    <td class="subtotal">${detail.subtotal}</td>
                </tr>
            `);
        });
    });

    // Update detail pembelian
    $('#table-detail').on('input', '.jumlah, .isi, .hb_grosir', function () {
        const row = $(this).closest('tr');
        const id = row.data('id');
        const jumlah = row.find('.jumlah').val();
        const isi = row.find('.isi').val();
        const hb_grosir = row.find('.hb_grosir').val();

        $.post('{{ route("pembelian-detail.update") }}', {
            id, jumlah, isi, hb_grosir, ppn: 10 // Contoh PPN 10%
        }, function (response) {
            row.find('.subtotal').text(response.data.subtotal);
        });
    });
});

function updateSummary() {
    if (!pembelianId) return;

    const diskon = $('#diskon-pembelian').val();
    const potongan = $('#potongan-pembelian').val();

    $.post('{{ route("pembelian-detail.update") }}', {
        pembelian_id: pembelianId,
        diskon,
        potongan,
    }, function (response) {
        $('#total-item').text(response.data.total_item);
        $('#total-subtotal').text(response.data.total_subtotal);
        $('#total-bayar').text(response.data.total_bayar);
    });
}

// Update diskon atau potongan
$('#diskon-pembelian, #potongan-pembelian').on('input', function () {
    updateSummary();
});

</script>
@endsection