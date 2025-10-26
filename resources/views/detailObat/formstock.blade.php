@extends('layouts.master')

@section('title')
   stock in obat
@endsection

@section('breadcrumb')
    @parent
    <li class="active">tambah obat baru</li>
@endsection

@section('content')

<div class="row">
    <div class="col-lg-12">
       
        <form action="{{ route('detailObat.store') }}" method="post" class="form-horizontal">
            @csrf
        <div class="box">
         
            <div class="box-header with-border">
              <h2>Tambah obat baru</h2>
            </div>
           
            <div class="box-body">

                <div class="form-group row">
                    <label for="supplier" class="col-lg-2 col-lg-offset-1 control-label">Supplier</label>
                    <div class="col-lg-6">
                        <input type="text" name="" value="{{$supplier->nama_supplier}}"  class="form-control" disabled>

                        <input type="hidden" name="supplier" value="{{$supplier->id_supplier}}"   disabled>

                        <span class="help-block with-errors"></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="lokasi" class="col-lg-2 col-lg-offset-1 control-label">lokasi</label>
                    <div class="col-lg-6">
                        <input type="text"  value="Gudang"  placeholder="Gudang" class="form-control" disabled>

                        <input type="hidden" name="lokasi" value="1" disabled>

                        <span class="help-block with-errors"></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nama_produk" class="col-lg-2 col-lg-offset-1 control-label">Master Obat</label>
                    <div class="col-lg-6">
                        <select name="produk_id" id="produk" class="form-control select2" required>
                            <option >->->->->->->->->->->->->-> Pilih Obat <-<-<-<-<-<-<-<-<-<-<-<-<-</option>
                            @foreach ($obat as $ob)
                            <option value="{{ $ob->kode_produk }}">{{ $ob->kode_produk }} / {{ $ob->nama_produk }}</option>
                            @endforeach
                        </select>
                        <span class="help-block with-errors"></span>
                    </div>
                </div>
                   
  
<input type="hidden" name="id_cabang" value="1">

                <div class="form-group row">
                    <label for="stock" class="col-lg-2 col-lg-offset-1 control-label">Stock</label>
                    <div class="col-lg-6">
                        <input type="number" name="stock" id="stock" class="form-control"  >
                        <span class="help-block with-errors"></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="ed" class="col-lg-2 col-lg-offset-1 control-label">Expired Date</label>
                    <div class="col-lg-6">
                        <input type="date" name="ed" id="ed" class="form-control" >
                        <span class="help-block with-errors"></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="batch" class="col-lg-2 col-lg-offset-1 control-label">batch</label>
                    <div class="col-lg-6">
                        <input type="text" name="batch" id="batch" class="form-control" >
                        <span class="help-block with-errors"></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="harga_beli" class="col-lg-2 col-lg-offset-1 control-label">harga</label>
                    <div class="col-lg-6">
                        <input type="text" name="harga_beli" id="harga_beli" class="form-control uang" >
                        <span class="help-block with-errors"></span>
                    </div>
                </div>
                </div>
<div class="box-footer">

    <div class="button-group">
        
                    <button class="btn btn-xl btn-flat btn-primary pull-right"  type="submit"><i class="fa fa-save"></i> Simpan</button>
                    <a href="{{route('detailObat.stockin')}}" type="button" class="btn btn-sm btn-flat btn-warning" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Batal</a>
                </div>
            </div>
       
            
            
        </div>
    </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
   
    
</script>
@endpush