@extends('layouts.master')

@section('title')
    Transaksi Pembelian
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
    <li class="active">Transaksi Pembelian</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box mb-3">
            <div class="box-header with-border">
                <div class="row">
                    <div class="col-lg-3">

                        <table>
                            <tr>
                                <td>Supplier</td>
                                <td>: {{ $supplier->nama_supplier }}</td>
                            </tr>
                            <tr>
                                <td>Telepon</td>
                                <td>: {{ $supplier->telepon }}</td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>: {{ $supplier->alamat }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-lg-2">
                    <div class="form-group ">
                        <label for="no_faktur" class="control-label">NO Faktur</label>
                            <input type="text"  id="no_faktur" class="form-control " value="{{ $pembelian->no_faktur ?? '' }}" required>
                    </div>
                    </div>
                    @php
    use Carbon\Carbon;

    // Format tanggal jika ada nilainya
    $tglFaktur = isset($pembelian->tgl_faktur) ? Carbon::parse($pembelian->tgl_faktur)->format('d/m/Y') : '';
    $tempo = isset($pembelian->tempo) ? Carbon::parse($pembelian->tempo)->format('d/m/Y') : '';
    $diskonUntuk = $pembelian->diskon_untuk ?? 'apotek';
@endphp

<div class="col-lg-2">
    <div class="form-group">
        <label for="tgl_faktur" class="control-label">Tanggal Faktur</label>
        <input type="text" id="tgl_faktur" class="form-control datepicker" value="{{ $tglFaktur }}" required>
    </div>
</div>

<div class="col-lg-2">
    <div class="form-group">
        <label for="tempo" class="control-label">Tanggal Jatuh Tempo</label>
        <input type="text" id="tempo" class="form-control datepicker" value="{{ $tempo }}" required>
    </div>
</div>

<div class="col-lg-2">
    <div class="form-group">
        <label for="metode" class="control-label">Metode</label>
        <select name="metode" id="metode" class="form-control select2" style="width: 100%;" required>
            <option value=""></option>
            @foreach ($metode as $met)
                <option value="{{ $met->id }}" @if ($met->id == $pembelian->metode_id) selected @endif>
                    {{ $met->metode }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="col-lg-1">
    <div class="form-group">
        <label for="diskon" class="control-label">Diskon Untuk</label>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="diskon_untuk" id="diskon_apotek" value="apotek" {{ $diskonUntuk === 'apotek' ? 'checked' : '' }}>
            <label class="form-check-label" for="diskon_apotek">Apotek</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="diskon_untuk" id="diskon_konsumen" value="konsumen" {{ $diskonUntuk === 'konsumen' ? 'checked' : '' }}>
            <label class="form-check-label" for="diskon_konsumen">Konsumen</label>
        </div>
    </div>
</div>


            </div>
        </div>
        <div class="box mb-3">
           
                <div class="box-body">
                       <form class="form-produk" action="{{ route('pembelian_detail.store',$pembelian->id) }}" method="post">
                    @csrf
                    <input type="hidden" name="id_pembelian" id="id_pembelian" value="{{ $pembelian->id }}">
                    <div class="row">
                       

                            <div class="form-group col-lg-4">
                                <label >produk</label>
                                
                                <select id="produk_id" class="form-control select2 " style="width: 100%;" name="produk_id" >
                                    <option value=""></option>
                                    @foreach ($produk as $obt)
                                    
                                    <option value="{{$obt->id}}"  data-isi="{{$obt->isi}}" data-satuan="{{$obt->satuan}}"><h3>{{$obt->kode_obat}} || {{$obt->nama_obat}} || {{$obt->satuan}}</h3></option>
                                    @endforeach
                                </select>
                            </div>
                        <div class="form-group col-lg-1">
                            <label for="jumlah" class="">Jumlah</label>
                            
                                <input type="text" name="jumlah" id="jumlah" class="form-control" placeholder="Jumlah Produk" required>
                                
                    </div>
                        <div class="form-group col-lg-1 col-md-1">
                            <label for="isi" class="">Isi</label>
                            
                                <input type="text" name="isi" id="isi" class="form-control" placeholder="isi Produk" required>
                                
                    </div>
                        <div class="form-group col-lg-1 col-md-1">
                            <label for="satuan" class="">satuan</label>
                            
                                <input type="text" name="satuan" id="satuan" class="form-control" placeholder="satuan Produk" required>
                                
                    </div>
                     <div class="form-group col-lg-2">
                            <label for="ed" class="">ed</label>
                            
                                <input type="text" name="ed" id="ed" class="form-control datepicker" placeholder="ed Produk" required>
                                
                    </div>
                        <div class="form-group col-lg-2">
                            <label for="batch" class="">batch</label>
                            
                                <input type="text" name="batch" id="batch" class="form-control" placeholder="batch Produk" required value="0">
                                
                    </div>
                     <div class="form-group col-lg-2">
                            <label >lokasi</label>
                            
                                <select id="lokasi_id" class="form-control select2 " style="width: 100%;" name="lokasi_id" >
                                    <option value=""></option>
                                    @foreach ($lokasi as $lok)
                                        
                                    <option value="{{$lok->id}}"><h3>{{$lok->name}} </h3></option>
                                    @endforeach
                                </select>
                    </div>
                        <div class="form-group col-lg-3">
                            <label for="harga_ppn" class="">harga_ppn</label>
                                <input type="text" name="harga_ppn" id="harga_ppn" class="form-control uang" placeholder="harga_ppn Produk" required  value="0">
                                
                    </div>
                        <div class="form-group col-lg-3">
                            <label for="harga_nonppn" class="">harga_nonppn</label>
                            
                                <input type="text" name="harga_nonppn" id="harga_nonppn" class="form-control uang" placeholder="harga_nonppn Produk" required value="0">
                                
                    </div>
                        <div class="form-group col-lg-1">
                            <label for="diskon" class="">diskon</label>
                            
                                <input type="text" name="diskon" id="diskon" class="form-control" placeholder="diskon Produk" required value="0">
                                
                    </div>
                       <div class="col-lg-3">

                           <button class="btn btn-success btn-sm" style="margin-top:30px" type="submit">Simpan</button>  
                        </div>

                </div>
                </form>
                </div>
               
            </div>
           <div class="row">
<div class="col-lg-12">
    <div class="box">
        <div class="box-header with-border">
    <h5 class="box-title">Daftar Produk</h5>
        </div>
        <div class="box-body">
<div class="table-responsive">

    <table class="table table-stiped table-bordered table-pembelian">
        <thead>
            <tr>

                <th width="3%">No</th>
                <th width="5%">Kode</th>
            <th width="25%">Nama</th>
            <th width="5%">Jumlah</th>
            <th width="5%">isi</th>
            <th width="5%">satuan</th>
            <th width="5%">lokasi</th>
            <th style="width:7em">Harga non PPN</th>
            <th style="width:7em">Harga Beli PPN</th>
            {{-- <th>Harga Jual</th> --}}
            <th width="5%">Diskon</th>
            <th style="width:7em">ED</th>
            <th style="width:7em">Batch</th>
            <th style="width:7em">Subtotal</th>
            <th width="3%"><i class="fa fa-cog"></i></th>
        </tr>
        </thead>
        <tbody>

        </tbody>
                </table>
        </div>
        </div>
        </div>
    </div>
</div>


                <div class="row">
                    <div class="col-lg-7">
                        <div class="tampil-bayar bg-primary"></div>
                        <div class="tampil-terbilang"></div>
                    </div>
                    <div class="col-lg-5">
                        <form action="{{ route('pembelian.store') }}" class="form-pembelian" method="post">
                            @csrf
                            <input type="hidden" name="id_pembelian" value="{{ $pembelian->id }}">
                            <input type="hidden" name="total" id="total">
                            <input type="hidden" name="bayar" id="bayar">
                            <input type="hidden" name="no_faktur" id="isino_faktur" value="{{ $pembelian->no_faktur ?? '' }}" required>
                            <input type="hidden" name="tgl_faktur" id="isitgl_faktur" value="{{ $pembelian->tgl_faktur ?? '' }}" required>
                            <input type="hidden" name="metode_id" id="metode_id" value="{{ $pembelian->metode_id ?? '' }}" required>
                            <input type="hidden" name="dskn_for" id="dskn_for" value="{{ $pembelian->diskon_untuk ?? '' }}" required>
                            <input type="hidden" name="tempo" id="tgl_tempo" value="{{ $pembelian->tempo ?? '' }}" required>

                            <div class="form-group row">
                                <label for="total_item" class="col-lg-3 control-label">Total Item</label>
                                <div class="col-lg-8">
                                    <input type="text" name="total_item" id="total-item" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="totalrp" class="col-lg-3 control-label">Total</label>
                                <div class="col-lg-8">
                                    <input type="text" id="total-harga"  class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="diskon" class="col-lg-3 control-label">Diskon</label>
                                <div class="col-lg-8">
                                    <input type="number" name="diskon" id="diskon-total" class="form-control" value="0" placeholder="Masukkan diskon dalam persen">
                                </div>
                            </div>
                         
                            <div class="form-group row">
                                <label for="potongan" class="col-lg-3 control-label">potongan</label>
                                <div class="col-lg-8">
                                    <input type="text" id="potongan" name="potongan" class="form-control uang" value="0" placeholder="Masukkan potongan jika ada">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="bayar" class="col-lg-3 control-label">Bayar</label>
                                <div class="col-lg-8">
                                    <input type="text" id="bayarrp" class="form-control">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-md btn-flat pull-right btn-simpan "><i class="fa fa-floppy-o"></i> Simpan Transaksi</button>
                <a href="{{route('pembelian.cancel',$pembelian->id)}}" type="button" class="btn btn-warning btn-md btn-flat pull-right " style="margin-right:7em;"><i class="fa fa-arrow-left"></i> Batal </a>
            </div>
        </div>
    </div>
</div>

@includeIf('pembelian_detail.form')
@endsection

@push('scripts')
<script>

$(function () {
        $('.select2').select2({
    });
    });
    
    $(function () {
        $('body').addClass('sidebar-collapse');

        $('.uang').mask('000.000.000.000',{reverse:true});

        $('.datepicker').datepicker({
           format: 'dd-mm-yyyy',
        //    startDate: '1d',
           autoclose: true
       });
       $("#datepicker").on("change", function() {
    this.setAttribute(
        "data-date",
        moment(this.value, "mm/dd/yyyy")
        .format( this.getAttribute("data-date-format") )
    )
}).trigger('input');


        })
       
        // table2 = $('.table-produk').DataTable({});

   

   let table;

    $(function () {
       table = $('.table-pembelian').DataTable({
    responsive: true,
    processing: true,
    serverSide: true,
    autoWidth: true,
    ajax: {
        url: '{{ route('pembelian_detail.data', $pembelian->id) }}',
  dataSrc: function (json) {
            // Ambil total dari server

            totalItem = json.total_item;
                totalSubtotal = parseFloat(json.total_subtotal);
            // Update input total item dan total harga
            $('#total-item').val(totalItem);
            $('#total-harga').val( totalSubtotal.toLocaleString('id-ID'));
            // Update input total item dan total harga
           
            $('#total').val(totalSubtotal);

                // Hitung total bayar
                updateTotalBayar();

                return json.data;
        }
    },
    columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false},
        {data: 'kode_obat', name: 'kode_obat'},
        {data: 'nama_obat', name: 'nama_obat'},
        {data: 'jumlah', name: 'jumlah'},
        {data: 'isi', name: 'isi'},
        {data: 'satuan', name: 'satuan'},
        {data: 'lokasi', name: 'lokasi'},
        {data: 'harga_nonppn', name: 'harga_nonppn'},
        {data: 'hb_grosir', name: 'hb_grosir'},
        {data: 'diskon', name: 'diskon'},
        {data: 'ed', name: 'ed'},
        {data: 'batch', name: 'batch'},
        {data: 'subtotal', name: 'subtotal'},
        {data: 'aksi', name: 'aksi', searchable: false, orderable: false},
    ],
});

    function formatRupiah(value) {
        return 'Rp '+ Number(value).toLocaleString('id-ID');
    }

    // Hitung total bayar
    function updateTotalBayar() {
        let diskon = parseFloat($('#diskon-total').val()) || 0;
        let potongan = parseFloat($('#potongan').val().replace(/\D/g, '')) || 0;

        // Diskon dihitung dari totalSubtotal
        let diskonNominal = (diskon / 100) * totalSubtotal;
        let bayar = totalSubtotal - diskonNominal - potongan;

        // Update ke form dan tampilan
        $('#bayar').val(bayar);
        $('#bayarrp').val(formatRupiah(bayar));

        $('.tampil-bayar').text(formatRupiah(bayar));
        $('.tampil-terbilang').text(terbilang(bayar) + ' rupiah');
    }

    // Ubah potongan ke format angka saat diketik
    $(document).on('input', '#potongan', function () {
        // Opsi: masking uang (atau bisa pakai library autoNumeric)
        $(this).val(formatRupiah(this.value.replace(/\D/g, '')));
        updateTotalBayar();
    });

    // Listener untuk input diskon
    $('#diskon-total').on('input', function () {
        updateTotalBayar();
    });

    // Fungsi ubah angka ke huruf (versi sederhana)
    function terbilang(nilai) {
        // Boleh ganti dengan plugin terbilang.js untuk lebih lengkap
        const satuan = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan"];
        function toWords(n) {
            if (n < 10) return satuan[n];
            if (n < 20) return satuan[n - 10] + " belas";
            if (n < 100) return satuan[Math.floor(n / 10)] + " puluh " + satuan[n % 10];
            if (n < 200) return "seratus " + toWords(n - 100);
            if (n < 1000) return satuan[Math.floor(n / 100)] + " ratus " + toWords(n % 100);
            if (n < 2000) return "seribu " + toWords(n - 1000);
            if (n < 1000000) return toWords(Math.floor(n / 1000)) + " ribu " + toWords(n % 1000);
            return n;
        }
        return toWords(Math.floor(nilai));
    }

        $(document).on('input', '#no_faktur', function () {
            let no=$(this).val();
          $('#isino_faktur').val(no);

        });
        $(document).on('change', '#tgl_faktur', function () {
            let tgl=$(this).val();
          $('#isitgl_faktur').val(tgl);

        });
        $(document).on('change', '#tempo', function () {
            let tgl=$(this).val();
          $('#tgl_tempo').val(tgl);

        });
        $(document).on('change', '#metode', function () {
            let metode=$(this).val();
          $('#metode_id').val(metode);

        });
        $(document).on('change', 'input[name="diskon_untuk"]', function () {
    let diskonUntuk = $(this).val();
    $('#dskn_for').val(diskonUntuk);
});

        $('.btn-simpan').on('click', function () {
            let temp=$('#tgl_tempo').val();
            let tglfak=$('#isitgl_faktur').val();
            let nofak=$('#isino_faktur').val();
            let metod=$('#metode_id').val();
            if (!nofak) {
                alert('Nomer faktur  wajib di isi');
            }else if(!tglfak){
                alert('tanggal faktur wajib di isi');
            }else if (!temp) {
                alert('tanggal jatuh tempo wajib di isi');
                
            }else if (!metod) {
                alert('Metode wajib di isi');
                
            }else{

                $('.form-pembelian').submit(); 
            }
        });
       
    });


    $(document).ready(function() {
    $('#produk_id').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var isi = selectedOption.data('isi');
        var satuan = selectedOption.data('satuan');

        // Set nilai input otomatis
        $('#jumlah').val(1);
        $('#isi').val(isi);
        $('#satuan').val(satuan);
    });
});
$(document).on('click', '#btn_update', function (e) {
    e.preventDefault();

     let form = $('#modal-form form'); // ganti ini dengan selektor form yang benar
    let url = form.attr('action');
    let method = form.find('input[name="_method"]').val() || 'POST';

    $.ajax({
        url: url,
        type: method,
        data: form.serialize(),
        success: function (response) {
            $('#modal-form').modal('hide');
          
            table.ajax.reload(null, false); // reload datatable tanpa reset paging
        },
        error: function (xhr) {
            alert('Gagal menyimpan data');
        }
    });
});

    $(document).on('submit', '.form-produk', function (e) {
        e.preventDefault();
        $.post($(this).attr('action'), $(this).serialize())
            .done((response) => {
                table.ajax.reload();
                $('.form-produk')[0].reset();
                $('#produk_id').val('').trigger('change');
                $('#lokasi').val('').trigger('change');
            })
            .fail((errors) => {
                alert('Tidak dapat menyimpan data');
                return;
            });
    });

    function editData(url,url1) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Pembelian detail');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url1);
        $('#modal-form [name=_method]').val('post');
   
        $.get(url)
            .done((response) => {
                $('#modal-form [name=produk_id]').val(response.produk_id).trigger('change',true)
                $('#modal-form [name=lokasi_id]').val(response.lokasi_id).trigger('change',true);
                $('#modal-form [name=jumlah]').val(response.jumlah);
                $('#modal-form [name=isi]').val(response.isi);
                $('#modal-form [name=harga_ppn]').val(response.hb_grosir);
                $('#modal-form [name=harga_nonppn]').val(response.harga_nonppn);
                $('#modal-form [name=satuan]').val(response.satuan);
                $('#modal-form [name=ed]').val(response.ed);
                $('#modal-form [name=batch]').val(response.batch);
                $('#modal-form [name=diskon]').val(response.diskon);
                $('#modal-form [name=subtotal]').val(response.subtotal);
            })
            .fail((errors) => {
                alert('Tidak dapat menampilkan data');
                return;
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