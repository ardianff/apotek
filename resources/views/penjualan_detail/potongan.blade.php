<div class="modal fade" id="modal-potongan" tabindex="-1" role="dialog" aria-labelledby="modal-potongan">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pilih diskon</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-member">
                    <thead>
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Diskon</th>
                        <th>Keterangan</th>
                        <th><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>
                        @foreach ($pot as $key => $item)
                            <tr>
                                <td width="5%">{{ $key+1 }}</td>
                                <td>{{ $item->kode }}</td>
                                <td>{{ $item->nominal }}</td>
                                <td>{{ $item->ket_diskon }}</td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-xs btn-flat"
                                        onclick="pilihpotongan('{{ $item->nominal }}', '{{ $item->kode }}')">
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