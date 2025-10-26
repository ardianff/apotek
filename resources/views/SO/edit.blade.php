@extends('layouts.master')

@section('title')
    Stok Opname Obat {{$produk->nama_obat??0}}
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Stok Opname Obat</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <form action="{{route('so.tambah',$produk->id)}}" method="post" class="form-horizontal">
@csrf
        <div class="box">
            <div class="box-header">

                <div class="row">
                    <div class="col-md-2">
                        <label for="kode_obat" class="control-label">Kode Obat</label>
                        <input type="text" name="kode_obat" id="kode_obat" class="form-control" value="{{$produk->kode_obat}}" disabled>
                        <input type="hidden" name="id" id="id" class="form-control" hidden>
                        <span class="help-block with-errors"></span>
                    </div>
                    <div class="col-md-8">
                            <label for="nama_obat" class="control-label">Nama Obat</label>
                                <input type="text" name="nama_obat" id="nama_obat" class="form-control" value="{{$produk->nama_obat}}" autofocus>
                                <span class="help-block with-errors"></span>
                    </div>
                    <div class="col-md-2">
                        <label for="satuan" class="control-label">satuan</label>
                            <input type="text" name="satuan" id="satuan" class="form-control"value="{{$produk->satuan}}" >
                            <span class="help-block with-errors"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <label for="stock" class="control-label">stock</label>
                            <input type="text" name="stock" id="stock" class="form-control" value="{{$produk->stock}}" >
                            <span class="help-block with-errors"></span>
                    </div>
                    <div class="col-md-4">
                        <label for="harga_beli" class="control-label">harga_beli</label>
                            <input type="text" name="harga_beli" id="harga_beli" class="form-control" value="{{$produk->harga_beli}}" >
                            <span class="help-block with-errors"></span>
                    </div>
                    <div class="col-md-2">
                        <label for="margin" class="control-label">margin</label>
                            <input type="text" name="margin" id="margin" class="form-control"value="{{$produk->margin}}" >
                            <span class="help-block with-errors"></span>
                    </div>
                    <div class="col-md-4">
                        <label for="harga_jual" class="control-label">harga_jual</label>
                            <input type="text" name="harga_jual" id="harga_jual" class="form-control"value="{{$produk->harga_jual}}" >
                            <span class="help-block with-errors"></span>
                    </div>

                </div>
    </div>
    <div class="box-body">
        <button id="add" class="btn btn-success btn-sm"> <i class="fa fa-plus-square" aria-hidden="true"></i> add</button>
        @foreach ($obat as $item)
        <div class="dinamis">

        <div class="row">
            
                <div class="col-md-3">
                    <label for="ed" class="control-label">Expired Date</label>
                    <input type="date" name="ed[]" id="ed" class="form-control" value="{{$item->ed}}" >
                    <span class="help-block with-errors"></span>
                </div>
                <div class="col-md-3">
                    <label for="stok" class="control-label">Stok</label>
                    <input type="number" name="stok[]" id="stok" value="{{$item->stock}}" class="form-control" >
                    <span class="help-block with-errors"></span>
                </div>
                <div class="col-md-4">
                        <label for="batch" class="control-label">Batch</label>
                        <input type="text" name="batch[]" id="batch" value="{{$item->batch}}" class="form-control"  >
                        <span class="help-block with-errors"></span>
                    </div>
                    <div class="col-md-2">
                        <label for="add" class="control-label">Tambah</label>
                        <div class="row">

                           
                            <a href="{{route('so.delbatch',$item->id)}}"  class="btn btn-danger btn-sm"> <i class="fa fa-trash" aria-hidden="true"></i> Hapus</a>
                        </div>
                        </div>
                </div>
            </div>
                @endforeach
            </div>
            <div class="box-footer">
                <div class="row">
                    <div class="col-md-9">

                        <a onclick="history.back()"  class="btn btn-sm btn-flat btn-warning" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Kembali</a>
                    </div>
                        <div class="col-md-3">

                            <button class="btn btn-sm btn-flat btn-primary " type="submit"><i class="fa fa-save"></i> Simpan</button>
                        </div>
                </div>
    </div>
            </div>
        </form>
            
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
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });
 
    });
        // $(document).on('input', '.stok', function () {
        //     let id = $(this).data('id');
        //     let stok = parseInt($(this).val());


        //     $.post(`{{ url('/so/create') }}/${id}`, {
        //             '_token': $('[name=csrf-token]').attr('content'),
        //             '_method': 'put',
        //             'stok': stok
        //         })
        //         .done(response => {
        //             // $(this).on('mouseout', function () {
        //                 // table.ajax.reload();
        //             // });
        //         })
                
        // });

       
    $(document).ready(function() {
      $("#add").click(function(e){ 
       e.preventDefault
          $(".dinamis").prepend(`
          <div class="row">
               
            <div class="col-md-4">
                    <label for="ed" class="control-label">Expired Date</label>
                    <input type="date" name="ed[]" id="ed" class="form-control " required>
                    <span class="help-block with-errors"></span>
                </div>
                <div class="col-md-3">
                    <label for="stok" class="control-label">Stok</label>
                    <input type="number" name="stok[]" id="stok" class="form-control" required>
                    <span class="help-block with-errors"></span>
                </div>
                <div class="col-md-4">
                        <label for="batch" class="control-label">Batch</label>
                        <input type="text" name="batch[]" id="batch" class="form-control"  >
                        <span class="help-block with-errors"></span>
                    </div>
                   <div class="col-md-1">
                   <label for="batch" class="control-label">Tambah</label>
                   <button id="hapus" class="btn btn-warning btn-sm"> <i class="fa fa-trash" aria-hidden="true"></i> cancel</button>
               </div>`);
      });
      $(document).on("click","#hapus",function(e){ 
        e.preventDefault();
        let row_item=$(this).parent().parent();
        $(row_item).remove();
      });
    });
</script>

@endpush