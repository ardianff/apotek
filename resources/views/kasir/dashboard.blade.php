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
        <h1>Selamat Datang <span class="text-bold">{{ auth()->user()->name }}</span></h1>
        <h2>Anda login sebagai KASIR</h2>
        <br><br>
        {{-- @foreach ($kasir as $stt) --}}
        @if (Session::get('shift')=='buka')
        <a href="{{ route('transaksi.baru') }}" class="btn btn-success btn-lg">Transaksi Baru</a>

        <a href="{{ route('shift.get_kasir',$kasir->id) }}" class="btn btn-warning btn-lg" id="tutup">Tutup Kasir</a>
        
        @elseif(Session::get('shift')=='tutup')
        <a onclick="bukashift()" class="btn btn-success btn-lg" id="kasir">Buka Kasir</a>
      
        @endif
        {{-- @endforeach --}}

            <br><br><br>
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
    