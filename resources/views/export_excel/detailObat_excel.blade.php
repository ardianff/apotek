<table class="table table-stiped table-bordered">
    <thead> 
        <tr>

            <th width="5%">No</th>  
            <th>Kode Obat</th>
            <th>Nama</th>
        <th>Satuan</th>
        <th>stock</th>
        <th>expired date</th>
        <th>batch</th>
        <th>Lokasi</th>
        <th>harga beli</th>
        <th>harga jual</th>
    </tr>
    </thead>
    <tbody>
        @foreach ($detil as $item)
            
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$item->obat->kode_obat?? ''}}</td>
            <td>{{$item->obat->nama_obat?? ''}}</td>
            <td>{{$item->obat->satuan??''}}</td>
            <td>{{$item->stock??0}}</td>
            <td>{{$item->ed??''}}</td>
            <td>{{$item->batch??0}}</td>
            <td>{{$item->lokasi->name??0}}</td>
            <td>{{$item->harga_beli??0}}</td>
            <td>{{$item->harga_jual??0}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<table class="table table-stiped table-bordered">
    <thead> 
        <tr>

            <th width="5%">No</th>  
            <th>Kode Obat</th>
            <th>Nama</th>
        <th>Satuan</th>
        <th>stock</th>
        <th>expired date</th>
        <th>batch</th>
        <th>Lokasi</th>
        <th>harga beli</th>
        <th>harga jual</th>
    </tr>
    </thead>
    <tbody>
        @foreach ($produk as $pdk)
            
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$pdk->kode_obat?? ''}}</td>
            <td>{{$pdk->nama_obat?? ''}}</td>
            <td>{{$pdk->satuan??''}}</td>
          <td>0</td>
          <td>0</td>
          <td>0</td>
          <td>0</td>
          <td>0</td>
          <td>0</td>
        </tr>
        @endforeach
    </tbody>
</table>