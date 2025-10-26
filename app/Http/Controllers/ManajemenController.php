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

class ManajemenController extends Controller
{
    public function index()
    {
        $produk = Produk::count();
        $supplier = Supplier::count();
        $cabang=Cabang::all();

        $tanggal_awal = date('Y-m-01');
        $tanggal_akhir = date('Y-m-d');

        $data_tanggal = array();
        $data_pendapatan = array();
        
        
        while (strtotime($tanggal_awal) <= strtotime($tanggal_akhir)) {
            $data_tanggal[] = (int) substr($tanggal_awal, 8, 2);

            $total_penjualan = Penjualan::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('bayar');
            // $total_pembelian = Pembelian::where('cabang_id',$cabang_id)->where('created_at', 'LIKE', "%$tanggal_awal%")->sum('bayar');
            // $total_pengeluaran = Pengeluaran::where('cabang_id',$cabang_id)->where('created_at', 'LIKE', "%$tanggal_awal%")->sum('nominal');

            // $pendapatan = $total_penjualan - $total_pembelian - $total_pengeluaran;
            $data_pendapatan[] += $total_penjualan;

            $tanggal_awal = date('Y-m-d', strtotime("+1 day", strtotime($tanggal_awal)));
        }

        $tanggal_awal = date('Y-m-01');
// expired date
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
$exp = Produk::with('lokasi')
->whereNotNull('ed')
->where('ed','!=','0')
->where('stock','!=','0')
->where('ed','<=',$datelist)
->count();


$prodk=Produk::all();
$stokmin=[];
foreach ($prodk as $key => $pro) {
    if ($pro->stock <= $pro->stok_minim) {
       $stokmin[]=$pro->id;
    } 
}
$stokout=Produk::whereIn('id',$stokmin)->count();
       
            return view('manajemen.Dashboard',compact('stokout', 'produk', 'supplier', 'exp', 'tanggal_awal', 'tanggal_akhir', 'data_tanggal', 'data_pendapatan'));
        
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
