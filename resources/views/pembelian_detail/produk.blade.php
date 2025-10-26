<div class="modal fade" id="modal-produk" tabindex="-1" role="dialog" aria-labelledby="modal-produk">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pilih Produk</h4>
            </div>
         

            <div class="modal-body">
                <div class="table-responsive">

                    <table class="table table-striped table-bordered table-produk">
                        <thead>
                        <th width="5%">No</th>
                      
                        <th>Kode</th>
                        <th>Nama</th>
                        <th width="5%">Satuan</th>
                        <th width="5%"><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>
                        @foreach ($produk as $key => $item)
                            <tr>
                                <td width="5%">{{ $key+1 }}</td>
                              
                                <td>{{ $item->kode_obat ??0}}</td>
                                <td>{{ $item->nama_obat ??0}}</td>
                                <td>{{ $item->satuan ?? 0 }}</td>
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
</div>