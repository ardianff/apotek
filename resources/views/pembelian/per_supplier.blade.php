@extends('layouts.master')

@section('title')
    Daftar Pembelian Per Supplier
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Pembelian Per Supplier</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            @if (\Session::has('iso'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                <strong>{{Session::get('iso')}}</strong> 
            </div>
    @endif
            @if (\Session::has('ga_iso'))
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                <strong>{{Session::get('ga_iso')}}</strong> 
            </div>
    @endif
            <div class="box-header with-border">
                <button onclick="addForm()" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Transaksi Baru</button>
                @empty(! session('id_pembelian'))
                <a href="{{ route('pembelian_detail.index') }}" class="btn btn-info btn-xs btn-flat"><i class="fa fa-pencil"></i> Transaksi Aktif</a>
                @endempty
            </div>
            <div class="box-body table-responsive">
                <table class="table table-striped table-bordered table-pembelian">
                    <thead>
                        <th width="">No</th>
                        <th width=""><i class="fa fa-cog"></i></th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>No Refrensi</th>
                        <th>Nomer Faktur</th>
                        <th>Tanggal Faktur</th>
                        <th>Tempo Pembayaran</th>
                        <th>Supplier</th>
                        <th>Total Item</th>
                        <th>Total Harga</th>
                        <th>Diskon</th>
                        <th>potongan</th>
                        <th>Total Bayar</th>
                        <th>Tanggal Pelunasan</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('pembelian.supplier')
@includeIf('pembelian.detail')
@includeIf('supplier.form')
@includeIf('pembelian.lunas')

@endsection

@push('scripts')
<script>
      $('.datepicker').datepicker({
        //    format: 'dd-mm-yyyy',
        //    startDate: '1d',
           autoclose: true
       });
    let table, table1;

    $(function () {
        table = $('.table-pembelian').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: true,
            ajax: {
                url: '{{ route('pembelian.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'aksi', searchable: false, sortable: false},
                {data: 'status'},
                {data: 'tanggal'},
                {data: 'no_ref'},
                {data: 'no_faktur'},
                {data: 'tgl_faktur'},
                {data: 'tempo'},
                {data: 'supplier'},
                {data: 'total_item'},
                {data: 'total_harga'},
                {data: 'diskon'},
                {data: 'potongan'},
                {data: 'bayar'},
                {data: 'tgl_pelunasan'},
            ]
        });

        $('.table-supplier').DataTable();
        
        table1 = $('.table-detail').DataTable({
            processing: true,
            bSort: false,
            dom: 'Brt',
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_obat'},
                {data: 'nama_obat'},
                {data: 'harga_beli'},
                {data: 'jumlah'},
                {data: 'subtotal'},
            ]
        })
    });
    function addSupplier(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Supplier');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=nama]').focus();
    }
    function pelunasan(id) {
        $('#modal-lunas').modal('show');
        $('#modal-lunas .modal-title').text('Pelunasan Pembelian');

        $('#modal-lunas [name=id]').val(id);
    }
    function addForm() {
        $('#modal-supplier').modal('show');
    }

    function showDetail(url) {
        $('#modal-detail').modal('show');

        table1.ajax.url(url);
        table1.ajax.reload();
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