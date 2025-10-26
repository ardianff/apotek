<div class="modal fade " id="modal-transfer" tabindex="-1" role="dialog" aria-labelledby="modal-transfer">
    <div class="modal-dialog modal-lg " role="document">
        <form action="{{route('detailObat.transfer') }}" method="post" class="form-horizontal">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                </div>
        <div class="modal-body">
            

                       
                        <input type="hidden" name="id_detail_obat">
                        <input type="hidden" name="produk_id">
                        <div class="form-group row">
                            <label for="lokasi" class="col-lg-2 col-lg-offset-1 control-label"> Lokasi</label>
                            <div class="col-lg-6">
                                <select name="lokasi" id="lokasi" class="form-control" required>
                                    <option value="">Pilih Lokasi</option>
                                    @foreach ($lokasi as $lok)
                                    <option value="{{ $lok->id_lokasi }}">{{ $lok->nama_lokasi }}</option>
                                    @endforeach
                                </select>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="jumlah" class="col-lg-2 col-lg-offset-1 control-label">Jumlah</label>
                            <div class="col-lg-6">
                                <input type="number" name="jumlah" id="jumlah" class="form-control"  value="0">
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