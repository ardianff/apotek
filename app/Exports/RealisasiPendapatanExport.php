<?php


namespace App\Exports;
use DB;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RealisasiPendapatanExport implements FromView,ShouldAutoSize
{

    public function view(): View
    {
    	

    	  $id_desa = Session::get('id');

            $realisasi_penda = DB::table('new_rincian_pendapatan')
             ->leftjoin('mst_jenis','new_rincian_pendapatan.kd_jenis','mst_jenis.kd_jenis')
             ->leftjoin('mst_kelompok','new_rincian_pendapatan.kd_kelompok','mst_kelompok.kd_kelompok')
            ->leftjoin('mst_obyek','new_rincian_pendapatan.kd_obyek','mst_obyek.kd_obyek')
            ->leftjoin('mst_sumber_dana','mst_sumber_dana.kd_sumber_dana','new_rincian_pendapatan.kd_sumber_dana')
            ->where('new_rincian_pendapatan.id_desa',$id_desa)
            ->where('new_rincian_pendapatan.thn',Session::get('thn'))
            ->where('new_rincian_pendapatan.direalisasikan','!=','true')
            ->get();

        return view('print_export.template_realisasi_pendapatan',compact('realisasi_penda'));
    }
}
