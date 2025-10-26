<form action="" method="get">
<div class="modal fade" id="modal-debit" tabindex="-1" role="dialog" aria-labelledby="modal-debit">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pilih debit</h4>
            </div>
            <div class="modal-body">

               <div class="form-group row">
                        <label for="" class="col-lg-4 control-label">Metode Pembayaran</label>
                        <div class="col-lg-8">
                            <select name="debit" id="debit" class="form-control" required>
                                <option ></option>
                                @foreach ($debit as $debt)
                                    
                                <option value="{{$debt->id}}">{{$debt->metode}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
               <div class="form-group row">
                        <label for="" class="col-lg-4 control-label">No Kartu / No Transaksi</label>
                        <div class="col-lg-8">
                           <input type="text" class="form-control" name="no_card" id="no_card" required>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-md  btn-simpan" style="width: 27rem;"><i class="fa fa-floppy-o"></i> Simpan</button>
            </div>
        </div>
    </div>
</div>
</form>
