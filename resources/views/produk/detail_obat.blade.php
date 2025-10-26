@extends('layouts.master')

@section('title')
  list Obat
@endsection

@section('breadcrumb')
    @parent
    <li class="active">list Obat</li>
@endsection

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
           List Obat 
             <h4>{{$produk->kode_obat}}|{{$produk->nama_obat}}</h4>
            </div>
            <div class="box-body">
              <div class="table-responsive"> 
        <table class="table  table-striped table-bordered" >
            <thead>
                <th width="5%">
                    <input type="checkbox" name="select_all" id="select_all">
                </th> 
            <th width="5%">No</th>
            <th>kode_obat</th>
            <th>nama Obat</th>
            <th>Stock </th>
            <th>satuan </th>
            <th>ed</th>
            <th>batch</th>
            <th>lokasi</th>
            <th>Pembelian</th>
            <th width="15%"><i class="fa fa-cog"></i></th>
            </thead>
            <tbody>
                @foreach ($detail as $key=> $item) 
                <tr>
                    <td>
                    <input type="checkbox" name="id[]" value="{{ $item->id}}">
                    </td>
                    <td>{{$key+1}}</td>
                    <td>{{$item->obat->kode_obat}}</td>
                    <td>{{$item->obat->nama_obat}}</td>
                    <td>{{$item->stock}}</td>
                    <td>{{$item->obat->satuan}}</td>
                    
                    <td>{{$item->ed}}</td>
                    <td>{{$item->batch}}</td>
                    <td>{{$item->lokasi->name ?? 'belum ada lokasi'}}</td>
                    <td>{{$item->beli_id ?? 'belum ada Pembelian'}}</td>
                    <td>
                    <div class="btn-group" role="group" aria-label="Button group">

                    <button type="button" onclick="editForm(`{{route('detailObat.update', $item->id)}}`)" class="btn btn-sm btn-info btn-flat"><i class="fa fa-pencil"></i></button>

                    <button type="button" onclick="deleteData(`{{route('produk.destroy', $item->id)}}`)" class="btn btn-sm btn-danger btn-flat"><i class="fa fa-trash"></i></button> 

                    <button type="button" onclick="editstock(`{{ route('detailObat.show', $item->id)}}`,`{{ route('detailObat.stock', $item->id)}}`)" class="btn btn-sm btn-warning btn-flat"><i class="fa fa-edit"></i></button>

                    </div>
                </td>
            </tr>
                @endforeach
            </tbody>
        </table>
                    <a onclick="history.back()" class="btn btn-warning btn-sm" type="button"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Kembali</a>
            </div>
        </div>
        </div>
    </div>
</div>
{{-- @includeIf('detailObat.form') --}}
@includeIf('detailObat.edit')
 @includeIf('detailObat.stock')
@endsection

@push('scripts')
<script>
    $('.uang').mask('0.000.000.000.000',{reverse:true});
    $(function () {
        
       

        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });
 
    });
   
    function editForm(url) {
        $('#modal-edit').modal('show');
        $('#modal-edit .modal-title').text('Edit Data Obat');

        $('#modal-edit form')[0].reset();
        $('#modal-edit form').attr('action', url);
        $('#modal-edit [name=_method]').val('put');

        $.get(url)
            .done((response) => {
                $('#modal-edit [name=obat]').val(response.obat['nama_obat']);              
                $('#modal-edit [name=satuan]').val(response.obat['satuan']);              
            
                $('#modal-edit [name=lokasi_id]').val(response.lokasi_id).attr('selected',true);
                $('#modal-edit [name=stock]').val(response.stock).attr('disabled',true);
                $('#modal-edit [id=ed]').val(response.ed);
                $('#modal-edit [name=batch]').val(response.batch);
               
              
            })
            .fail((errors) => {
                alert('Tidak dapat menampilkan data');
                return;
            });
    }
    function editstock(url,url2) {
        $('#modal-stock').modal('show');
        $('#modal-stock .modal-title').text('Ubah Stock Obat');

        $('#modal-stock form')[0].reset();
        $('#modal-stock form').attr('action', url2);
        $('#modal-stock [name=_method]').val('post');
        $.get(url)
            .done((response) => {
                $('#modal-stock [name=obat]').val(response.obat['nama_obat']);              
                $('#modal-stock [name=satuan]').val(response.obat['satuan']);              
            
                $('#modal-stock [id=stock]').val(response.stock).attr('disabled',true);
           
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

    function deleteSelected(url) {
        if ($('input:checked').length > 1) {
            if (confirm('Yakin ingin menghapus data terpilih?')) {
                $.post(url, $('.form-produk').serialize())
                    .done((response) => {
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menghapus data');
                        return;
                    });
            }
        } else {
            alert('Pilih data yang akan dihapus');
            return;
        }
    }
    $('[name=select_all]').on('click', function () {
            $(':checkbox').prop('checked', this.checked);
        });
   </script>
@endpush