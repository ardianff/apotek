@extends('layouts.master')

@section('title')
    Daftar apoteker
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar apoteker</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="addForm('{{ route('apoteker.store') }}')" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Tambah</button>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered">
                    <thead>
                        <th width="5%">No</th>
                        <th>Nama</th>
                        <th>No.SIPA/SIA</th>
                        <th>No.STRA</th>
                        <th>Jabatan</th>
                        <th>Mulai Tugas Tanggal</th>
                        <th>Status</th>
                        <th>Alamat</th>
                        <th>No HP</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('apoteker.form')
@endsection

@push('scripts')
<script>
    let table;

    $(function () {
        table = $('.table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('apoteker.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'nama'},
                {data: 'no_sipa'},
                {data: 'no_stra'},
                {data: 'jabatan'},
                {data: 'mulai_tgs'},
                {data: 'stt'},
                {data: 'alamat'},
                {data: 'tlpn'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

        $('#modal-form').validator().on('submit', function (e) {
            if (! e.preventDefault()) {
                $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                    .done((response) => {
                        $('#modal-form').modal('hide');
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menyimpan data');
                        return;
                    });
            }
        });
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah apoteker');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=nama]').focus();
        $('#modal-form [name=no_sipa]').focus();
        $('#modal-form [name=no_stra]').focus();
        $('#modal-form [name=jabatan]').focus();
        $('#modal-form [name=mulai_tgs]').focus();
        $('#modal-form [name=stt]').focus();
        $('#modal-form [name=alamat]').focus();
        $('#modal-form [name=tlpn]').focus();
    }

    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit apoteker');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=nama]').focus();
        $('#modal-form [name=no_sipa]').focus();
        $('#modal-form [name=no_stra]').focus();
        $('#modal-form [name=jabatan]').focus();
        $('#modal-form [name=mulai_tgs]').focus();
        $('#modal-form [name=stt]').focus();
        $('#modal-form [name=alamat]').focus();
        $('#modal-form [name=tlpn]').focus();

        $.get(url)
            .done((response) => {
                $('#modal-form [name=nama]').val(response.nama);
                $('#modal-form [name=no_sipa]').val(response.no_sipa);
                $('#modal-form [name=no_stra]').val(response.no_stra);
                $('#modal-form [name=jabatan]').val(response.jabatan);
                $('#modal-form [name=mulai_tgs]').val(response.mulai_tgs);
                $('#modal-form [name=stt]').val(response.stt);
                $('#modal-form [name=alamat]').val(response.alamat);
                $('#modal-form [name=tlpn]').val(response.tlpn);
            })
            .fail((errors) => {
                alert('Tidak dapat menampilkan data');
                return;
            });
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

    $(document).ready(function () {
        $(".datepicker").datepicker({
      format: 'dd-mm-yyyy',
      autoclose: true,
      todayHighlight: true,
  });
    })
</script>
@endpush