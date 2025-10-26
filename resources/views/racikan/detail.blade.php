@extends('layouts.master')

@section('title')
    Daftar Obat racikan
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar obat racikan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="addForm('{{ route('racikan.add') }}')" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Tambah</button>
           

            </div>
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered">
                    <thead>
                        <th width="5%">No</th>
                        <th>Kode Obat</th>
                        <th>Nama Obat</th>
                        <th>Jumlah</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>
                        @foreach ($racikan as $item)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$item->produk->kode_obat}}</td>
                                <td>{{$item->produk->nama_obat}}</td>
                                <td>{{$item->jumlah}}</td>
                                <td>
                                    <button onclick="deleteData(`{{route('racikan.delete_detail',$item->id)}}`)" class="btn btn-sm btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="box-footer">
                <a href="{{ route('racikan.index') }}" class="btn btn-warning btn-sm btn-flat"><i class="fa fa-arrow-circle-left" style="margin-right: 5px" aria-hidden="true"></i><i class="fa fa-book" aria-hidden="true"></i> Daftar Racikan</a>
            </div>
        </div>
    </div>
</div>
@includeIf('racikan.form_detail')

@endsection

@push('scripts')
<script>
    $(function () {
        $('#mySelect2').select2({
        dropdownParent: $('#modal-detail')
    });
    });
    function addForm(url) {
        $('#modal-detail').modal('show');
        $('#modal-detail .modal-title').text('Tambah racikan');

        $('#modal-detail form')[0].reset();
        $('#modal-detail form').attr('action', url);
        $('#modal-detail [name=_method]').val('post');
  
      
    }

   
    function deleteData(url) {
        if (confirm('Yakin ingin menghapus data terpilih?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    window.location.reload();
                })
                .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    return errors;
                });
        }
    }
    
</script>
@endpush