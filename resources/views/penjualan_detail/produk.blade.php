<div class="modal fade" id="modal-produk" tabindex="-1" role="dialog" aria-labelledby="modal-produk">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pilih Obat</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">

                    <table class="table table-striped table-bordered table-produk">
                        <thead>
                        <th width="5%">No</th>
                      
                        <th>Nama</th>
                        <th width="5%">Stok</th>
                        <th width="5%">Satuan</th>
                        <th width="5%">Expired date</th>
                        <th>Harga Jual</th>
                        <th width="5%"><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>
                        @foreach ($obat as $key => $item)
                            <tr>
                                <td width="5%">{{ $key+1 }}</td>
                              
                                <td>{{ $item->obat->nama_obat ??0}}</td>
                                <td>{{ $item->stock ??0}}</td>
                                <td>{{ $item->obat->satuan ?? 0 }}</td>
                                <td style="max-width: 50px">{{ $item->ed ??0}}</td>
                                <td>{{ $item->harga_jual ??0}}</td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-xs btn-flat"
                                        onclick="pilihProduk('{{ $item->id }}')">
                                        <i class="fa fa-check-circle"></i>
                                        Pilih
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>