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
            <input type="hidden" name="cabang_id" id="cabang_id" value="{{ auth()->user()->cabang_id }}">
            <div class="row">
                <div class="col-md-2">
                    <label for="kode_obat" class="control-label">Kode Obat</label>
                    <input type="text" name="kode_obat" id="kode_obat" class="form-control" disabled>
                    <span class="help-block with-errors"></span>
                </div>
                <div class="col-md-10">
                        <label for="nama_obat" class="control-label">Nama Obat</label>
                            <input type="text" name="nama_obat" id="nama_obat" class="form-control" required autofocus>
                            <span class="help-block with-errors"></span>
                </div>
            </div>
            
            
            <div class="row">
                <div class="col-md-3">
                    <label for="satuan" class="control-label">satuan</label>
                        <input type="text" name="satuan" id="satuan" class="form-control" required>
                        <span class="help-block with-errors"></span>
                </div>
                <div class="col-md-3">
                    <label for="isi" class=" control-label">isi</label>
                    <div class="input-group input-group-sm">
                        <input type="number" name="isi" id="isi" class="form-control" value="0" required>
                    </div>
                    <span class="help-block with-errors"></span>
                </div>

                <div class="col-md-3">
                    <label for="stok_minim" class=" control-label">Stok Minimal</label>
                        <input type="text" name="stok_minim" id="stok_minim" class="form-control" value="0" >
                        <span class="help-block with-errors"></span>
                </div>
                <div class="col-md-3">
                    <label for="margin" class=" control-label">margin</label>
                        <input type="number" name="margin" id="margin" class="form-control " value="0" required>
                        <span class="help-block with-errors"></span>
                </div>
               
              
            </div>
           
           
            <div class="row">
                <div class="col-md-4">
                    <label for="kategori_id" class=" control-label">kategori</label>
                        <select name="kategori_id" id="kategori_id" class="form-control" >
                            @foreach ($kategori as $gl)
                            <option value="{{ $gl->id }}">{{ $gl->nama_kategori }}</option>
                            @endforeach
                        </select>
                        <span class="help-block with-errors"></span>
                </div>
                <div class="col-md-4">
                    <label for="golongan_id" class="control-label">golongan</label>
                            <select name="golongan_id" id="golongan_id" class="form-control" >
                                @foreach ($golongan as $gl)
                                <option value="{{ $gl->id }}">{{ $gl->nama_gol }}</option>
                                @endforeach
                            </select>
                            <span class="help-block with-errors"></span>
                </div>
                <div class="col-md-4">
                    <label for="jenis_id" class=" control-label">jenis</label>
                        <select name="jenis_id" id="jenis_id" class="form-control" >
                            @foreach ($jenis as $jns)
                            <option value="{{ $jns->id }}">{{ $jns->nama_jenis }}</option>
                            @endforeach
                        </select>
                        <span class="help-block with-errors"></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <label for="konsi" class=" control-label">Konsinasi</label>
                        <select name="konsi" id="konsi" class="form-control" >
                           
                            <option value="0">tidak</option>
                            <option value="1">Ya</option>
                        </select>
                        <span class="help-block with-errors"></span>
                </div>
                <div class="col-md-4">
                    <label for="merk" class="control-label">Principal</label>
                        <input type="text" name="merk" id="merk" class="form-control">
                        <span class="help-block with-errors"></span>
                </div>
                <div class="col-md-4">
                    <label for="dosis" class=" control-label">dosis</label>
                            <input type="text" name="dosis" id="dosis" class="form-control">
                            <span class="help-block with-errors"></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label for="kandungan" class=" control-label">kandungan</label>
                        <input type="text" name="kandungan" id="kandungan" class="form-control">
                        <span class="help-block with-errors"></span>
                </div>
                <div class="col-md-3">
                    <label for="kegunaan" class=" control-label">kegunaan</label>
                        <input type="text" name="kegunaan" id="kegunaan" class="form-control">
                        <span class="help-block with-errors"></span>
                </div>
                <div class="col-md-3">
                    <label for="efek" class=" control-label">Efek Samping</label>
                        <input type="text" name="efek" id="efek" class="form-control">
                        <span class="help-block with-errors"></span>
                </div>
                <div class="col-md-3">
                    <label for="zat" class=" control-label">zat Prekursor Aktif</label>
                            <input type="text" name="zat" id="zat" class="form-control">
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