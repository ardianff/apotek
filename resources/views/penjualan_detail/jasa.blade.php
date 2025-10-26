<div class="modal fade" id="modal-jasa" tabindex="-1" role="dialog" aria-labelledby="modal-jasa">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pilih Jasa</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-jasa">
                    <thead>
                        <th width="5%">No</th>
                        <th>jasa</th>
                        <th>Nominal</th>
                      
                        <th><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>
                        @foreach ($jasa as $key => $item)
                            <tr>
                                <td width="5%">{{ $key+1 }}</td>
                                <td>{{ $item->nama_jasa }}</td>
                                <td>{{ $item->nominal }}</td>
                               
                                <td>
                                    <a href="#" class="btn btn-primary btn-xs btn-flat"
                                        onclick="pilihjasa('{{ $item->nominal}}')">
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