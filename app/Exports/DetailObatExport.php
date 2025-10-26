<?php


namespace App\Exports;

use Illuminate\Contracts\View\View;
use App\Models\Produk;
use App\Models\DetailObat;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DetailObatExport implements FromView, ShouldAutoSize
{

    // private $data;

    // public function __construct($data)
    // {
    //     $this->data = $data;
    // }

    public function view(): View
    {
        $detil = DetailObat::with(['obat','lokasi','pembelian'])->where('stock','!=',0)->where('cabang_id',auth()->user()->cabang_id)->orderBy('produk_id','ASC')->get();

       $produk = Produk::with(['lokasi', 'kategori', 'obat', 'cabang'])
    ->whereNotIn('id', function($query) {
        $query->select('produk_id')
              ->from('detail_obat')
              ->where('cabang_id',auth()->user()->cabang_id); // optional, bisa dihapus jika semua produk_id
    })
    ->orderBy('nama_obat', 'ASC')
    ->get();
        return view('export_excel.detailObat_excel', compact('detil','produk'));
    }
}
