
                
           <table class="table table-stiped table-bordered">
            <thead>
                <tr>

                    <th width="5%">No</th>
                    <th>Kode Obat</th>
                    <th>Obat</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual </th>
                    <th>Total Penjualan </th>
                    {{-- <th>Pendapatan</th> --}}
                </tr>
            </thead>
            @foreach ($detil as $item)
            <tbody>
                    
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$item->prodk->kode_obat??0}}</td>
                    <td>{{$item->prodk->nama_obat??0}}</td>
                    <td>{{$item->prodk->harga_beli??0}}</td>
                    <td>{{$item->prodk->harga_jual??0}}</td>
                    <td>{{$item->total}}</td>
                </tr>
            </tbody>
            @endforeach
        </table>