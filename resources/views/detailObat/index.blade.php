@extends('layouts.master')

@section('title')
Daftar Detail Obat
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Detail Obat</li>
@endsection

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="box">

            <div class="box-header with-border">
@if (Session::has('success'))
<div class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h4><i class="icon fa fa-check"></i> Sukses!</h4>
    {!! \Session::get('success') !!}
    </div>
@endif
@if (Session::has('warning'))
<div class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h4><i class="icon fa fa-check"></i> Sukses!</h4>
    {!! \Session::get('warning') !!}
    </div>
@endif
                <div class="btn-group">
                    <button onclick="addForm('{{ route('detailObat.store') }}')" class="btn btn-success btn-sm "><i class="fa fa-plus-circle"></i> Tambah</button>
                    <button onclick="deleteSelected('{{ route('detailObat.delete_selected') }}')" class="btn btn-danger btn-sm "><i class="fa fa-trash"></i> Hapus</button>
                    <button onclick="cetakBarcode('{{ route('detailObat.cetak_barcode') }}')" class="btn btn-info btn-sm "><i class="fa fa-barcode"></i> Cetak Barcode</button>
                    <a href="{{ route('export.detailObat') }}" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Excel </a>
                    <!--<a href="{{ route('detailObat.transfer') }}" class="btn btn-warning btn-sm"><i class="fa fa-file-archive" aria-hidden="true"></i> transfer </a>-->

                </div>


            </div>
            <div class="box-body table-responsive">
                <div class="table-responsive">
                <form action="" method="post" class="form-produk">
                    @csrf
                    <table class="table table-striped table-bordered">
                        <thead>
                            <th width="5%">
                                <input type="checkbox" name="select_all" id="select_all">
                            </th>
                            <th width="5%">No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Satuan</th>
                            <th>stock</th>
                            <th>lokasi</th>
                            <th>exp date</th>
                            <th>batch</th>
                            <th >Harga Beli</th>
                            <th >Harga Jual</th>
                            <th width="8%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>
            </form>
                </div>
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
    <label for="importexcel">Import produk file excel</label>
    <input id="importexcel" class="form-control" type="file" name="import_produk">
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
@includeIf('detailObat.form')
@includeIf('detailObat.edit')
 @includeIf('detailObat.stock')

@endsection

@push('scripts')
<script>
function format_uang(angka, withDecimal = false) {
    if (!angka) return '';

    // pastikan angka berupa float
    angka = parseFloat(angka);

    return angka.toLocaleString('id-ID', {
        minimumFractionDigits: withDecimal ? 2 : 0,
        maximumFractionDigits: withDecimal ? 2 : 0
    });
}
</script>

<script>
 $(function () {
        $('.select2').select2({
    });
    });
$(function () {



        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });

    });
    let table, table2;

    $(function () {
        table = $('.table').DataTable({
            responsive: true,
            // processing: false,
            // serverSide: false,
            autoWidth: true,
            ajax: {
                url: '{{ route('detailObat.data') }}',
            },
            columns: [
                {data: 'select_all', searchable: false, sortable: false},
                {data: 'DT_RowIndex'},
                {data: 'kode_obat'},
                {data: 'nama_obat'},
                {data: 'satuan'},
                {data: 'stock',className: 'text-center'},
                {data: 'lokasi'},
                
                {data: 'ed',className: 'text-right'},
                {data: 'batch'},
                {data: 'harga_beli',className: 'text-right'},
                {data: 'harga_jual',className: 'text-right'},
                {data: 'aksi', searchable: false, sortable: false},
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
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Data Obat');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        // $('#modal-form [name=produk_id]').focus();
    }
   

    function editForm(url) {
        $('#modal-edit').modal('show');
        $('#modal-edit .modal-title').text('Edit Data Obat');

        $('#modal-edit form')[0].reset();
        $('#modal-edit form').attr('action', url);
        $('#modal-edit [name=_method]').val('put');

        $.get(url)
            .done((response) => {
                let dateParts = response.ed.split('-'); // Split the date into parts
    //             let hargaBeli = response.harga_beli.toString()
    // .replace(/\./g, '')   // hapus titik pemisah ribuan dari DB kalau ada
    // .replace(',', '.');
    // let hargaJual = response.harga_jual.toString()
    // .replace(/\./g, '')  
    // .replace(',', '.');
    
let ed = dateParts[2] + '-' + dateParts[1] + '-' + dateParts[0];
                $('#modal-edit [name=obat]').val(response.obat['nama_obat']);
                $('#modal-edit [name=satuan]').val(response.obat['satuan']);

                $('#modal-edit [name=lokasi_id]').val(response.lokasi_id).trigger('change',true);
                $('#modal-edit [name=stock]').val(response.stock).attr('disabled',true);
                $('#modal-edit [id=ed]').val(ed);
                $('#modal-edit [name=batch]').val(response.batch);
                $('#modal-edit [name=diskon]').val(response.diskon);
                $('#modal-edit [name=harga_beli]')
    .val(format_uang(response.harga_beli));
                $('#modal-edit [name=harga_jual]')
    .val(format_uang(response.harga_jual));

            })
            .fail((errors) => {
                alert('Tidak dapat menampilkan data');
                return;
            });
    }
    function editstock(url,url2) {
        $('#modal-stock').modal('show');
        $('#modal-stock .modal-title').text('Ubah Stock Obat');

        $('#modal-stock form')[0].reset();
        $('#modal-stock form').attr('action', url2);
        $('#modal-stock [name=_method]').val('post');
        $.get(url)
            .done((response) => {
                $('#modal-stock [name=obat]').val(response.obat['nama_obat']);
                $('#modal-stock [name=satuan]').val(response.obat['satuan']);

                $('#modal-stock [id=stock]').val(response.stock).attr('disabled',true);

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

  $('.uang').mask('0.000.000.000.000', {reverse: true});

</script>
@endpush
