@extends('layouts.master')

@section('title')
    Stok Opname Obat Lokasi {{$loka??'semua'}}
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Stok Opname Obat</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header">
 @if (session('message'))
        @php 
            $msg = session('message'); 
            $type = $msg['type'] ?? 'success'; 
            $text = $msg['text'] ?? 'Berhasil'; 
        @endphp
    
        <div id="floating-alert" 
             class="alert alert-{{ $type }} alert-dismissible fade show" 
             role="alert"
             style="position: fixed; top: 20px; right: 20px; z-index: 1050; min-width: 250px;">
            {{ $text }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    
        <script>
            setTimeout(() => {
                const alertEl = document.getElementById('floating-alert');
                if (alertEl) {
                    alertEl.classList.remove('show');
                    alertEl.classList.add('fade');
                    setTimeout(() => alertEl.remove(), 500); // Hapus elemen setelah fade out
                }
            }, 4000); // Auto hide after 4 seconds
        </script>
    @endif
                <form action="{{route('so.index')}}" method="GET">
                    <div class="row">

                        <div class="form-group">
                            <label for="" class="col-lg-1 col-lg-offset-1 control-label">Pilih Lokasi</label>
                <div class="col-lg-5">
                  
                    <select id="" class="form-control select2" name="lokasi_id">
                        <option value="semua">Tampilkan Semua</option>
                        @foreach ($lokasi as $item)
                           
                        <option  value="{{$item->id}}" {{ ($id_lok == $item->id ? "selected":"") }}>{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-2">
                <button type="submit" class="btn btn-success"><i class="fa fa-search" aria-hidden="true"></i> Tampilkan</button>
            </div>
            <div class="col-lg-2">
                <a href="{{ route('so.export') }}" target="_blank" class="btn btn-success btn-xs btn-flat"><i class="fa fa-file-excel-o"></i> Export PDF</a>

            </div>
        </div>
            
        </form>
    </div>
    <div class="box-body">
        <div class="table-responsive">

        <table class="table table-striped table-bordered table-stok">
            <thead>
                <tr>
                    <th>no</th>
                    <th>Nama Obat</th>
                <th>Stok SO</th>
                <th>Stok Sistem</th>
                <th>EXP Date</th>
                <th>Kode Obat</th>
                <th>Lokasi</th>
                <th>Satuan</th>
                <th>margin</th>
                <th>harga jual</th>
                <th>Aksi</th>
                </tr>
            </thead>
           
        </table>
    </div>

    </div>
            </div>
            
        </div>
    </div>
@includeIf('SO.form')
@includeIf('detailObat.edit')

    @endsection

    @push('scripts')
<script>
    $(function () {
        $('.select2').select2({
    });
    });

    $(function () {
        table = $('.table-stok').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: true,
            ajax: {
                url: '{{ route('so.data',$id_lok) }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'nama_obat'},
                {data: 'stokop' , searchable: false, sortable: false},
                {data: 'stock'},
                {data: 'ed'},
                {data: 'kode_obat'},
                {data: 'lokasi'},
                {data: 'satuan'},
                {data: 'margin'},
                {data: 'harga_jual'},
                {data: 'aksi' , searchable: false, sortable: false},

               
            ]
        });
        });
        $(function () {
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });
 
    });

    // input SO

 $(document).on('change', '.input-stokop', function() {
    var id = $(this).data('id');
    var value = $(this).val();

    $.ajax({
        url: "{{ route('so.proses') }}",
        type: "GET",
        data: {
            _token: '{{ csrf_token() }}',
            id: id,
            stok_so: value
        },
        success: function(res) {
           if (res.success) {
           table.ajax.reload();
            showMessage(response.type || 'success', response.message || 'Data tarif berhasil di pindah');
    } else {
         showMessage(response.type || 'danger', response.message || 'gagal melakukan eksekusi');
    }
        },
        error: function(xhr) {
             showMessage(response.type || 'danger', response.message || 'gagal melakukan eksekusi');
        }
    });
});

// Fungsi untuk menampilkan alert
 function showMessage(type = 'success', message = '') {
    const alertHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    $('#ajax-alert').html(alertHTML);

    // Auto hide after 4 seconds
    setTimeout(() => {
        $('#ajax-alert .alert').alert('close');
    }, 4000);
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
let ed = dateParts[2] + '-' + dateParts[1] + '-' + dateParts[0];
                $('#modal-edit [name=obat]').val(response.obat['nama_obat']);
                $('#modal-edit [name=satuan]').val(response.obat['satuan']);

                $('#modal-edit [name=lokasi_id]').val(response.lokasi_id).attr('selected',true);
                $('#modal-edit [name=stock]').val(response.stock).attr('disabled',true);
                $('#modal-edit [id=ed]').val(ed);
                $('#modal-edit [name=batch]').val(response.batch);
                $('#modal-edit [name=diskon]').val(response.diskon);
                $('#modal-edit [name=harga_beli]').val(response.harga_beli).mask('0.000.000.000.000', {reverse: true});
                $('#modal-edit [name=harga_jual]').val(response.harga_jual).mask('0.000.000.000.000', {reverse: true});

            })
            .fail((errors) => {
                alert('Tidak dapat menampilkan data');
                return;
            });
    }

// $(document).on('input', '.stok', function () {
        //     let id = $(this).data('id');
        //     let stok = parseInt($(this).val());


        //     $.post(`{{ url('/so/create') }}/${id}`, {
        //             '_token': $('[name=csrf-token]').attr('content'),
        //             '_method': 'put',
        //             'stok': stok
        //         })
        //         .done(response => {
        //             // $(this).on('mouseout', function () {
        //                 // table.ajax.reload();
        //             // });
        //         })
                
        // });

        function tambah(url,url1) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Detail Obat');

        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');

        $.get(url1)
            .done((response) => {
                $('#modal-form [name=kode_obat]').val(response.kode_obat);
                $('#modal-form [name=nama_obat]').val(response.nama_obat);
                $('#modal-form [name=satuan]').val(response.satuan);
               

         
            })
            .fail((errors) => {
                alert('Tidak dapat menampilkan data');
                return;
            });
    }
    $(document).ready(function() {
      $("#add").click(function(e){ 
       e.preventDefault
          $(".dinamis").prepend(`
          <div class="row">
               
            <div class="col-md-4">
                    <label for="ed" class="control-label">Expired Date</label>
                    <input type="date" name="ed[]" id="ed" class="form-control " required>
                    <span class="help-block with-errors"></span>
                </div>
                <div class="col-md-3">
                    <label for="stock" class="control-label">Stok</label>
                    <input type="number" name="stock[]" id="stock" class="form-control" required>
                    <span class="help-block with-errors"></span>
                </div>
                <div class="col-md-4">
                        <label for="batch" class="control-label">Batch</label>
                        <input type="text" name="batch[]" id="batch" class="form-control"  >
                        <span class="help-block with-errors"></span>
                    </div>
                   <div class="col-md-1">
                   <label for="batch" class="control-label">Tambah</label>
                   <button id="hapus" class="btn btn-danger btn-sm"> <i class="fa fa-trash" aria-hidden="true"></i> Hapus</button>
               </div>`);
      });
      $(document).on("click","#hapus",function(e){ 
        e.preventDefault();
        let row_item=$(this).parent().parent();
        $(row_item).remove();
      });
    });
</script>

@endpush