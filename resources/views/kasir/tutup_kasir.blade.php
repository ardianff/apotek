@extends('layouts.master')

@section('title')
    Dashboard
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Dashboard</li>
@endsection

@section('content')
<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body text-center">
                <form action="{{route('shift.tutup_kasir')}}" method="post" class="form-horizontal">
                    @csrf
                {{-- @foreach ($kasir as $item) --}}
                    
                   
                            <input type="hidden" name="id_kasir" id="id_kasir" value="{{$kasir->id}}">
                           <div class="form-group row">
                               <label class="col-lg-2 col-lg-offset-1 control-label" for="shift"> Shift</label>
                               <div class="col-lg-6">
                                <input type="text" class="form-control" name="nama_shift" value="{{$kasir->shift->nama_shift}}" id="shift_tutup" readonly>
                                </div>
                           </div>
                           <div class="form-group row">
                               <label class="col-lg-2 col-lg-offset-1 control-label" for="yg_buka"> Dibuka Oleh</label>
                               <div class="col-lg-6">
                                <input type="text" class="form-control" name="yg_buka" id="yg_buka" value="{{$kasir->buka->name}}" readonly>
                                </div>
                           </div>
                           <div class="form-group row">
                               <label class="col-lg-2 col-lg-offset-1 control-label" for="shift">Buka Pukul</label>
                               <div class="col-lg-6">
                                <input type="text" class="form-control tanggal" name="waktu_buka" id="bukashift" value="{{@tanggal_indonesia($kasir->waktu_buka)??0}}" readonly>
                                </div>
                           </div>
                           <div class="form-group row">
                            <label class="col-lg-2 col-lg-offset-1 control-label" for="yg_nutup"> Ditutup Oleh</label>
                            <div class="col-lg-6">
                             <input type="text" class="form-control"  value="{{auth()->user()->name}}"  readonly>
                             <input type="hidden"  name="yg_nutup" id="yg_nutup" value="{{auth()->user()->id}}"  readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-lg-offset-1 control-label" for="shift">Tutup Pukul</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control "  value="{{@tanggal_indonesia($waktu_tutup)??0}}" readonly>
                                <input type="hidden"  name="waktu_tutup" id="waktu_tutup" value="{{$waktu_tutup}}"  readonly>
                                
                                </div>
                           </div>
                            <div class="form-group row">
                                <label for="saldo-awal" class="col-lg-2 col-lg-offset-1 control-label">Saldo Awal</label>
                                <div class="col-lg-6">
                                    <input type="text" name="saldo_awal" id="saldo_awal" value="{{$kasir->saldo_awal}}" class="form-control uang" readonly>
                                    <span class="help-block with-errors"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="total_penjualan" class="col-lg-2 col-lg-offset-1 control-label">Total Penjualan</label>
                                <div class="col-lg-6">

                                    <input type="text" class="form-control uang" name="total_penjualan"  id="total_penjualan" value="{{$total_jual}}" readonly>

                                    <span class="help-block with-errors"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="pengeluaran" class="col-lg-2 col-lg-offset-1 control-label">Pengeluaran</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control uang"name="pengeluaran" id="pengeluaran" value="{{$pengeluaran}}" readonly>
                                    <span class="help-block with-errors"></span>
                                </div>
                            </div>
                            {{-- <input type="hidden" name="total_awal" id="total_awal" value="{{$total_awal}}"> --}}
                            <div class="form-group row">
                                <label for="saldo-tunai" class="col-lg-2 col-lg-offset-1 control-label">Saldo Tunai</label>
                                <div class="col-lg-6">
                                    <input type="text" name="tunai" id="tunai" class="form-control uang" value="{{$tunai}}" readonly>
                                    <span class="help-block with-errors"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nontunai" class="col-lg-2 col-lg-offset-1 control-label">Saldo nontunai</label>
                                <div class="col-lg-6">
                                    <input type="text" name="nontunai" id="nontunai" class="form-control uang" value="{{$nontunai}}" readonly>
                                    <span class="help-block with-errors"></span>
                                </div>
                            </div>
                        
                            <div class="form-group row">
                                <label for="" class="col-lg-2 col-lg-offset-1 control-label">Rincian nontunai</label>
                                <div class="col-lg-6">
                                    <table class="table table-dark table-bordered table-striped">
                                        <tbody>
                                            @foreach ($metode as $me)
                                            <tr>
                                                <td>{{$me->metode}} :</td>
                                                <td>{{$me->id}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                   
                                   
                                    <span class="help-block with-errors"></span>
                                </div>
                            </div>
                        
                        
                            {{-- <div class="form-group row">
                                <label for="selisih" class="col-lg-2 col-lg-offset-1 control-label">Selisih</label>
                                <div class="col-lg-6">
                                    <input type="text" name="selisih"  id="selisih" class="form-control " value="" >
                                    <span class="help-block with-errors"></span>
                                </div>
                            </div> --}}
                            <div class="form-group row">
                                <label for="total_saldo" class="col-lg-2 col-lg-offset-1 control-label">Total Saldo</label>
                                <div class="col-lg-6">
                                    <input type="text" name="saldo_akhir" id="saldo_akhir" class="form-control uang" value="{{$total_saldo}}" readonly>
                                    <span class="help-block with-errors"></span>
                                </div>
                            </div>
                           
                            {{-- <div class="form-group row">
                                <label for="penerima" class="col-lg-2 col-lg-offset-1 control-label">Diserahkan kepada</label>
                                <div class="col-lg-6">
                                    <input type="text" name="penerima" id="penerima" class="form-control " value="{{auth()}}">
                                    <span class="help-block with-errors"></span>
                                </div>
                            </div> --}}
                            <div class="form-group row">
                                <label for="catatan" class="col-lg-2 col-lg-offset-1 control-label">Catatan</label>
                                <div class="col-lg-6">
                                    <textarea name="catatan" id="catatan" cols="10" class="form-control" rows="5"></textarea>
                                    <span class="help-block with-errors"></span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-sm btn-flat btn-primary"><i class="fa fa-save"></i> Simpan</button>
                            <button type="button" class="btn btn-sm btn-flat btn-warning" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Batal</button>
                        </div>
                    </div>
                {{-- @endforeach --}}

                </form>
            </div>
        </div>
    </div>
</div>
<!-- /.row (main row) -->

@endsection

@push('scripts')
<script type="text/javascript">

// $(document).ready(function(){
 
//        $('.tunai').keyup(function () {
//         var a =$('.tunai').val();
//         var b =a.replace('.','');
//         $('#tunai').val(b);
//        });
//        $('.nontunai').keyup(function () {
//      var c =$('.nontunai').val();
//      var d =c.replace('.','');
//      $('#nontunai').val(d);
//     });
//    });
// $(document).ready(function(){
//     $(".tunai, .nontunai").keyup(function() {
//         var tunai  = $("#tunai").val();
//         var nontunai = $("#nontunai").val();
//         var pengeluaran= $('#pengeluaran').val();
//         var penjualan=$('#saldo_penjualan').val();

//         var total = parseInt(tunai) + parseInt(nontunai);
//         var selisih =total - parseInt(penjualan);
//         var saldo_akhir =total - parseInt(pengeluaran);

//             $("#saldo_akhir").val(saldo_akhir);
//     $("#selisih").val(selisih);

//         });
        
//     });
//     $(document).ready(function () {

//         $('.uang').mask('0.000.000.000.000.000',{reverse:true});
        
//     });


// function sum() {
    
//     var tunai= $('#tunai').val();
//     var nontunai= $('#nontunai').val();

//     var pengeluaran= $('#pengeluaran').val();
    
//     var penjualan=$('#saldo_penjualan').val();

//     var total =parseInt(tunai)+ parseInt(nontunai);
//     var selisih =total-parseInt(penjualan);
//     var saldo_akhir =total-parseInt(pengeluaran);


//     $("#saldo_akhir").val(saldo_akhir);
//     $("#selisih").val(selisih);
// }

        
    
</script>
@endpush
    

