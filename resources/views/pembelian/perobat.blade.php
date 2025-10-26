@extends('layouts.master')

@section('title')
    Daftar Pembelian Per obat
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Pembelian Per obat</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header">

                <form action="{{route('pembelian.perobat')}}" method="GET">
                    <div class="row">

                        <div class="form-group">
                            <label for="" class="col-lg-1 col-lg-offset-1 control-label">Pilih Obat</label>
                <div class="col-lg-5">
                  
                    <select id="" class="form-control select2" name="obat">
                        @foreach ($produk as $item)
                           
                        <option  value="{{$item->id}}" {{ ($produk_id == $item->id ? "selected":"") }}>{{$item->nama_obat}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-2">
                <button type="submit" class="btn btn-success"><i class="fa fa-search" aria-hidden="true"></i> </button>
            </div>
        </div>
            
        </form>
    </div>
    <div class="box-body">
        <table class="table table-light">
            <thead>
                <tr><th>
                    no
                </th>
                    <th>Kode Obat</th>
                    <th>Nama Obat</th>
                    <th>tanggal</th>
                    <th>Supplier</th>
                    <th>Jumlah</th>
                    <th>Harga Beli Grosir</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                
                @foreach ($detail as $key=> $item)
                    
                <tr><td>{{ $loop->iteration}}</td>
                    <td>{{$item->produk->kode_obat??0}}</td>
                    <td>{{$item->produk->nama_obat??0}}</td>
                    <td>{{$item->pembelian->tgl_faktur??0}}</td>
                    <td>{{$item->pembelian->supplier->nama_supplier??0}}</td>
                    <td>{{$item->jumlah}}</td>
                    <td>{{$item->hb_grosir}}</td>
                    <td>{{$item->subtotal}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
            </div>
            
        </div>
    </div>

    @endsection

    @push('scripts')
<script>
    $(function () {
        $('.select2').select2({
    });
    });
</script>

@endpush