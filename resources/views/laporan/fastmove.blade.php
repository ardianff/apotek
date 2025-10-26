@extends('layouts.master')

@section('title')
    Laporan Rangking Penjualan Obat {{ tanggal_indonesia($tanggalAwal, false) }} s/d {{ tanggal_indonesia($tanggalAkhir, false) }}
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('/AdminLTE-2/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endpush

@section('breadcrumb')
    @parent
    <li class="active">Laporan fastmoving</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="updatePeriode()" class="btn btn-info btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Ubah Periode</button>
                <a href="{{ route('export.fastmove', [$tanggalAwal, $tanggalAkhir,$limit]) }}" target="_blank" class="btn btn-success btn-xs btn-flat"><i class="fa fa-file-excel-o"></i> Export PDF</a>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered">
                    <thead>
                        <th width="5%">No</th>
                        <th>Kode Obat</th>
                        <th>Obat</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual </th>
                        <th>Total Penjualan </th>
                        {{-- <th>Pendapatan</th> --}}
                    </thead>
                    <tbody>
                        @foreach ($detil as $item)
                            
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$item->prodk->kode_obat??0}}</td>
                            <td>{{$item->prodk->nama_obat??0}}</td>
                            <td>{{$item->prodk->harga_beli??0}}</td>
                            <td>{{$item->prodk->harga_jual??0}}</td>
                            <td>{{$item->total}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('laporan.fasmove') }}" method="get" data-toggle="validator" class="form-horizontal">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Periode Laporan</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="tanggal_awal" class="col-lg-2 col-lg-offset-1 control-label">Tanggal Awal</label>
                        <div class="col-lg-6">
                            <input type="text" name="tanggal_awal" id="tanggal_awal" class="form-control datepicker" required autofocus
                                value="{{ request('tanggal_awal') }}"
                                style="border-radius: 0 !important;">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tanggal_akhir" class="col-lg-2 col-lg-offset-1 control-label">Tanggal Akhir</label>
                        <div class="col-lg-6">
                            <input type="text" name="tanggal_akhir" id="tanggal_akhir" class="form-control datepicker" required
                                value="{{ request('tanggal_akhir') ?? date('Y-m-d') }}"
                                style="border-radius: 0 !important;">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="limit" class="col-lg-2 col-lg-offset-1 control-label">Jumlah Teratas</label>
                        <div class="col-lg-6">
                        <input id="limit" class="form-control" type="number" name="limit" >
                    </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-flat btn-primary"><i class="fa fa-save"></i> Simpan</button>
                    <button type="button" class="btn btn-sm btn-flat btn-warning" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('/AdminLTE-2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script>
    // let table;

    // $(function () {
    //     table = $('.table').DataTable({
    //         responsive: true,
    //         processing: true,
    //         serverSide: true,
    //         autoWidth: false,
    //         ajax: {
    //             url: '{{ route('laporan.data', [$tanggalAwal, $tanggalAkhir]) }}',
    //         },
    //         columns: [
    //             {data: 'DT_RowIndex', searchable: false, sortable: false},
    //             {data: 'tanggal'},
    //             {data: 'penjualan'},
    //             {data: 'pembelian'},
    //             {data: 'pengeluaran'},
    //             // {data: 'pendapatan'}
    //         ],
    //         dom: 'Brt',
    //         bSort: false,
    //         bPaginate: false,
    //     });

// });
 $('.table').DataTable();
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });

    function updatePeriode() {
        $('#modal-form').modal('show');
    }
</script>
@endpush