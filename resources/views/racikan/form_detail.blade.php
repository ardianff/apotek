<div class="modal fade" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="modal-detail">
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
                    <input type="hidden" name="racikan_id" value="{{$rck->id}}">
                    <div class="form-group row">
                        <label for="produk_id" class="col-lg-2 col-lg-offset-1 control-label">Obat</label>
                        <div class="col-lg-6">
                            <select name="produk_id" id="mySelect2" class="form-control">
                                @foreach ($produk as $item)
                                    
                                <option value="{{$item->id}}">{{$item->nama_obat}} | {{$item->stock}} | {{$item->satuan}} | {{format_uang($item->harga_jual)}} </option>
                                @endforeach
                            </select>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="jumlah" class="col-lg-2 col-lg-offset-1 control-label">jumlah</label>
                        <div class="col-lg-6">
                            <input type="number" name="jumlah" id="jumlah" class="form-control uang" required autofocus>
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