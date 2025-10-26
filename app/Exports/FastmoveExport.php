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


class FastmoveExport implements FromView, ShouldAutoSize
{

    private $tanggalAwal;
    private $tanggalAkhir;
    private $limit;

    public function __construct($tanggalAwal,$tanggalAkhir,$limit)
    {
        $this->tanggalAwal = $tanggalAwal;
        $this->tanggalAkhir = $tanggalAkhir;
        $this->batas = $limit;
    }

    public function view(): View
    {
        $penjualan=Penjualan::where('cabang_id',auth()->user()->cabang_id)->whereBetween('created_at', [$this->tanggalAwal,$this->tanggalAkhir])->pluck('id');

        $detil=PenjualanDetail::with('prodk')->whereIn('penjualan_id',$penjualan)
        ->selectRaw("produk_id")
        ->selectRaw('SUM(jumlah) as total')
        ->orderBy('total','DESC')
        ->groupBy('produk_id')
        ->take($this->batas)
        ->get();

        return view('export_excel.fastmove_excel', compact('detil'));
    }
}
