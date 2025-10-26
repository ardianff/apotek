<div class="modal fade" id="modal-supplier" tabindex="-1" role="dialog" aria-labelledby="modal-supplier">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pilih Supplier </h4>
                <h5 class="text-danger"> Belum Ada Supplier 

                    <button onclick="addSupplier('{{ route('supplier.store') }}')" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Tambah Supplier Baru</button>
                </h5>

            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-supplier">
                    <thead>
                        <th width="5%">No</th>
                        <th>Nama</th>
                        <th>Telepon</th>
                        <th>Alamat</th>
                        <th><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>
                        @foreach ($supplier as $key => $item)
                            <tr>
                                <td width="5%">{{ $key+1 }}</td>
                                <td>{{ $item->nama_supplier }}</td>
                                <td>{{ $item->telepon }}</td>
                                <td>{{ $item->alamat }}</td>
                                <td>
                                    {{-- <div class="input-group-btn">
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Pili
                                        <span class="fa fa-caret-down"></span></button>
                                        <ul class="dropdown-menu">
                                        <li> <a href="{{ route('pembelian.create', $item->id,1) }}" class="btn btn-primary btn-xs btn-flat">
                                            <i class="fa fa-check-circle"></i>
                                             PPN
                                        </a></li>
                                        <li> <a href="{{ route('pembelian.create', $item->id,2) }}" class="btn btn-primary btn-xs btn-flat">
                                            <i class="fa fa-check-circle"></i>
                                            NON PPN
                                        </a></li>
                                       
                                        </ul>
                                        </div> --}}
                                    <a href="{{ route('pembelian.create', $item->id) }}" class="btn btn-primary btn-xs btn-flat">
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