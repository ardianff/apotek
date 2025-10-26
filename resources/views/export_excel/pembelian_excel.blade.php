<table class="table table-striped table-bordered table-pembelian ">
    <thead>
        <tr style="background-color: rgb(201, 201, 201);color:rgb(3, 7, 58);" >
        
        <th width="5%" >No</th>
        <th>Tanggal Input</th>
        <th>Tanggal Faktur</th>
        <th>No Faktur</th>
        <th>Supplier</th>
        <th>Total Item</th>
        <th>Total Harga</th>
        <th>Diskon</th>
        <th>Potongan</th>
        <th>Status</th>
        <th>Total Pembayaran</th>
       
        
    </tr>
    </thead>
    <tbody>
        @foreach ($pemb as $hey=>  $item)
            
        <tr style="background-color: black;color: white;">
            <td>{{$loop->iteration}}</td>
            <td style="width: 10%;">{{tanggal_indonesia($item->created_at)}}</td>
            <td style="width: 10%;">{{$item->tgl_faktur}}</td>
            <td>{{$item->no_faktur}}</td>
            <td>{{$item->supplier->name}}</td>
            <td> {{format_uang($item->total_item)}}</td>
            <td>Rp. {{format_uang($item->total_harga)}}</td>
            <td>{{$item->diskon}}</td>
            <td>{{$item->potongan}}</td>
            <td>{{$item->status}}</td>
            <td>Rp. {{format_uang($item->bayar)}}</td>
           
        </tr>
       
        <tr style="background-color: rgb(201, 201, 201);color:rgb(0, 10, 10);">
            <td><b>No</b></td>
            <td><b>ED</b></td>
            <td><b>Batch</b></td>
            <td><b>Nama Obat</b></td>
            <td><b>Satuan</b></td>
            <td><b>Harga Beli Ecer</b></td>
            <td><b>Harga Beli Grosir</b></td>
            <td><b>Diskon</b></td>
            <td><b>Jumlah</b></td>
            <td><b>Isi</b></td>
            <td><b>Subtotal</b></td>
          
        </tr>
            
        @foreach ($detail[$hey] as  $putih)
            
        <tr style="background-color:rgb(7, 30, 39) ;color: rgb(255, 255, 255)">
            <td>
                {{chr(64+ $loop->iteration)}} 
            </td>
            <td> {{$putih->ed}}</td>
            <td> {{$putih->batch}}</td>
            <td style="width: 20%;">
                {{$putih->produk->nama_obat ?? 0}}
        </td>
            <td>
                {{$putih->produk->satuan ?? 0}}
        </td>
            <td>
               Rp. {{format_uang($putih->harga_beli??0)}}
        </td>
            <td>
               Rp. {{format_uang($putih->hb_grosir??0)}}
        </td>
            <td>
                {{$putih->diskon??0}}
        </td>
            <td>
                {{$putih->jumlah??0}}
        </td>
            <td>
                {{$putih->isi??0}}
        </td>
            <td>
              Rp.  {{format_uang($putih->subtotal??0)}}
        </td>
       
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
           
        
        </tr>
        @endforeach
        <tr>
            <td colspan="7" ><h4 style="float: right"><b >TOTAL :</b></h4></td>
            
            <td colspan="2"><h4><b>Rp {{format_uang($totali)}}</b></h4></td>
        </tr>
    </tbody>
</table>