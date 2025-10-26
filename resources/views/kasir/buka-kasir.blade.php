@extends('layouts.master')

@section('title')
    BUKA KASIR
@endsection

@section('breadcrumb')
    @parent
    <li class="active">BUKA KASIR</li>
@endsection

@section('content')
<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-lg-12">
        <div class="box">
        <div class="box-body text-center">
            <form action="{{route('shift.buka_kasir')}}" method="post" class="form-horizontal">
                @csrf
    
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h2 class="modal-title bg-primary">Buka Shift</h2>
                    </div>
                    <div class="modal-body">
                       <div class="form-group row">
                           <label class="col-lg-2 col-lg-offset-1 control-label" for="shift">Pilih Shift</label>
                           <div class="col-lg-6">
    
                               <select id="shift" class="form-control" name="shift">
                                   <option value="">Pilih shift</option>
                                   @foreach ($shift as $sh)
                                    <option value="{{$sh->id}}">{{$sh->nama_shift}}</option>
                                    
                                    @endforeach
                                </select>
                            </div>
                       </div>
                        <div class="form-group row">
                            <label for="saldo-awal" class="col-lg-2 col-lg-offset-1 control-label">Saldo Awal Rp:</label>
                            <div class="col-lg-6">
                                <input type="text" name="saldo_awal" id="" class="form-control uang" required>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-lg-2 col-lg-offset-1 control-label">Petugas</label>
                            <div class="col-lg-6">
                                <input type="text" name="petugas" id="" class="form-control " required>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        
                       
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-flat btn-primary"><i class="fa fa-save"></i> Simpan</button>
                        <button type="button" class="btn btn-sm btn-flat btn-warning" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Batal</button>
                    </div>
                </div>
            </form>
        </div>
        </div>
    </div>
</div>
<!-- /.row (main row) -->
@includeIf('kasir.modal_kasir')

@endsection

@push('scripts')
<script type="text/javascript">


function bukashift() {
        $('#modal-kasir').modal('show');
    }


    $(document).ready(function(){


$('.uang').mask('Rp. '+'000.000.000.000.000', {reverse: true});
});

</script>
@endpush
    