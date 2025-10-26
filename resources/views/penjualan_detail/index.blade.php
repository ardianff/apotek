@extends('layouts.master')

@section('title')
    Transaksi Penjualan
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

    .table-penjualan tbody tr:last-child {
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
    <li class="active">Transaksi Penjaualn</li>
@endsection

@section('content')

<div class="row" >
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body">
            

                            <form class="form-produk">
                                @csrf
                    <div class="form-group row">
                        <label for="kode_produk" class="col-lg-2">Pilih Produk</label>
                        <div class="col-lg-7">
                            <div class="input-group">
                                <input type="hidden" name="penjualan_id" id="penjualan_id" value="{{ $penjualan_id }}">
                                <input type="hidden" name="produk_id" id="produk_id">
                                <select id="pilih_produk" class="form-control select2 pilih_produk" name="pilih_produk" >
                                    <option value=""></option>
                                    @foreach ($obat as $obt)
                                        
                                    <option value="{{$obt->id}}"><h3>{{$obt->obat->nama_obat??0}} &nbsp;||&nbsp;  Stok : {{$obt->stock??0}} &nbsp; || {{$obt->satuan??0}} &nbsp; || Rp. {{format_uang($obt->harga_jual??0)}}  &nbsp; || &nbsp; {{$obt->ed ?? ''}}  &nbsp; || &nbsp; {{$obt->lokasi->name??''}}</h3></option>
                                    @endforeach
                                </select>
                                    <button onclick="tampilProduk()" class="btn btn-info btn-flat" type="button"><i class="fa fa-search-plus"></i></button>
                            </div>
                        </div>
                        
                    </div>
                </form>
                      
                <div class="row">
                    <div class="col-lg-8">
                        
                 <div class="row">
                    <div class="col-lg-12">

                    
                <table class="table table-stiped table-bordered table-penjualan">
                    <thead>
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Batch</th>
                        <th>Harga</th>
                        <th style="width: 20px">Jumlah</th>
                        <th style="width: 20px">Diskon</th>
                        <th>Subtotal</th>
                        <th width="5%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="tampil-bayar bg-primary"></div>
                <div class="tampil-terbilang"></div>
                @if (\Session::has('gagal'))
<div class="alert alert-warning alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
    <h4><i class="icon fa fa-info"></i> {{ \Session::get('gagal') }}</h4>
    
    </div>
@endif
            </div> 
            </div> 

            </div> 
            <div class="col-lg-4">
                <form action="{{ route('transaksi.simpan') }}" class="form-penjualan" method="post">
                    @csrf
                    <input type="hidden" name="penjualan_id" value="{{ $penjualan_id }}">
                    <input type="hidden" name="total" id="total">
                    <input type="hidden" name="total_item" id="total_item">
                    <input type="hidden" name="bayar" id="bayar">
                    <input type="hidden" name="metode_id" id="metode">
                    <input type="hidden" name="no_kartu" id="no_kartu">

                    <div class="form-group row">
                        <label for="totalrp" class="col-lg-4 control-label">Total</label>
                        <div class="col-lg-8">
                            <input type="text" id="totalrp" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="kode_diskon" class="col-lg-4 control-label">Kode Diskon</label>
                        <div class="col-lg-8">
                            <div class="input-group">
                                <input type="text" class="form-control" id="kode_diskon" value="">
                                <span class="input-group-btn">
                                    <button onclick="tampilpotongan()" class="btn btn-info btn-flat" type="button"><i class="fa fa-arrow-right"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="jasa" class="col-lg-4 control-label">Pilih Jasa</label>
                        <div class="col-lg-8">
                            <div class="input-group">
                                <input type="text" class="form-control" id="jasa" name="jasa" value="{{$penjualan->jasa}}">
                                <span class="input-group-btn">
                                    <button onclick="tampiljasa()" class="btn btn-info btn-flat" type="button"><i class="fa fa-user-md"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="member" class="col-lg-4 control-label">Pilih member</label>
                        <div class="col-lg-8">
                            <div class="input-group">
                                <input type="text" class="form-control" id="member" name="member" placeholder="member">
                                <input type="hidden" class="form-control" id="member_id" name="member_id" value="0">
                                <span class="input-group-btn">
                                    <button onclick="tampilMember()" class="btn btn-info btn-flat" type="button"><i class="fa fa-user"></i></button>
                                </span>
                                       
                                    </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="diskon" class="col-lg-4 control-label">Diskon</label>
                        <div class="col-lg-8">
                            <input type="number" name="diskon" id="diskon" class="form-control" 
                                value="{{$penjualan->diskon}}" 
                                readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="bayarrp" class="col-lg-4 control-label">Bayar</label>
                        <div class="col-lg-8">
                            <input type="text" id="bayarrp" class="form-control uang" readonly>
                        </div>
                    </div>
                    {{-- <div class="form-group row">
                        <label for="" class="col-lg-4 control-label">Metode Pembayaran</label>
                        <div class="col-lg-8">
                            <select name="metode_id" id="" class="form-control">
                                @foreach ($metode as $metod)
                                    
                                <option value="{{$metod->id}}">{{$metod->metode}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> --}}
                    <div class="form-group row">
                        <label for="diterima" class="col-lg-4 control-label">Diterima</label>
                        <div class="col-lg-8">
                            <input type="text" id="diterima" class="form-control uang" name="diterima" value="{{ $penjualan->diterima ?? 0 }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="kembali" class="col-lg-4 control-label">Kembali</label>
                        <div class="col-lg-8">
                            <input type="text" id="kembali" name="kembali" class="form-control" value="0" readonly>
                        </div>
                    </div>
                </form>
            </div>   
        </div> 
        </div> 
               
            

            <div class="box-footer">
                <div class="row">
                    <div class="col-md-3"><a href="{{route('penjualan.cancel',$penjualan_id)}}" type="submit" class="btn btn-warning btn-md " style="margin-right:4em;" ><i class="fa fa-undo"></i> Batal </a></div>

                    <div class="col-md-3">  <button type="submit" class="btn btn-info btn-md  btn-kredit" style="width: 27rem;"><i class="fa fa-credit-card"></i> Kredit</button></div>

                    <div class="col-md-3">   <button type="submit" class="btn btn-primary btn-md  btn-debit" style="width: 27rem" ><i class="fa fa-qrcode"></i> Debit</button></div>

                    <div class="col-md-3"> <button type="submit" class="btn btn-success btn-md  btn-tunai" style="width: 27rem" ><i class="fa fa-money"></i> Tunai</button></div>
                </div>
               
             
              
                
            </div>
        </div>
    </div>
</div>

@includeIf('penjualan_detail.produk')
@includeIf('penjualan_detail.jasa')
@includeIf('penjualan_detail.debit')
@includeIf('penjualan_detail.kredit')
@includeIf('penjualan_detail.member')
@includeIf('member.form')
@includeIf('penjualan_detail.potongan')
@endsection

@push('scripts')
<script>
      $(function () {
        $('.select2').select2({
    });
    });
    let table, table2, table3;

    $(function () {
        $('body').addClass('sidebar-collapse');
        // $('div.dataTables_filter input', table.table().container()).focus();

        table = $('.table-penjualan').DataTable({
            responsive: true,
            // processing: true,
            // serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('transaksi.data', $penjualan_id) }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_produk'},
                {data: 'nama_produk'},
                {data: 'ed'},
                {data: 'harga_jual'},
                {data: 'jumlah'},
                {data: 'diskon'},
                {data: 'subtotal'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
            // dom: 'Brt',
            // bSort: false,
            // paginate: false
        });
        // .on('draw.dt', function () {
        //     loadForm($('#diskon').val(),$('#jasa').val());
        //     setTimeout(() => {
        //         $('#diterima').trigger('input');
        //     }, 300);
        // });
        table2 = $('.table-produk').DataTable();
        table3 = $('.table-member').DataTable();

        $(document).on('change', '.quantity', function () {
            let id = $(this).data('id');
            let max = parseInt($(this).data('max'));
            let jumlah = parseInt($(this).val());

            if (jumlah < 1) {
                $(this).val(1);
                alert('Jumlah tidak boleh kurang dari 1');
                return;
            }
            if (jumlah > max) {
                $(this).val(max);
                alert('Jumlah tidak boleh lebih dari '+max);
                return;
            }

            $.post(`{{ url('/transaksi') }}/${id}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'put',
                    'jumlah': jumlah
                })
                .done(response => {
                    // $(this).on('mouseout', function () {
                        table.ajax.reload(() => loadForm($('#diskon').val()));
                    // });
                })
                
        });

        $(document).on('input', '#diskon', function () {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }

            loadForm($(this).val());
        });

     
      

        $('#diterima').on('input', function () {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }
           
            loadForm($('#diskon').val(),$('#jasa').val(), $(this).val());
        }).focus(function () {
            $(this).select();
        });

        $('.btn-tunai').on('click', function () {
           $('#metode').val(1);
           $('.form-penjualan').submit();
        });
        $('.btn-debit').on('click', function () {
        $('#modal-debit').modal('show');
        });
        
        $('.btn-kredit').on('click', function () {
        $('#modal-kredit').modal('show');
        });

        $('#debit').on('change', function () {
            let debt=$(this).val();
            $('#metode').val(debt);
        });

        $('#kredit').on('change', function () {
            $('#metode').val($(this).val());
        });

        $('#no_card').on('input', function () {
            $('#no_kartu').val($(this).val());
        });


        $('.btn-simpan').on('click', function () {
          
           $('.form-penjualan').submit();
        });
    });

    function tampilProduk() {
        $('#dataTables_filter input').focus();
        $('#modal-produk').modal('show');

    }

    $('#resultadomodal').on( 'draw.dt', function () {
 
 $('#resultadomodal_filter input').on('keypress', function(event){

 if (event.keyCode ===13){

   $('.cantidad:first').focus();
  }
});} );

    function hideProduk() {
        $('#modal-produk').modal('hide');
    }

    $('.pilih_produk').on('change', function() {
        let id = $(this).val();
        $('#produk_id').val(id);
        tambahProduk();
});
    $('#kode_produk').on('input', function() {
        
        tampilProduk();
});
    function pilihProduk(id) {
        $('#produk_id').val(id);
        hideProduk();
        tambahProduk();
    }

   

    function tambahProduk() {
        $.post('{{ route('transaksi.store') }}', $('.form-produk').serialize())
            .done(response => {
                // console.log(response);
                $('#kode_produk').focus();
                table.ajax.reload(() => loadForm($('#diskon').val(),$('#jasa').val()));
            })
            .fail(errors => {
                alert('Tidak dapat menyimpan data');
                return;
            });
    }
    window.onload = function() {
        $('#kode_produk').focus();
}
   
    function tampilpotongan() {
        $('#modal-potongan').modal('show');
    }

    function pilihpotongan(nominal, kode) {
        $('#diskon').val(nominal);
        $('#kode_diskon').val(kode);
        loadForm($('#diskon').val(),$('#jasa').val());
        $('#diterima').val(0).focus().select();
        hidepotongan();
    }

    function hidepotongan() {
        $('#modal-potongan').modal('hide');
    }


    function tampiljasa() {
        $('#modal-jasa').modal('show');
    }

    function pilihjasa(nom) {
       
        $('#jasa').val(nom);
    
        loadForm($('#diskon').val(),$('#jasa').val());
        $('#diterima').val(0).focus().select();
        hidejasa();
    }
    
    function hidejasa() {
        $('#modal-jasa').modal('hide');
    }
    function tampilMember() {
        $('#modal-member').modal('show');
    }
    
    function pilihMember(id,nama) {
        $('#member').val(nama);
        $('#member_id').val(id);

        hidemember();
    }
    

    function hidemember() {
        $('#modal-member').modal('hide');
    }
    function deleteData(url) {
        if (confirm('Yakin ingin menghapus data terpilih?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload(() => loadForm($('#diskon').val()));
                })
                .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    return;
                });
        }
    }
    

    function loadForm(diskon = 0,jasa= 0, diterima = 0) {
        $('#total').val($('.total').text());
        $('#total_item').val($('.total_item').text());

        $.get(`{{ url('/transaksi/loadform') }}/${diskon}/${jasa}/${$('.total').text()}/${diterima}`)
            .done(response => {
                $('#totalrp').val('Rp. '+ response.totalrp);
                $('#bayarrp').val(response.bayarrp);
                $('#bayar').val(response.bayar);
                $('.tampil-bayar').text('Bayar: Rp. '+ response.bayarrp);
                $('.tampil-terbilang').text(response.terbilang);

                $('#kembali').val('Rp.'+ response.kembalirp);
                if ($('#diterima').val() != 0) {
                    $('.tampil-bayar').text('Kembali: Rp. '+ response.kembalirp);
                    $('.tampil-terbilang').text(response.kembali_terbilang);
                }
            })
            .fail(errors => {
                alert('Tidak dapat menampilkan data');
                return;
            })
    }
    function addMember(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Member');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
    }

    $('.uang').mask('0.000.000.000.000',{reverse:true})

  
</script>
@endpush