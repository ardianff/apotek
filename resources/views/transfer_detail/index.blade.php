@extends('layouts.master')

@section('title')
    Transfer Obat
@endsection

@push('css')
<style>
    
    .table-trasnfer tbody tr:last-child {
        display: none;
    }

  
    
</style>
@endpush
@section('breadcrumb')
    @parent
    <li class="active">Transfer Obat</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <div class="row">
                    <div class="col-lg-4">

                        <table>
                            <tr>
                                <td>Cabang</td>
                                <td>: {{ $cabang->name }}</td>
                            </tr>
                            <tr>
                                <td>Apj</td>
                                <td>: {{ $cabang->apj }}</td>
                            </tr>
                            <tr>
                                <td>Telepon</td>
                                <td>: {{ $cabang->no_telp }}</td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>: {{ $cabang->alamat }}</td>
                            </tr>
                        </table>
                    </div>
                   <div class="col-lg-5">
                    <form action="{{route('transfer.store')}}" method="post" class="form-transfer">
                        @csrf
                        <input type="hidden" name="transfer_id" value="{{ $transfer->id }}">

                    <div class="form-group row">
                        <label for="ket" class="col-lg-3 control-label">Keterangan</label>
                        <div class="col-lg-8">
                            <input type="text" name="ket" id="ket" class="form-control " >
                        </div>
                    </div>
                   </div>
                </div>
            </form>
            </div>
            <div class="box-body">
                    
                <form class="form-produk">
                    @csrf
                    <div class="form-group row">
                        <label for="kode_produk" class="col-lg-2">Kode Produk</label>
                        <div class="col-lg-5">
                            <div class="input-group">
                                <input type="hidden" name="transfer_id" id="transfer_id" value="{{ $transfer->id }}">
                                <input type="hidden" name="id_produk" id="id_produk">
                                <input type="text" class="form-control" name="kode_produk" id="kode_produk">
                                <span class="input-group-btn">
                                    <button onclick="tampilProduk()" class="btn btn-info btn-flat" type="button"><i class="fa fa-arrow-right"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
                
                <table class="table table-stiped table-bordered table-trasnfer">
                    <thead>
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>satuan</th>
                        <th width="15%">Jumlah</th>
                        <th >ED</th>
                        <th >Batch</th>
                        <thead width="5%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
            </div>
                
        

            <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-sm btn-flat pull-right btn-simpan"><i class="fa fa-floppy-o"></i> Simpan Transaksi</button>
            </div>
        </div>
    </div>
</div>

@includeIf('transfer_detail.produk')
@endsection

@push('scripts')
<script>
    let table, table2;

    $(function () {

        $('body').addClass('sidebar-collapse');

    //     $('.datepicker').datepicker({
    //        format: 'dd-mm-yyyy',
    //     //    startDate: '1d',
    //        autoclose: true
    //    });
      
        table = $('.table-trasnfer').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('transfer_detail.data', $transfer->id) }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_produk'},
                {data: 'nama_produk'},
                {data: 'satuan'},
                {data: 'jumlah'},
                {data: 'ed'},
                {data: 'batch'},
                {data: 'aksi', searchable: false, sortable: false},
            ],
            dom: 'Brt',
            bSort: false,
            paginate: false
        })
      
        table2 = $('.table-produk').DataTable();

   

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

            $.post(`{{ url('/transfer_detail/update') }}/${id}`, {
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
            $('.form-transfer').submit();
        });
    });

    function tampilProduk() {
        $('#modal-produk').modal('show');
    }

    function hideProduk() {
        $('#modal-produk').modal('hide');
    }

    function pilihProduk(id, kode) {
        $('#id_produk').val(id);
        $('#kode_produk').val(kode);
        hideProduk();
        tambahProduk();
    }

    function tambahProduk() {
        $.post('{{ route('transfer_detail.store') }}', $('.form-produk').serialize())
            .done(response => {
                $('#kode_produk').focus();
                table.ajax.reload();
            })
            .fail(errors => {
                alert('Tidak dapat menyimpan data');
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
                    table.ajax.reload(() => loadForm($('#diskon').val()));
                })
                .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    return;
                });
        }
    }
   

   
</script>
@endpush