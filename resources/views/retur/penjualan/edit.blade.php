<div class="modal fade " id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="modal-edit">
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
                            <label for="metode" class="col-lg-2 col-lg-offset-1 control-label"> metode pembayaran</label>
                            <div class="col-lg-6">
                                <select name="metode_id" id="metode" class="form-control" required >
                                    <option value="">Pilih Metode</option>
                                    @foreach ($metode as $item)
                                    <option value="{{ $item->id }}"  >{{ $item->metode }}</option>
                                    @endforeach
                                </select>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
      @if (auth()->user()->level == 100)
          
      <div class="form-group row">
          <label for="jml_item" class="col-lg-2 col-lg-offset-1 control-label">Jumlah Item</label>
                            <div class="col-lg-6">
                                <input type="number" name="jml_item" id="jml_item" class="form-control"  >
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                       
                 
                        <div class="form-group row">
                            <label for="bayar" class="col-lg-2 col-lg-offset-1 control-label">Jumlah Bayar</label>
                            <div class="col-lg-6">
                                <input type="text" name="bayar" id="bayar" class="form-control" >
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="diskon" class="col-lg-2 col-lg-offset-1 control-label">diskon</label>
                            <div class="col-lg-6">
                                <input type="number" name="diskon" id="diskon" class="form-control" >
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="diterima" class="col-lg-2 col-lg-offset-1 control-label">Diterima</label>
                            <div class="col-lg-6">
                                <input type="text" name="diterima" id="diterima" class="form-control" >
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        @endif
                    </div>

                <div class="modal-footer">
                    <button class="btn btn-sm btn-flat btn-primary"><i class="fa fa-save"></i> Simpan</button>
                    <button type="button" class="btn btn-sm btn-flat btn-warning" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>