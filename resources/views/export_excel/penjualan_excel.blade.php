
                <table class="table table-striped table-bordered table-penjualan ">
                    <thead>
                        <tr style="background-color: rgb(201, 201, 201);color:rgb(3, 7, 58);" >
                        
                        <th width="5%" >No</th>
                        <th>Tanggal</th>
                        <th>No Nota</th>
                        <th>Petugas</th>
                        <th>Shift</th>
                        <th>Metode</th>
                        <th>total harga</th>
                        <th>Diskon</th>
                        <th>total Bayar</th>
                        
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($penj as $hey=>  $item)
                            
                        <tr style="background-color: black;color: white;">
                            <td>{{$loop->iteration}}</td>
                            <td>{{tanggal_indonesia($item->created_at)}}</td>
                            <td>{{$item->no_nota}}</td>
                            <td>{{$item->user->name}}</td>
                            <td>{{$item->shift->nama_shift}}</td>
                            <td>{{$item->metod->metode ?? 0}}</td>
                            <td>Rp. {{format_uang($item->total_harga)}}</td>
                            <td>{{$item->diskon}}</td>
                            <td>Rp. {{format_uang($item->bayar)}}</td>
                           
                        </tr>
                       
                        <tr style="background-color: rgb(201, 201, 201);color:rgb(0, 10, 10);">
                            <td></td>
                            <td><b>No</b></td>
                            <td><b>Nama Obat</b></td>
                            <td><b>Satuan</b></td>
                            <td><b>Harga</b></td>
                            <td><b>Jumlah</b></td>
                            <td><b>Subtotal</b></td>
                            <td></td>
                            <td></td>
                        </tr>
                            
                        @foreach ($detail[$hey] as  $putih)
                            
                        <tr style="background-color:rgb(7, 30, 39) ;color: rgb(255, 255, 255)">
                            <td></td>
                            <td>
                                {{chr(64+ $loop->iteration)}} 
                            </td>
                            <td>
                                {{$putih->prodk->nama_obat ?? 0}}
                        </td>
                            <td>
                                {{$putih->prodk->satuan ?? 0}}
                        </td>
                            <td>
                               Rp. {{format_uang($putih->harga_jual??0)}}
                        </td>
                            <td>
                                {{$putih->jumlah??0}}
                        </td>
                            <td>
                              Rp.  {{format_uang($putih->subtotal??0)}}
                        </td>
                        <td></td>
                        <td></td>
                    </tr>

                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                           
                        
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="7" ><h4 style="float: right"><b >TOTAL :</b></h4></td>
                            
                            <td colspan="2"><h4><b>Rp {{format_uang($totali)}}</b></h4></td>
                        </tr>
                    </tbody>
                </table>
          