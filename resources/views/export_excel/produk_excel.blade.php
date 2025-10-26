{{-- <link rel="stylesheet" href="{{ asset('/AdminLTE-2/bower_components/bootstrap/dist/css/bootstrap.min.css') }}"> --}}
<table >
    <thead>
      <tr>

          <th width="5%">No</th>
          <th>Kode Obat</th>
        <th>Nama</th>
        <th>satuan</th>
        <th>stok</th>
       
      
        
    </tr>

    </thead>
    <tbody>
        @foreach ($produk as $item)
        <tr>
           <td>{{$loop->iteration}}</td> 
           <td>{{$item->kode_obat??0}}</td> 
           <td>{{$item->nama_obat??0}}</td> 
           <td>{{$item->satuan ??0}}</td> 
           <td>{{$item->stock_produk}}</td> 
          
          
          
        </tr>
        @endforeach
        
    </tbody>