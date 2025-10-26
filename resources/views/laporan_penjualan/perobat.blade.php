@extends('layouts.master')

@section('title')
    Daftar Penjualan
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Penjualan Per Obat</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="updatePeriode()" class="btn btn-info btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Ubah Periode</button>
              
                <a href="{{ route('export.penjualanproduk',[$tanggalAwal,$tanggalAkhir]) }}" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export penjualan produk </a>
                <h4>Periode {{tanggal_indonesia($tanggalAwal)}} - {{tanggal_indonesia($tanggalAkhir)}}</h4>
            </div>
        <div class="box-body table-responsive">
            <div class="box box-primary">
                <div class="box-body">
                    
                    <table class="table table-striped table-bordered table-hoverred table-perobat ">
                        
                        <thead>
                            <tr  >
                            
                            <th width="5%" >No</th>
                            <th>kode obat</th>
                            <th>nama obat</th>
                            <th>satuan</th>
                            <th>isi</th>
                            <th>harga Beli</th>
                            <th>Margin</th>
                            <th>harga Jual</th>
                            <th>Jumlah Penjualan</th>
                            <th width="10em" >Total Penjualan</th>
                            <th width="10em">Total Modal</th>
                            <th width="10em">Total Laba</th>
                           
                            
                        </tr>
                        </thead>
                       
            </table>
        </div>
    </div>
            <div class="row">
                <div class="col-lg-10"><h4 style="float: right;"><b >TOTAL :</b></h4></div>
                <div class="col-lg-2"><h4><b>Rp {{format_uang($totali)}}</b></h4></div>
            </div>
            
        </div>
        </div>
    </div>
</div>

@includeIf('laporan_penjualan.formperobat')
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
   
$(function () {
    table = $('.table-perobat').DataTable({
        responsive: true,
        // processing: true,
        // serverSide: true,
        autoWidth: true,
        ajax: {
            url: '{{ route('penjualanPerobat.data',[$tanggalAwal, $tanggalAkhir]) }}',
        },
        columns: [
            {data: 'DT_RowIndex', searchable: false, sortable: false},
            {data: 'kode_obat'},
            {data: 'nama_obat'},
            {data: 'satuan'},
            {data: 'isi'},
            {data: 'harga_beli'},
            {data: 'margin'},
            {data: 'harga_jual'},
            {data: 'jumlah'},
            {data: 'total_penjualan'},
            {data: 'total_modal'},
            {data: 'total_laba'},
        ]
   
    })
    });
</script>
@endpush