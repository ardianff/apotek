@extends('layouts.master')

@section('title')
    Daftar Supplier
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Supplier</li>
@endsection

@section('content')

<div class="row">
    <div class="col-lg-12">
       
        <div class="box">
         
            <div class="box-header with-border">
              
                <button onclick="addForm()" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Tambah Obat masuk</button>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered table-obat">
                    <thead>
                        <th width="5%">No</th>
                        <th>Nama Obat</th>
                        <th>kode Obat</th>
                        {{-- <th>Tanggal</th> --}}
                        {{-- <th>Sumber Dana</th> --}}
                        <th>jumlah</th>
                        {{-- <th>lokasi</th>
                        <th>batch</th>
                        <th>ed</th> --}}
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>

                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('detailObat.supplier')
@endsection

@push('scripts')
<script>
    let table,table1;

    $(function () {
        table = $('.table-obat').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('kartu_stock.stockin') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'nama_produk'},
                {data: 'produk_id'},
                // {data: 'nama_supplier'},
                {data: 'jumlah'},
                // {data: 'lokasi'},
                // {data: 'batch'},
                // {data: 'ed'},
            ]
        });

      $table1=  $('.table-supplier').DataTable();

       
    });

    function addForm() {
        $('#modal-supplier').modal('show');
        
    }

    
    
</script>
@endpush