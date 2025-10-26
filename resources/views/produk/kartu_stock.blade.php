@extends('layouts.master')

@section('title')
    Kartu Stock
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Kartu Stock</li>
@endsection

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
             Kartu Stock 
             <h4>{{$produk->kode_obat}}|{{$produk->nama_obat}}{{$id}}</h4>
            </div>
            <div class="box-body">
               
                    <table class="table table-stock table-stripped table-bordered" >
                        <thead>
                           
                            <th width="5%">No</th>
                            <th>Tanggal</th>
                            <th>Stock Awal</th>
                            <th>Stock Out</th>
                            <th>Stock In</th>
                            <th>Stock Akhir</th>
                            <th>keterangan</th>
                            <th>User</th>
                            
                        </thead>
                        <tbody>
                        
                        </tbody>
                    </table>
                    <a href="{{route('produk.index')}}" class="btn btn-warning btn-sm" type="button"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Kembali</a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $('.uang').mask('0.000.000.000.000',{reverse:true});
    
   
    let table;

$(function () {
    table = $('.table-stock').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        autoWidth: false,
        ajax: {
            url: '{{ route('produk.data_kartu_stock',$id) }}',
        },
        columns: [
            {data: 'DT_RowIndex', searchable: false, sortable: false},
            {data: 'created_at'},
            {data: 'stock_awal'},
            {data: 'stock_out'},
            {data: 'stock_in'},
            {data: 'sisa'},
            {data: 'ket_stock'},
            {data: 'user_id'},
        ]
   
    });
    
       

});
</script>
@endpush