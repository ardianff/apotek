@extends('layouts.master')

@section('title')
    Daftar Produk
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Produk</li>
@endsection

@section('content')
@if (Session::has('success'))
<div class="alert alert-success" role="alert">
    {!! \Session::get('success') !!}
  </div>
@endif
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <div class="btn-group">
                    <button onclick="addForm('{{ route('produk.store') }}')" class="btn btn-success btn-sm "><i class="fa fa-plus-circle"></i> Tambah</button>
                    <button onclick="deleteSelected('{{ route('produk.delete_selected') }}')" class="btn btn-danger btn-sm "><i class="fa fa-trash"></i> Hapus</button>
                    <button onclick="cetakBarcode('{{ route('produk.cetak_barcode') }}')" class="btn btn-info btn-sm "><i class="fa fa-barcode"></i> Cetak Barcode</button>
                </div>
                    <a href="{{ route('export.produk') }}" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Excel</a>

                    <button onclick="excel()" class="btn btn-success btn-sm"><i class="fa fa-file" aria-hidden="true"></i> Import Excel</button>
                    <button type="button" href="{{URL::to('/')}}/file/template_produk.xlsx" target="_blank" class="btn btn-primary btn-sm"><i class="fa fa-download" aria-hidden="true"></i> Dwonload Template produk</button>
            </div>
            <div class="box-body table-responsive">
                <form action="" method="post" class="form-produk">
                    @csrf
                    <table id="" class="table table-produk table-stiped table-bordered" >
                        <thead>
                            <th width="5%">
                                <input type="checkbox" name="select_all" id="select_all">
                            </th>
                            <th width="5%">No</th>
                            <th width="10%"><i class="fa fa-cog"></i></th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>satuan</th>
                            <th>Total stock</th>
                            <th>Isi</th>
                            <th>Kategori</th>
                            <th>Stock Minim</th>
                            
                        </thead>
                        <tbody>
                        
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<form action="{{ route('import.produk') }}" method="post" enctype="multipart/form-data">
<div class="modal fade" id="modal-excel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Import Excel</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          @csrf
<div class="form-group">
    <label for="importexcel">Import Obat file excel</label>
    <input id="importexcel" class="form-control" type="file" name="data_obat">
</div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-success">import</button>
</div>
</div>
</div>
</div>
</form>
@includeIf('produk.form')
@endsection

@push('scripts')
<script>
    $('.uang').mask('0.000.000.000.000',{reverse:true});
    
    function excel() {
        $('#modal-excel').modal('show');
    }
    let table,table2;

$(function () {
    table = $('.table-produk').DataTable({
        responsive: true,
        // processing: true,
        // serverSide: true,
        autoWidth: true,
        ajax: {
            url: '{{ route('produk.data') }}',
        },
        columns: [
            {data: 'select_all', searchable: false, sortable: false},
            {data: 'DT_RowIndex', searchable: false, sortable: false},
            {data: 'aksi', searchable: false, sortable: false},
            {data: 'kode_obat'},
            {data: 'nama_obat'},
            {data: 'satuan'},
            {data: 'stock_produk'},
            {data: 'isi'},
            {data: 'kategori'},
            {data: 'stok_minim'},
        ]
   
    });
    
       

    $('#modal-form').validator().on('submit', function (e) {
            if (! e.preventDefault()) {
                $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                    .done((response) => {
                        $('#modal-form').modal('hide');
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menyimpan data');
                        return;
                    });
            }
        });

        $('[name=select_all]').on('click', function () {
            $(':checkbox').prop('checked', this.checked);
        });
        table1 = $('.table_stock').DataTable({
            processing: true,
            bSort: false,
            dom: 'Brt',
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'created_at'},
                {data: 'stock_awal'},
                {data: 'stok_out'},
                {data: 'stok_in'},
                {data: 'sisa'},
                {data: 'Ket_stock'},
                {data: 'user_id'},
            ]
        })
});

function kartu_stok(url) {
    
    $('#modal_kartu_stock').modal('show');
    table1.ajax.url(url);
        table1.ajax.reload();

}
    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Data Obat');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=nama_produk]').focus();
    }

    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Data Obat');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=nama_produk]').focus();

        $.get(url)
            .done((response) => {
                $('#modal-form [name=kode_obat]').val(response.kode_obat);
                $('#modal-form [name=nama_obat]').val(response.nama_obat);
                $('#modal-form [name=satuan]').val(response.satuan);
                $('#modal-form [name=isi]').val(response.isi);
                $('#modal-form [name=stok_minim]').val(response.stok_minim);
                $('#modal-form [name=margin]').val(response.margin);
                $('#modal-form [name=kategori_id]').val(response.kategori_id).attr('selcted',true);
                $('#modal-form [name=golongan_id]').val(response.golongan_id).attr('selcted',true);
                $('#modal-form [name=jenis_id]').val(response.jenis_id).attr('selcted',true);
                $('#modal-form [name=kandungan]').val(response.kandungan);
                $('#modal-form [name=kegunaan]').val(response.kegunaan);
                $('#modal-form [name=zat]').val(response.zat);
                $('#modal-form [name=merk]').val(response.merk);
                $('#modal-form [name=efek]').val(response.efek);
                $('#modal-form [name=dosis]').val(response.dosis);
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

    function deleteSelected(url) {
        if ($('input:checked').length > 1) {
            if (confirm('Yakin ingin menghapus data terpilih?')) {
                $.post(url, $('.form-produk').serialize())
                    .done((response) => {
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menghapus data');
                        return;
                    });
            }
        } else {
            alert('Pilih data yang akan dihapus');
            return;
        }
    }

    function cetakBarcode(url) {
        if ($('input:checked').length < 1) {
            alert('Pilih data yang akan dicetak');
            return;
        } else if ($('input:checked').length < 3) {
            alert('Pilih minimal 3 data untuk dicetak');
            return;
        } else {
            $('.form-produk')
                .attr('target', '_blank')
                .attr('action', url)
                .submit();
        }
    }
    $(function () {
        
       

       $('.datepicker').datepicker({
           format: 'dd-mm-yyyy',
           autoclose: true
       });

   });


   $(document).on('click', '#hitung', function () {
        let hb_grosir =  $('#hb_grosir').val();
        let harga_beli =  $('#harga_beli').val();
        let hrg_beli = harga_beli.replace('.','');
        let hrg_grosir = hb_grosir.replace('.','');
        let margin = $('#margin').val();
        let isi = $('#isi').val();
        let diskon = $('#diskon').val();
        let hbg=parseInt(hrg_grosir);
        let hbe=parseInt(hrg_beli);
        let isinya=parseInt(isi);
        if (hbe) {
            let mark=hbe * margin / 100 ;
            let dsk=hbe * diskon / 100 ;
            let hasil= hbe + mark - dsk;
            let hasilecer= Math.ceil(hasil);
            let hasilfix= hasilecer*isinya;
            let hb_gross=hbe * isinya;
            let hrg_bel=hbe;
            $('#hj_grosir').mask('0.000.000.000.000',{reverse:true}).val(hasilfix).trigger('input');
        $('#harga_jual').mask('0.000.000.000.000',{reverse:true}).val(hasilecer).trigger('input');
        $('#harga_beli').mask('0.000.000.000.000',{reverse:true}).val(hrg_bel).trigger('input');
        $('#hb_grosir').mask('0.000.000.000.000',{reverse:true}).val(hb_gross).trigger('input');
  
        }else{
        let mark=hbg * margin / 100 ;
            let dsk=hbg * diskon / 100 ;
            let hasil= hbg + mark - dsk;
            let hasilfix= Math.ceil(hasil);
            let hasilecer= Math.ceil(hasilfix / isinya);
            let hrg_bel=Math.ceil(hbg/isinya);
            let hb_gross=hbg;
            $('#hj_grosir').mask('0.000.000.000.000',{reverse:true}).val(hasilfix).trigger('input');
        $('#harga_jual').mask('0.000.000.000.000',{reverse:true}).val(hasilecer).trigger('input');
        $('#harga_beli').mask('0.000.000.000.000',{reverse:true}).val(hrg_bel).trigger('input');
        $('#hb_grosir').mask('0.000.000.000.000',{reverse:true}).val(hb_gross).trigger('input');
            
        }
        
        

       
        });
</script>
@endpush