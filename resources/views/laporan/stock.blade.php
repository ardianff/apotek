@extends('layouts.master')

@section('title')
    Daftar Produk
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Produk</li>
@endsection

@section('content')
@if (Session::has('success'))
<div class="alert alert-success" role="alert">
    {!! \Session::get('success') !!}
  </div>
@endif
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
               <div class="row">
                <div class="col-md-1">
                   
                </div>
                <form action="{{route('laporan.stock')}}" method="get">
                <div class="col-md-3">
                        <div class="input-group row">
                            <label for="cabang">Pilih Cabang</label>
                            <select id="cabang" class="form-control" name="cabang" id="cabang">
                                <option  value="0">Tampilkan Semua</option>

                                @foreach ($cabang as $item)
                                
                                <option  value="{{$item->id}}">{{$item->nama_cabang}}</option>
                                @endforeach
                            </select>
                        </div>
                  

                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary text-center btn-sm"> <i class="fa fa-search fa-sm    "></i> tampilkan</button>
                    <a href="{{ route('export.stock',$id) }}" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Excel</a>
                </div>
            </form>

               </div>
                   
                   
                   
            </div>
            <div class="box-body table-responsive">
                
                    <table  class="table table-produk table-stiped table-bordered" >
                        <thead>
                          
                            <th width="5%">No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>stok</th>
                            <th>Harga Beli</th>
                            <th>Nilai</th>
                            <th >cabang</th>
                            
                        </thead>
                       
                    </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->

@endsection

@push('scripts')
<script>
    
    
    let table;

$(function () {
    table = $('.table-produk').DataTable({
        responsive: true,
        // processing: true,
        // serverSide: true,
        autoWidth: true,
        ajax: {
            url: '{{ route('laporan.datastock',$id) }}',
        },
        columns: [
            {data: 'DT_RowIndex', searchable: false, sortable: false},
            {data: 'kode_obat'},
            {data: 'nama_obat'},
            {data: 'stock'},
            {data: 'harga_beli'},
            {data: 'nilai'},
            {data: 'cabang'}
           
        ]
   
    });
    });
    
       

  
</script>
@endpush