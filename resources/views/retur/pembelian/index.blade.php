@extends('layouts.master')

@section('title')
    Daftar retur Pembelian
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar retur Pembelian</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <div class="row">
                    <div class="col-lg-6">                   
                <table>
                    <tr>
                        <td>Supplier</td>
                        <td>: {{ $retur_pembelian->supplier->nama_supplier ??0}}</td>
                    </tr>
                    <tr>
                        <td>Telepon</td>
                        <td>: {{ $retur_pembelian->supplier->telepon }}</td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>: {{ $retur_pembelian->supplier->alamat }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-lg-6">
                <table>
                    <tr>
                        <td>no faktur</td>
                        <td>: {{ $retur_pembelian->no_faktur }}</td>
                    </tr>
                    <tr>
                        <td>tgl faktur</td>
                        <td>: {{ $retur_pembelian->pembelian->tgl_faktur }}</td>
                    </tr>
                   
                </table>
            </div>
        </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-stripped table-bordered table-retur">
                    <thead>
                        <tr>

                            <th width="5%">No</th>
                            <th>kd obat</th>
                        <th>Nama Obat</th>
                        <th>Batch</th>
                        <th>ED</th>
                        <th>Harga Beli</th>
                        <th>diskon</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                        <th><span class="fa fa-cog"></span></th>
                        
                    </tr>
                    </thead>
                   
                </table>
            </div>
            <div class="row mt-3">
                <div class="col-md-1 col-md-offset-1">
                    <a href="{{route('retur_pembelian.cancel',$retur_pembelian->id)}}" class="btn btn-warning"> Batal <i class="fa fa-arrow-left fa-xs"></i></a>
    
                   </div>
                <div class="col-md-1"></div>

                <div class="col-md-3">
                     <h3>Total Item : {{ $retur_pembelian->total_item }}</h3>
                </div>
                <div class="col-md-4">
                    <h3>Total Harga : Rp {{ format_uang($retur_pembelian->total_harga) }}</h3>
                </div>
               <div class="col-md-1 mt-2">
                <a href="{{route('retur_pembelian.store',$retur_pembelian->id)}}" class="btn btn-success"> simpan <i class="fa fa-save fa-xs"></i></a>
               </div>
              
            </div>
        </div>
    </div>
</div>

@includeIf('retur.pembelian.form')

@endsection

@push('scripts')
<script>
   let table, table1;

$(function () {
    table = $('.table-retur').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        autoWidth: true,
        ajax: {
            url: '{{ route('retur_pembelian.data', $retur_pembelian->id) }}',
        },
        columns: [
            {data: 'DT_RowIndex', searchable: false, sortable: false},
            {data: 'kd_obat'},
            {data: 'nama_obat'},
            {data: 'batch'},
            {data: 'ed'},
            {data: 'harga_beli'},
            {data: 'diskon'},
            {data: 'jumlah'},
            {data: 'subtotal'},
           
            {data: 'aksi', searchable: false, sortable: false},
        ]
    });
    });
  
  function edit(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('retur jumlah obatt');

        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $.get(url)
            .done((response) => {
                $('#modal-form [name=nama_obat]').val(response.produk['nama_obat']);
                $('#modal-form [name=batch]').val(response.detailobat['batch']);
                $('#modal-form [name=ed]').val(response.detailobat['ed']);
                $('#modal-form [name=harga_beli]').val(response.harga_beli);
                $('#modal-form [name=jumlah]').val(response.jumlah);
                $('#modal-form [name=jumlah_retur]').val(response.jumlah_retur);
                table.ajax.reload();


            })
            .fail((errors) => {
                alert('Tidak dapat menampilkan data');
                return;
            });
        }

    function deleteData(url) {
        if (confirm('Yakin ingin menghapus data terpilih?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload();
                })
                .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    return;
                });
        }
    }
</script>
@endpush