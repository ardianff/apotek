<?php


namespace App\Exports;
use DB;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RealisasiBelanjaExport implements FromView,ShouldAutoSize
{

    public function view(): View
    {


    	 $id_desa = Session::get('id');

    	  $realisasi_belanja = DB::table('new_anggaran_belanja')
    	    ->leftjoin('rkp','rkp.id_rkp','new_anggaran_belanja.id_rkp')
            ->where('new_anggaran_belanja.id_desa',$id_desa)
            ->where('new_anggaran_belanja.thn',Session::get('thn'))
            ->where('new_anggaran_belanja.direalisasikan','!=','true')
            ->select('new_anggaran_belanja.*','rkp.*')
            ->get();

        return view('print_export.template_realisasi_belanja',compact('realisasi_belanja'));
    }
}
