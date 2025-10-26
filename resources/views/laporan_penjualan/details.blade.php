@extends('layouts.master')

@section('title')
    Daftar Penjualan
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Penjualan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="updatePeriode()" class="btn btn-info btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Ubah Periode</button>
                <a href="{{ route('export.penjualan',[$tanggalAwal,$tanggalAkhir]) }}" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Excel </a>
                     <a href="{{ route('export.penjualanproduk',[$tanggalAwal,$tanggalAkhir]) }}" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export penjualan produk </a>
                <h4>Periode {{tanggal_indonesia($tanggalAwal)}} - {{tanggal_indonesia($tanggalAkhir)}}</h4>
            </div>
            <div class="box-body table-responsive">
                @foreach ($penj as $hey=>  $item)
        <div class="box box-primary">
            <div class="box-body">
                
           
                <table class="table table-striped table-bordered table-penjualan ">

                    <thead>
                        <tr style="background-color: rgb(142, 142, 143);color:rgb(0, 0, 0);" >
                        
                        <th width="5%" >No</th>
                        <th>Tanggal</th>
                        <th>No Nota</th>
                        <th>Petugas</th>
                        <th>Shift</th>
                        <th>Metode</th>
                        <th>total harga</th>
                        <th>Diskon</th>
                        <th>total Bayar</th>
                        
                    </tr>
                    </thead>
                    <tbody>
                            
                        <tr >
                            <td>{{$loop->iteration}}</td>
                            <td>{{tanggal_indonesia($item->created_at)}}</td>
                            <td>{{$item->no_nota}}</td>
                            <td>{{$item->user->name}}</td>
                            <td>{{$item->shift->nama_shift}}</td>
                            <td>{{$item->metod->metode ?? 0}}</td>
                            <td>Rp. {{format_uang($item->total_harga)}}</td>
                            <td>{{$item->diskon}}</td>
                            <td>Rp. {{format_uang($item->bayar)}}</td>
                           
                        </tr>
                       
                        <tr style="background-color: rgb(122, 121, 121);color:rgb(0, 0, 0);">
                            <td></td>
                            <td><b>No</b></td>
                            <td><b>Nama Obat</b></td>
                            <td><b>Satuan</b></td>
                            <td><b>Harga</b></td>
                            <td><b>Jumlah</b></td>
                            <td><b>Subtotal</b></td>
                            <td></td>
                            <td></td>
                        </tr>
                            
                        @foreach ($detail[$hey] as  $putih)
                            
                        <tr style="background-color: rgb(231, 244, 248);color:rgb(0, 0, 0);">
                            <td></td>
                            <td>
                                {{chr(64+ $loop->iteration)}} 
                            </td>
                            <td>
                                {{$putih->prodk->nama_obat ?? 0}}
                        </td>
                            <td>
                                {{$putih->prodk->satuan ?? 0}}
                        </td>
                            <td>
                               Rp. {{format_uang($putih->harga_jual??0)}}
                        </td>
                            <td>
                                {{$putih->jumlah??0}}
                        </td>
                            <td>
                              Rp.  {{format_uang($putih->subtotal??0)}}
                        </td>
                        <td></td>
                        <td></td>
                    </tr>

                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                           
                        
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
                @endforeach
                <div class="row">
                    <div class="col-lg-10"><h4 style="float: right;"><b >TOTAL :</b></h4></div>
                    <div class="col-lg-2"><h4><b>Rp {{format_uang($totali)}}</b></h4></div>
                </div>
               
            </div>
        </div>
    </div>
</div>

@includeIf('laporan_penjualan.form')
@endsection

@push('scripts')
<script>
    $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
   function updatePeriode() {
        $('#modal-form').modal('show');
    }
   
</script>
@endpush