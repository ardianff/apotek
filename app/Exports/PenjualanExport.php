<?php


namespace App\Exports;

use Illuminate\Contracts\View\View;
use App\Models\Produk;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;


class PenjualanExport implements FromView, ShouldAutoSize
{

    private $tanggalAwal;
    private $tanggalAkhir;

    public function __construct($tanggalAwal,$tanggalAkhir)
    {
        $this->tanggalAwal = $tanggalAwal;
        $this->tanggalAkhir = $tanggalAkhir;
    }

    public function view(): View
    {
        $penj =Penjualan::with(['details','shift','user','metod'])->where('cabang_id',auth()->user()->cabang_id)->where('status','Lunas')->whereBetween('created_at', [$this->tanggalAwal,$this->tanggalAkhir])->get();
        $totali =Penjualan::where('cabang_id',auth()->user()->cabang_id)->where('status','Lunas')->whereBetween('created_at', [$this->tanggalAwal,$this->tanggalAkhir])->sum('bayar');
      $detail=[];
      foreach ($penj as $key => $value) {
          $detil=PenjualanDetail::with('prodk')->where('penjualan_id',$value->id)->get();
          $detail[]=$detil;
            
      }
        return view('export_excel.penjualan_excel', compact('penj','detail','totali'));
    }
}
