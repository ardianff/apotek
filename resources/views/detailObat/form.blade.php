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
                            <label for="nama_produk" class="col-lg-2 col-lg-offset-1 control-label">Master Obat</label>
                            <div class="col-lg-6">
                                <select name="produk_id" id="produk_id" class="form-control select2" required>
                                    <option >->->->->->->->->->->->->-> Pilih Obat <-<-<-<-<-<-<-<-<-<-<-<-<-</option>
                                    @foreach ($obat as $ob)
                                    <option value="{{ $ob->id }}">{{ $ob->kode_obat }} | {{ $ob->nama_obat }} | {{$ob->satuan}} | {{$ob->kategori->nama_kategori??0}}</option>
                                    @endforeach
                                </select>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                   
                                        
                        <div class="form-group row">
                            <label for="lokasi" class="col-lg-2 col-lg-offset-1 control-label"> Lokasi</label>
                            <div class="col-lg-6">
                                <select name="lokasi_id" id="lokasi" class="form-control" required >
                                    <option value="">Pilih Lokasi</option>
                                    @foreach ($lokasi as $lok)
                                    <option value="{{ $lok->id }}"  >{{ $lok->name }}</option>
                                    @endforeach
                                </select>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        
                 
                      
                       
             <div class="form-group row">
                            <label for="stock" class="col-lg-2 col-lg-offset-1 control-label">stock</label>
                            <div class="col-lg-6">
                                <input type="number" name="stock" id="stock" class="form-control" >
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
                            <label for="harga_beli" class="col-lg-2 col-lg-offset-1 control-label">harga_beli</label>
                            <div class="col-lg-6">
                                <input type="text" name="harga_beli" id="harga_beli" class="form-control uang" >
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="harga_jual" class="col-lg-2 col-lg-offset-1 control-label">harga_jual</label>
                            <div class="col-lg-6">
                                <input type="text" name="harga_jual" id="harga_jual" class="form-control uang" >
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                     
                        <div class="form-group row">
                            <label for="diskon" class="col-lg-2 col-lg-offset-1 control-label">diskon</label>
                            <div class="col-lg-6">
                                <input type="text" name="diskon" id="diskon" class="form-control" >
                                <span class="help-block with-errors"></span>
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