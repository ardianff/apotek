@extends('layouts.master')

@section('title')
    Retur Pembelian
@endsection

@push('css')
<style>
    .tampil-bayar {
        font-size: 5em;
        text-align: center;
        height: 100px;
    }

    .tampil-terbilang {
        padding: 10px;
        background: #f0f0f0;
    }

    .table-pembelian tbody tr:last-child {
        display: none;
    }

    @media(max-width: 768px) {
        .tampil-bayar {
            font-size: 3em;
            height: 70px;
            padding-top: 5px;
        }
    }
</style>
@endpush

@section('breadcrumb')
    @parent
    <li class="active">Retur Pembelian</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <table>
                    <tr>
                        <td>Supplier</td>
                        <td>: {{ $pembelian->tgl_faktur }}</td>
                    </tr>
                    <tr>
                        <td>Telepon</td>
                        <td>: {{ $pembelian->supplier->telepon }}</td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>: {{ $pembelian->supplier->alamat }}</td>
                    </tr>
                </table>

            </div>
            <div class="box-body">
                <table class="table table-stiped table-bordered">
                    <thead>
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th width="15%">Jumlah</th>
                        <th width="15%">Diskon</th>
                        <th >ED</th>
                        <th >Batch</th>
                        <th>Subtotal</th>
                        <th width="5%"><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>
                        @foreach ($daftar as$key=> $item)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$item->produk->kode_obat}}</td>
                                <td>{{$item->produk->nama_obat}}</td>
                                <td>Rp. {{format_uang($item->harga_beli)}}</td>
                                <td>{{$item->jumlah}}</td>
                                <td>{{$item->diskon}}</td>
                                <td>{{$item->ed}}</td>
                                <td>{{$item->batch}}</td>
                                <td>Rp. {{format_uang($item->subtotal)}}</td>
                                <td>  <a href="#" class="btn btn-primary btn-xs btn-flat"
                                    onclick="pilihProduk('{{ $item->id }}','{{$item->diskon}}')">
                                    <i class="fa fa-check-circle"></i>
                                    Pilih
                                </a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
               

             
            </div>

         
        </div>

    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header">

            </div>
            <div class="box-body">
                <form class="form-produk" >
                    @csrf
                    
                    <input type="hidden" name="retur_id" id="retur_id" value="{{ $id_retur }}">
                    <input type="hidden" name="detail_id" id="detail_id">
                    <input type="hidden" name="diskon" id="diskon">
                            
                </form>
                <table class="table table-stiped table-bordered table-pembelian">
                    <thead>
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th width="15%">Jumlah</th>
                        <th width="15%">Diskon</th>
                        <th >ED</th>
                        <th >Batch</th>
                        <th>Subtotal</th>
                        <th width="5%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
            </div>
            <form class="form-pembelian" method="post" action="{{route('retur_pembelian.store')}}">
                @csrf
                <input type="hidden" name="id_pembelian_retur" id="id_pembelian_retur" value="{{ $id_retur }}">
            </form>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-sm btn-flat pull-right btn-simpan"><i class="fa fa-floppy-o"></i> Simpan Retur</button>
            </div>
        </div>
    </div>
</div>
{{-- @includeIf('pembelian_detail.produk') --}}
@endsection

@push('scripts')
<script>
    let table, table2;

    $(function () {

        $('body').addClass('sidebar-collapse');

        
      
        table = $('.table-pembelian').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('retur_pembelian_detail.data',$id_retur) }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_produk'},
                {data: 'nama_produk'},
                {data: 'harga_beli'},
                {data: 'jumlah'},
                {data: 'diskon'},
                {data: 'ed'},
                {data: 'batch'},
                {data: 'subtotal'},
                {data: 'aksi', searchable: false, sortable: false},
            ],
            dom: 'Brt',
            bSort: false,
            paginate: false
        });   

        $(document).on('input', '.quantity', function () {
            let id = $(this).data('id');
            let jumlah = parseInt($(this).val());

            if (jumlah < 1) {
                $(this).val(1);
                alert('Jumlah tidak boleh kurang dari 1');
                return;
            }
            if (jumlah > 10000) {
                $(this).val(10000);
                alert('Jumlah tidak boleh lebih dari 10000');
                return;
            }

            $.post(`{{ url('/retur_pembelian_detail') }}/${id}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'put',
                    'jumlah': jumlah
                })
                .done(response => {
                    $(this).on('mouseout', function () {
                        table.ajax.reload();
                    });
                })
                
        });

       

       

        $('.btn-simpan').on('click', function () {
            $('.form-pembelian').submit();
        });
    });


    function pilihProduk(id,diskon) {
        $('#detail_id').val(id);
        $('#diskon').val(diskon);
        tambahProduk();
    }

    function tambahProduk() {
        $.post('{{ route('retur_pembelian_detail.store') }}', $('.form-produk').serialize())
            .done(response => {
                table.ajax.reload();
            })
            .fail(errors => {
                alert('Tidak dapat menyimpan data');
                return errors;
            });
    }

    function deleteData(url) {
        if (confirm('Yakin ingin menghapus data terpilih?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload();
                })
                .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    return;
                });
        }
    }
   

</script>
@endpush