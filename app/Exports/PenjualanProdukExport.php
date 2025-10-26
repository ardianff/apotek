<?php


namespace App\Exports;

use Illuminate\Contracts\View\View;
use App\Models\Produk;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use DB;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;


class PenjualanProdukExport implements FromView, ShouldAutoSize
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
        $penj =Penjualan::where('cabang_id',auth()->user()->cabang_id)->where('status','Lunas')->whereBetween('created_at', [$this->tanggalAwal,$this->tanggalAkhir])->orderBy('id','DESC')->pluck('id');

        $detail=PenjualanDetail::whereIn('penjualan_id', $penj)
        ->groupBy('produk_id')
        ->select('produk_id', DB::raw('SUM(jumlah) as total_jumlah'))
        ->get();
        $penjualan=[];
        foreach ($detail as $key => $value) {
            # code...
            $produk=Produk::where('id',$value->produk_id)->first();
if($produk){
    

            $harga_bel=$produk->harga_jual-($produk->harga_jual*$produk->margin/100);
            $penjualan[]=[
                "kode_obat"=>$produk->kode_obat,
                "nama_obat"=>$produk->nama_obat,
                "satuan"=>$produk->satuan,
                "isi"=>$produk->isi,
                "harga_beli"=>$harga_bel,
                "hb_grosir"=>$produk->hb_grosir,
                "margin"=>$produk->margin,
                "harga_jual"=>$produk->harga_jual,
                "hj_grosir"=>$produk->hj_grosir,
                "jumlah"=>$value->total_jumlah,
                "total_penjualan"=>$value->total_jumlah*$produk->harga_jual,
                "total_modal"=>$value->total_jumlah*$harga_bel,
                "total_laba"=>($produk->harga_jual-$harga_bel)*$value->total_jumlah,
            ];
        
        }else{
        }
        }
        

        $totali=PenjualanDetail::whereIn('penjualan_id', $penj)->sum('subtotal');

        return view('export_excel.penjualan_produk_excel', compact('penjualan','totali'));
    }
}
