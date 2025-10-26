<?php


namespace App\Exports;

use Illuminate\Contracts\View\View;
use App\Models\Produk;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;


class PembelianExport implements FromView, ShouldAutoSize
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
        $pemb =Pembelian::with(['pembelian_detail','supplier','user'])->where('cabang_id',auth()->user()->cabang_id)->whereBetween('created_at', [$this->tanggalAwal,$this->tanggalAkhir])->get();
        $totali =Pembelian::where('cabang_id',auth()->user()->cabang_id)->whereBetween('created_at', [$this->tanggalAwal,$this->tanggalAkhir])->sum('bayar');
        $detail=[];
        foreach ($pemb as $key => $value) {
            $detil=PembelianDetail::with('produk')->where('pembelian_id',$value->id)->get();
            $detail[]=$detil;
              
        }
        return view('export_excel.pembelian_excel', compact('pemb','detail','totali'));
    }
}
