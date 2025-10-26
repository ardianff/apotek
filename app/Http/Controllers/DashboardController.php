<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Member;
use App\Models\Pembelian;
use App\Models\Pengeluaran;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\DetailObat;
use App\Models\Supplier;
use App\Models\Shift;
use App\Models\Laporan_kasir;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    function index() 
    {
        $cabang_id = auth()->user()->cabang_id;
          $today = now()->toDateString();
    $month = now()->month;
    $year = now()->year;
    $hariIni = Carbon::today();
$nextMonth = $hariIni->copy()->addMonth();



          $total_obat = DetailObat::where('cabang_id', $cabang_id)
        ->distinct('produk_id')
        ->count('produk_id');
        
          $total_stock = DetailObat::where('cabang_id', $cabang_id)
        ->sum('stock');

        $total_pembelian = Pembelian::where('cabang_id', $cabang_id)
        ->whereMonth('tgl_faktur', $month)
        ->whereYear('tgl_faktur', $year)
        ->sum('total_harga');

        $penjualanHariIni = Penjualan::where('cabang_id', $cabang_id)->whereDate('created_at', $today)
        ->where('status', 'Lunas')
        ->sum('total_harga');

        $penjualanBulanIni = Penjualan::where('cabang_id', $cabang_id)
        ->whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->where('status', 'Lunas')
        ->sum('total_harga');

        $pengeluaranBulanIni = Pengeluaran::where('cabang_id', $cabang_id)
        ->whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->sum('nominal');

        $produkMenipis = DetailObat::join('produk', 'detail_obat.produk_id', '=', 'produk.id')
    ->where('detail_obat.cabang_id', $cabang_id)
    ->where('detail_obat.stock', '>', 0)
    ->whereColumn('detail_obat.stock', '<', 'produk.stok_minim')
    ->select('produk.nama_obat', 'detail_obat.stock', 'produk.stok_minim')
    ->get();
        $stockMinim = $produkMenipis->count();

$hampirED = DetailObat::where('stock', '>', 0)
    ->whereNotNull('ed')
    ->whereDate(DB::raw("STR_TO_DATE(ed, '%d-%m-%Y')"), '<=', $nextMonth)
    ->whereDate(DB::raw("STR_TO_DATE(ed, '%d-%m-%Y')"), '>=', $hariIni)
    ->count();

        return view('admin.dash',compact('total_obat','total_stock','total_pembelian','penjualanHariIni','penjualanBulanIni','pengeluaranBulanIni','stockMinim','hampirED'));
        
    }
   
    public function ed()
    {
              
        $td6 = Carbon::today()->addDays(60);
        $td = Carbon::today();
        $d1=strtotime($td6);
        $d2=strtotime($td);

$datelist = [];
 
for ($currentDate = $d2; $currentDate <= $d1; 
                            $currentDate += (86400)) {
                                    
$date = date('Y-m-d', $currentDate);
$datelist[] = $date;
}
$obat = Produk::with('lokasi')
->where('cabang_id',auth()->user()->cabang_id)
->whereNotNull('ed')
->where('ed','!=','0')
->where('stock','!=','0')
->where('ed','<=',$datelist)
->orderBy('ed','asc')
->get();

        return datatables()
        ->of($obat)
        ->addIndexColumn()
      
        ->addColumn('kode_obat', function ($obat) {
        return '<span class="label label-success">'. $obat->kode_obat .'</span>';
        })

        ->addColumn('stock', function ($obat) {
        return format_uang($obat->stock);
        })
        ->addColumn('lokasi', function ($obat) {
        return $obat->lokasi->name ?? 0;
        })
        ->addColumn('ed', function ($obat) {
        return date('d-m-Y',strtotime($obat->ed));
        })

      
        ->rawColumns([ 'kode_obat','stock','ed'])
        ->make(true);
            }

    public function stok()
    {
        $pdrd = Produk::where('cabang_id',auth()->user()->cabang_id)
        // ->where('stock','<=',10)
        // ->orderBy('stock','desc')
        ->get();
        $stok=[];
        foreach ($pdrd as $key => $value) {
            if ($value->stock<= $value->stok_minim) {
              $stok[]=$value->id;
            }
        }
        // return($stok);

$produk=Produk::where('cabang_id',auth()->user()->cabang_id)->whereIn('id',$stok)->get();
        return datatables()
        ->of($produk)
        ->addIndexColumn()
      
        ->addColumn('kode_obat', function ($produk) {
        return '<span class="label label-success">'. $produk->kode_obat .'</span>';
        })

        ->addColumn('stock', function ($produk) {
        return format_uang($produk->stock);
        })
        // ->addColumn('loka', function ($produk) {
        //     return $produk->location['nama_lokasi'];
        //     })
      
        ->rawColumns([ 'kode_obat','stock'])
        ->make(true);
            }
}
