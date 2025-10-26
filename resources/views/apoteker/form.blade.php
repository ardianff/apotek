<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog modal-lg" role="document">
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
                        <label for="nama" class="col-lg-2 col-lg-offset-1 control-label">nama</label>
                        <div class="col-lg-6">
                            <input type="text" name="nama" id="nama" class="form-control" required autofocus>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="no_sipa" class="col-lg-2 col-lg-offset-1 control-label">No SIPA/SIA</label>
                        <div class="col-lg-6">
                            <input type="text" name="no_sipa" id="no_sipa" class="form-control"  autofocus>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="no_stra" class="col-lg-2 col-lg-offset-1 control-label">No STRA</label>
                        <div class="col-lg-6">
                            <input type="text" name="no_stra" id="no_stra" class="form-control"  autofocus>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="jabatan" class="col-lg-2 col-lg-offset-1 control-label">Jabatan</label>
                        <div class="col-lg-6">
                            <input type="text" name="jabatan" id="jabatan" class="form-control"  autofocus>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="mulai_tgs" class="col-lg-2 col-lg-offset-1 control-label">Awal Mulai Bertugas </label>
                        <div class="col-lg-6">
                        <div class="input-group date">
                         <div class="input-group-addon">
                                <span class="fa fa-calendar"></span>
                            </div>

                            <input  type="text" class="form-control datepicker" name="mulai_tgs" id="mulai_tgs">
                        </div>
                        </div>
                       </div>
                    <div class="form-group row">
                        <label for="stt" class="col-lg-2 col-lg-offset-1 control-label">Status</label>
                        <div class="col-lg-6">
                         
                            <select class="form-control" name="stt" id="stt">
                              <option value="aktif">Aktif</option>
                              <option value="deactive">Tidak Aktif</option>
                              <option value="cuti">Cuti</option>
                            </select>
                       
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="alamat" class="col-lg-2 col-lg-offset-1 control-label">alamat</label>
                        <div class="col-lg-6">
                            <input type="text" name="alamat" id="alamat" class="form-control"  autofocus>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tlpn" class="col-lg-2 col-lg-offset-1 control-label">Nomer Telepon/HP</label>
                        <div class="col-lg-6">
                            <input type="text" name="tlpn" id="tlpn" class="form-control"  autofocus>
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