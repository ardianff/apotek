{{-- <link rel="stylesheet" href="{{ asset('/AdminLTE-2/bower_components/bootstrap/dist/css/bootstrap.min.css') }}"> --}}
<table >
    <thead>
      <tr>

          <th width="5%">No</th>
          <th>Kode Obat</th>
        <th>Nama</th>
        <th>stok</th>
        <th>satuan</th>
        <th>isi</th>
        <th>Harga Beli </th>
        <th>margin</th>
        <th>Harga Jual </th>
        <th>expired date</th>
        <th>Batch</th>
      
        
    </tr>

    </thead>
    <tbody>
        @foreach ($produk as $item)
        <tr>
           <td>{{$loop->iteration}}</td> 
           <td>{{$item->obat->kode_obat??0}}</td> 
           <td>{{$item->obat->nama_obat??0}}</td> 
           <td>{{$item->stock}}</td> 
           <td>{{$item->obat->satuan ??0}}</td> 
           <td>{{$item->obat->isi ??0}}</td> 
           <td>{{$item->harga_beli}}</td> 
           <td>{{$item->margin}}</td> 
           <td>{{$item->harga_jual}}</td>  
           <td>{{$item->ed ?? 0}}</td> 
           <td>{{$item->batch ?? 0}}</td> 
          
        </tr>
        @endforeach
        
    </tbody>