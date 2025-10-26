@extends('layouts.master')

@section('title')
    Dashboard
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Dashboard</li>
@endsection

@section('content')
<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>{{ $supplier }}</h3>

                <p>Total Supplier</p>
            </div>
            <div class="icon">
                <i class="fa fa-cube"></i>
            </div>
            <a href="{{ route('supplier.index') }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
             <h3>{{ $produk }}</h3> 

                <p>Total Obat</p> 
            </div>
            <div class="icon">
                <i class="fa fa-cubes"></i>
            </div>
            <a href="{{ route('produk.index') }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>{{ $exp }}</h3>

                <p>Obat Akan ED</p>
            </div>
            <div class="icon">
                <i class="fa fa-id-card"></i>
            </div>
            <a href="#exp" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3>{{ $stokout }}</h3>

                <p>Obat Akan Habis</p>
            </div>
            <div class="icon">
                <i class="fa fa-truck"></i>
            </div>
            <a href="#stokmin" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
</div>
<!-- /.row -->
<!-- Main row -->
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Grafik Omset Penjualan  {{ tanggal_indonesia($tanggal_awal, false) }} s/d {{ tanggal_indonesia($tanggal_akhir, false) }}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="chart">
                            <!-- Sales Chart Canvas -->
                            <canvas id="salesChart" style="height: 180px;"></canvas>
                        </div>
                        <!-- /.chart-responsive -->
                    </div>
                </div>
                <!-- /.row -->
            </div>
        </div>
        <!-- /.box -->


    </div>
    <!-- /.col -->
</div>
<!-- /.row (main row) -->

{{-- table ed --}}
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary" id="exp">
            <div class="box-header">
               <h2> Daftar Obat akan expired</h2>
            </div>
            <div class="box-body table-responsive">
              
                    <table class="table table-stiped table-bordered" id="table-ed">
                        <thead>
                            
                            <th width="5%">No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                        
                            <th>stock</th>
                            <th>lokasi</th>
                            <th>expired date</th>
                            <th>batch</th>
                        </thead>
                    </table>
            </div>
        </div>
    </div>
</div>
{{-- table out stok --}}
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary" id="stokmin">
            <div class="box-header">
               <h2> Daftar Obat Stock Minim</h2>
            </div>
            <div class="box-body table-responsive">
              
                    <table class="table table-stiped table-bordered" id="table-stok">
                        <thead>
                            
                            <th width="5%">No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                        
                            <th>stock</th>
                        </thead>
                    </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- ChartJS -->
<script src="{{ asset('AdminLTE-2/bower_components/chart.js/Chart.js') }}"></script>
<script>
$(function() {
    // Get context with jQuery - using jQuery's .get() method.
    var salesChartCanvas = $('#salesChart').get(0).getContext('2d');
    // This will get the first returned node in the jQuery collection.
    var salesChart = new Chart(salesChartCanvas);

    var salesChartData = {
        labels: {{ json_encode($data_tanggal) }},
        datasets: [
            {
                label: 'Pendapatan',
                fillColor           : 'rgba(60,141,188,0.9)',
                strokeColor         : 'rgba(60,141,188,0.8)',
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(60,141,188,1)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(60,141,188,1)',
                data: {{ json_encode($data_pendapatan) }}
            }
        ]
    };

    var salesChartOptions = {
        pointDot : false,
        responsive : true
    };

    salesChart.Line(salesChartData, salesChartOptions);
});
</script>
<script>
    let table,table1;
      $(function () {
        table = $('#table-ed').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('ed.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_obat'},
                {data: 'nama_obat'},
                {data: 'stock'},
                {data: 'lokasi'},
                {data: 'ed'},
                {data: 'batch'},
            ]
        });
        table1 = $('#table-stok').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('stokout.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_obat'},
                {data: 'nama_obat'},
              
                {data: 'stock'},
              
            ]
        });
        $('.uang').mask('0.000.000.000.000', {reverse: true});

        });
</script>
@endpush