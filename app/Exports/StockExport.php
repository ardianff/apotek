<?php


namespace App\Exports;

use Illuminate\Contracts\View\View;
use App\Models\Produk;
use App\Models\Cabang;
use App\Models\PembelianDetail;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;


class StockExport implements FromView, ShouldAutoSize
{

    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function view(): View
    {
        if ($this->id==0) {
            $produk=Produk::with('cabang')->orderBy('nama_obat','ASC')->get();
            foreach ($produk as $key => $value) {
                $stok[]=$value->stock*$value->harga_beli??0;
            }
            $total_stock=array_sum($stok);
        }else{

            $produk=Produk::with('cabang')->where('cabang_id',$this->id)->orderBy('nama_obat','ASC')->get();

            foreach ($produk as $key => $value) {
                $stok[]=$value->stock*$value->harga_beli??0;
            }
            $total_stock=array_sum($stok);
        }
        return view('export_excel.stock_excel', compact('produk','total_stock'));
    }
}
