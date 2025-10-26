
                <table class="table table-striped table-bordered table-penjualan ">
                    <thead>
                        <tr style="background-color: rgb(201, 201, 201);color:rgb(3, 7, 58);" >
                        
                        <th width="5%" >No</th>
                        {{-- <th>Tanggal</th> --}}
                        <th>kode obat</th>
                        <th>nama obat</th>
                        <th>satuan</th>
                        <th>isi</th>
                        <th>harga Beli</th>
                        <th>harga Beli Grosir</th>
                        <th>Margin</th>
                        <th>harga Jual</th>
                        <th>harga Jual Grosir</th>
                        <th>Jumlah Penjualan</th>
                        <th>Total Penjualan</th>
                        <th>Total Modal</th>
                        <th>Total Laba</th>
                       
                        
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($penjualan as $hey=>  $item)
                            
                        <tr style="background-color: black;color: white;">
                            <td>{{$loop->iteration}}</td>
                            {{-- <td>{{tanggal_indonesia($item['tanggal']??0)}}</td> --}}
                            <td>{{$item['kode_obat']}}</td>
                            <td>{{$item['nama_obat']}}</td>
                            <td>{{$item['satuan']}}</td>
                            <td>{{$item['isi']}}</td>
                            <td>{{$item['harga_beli']}}</td>
                            <td>{{$item['hb_grosir']}}</td>
                            <td>{{$item['margin']}}</td>
                            <td>{{$item['harga_jual']}}</td>
                            <td>{{$item['hj_grosir']}}</td>
                            <td>{{$item['jumlah']}}</td>
                            <td>Rp. {{format_uang($item['total_penjualan'])}}</td>
                            <td>Rp. {{format_uang($item['total_modal'])}}</td>
                            <td>Rp. {{format_uang($item['total_laba'])}}</td>
                           
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="7" ><h4 style="float: right"><b >TOTAL :</b></h4></td>
                            
                            <td colspan="2"><h4><b>Rp {{format_uang($totali)}}</b></h4></td>
                        </tr>
                    </tbody>
                </table>
          