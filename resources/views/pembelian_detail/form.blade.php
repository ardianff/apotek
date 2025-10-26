<div class="modal fade " id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog modal-lg " role="document">
        <form action="" method="post" class="form-horizontal">
            @csrf
            @method('post')

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                </div>
        <div class="modal-body">
            

                        <div class="form-group row">
                            <label for="nama_produk" class="col-lg-2 col-lg-offset-1 control-label">Pilih Obat</label>
                            <div class="col-lg-6">
                                <select name="produk_id" id="prdk_id" class="form-control select2" required>
                                    <option >->->->->->->->->->->->->-> Pilih Obat <-<-<-<-<-<-<-<-<-<-<-<-<-</option>
                                    @foreach ($produk as $ob)
                                    <option value="{{ $ob->id }}">{{ $ob->kode_obat }} | {{ $ob->nama_obat }} || {{$obt->satuan}}</option>
                                    @endforeach
                                </select>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                   
                        <div class="form-group row">
                            <label for="satuan" class="col-lg-2 col-lg-offset-1 control-label">satuan</label>
                            <div class="col-lg-6">
                                <input type="text" name="satuan"  id="satuan" class="form-control"  required>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>                
                        <div class="form-group row">
                            <label for="lokasi" class="col-lg-2 col-lg-offset-1 control-label"> Lokasi</label>
                            <div class="col-lg-6">
                             
                                <select id="lokasi_id" class="form-control" name="lokasi_id">
                                    @foreach ($lokasi as $lk)
                                        
                                    <option value="{{$lk->id}}">{{$lk->name}}</option>
                                    @endforeach
                                </select>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                                             
          
                        <div class="form-group row">
                            <label for="jumlah" class="col-lg-2 col-lg-offset-1 control-label">jumlah</label>
                            <div class="col-lg-6">
                                <input type="number" name="jumlah"  id="jumlah" class="form-control"  required>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="isi" class="col-lg-2 col-lg-offset-1 control-label">isi</label>
                            <div class="col-lg-6">
                                <input type="number" name="isi"  id="isi" class="form-control"  required>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="ed" class="col-lg-2 col-lg-offset-1 control-label">Expired Date</label>
                            <div class="col-lg-6">
                                <input type="date" name="ed" id="ed" class="form-control" required>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="batch" class="col-lg-2 col-lg-offset-1 control-label">batch</label>
                            <div class="col-lg-6">
                                <input type="text"  name="batch" id="batch" class="form-control" required>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                       
                     
                        <div class="form-group row">
                            <label for="harga_ppn" class="col-lg-2 col-lg-offset-1 control-label">Harga PPN </label>
                            <div class="col-lg-6">
                                <input type="text"  name="harga_ppn" id="harga_ppn" class="form-control uang" required >
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="harga_nonppn" class="col-lg-2 col-lg-offset-1 control-label">harga Non PPN </label>
                            <div class="col-lg-6">
                                <input type="text"  name="harga_nonppn" id="harga_nonppn" class="form-control uang" required >
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="diskon" class="col-lg-2 col-lg-offset-1 control-label">Diskon </label>
                            <div class="col-lg-6">
                                <input type="number" name="diskon" id="diskon" class="form-control "  required>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="subtotal" class="col-lg-2 col-lg-offset-1 control-label">subtotal</label>
                            <div class="col-lg-6">
                                <input type="number"  name="subtotal" id="subtotal" class="form-control uang" required>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        
                     


                    </div>

                <div class="modal-footer">
                    <button type="button" id="btn_update" class="btn btn-sm btn-flat btn-primary"><i class="fa fa-save"></i> Simpan</button>
                    <button type="button" class="btn btn-sm btn-flat btn-warning" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>