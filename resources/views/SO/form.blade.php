<div class="modal fade " id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog modal-lg " role="document">
        <form action="" method="post" class="form-horizontal">
            @csrf
            {{-- @method('post') --}}

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-2">
                    <label for="kode_obat" class="control-label">Kode Obat</label>
                    <input type="text" name="kode_obat" id="kode_obat" class="form-control" disabled>
                    <input type="hidden" name="id" id="id" class="form-control" hidden>
                    <span class="help-block with-errors"></span>
                </div>
                <div class="col-md-8">
                        <label for="nama_obat" class="control-label">Nama Obat</label>
                            <input type="text" name="nama_obat" id="nama_obat" class="form-control" disabled autofocus>
                            <span class="help-block with-errors"></span>
                </div>
                <div class="col-md-2">
                    <label for="satuan" class="control-label">satuan</label>
                        <input type="text" name="satuan" id="satuan" class="form-control"disabled >
                        <span class="help-block with-errors"></span>
                </div>
            </div>
            <div class="dinamis">

            <div class="row">
               
                <div class="col-md-4">
                    <label for="ed" class="control-label">Expired Date</label>
                    <input type="date" name="ed[]" id="ed" class="form-control" required>
                    <span class="help-block with-errors"></span>
                </div>
                <div class="col-md-3">
                    <label for="stock" class="control-label">Stok</label>
                    <input type="number" name="stock[]" id="stock" class="form-control" required>
                    <span class="help-block with-errors"></span>
                </div>
                <div class="col-md-4">
                        <label for="batch" class="control-label">Batch</label>
                        <input type="text" name="batch[]" id="batch" class="form-control"  >
                        <span class="help-block with-errors"></span>
                    </div>
                    <div class="col-md-1">
                    <label for="batch" class="control-label">Tambah</label>
                    <button id="add" class="btn btn-success btn-sm"> <i class="fa fa-plus-square" aria-hidden="true"></i> add</button>
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