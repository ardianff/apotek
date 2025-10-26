<div class="modal fade" id="modal-produk" tabindex="-1" role="dialog" aria-labelledby="modal-produk">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pilih Produk</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-produk">
                    <thead>
                        <th width="5%">No</th>
                      
                        <th>Nama</th>
                        <th>Stok</th>
                        <th>Satuan</th>
                        <th  width="5%">Lokasi</th>
                        <th>Harga Jual</th>
                        <th><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>
                        @foreach ($produk as $key => $item)
                            <tr>
                                <td width="5%">{{ $key+1 }}</td>
                              
                                <td>{{ $item->nama_obat }}</td>
                                <td>{{ $item->stock }}</td>
                                <td>{{ $item->obat->satuan ?? 0 }}</td>
                                <td  width="5%">{{ $item->lokasi->name??0 }}</td>
                                <td>{{ $item->harga_jual }}</td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-xs btn-flat"
                                        onclick="pilihProduk('{{ $item->id }}', '{{ $item->kode_obat }}')">
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