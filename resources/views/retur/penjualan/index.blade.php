@extends('layouts.master')

@section('title')
    Transaksi Retur Penjualan
@endsection

@push('css')
<style>
   
</style>
@endpush

@section('breadcrumb')
    @parent
    <li class="active">Transaksi Retur Penjaualan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header">
                <h5>Penjualan</h5>
                <table class="table table-stiped table-bordered " id="penjualan">
                    <thead>
                        <th>Tgl Transaksi</th>
                        <th width="10%">No Nota</th>
                        <th>Jumlah Item</th>
                        <th>Jumlah Bayar</th>
                        <th width="5%">Diskon</th>
                        <th >Diterima</th>
                        <th>Metode</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>
                        <tr>

                            <td>{{tanggal_indonesia($penjualan->created_at, false)}}</td>
                            <td>{{$penjualan->no_nota}}</td>
                            <td>{{format_uang($penjualan->total_item)}}</td>
                            <td>{{format_uang($penjualan->bayar)}}</td>
                            <td>{{$penjualan->diskon . '%'}}</td>
                            <td>{{format_uang($penjualan->diterima)}}</td>
                            <td>{{$penjualan->metod->metode??0}}</td>
                            <td> <a  class="btn btn-warning btn-xs "
                                onclick="editPenjualan(`{{route('retur.penjualan_show',$penjualan->id)}}`,`{{route('retur.penjualan_edit',$penjualan->id)}}` )">
                                <i class="fa fa-pencil"></i>
                                
                            </a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="box-body">   
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-stiped table-bordered " id="detail">
                                <thead>
                                    <th width="5%">No</th>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Harga</th>
                                    <th width="15%">Jumlah</th>
                                    <th>Diskon</th>
                                    <th>Subtotal</th>
                                    <th width="15%"><i class="fa fa-cog"></i></th>
                                </thead>
                                <tbody>
                                    @foreach ($detail as $item)
                                    <tr>

                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$item->prodk->kode_obat}}</td>
                                        <td>{{$item->prodk->nama_obat}}</td>
                                        <td>{{format_uang($item->harga_jual)}}</td>
                                        <td>{{$item->jumlah}}</td>
                                        <td>{{$item->diskon}}</td>
                                        <td>{{format_uang($item->subtotal)}}</td>
                                        <td> <a  class="btn btn-danger btn-xs "
                                            onclick="retur(`{{ route('retur.penjualan_delete', $item->id) }} `)">
                                            <i class="fa fa-trash"></i>
                                            
                                        </a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
            </div> 
            <div class="box-footer">
                <a href="{{route('penjualan.index')}}" class="btn btn-primary btn-sm btn-flat pull-right btn-simpan" ><i class="fa fa-floppy-o"></i> Simpan Transaksi</a>
            </div>
        </div>
    </div>
</div>

@includeIf('retur.penjualan.edit')

@endsection

@push('scripts')
<script>
  
    $('.uang').mask('0.000.000.000.000',{reverse:true})
   

$(function () {
    $('#modal-edit').validator().on('submit', function (e) {
            if (! e.preventDefault()) {
                $.post($('#modal-edit form').attr('action'), $('#modal-edit form').serialize())
                    .done((response) => {
                        $('#modal-edit').modal('hide');
                        location.reload();
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menyimpan data');
                        return;
                    });
            }
        });
  

})
function editPenjualan(url,url1) {
        $('#modal-edit').modal('show');
        $('#modal-edit .modal-title').text('Edit Penjualan');

        $('#modal-edit form')[0].reset();
        $('#modal-edit form').attr('action', url1);
        $('#modal-edit [name=_method]').val('POST');

        $.get(url)
            .done((response) => {
                $('#modal-edit [name=metode_id]').val(response.metode_id).attr('selcted',true);
                $('#modal-edit [name=jml_item]').val(response.total_item);
                $('#modal-edit [name=bayar]').val(response.bayar);
                $('#modal-edit [name=diskon]').val(response.diskon);
                $('#modal-edit [name=diterima]').val(response.diterima);
              
            })
            .fail((errors) => {
                alert('Tidak dapat menampilkan data');
                return;
            });
    }

    function retur(url) {
        if (confirm('Yakin ingin menghapus data terpilih?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'post'
                })
                .done((response) => {
                    location.reload();
                })
                .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    return;
                });
        }
    }
</script>
@endpush