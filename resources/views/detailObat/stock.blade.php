<div class="modal fade " id="modal-stock" tabindex="-1" role="dialog" aria-labelledby="modal-stock">
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
                <label for="obat" class="col-lg-2 col-lg-offset-1 control-label">Nama Obat</label>
                <div class="col-lg-6">
                    <input type="text" name="obat" id="obat" class="form-control"  disabled>
                    <input type="text" name="satuan" id="satuan" class="form-control"  disabled>
                    <span class="help-block with-errors"></span>
                </div>
            </div>
            <!--<span><p class="text-danger">beri tanda mines (-) diawal untuk mengurangi stock</p></span>-->
                    <div class="form-group row">
                        <label for="stock" class="col-lg-2 col-lg-offset-1 control-label">Stock</label>
                    <div class="col-lg-6">
                        <input type="number" name="" id="stock" class="form-control" disabled>
                        <span class="help-block with-errors"></span>
                        <input type="number" name="stock" id="" class="form-control" >
                        <span class="help-block with-errors"></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="ket_stock" class="col-lg-2 col-lg-offset-1 control-label">keterangan </label>
                    <div class="col-lg-6">
                        <select name="ket_stock" id="ket_stock" class="form-control " required>
                            <option value="rusak">SO</option>
                            <option value="ed">ED</option>
                            <option value="hilang">hilang/Rusak</option>
                            <option value="Ditarik">Ditarik dari peredaran</option>
                            <option value="Transfer">Transfer</option>
                            <option value="salah_input">Koreksi</option>
                           
                        </select>
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