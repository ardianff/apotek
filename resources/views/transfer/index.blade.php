@extends('layouts.master')

@section('title')
    Transfer Obat
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Transfer Obat</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="addForm()" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Transfer Baru</button>
                @empty(! session('trasnfer'))
                <a href="{{ route('transfer_detail.index') }}" class="btn btn-info btn-xs btn-flat"><i class="fa fa-pencil"></i> Transfer Aktif</a>
                @endempty
            </div>
            <div class="box-body ">
            
                    <table class="table table-transfer table-stiped table-bordered">
                        <thead>
                            
                            <th width="5%">No</th>
                            <th>Tanggal</th>
                            <th>Ke Cabang</th>
                            <th>Total Item</th>
                            <th>Keterangan</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                       
                    </table>
            </div>
        </div>
    </div>
</div>

@includeIf('transfer.cabang')
@includeIf('transfer.detail')
@endsection



@push('scripts')
<script>
    let table;

    $(function () {
        table = $('.table-transfer').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('transfer.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'tanggal'},
                {data: 'nama_cabang'},
                {data: 'total_item'},
                {data: 'ket'},
                {data: 'aksi', searchable: false, sortable: false}

               
            ]
        });
        table1 = $('.table-detail').DataTable({
            processing: true,
            bSort: false,
            dom: 'Brt',
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_obat'},
                {data: 'nama_obat'},
                {data: 'satuan'},
                {data: 'jumlah'},
                {data: 'ed'},
                {data: 'batch'},
            ]
        })
      
    });

    function addForm() {
        $('#modal-cabang').modal('show');
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