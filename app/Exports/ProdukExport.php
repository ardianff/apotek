<?php


namespace App\Exports;

use Illuminate\Contracts\View\View;
use App\Models\Produk;
use App\Models\DetailObat;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProdukExport implements FromView, ShouldAutoSize
{

    // private $data;

    // public function __construct($data)
    // {
    //     $this->data = $data;
    // }

    public function view(): View
    {
        // $produk = DetailObat::with(['obat'])->where('cabang_id', auth()->user()->cabang_id)->get();
        

        // // return $produk;
        // return view('export_excel.produk_excel', compact('produk'));


         $produk = Produk::with(['lokasi', 'kategori', 'obat', 'cabang'])->orderBy('nama_obat', 'ASC')->get();
        // $produk = Produk::with(['lokasi', 'kategori', 'obat'])->get();
        // $produk = Produk::all();
        // $produk = Produk::where('cabang_id', auth()->user()->cabang_id)->get();

        // Mengambil total stok dari DetailObat
$stokData = DetailObat::select('produk_id', \DB::raw('SUM(stock) as total_stok'))
    // ->where('cabang_id', auth()->user()->cabang_id)
    ->groupBy('produk_id')
    ->pluck('total_stok');

foreach ($produk as $barang) {
    $barang->stock_produk = $stokData[$barang->id] ?? 0;
}
        return view('export_excel.produk_excel', compact('produk'));


    }
}
