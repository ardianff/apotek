@extends('layouts.master')

@section('title')
    Daftar Supplier
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Supplier</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
            </div>
            <div class="box-body table-responsive">
                <form action="" method="post" class="form-produk">
                    @csrf
                    <table id="" class="table table-produk table-stiped table-bordered" >
                        <thead>
                            <th width="5%">
                                <input type="checkbox" name="select_all" id="select_all">
                            </th>
                            <th width="5%">No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>stok</th>
                            <th>satuan</th>
                            <th>lokasi</th>
                            <th>ed</th>
                            <th>harga jual</th>
                            
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                        <tbody>
                            @foreach ($produk as $key => $item)
                            <tr>
                                <td>
                                    <input type="checkbox" name="id[]" value="{{$item->id}}">
                                </td>
                                <td width="5%">{{ $key+1 }}</td>
                              
                                <td>{{ $item->kode_obat }}</td>
                                <td>{{ $item->nama_obat }}</td>
                                <td>{{ $item->stock }}</td>
                                <td>{{ $item->satuan ?? 0 }}</td>
                                <td>{{ $item->lokasi->name?? 'belum ada lokasi' }}</td>
                                <td style="max-width: 50px">{{ $item->ed }}</td>
                                <td>{{ $item->harga_jual }}</td>
                                <td>
                                    {{-- <div class="btn-group">
                                        <button type="button" data-toggle="tooltip" data-placement="top" title="ubah data" onclick="editForm('{{ route('produk.update', $produk->id) }}')" class="btn btn-sm btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    
                                        <button data-toggle="tooltip" data-placement="top" title="hapus data" type="button" onclick="deleteData('{{ route('produk.destroy', $produk->id) }}')" class="btn btn-sm btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                    
                                       <a data-toggle="tooltip" data-placement="top" title="kartu stock" type="button" href="{{route('produk.kartu_stock', $produk->id)}}" class="btn btn-sm btn-success btn-flat"><i class="fa fa-book"></i></a>
                                    
                                    </div> --}}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let table;

    $(function () {
        table = $('.table-produk').DataTable();


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
        $('#modal-form .modal-title').text('Tambah Supplier');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=nama]').focus();
    }

    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Supplier');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=nama]').focus();

        $.get(url)
            .done((response) => {
                $('#modal-form [name=nama]').val(response.nama);
                $('#modal-form [name=telepon]').val(response.telepon);
                $('#modal-form [name=alamat]').val(response.alamat);
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
</script>
@endpush