@extends('layouts.master')

@section('title')
    Stok Opname Obat
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Stok Opname Obat</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header">

                <form action="{{route('so.index')}}" method="GET">
                    <div class="row">

                        <div class="form-group">
                            <label for="" class="col-lg-1 col-lg-offset-1 control-label">Pilih Lokasi</label>
                <div class="col-lg-5">
                  
                    <select id="" class="form-control select2" name="lokasi_id">
                        <option value="semua">Tampilkan Semua</option>
                        @foreach ($lokasi as $item)
                           
                        <option  value="{{$item->id}}" {{ ($id_lok == $item->id ? "selected":"") }}>{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-2">
                <button type="submit" class="btn btn-success"><i class="fa fa-search" aria-hidden="true"></i> </button>
            </div>
            <div class="col-lg-2">
                <a href="{{ route('so.export') }}" target="_blank" class="btn btn-success btn-xs btn-flat"><i class="fa fa-file-excel-o"></i> Export PDF</a>

            </div>
        </div>
            
        </form>
    </div>
    <div class="box-body">
        <table class="table table-striped table-bordered table-stok">
            <thead>
                <tr>
                <th>no</th>
                <th>Kode Obat</th>
                <th>Lokasi</th>
                <th>Nama Obat</th>
                <th>Satuan</th>
                <th>Stok Sistem</th>
                <th>Stok Fisik</th>   
                </tr>
            </thead>
           
        </table>
    </div>
            </div>
            
        </div>
    </div>

    @endsection

    @push('scripts')
<script>
    $(function () {
        $('.select2').select2({
    });
    });

    $(function () {
        table = $('.table-produk').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: true,
            ajax: {
                url: '#',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                // {data: 'aksi', searchable: false, sortable: false},
                {data: 'kode_obat'},
                {data: 'lokasi'},
                {data: 'nama_obat'},
                {data: 'satuan'},
                {data: 'stock'},
                {data: 'stokSO'},
               
            ]
        });
        });

        $(document).on('input', '.stok', function () {
            let id = $(this).data('id');
            let stok = parseInt($(this).val());


            $.post(`{{ url('/so/create') }}/${id}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'put',
                    'stok': stok
                })
                .done(response => {
                    // $(this).on('mouseout', function () {
                        // table.ajax.reload();
                    // });
                })
                
        });
</script>

@endpush