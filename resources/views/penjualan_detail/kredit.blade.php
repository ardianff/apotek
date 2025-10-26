<div class="modal fade" id="modal-kredit" tabindex="-1" role="dialog" aria-labelledby="modal-kredit">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pilih kredit</h4>
            </div>
            <div class="modal-body">
               <div class="form-group row">
                        <label for="" class="col-lg-4 control-label">Metode Pembayaran</label>
                        <div class="col-lg-8">
                            <select name="kredit" id="kredit" class="form-control" required>
                                @foreach ($kredit as $metod)
                                    
                                <option value="{{$metod->id}}">{{$metod->metode}}</option>
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
                <button type="submit" class="btn btn-info btn-md  btn-simpan" style="width: 27rem;"><i class="fa fa-floppy-o"></i> Simpan</button>
            </div>
        </div>
    </div>
</div>