<?php


namespace App\Exports;
use DB;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BelanjaExport implements FromView,ShouldAutoSize
{

    public function view(): View
    {
    	

    	 $rkp=DB::table('rkp')
    	 ->where('rkp.id_desa',Session::get('id'))
         ->where('rkp.thn',Session::get('thn'))
         ->whereNOTIn('id_rkp',function($query){

               $query->select('id_rkp')->from('new_anggaran_belanja')
               ->leftjoin('total_anggaran','total_anggaran.id_total','new_anggaran_belanja.id_total')
               ->where('total_anggaran.terbaru','true');

            })
         ->get();

        return view('print_export.template_belanja',compact('rkp'));
    }
}
