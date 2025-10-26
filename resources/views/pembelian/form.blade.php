<div class="modal fade" id="modal-pembelian" tabindex="-1" role="dialog" aria-labelledby="modal-pembelian">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{route('pembelian.create')}}" method="post" class="form-horizontal">
            @csrf
            @method('post')

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Buat Faktur Pembelian</h4>
                </div>
                <div class="modal-body">
                   
                    <div class="form-group row">
                        <label for="supplier_id" class="col-lg-2 col-lg-offset-1 control-label">Pilih Supplier</label>
                        <div class="col-lg-6">

                             <select id="supplier_id" class="form-control select2" name="supplier_id" style="width: 430px" required>
                                @foreach ($supplier as $item)
                                    
                                <option value="{{$item->id}}">{{$item->nama_supplier}} || {{$item->alamat}}</option>
                                @endforeach
                            </select>
                            <span class="help-block with-errors"></span>
                        </div>
                        <div class="col-lg-2">
                            <button type="button" class="btn btn-success btn-xs btn-flat" onclick="addSupplier('{{ route('supplier.store') }}')"><i class="fa fa-plus-circle"></i> Tambah Supplier Baru</button>
                    </div>
                    </div>
                  
                    <div class="form-group row">
                        <label for="no_faktur" class="col-lg-2 col-lg-offset-1 control-label">No Faktur</label>
                        <div class="col-lg-6">
                         
                           <input type="text" name="no_faktur" id="no_faktur" class="form-control" placeholder="No Faktur" required>
                           
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tgl_faktur" class="col-lg-2 col-lg-offset-1 control-label">Tgl Faktur</label>
                        <div class="col-lg-6">
                         
                           <input type="text" name="tgl_faktur" id="tgl_faktur" class="form-control datepicker" placeholder="Tanggal Faktur" required>
                           
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tgl_tempo" class="col-lg-2 col-lg-offset-1 control-label">Tgl Jatuh Tempo</label>
                        <div class="col-lg-6">
                         
                           <input type="text" name="tgl_tempo" id="tgl_tempo" class="form-control datepicker" placeholder="Tanggal Faktur" required>
                           
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                   <div class="form-group row">
                        <label for="metode_id" class="col-lg-2 col-lg-offset-1 control-label">Pilih Metode</label>
                        <div class="col-lg-6">

                             <select id="metode_id" class="form-control" name="metode_id" required>
                                @foreach ($metode as $val)
                                    
                                <option value="{{$val->id}}">{{$val->metode}} </option>
                                @endforeach
                            </select>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                     <div class="form-group row">
                            <label for="diskon" class=" col-lg-2 col-lg-offset-1 control-label">Diskon Untuk</label>
                            <div class="col-lg-2">

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="diskon_untuk" id="diskon_apotek" value="apotek" checked>
                                    <label class="form-check-label" for="diskon_apotek">Apotek</label>
                                </div>
                            </div>
                    <div class="col-lg-2">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="diskon_untuk" id="diskon_konsumen" value="konsumen">
                            <label class="form-check-label" for="diskon_konsumen">Konsumen</label>
                        </div>
                    </div>
                </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-flat btn-primary"><i class="fa fa-save"></i> Simpan</button>
                    <button type="button" class="btn btn-sm btn-flat btn-warning" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>