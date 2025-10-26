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
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered table-penjualan">
                    <thead>
                        <th width="5%">No</th>
                        <th width="5%">Tanggal</th>
                        <th>Shift</th>
                        <th>Petugas</th>
                        <th>total penjualan</th>
                        <th>penjualan tunai</th>
                        <th>penjualan nontunai</th>
                        <th>pengeluaran</th>
                        <th>total saldo</th>
                        <th>status</th>
                        
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- @includeIf('penjualan.detail') --}}
@endsection

@push('scripts')
<script>
    let table, table1;

    $(function () {
        table = $('.table-penjualan').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('pershift.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'tanggal'},
                {data: 'shift'},
                {data: 'petugas'},
                {data: 'total_penjualan'},
                {data: 'tunai'},
                {data: 'nontunai'},
                {data: 'pengeluaran'},
                {data: 'saldo_akhir'},
                {data: 'status'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

        // table1 = $('.table-detail').DataTable({
        //     processing: true,
        //     bSort: false,
        //     dom: 'Brt',
        //     columns: [
        //         {data: 'DT_RowIndex', searchable: false, sortable: false},
        //         {data: 'kode_obat'},
        //         {data: 'nama_obat'},
        //         {data: 'harga_jual'},
        //         {data: 'jumlah'},
        //         {data: 'subtotal'},
        //     ]
        // })
    });

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