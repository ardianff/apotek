<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Produk;
use App\Models\DetailObat;
use App\Models\kartu_stock;
use App\Models\Lokasi;
use App\Models\Metode;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class PembelianDetailController extends Controller
{
    public function index()
    {
        $pembelian = Pembelian::find(session('pembelian_id'));
        $produk = Produk::where('cabang_id',auth()->user()->cabang_id)->orderBy('nama_obat','asc')->get();
        $lokasi = Lokasi::where('cabang_id',auth()->user()->cabang_id)->get();
        $metode = Metode::where('kategori', 'pembelian')->get();
        $supplier = Supplier::find(session('supplier_id'));
        $diskon = Pembelian::find(session('pembelian_id'))->diskon ?? 0;

        if (! $supplier) {
            abort(404);
        }
        // dd($data);

        // return view('pembelian_detail.index', compact('pembelian', 'produk', 'supplier', 'diskon','metode','lokasi'));
        return view('pembelian_detail.index', compact('pembelian', 'produk', 'supplier', 'diskon','metode','lokasi'));
        
    }

    public function data($id)
    {
        $pembelian=PembelianDetail::with(['produk','lokasi'])->where('pembelian_id',$id);
        $data = $pembelian->orderBy('id','DESC')->get();
        $totalItem = $data->sum('jumlah');
    $totalSubtotal = $data->sum('subtotal');
            return datatables()
            ->of($data)
            ->addIndexColumn()
           
            ->addColumn('kode_obat', function ($data) {
                return '<span class="label label-success">' . $data->produk->kode_obat . '</span>';
            })
            ->addColumn('nama_obat', function ($data) {
                return $data->produk->nama_obat;
            })
            ->addColumn('lokasi', function ($data) {
                return $data->lokasi->name??0;
            })
            ->addColumn('hb_grosir', function ($data) {
                return format_uang($data->hb_grosir??0);
            })
            ->addColumn('harga_nonppn', function ($data) {
                return format_uang($data->harga_nonppn??0);
            })
            ->addColumn('subtotal', function ($data) {
                return format_uang($data->subtotal??0);
            })
            ->addColumn('ed', function ($data) {
                return tanggal($data->ed??date('Y-m-d'));
            })
            ->addColumn('diskon', function ($data) {
                return $data->diskon.' %';
            })
               ->addColumn('aksi', function ($data) {

                  $delete=' <button data-toggle="tooltip" data-placement="top" title="hapus data" type="button" onclick="deleteData(`' . route('pembelian_detail.destroy', $data->id) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>';
                  $edit=' <button data-toggle="tooltip" data-placement="top" title="ubah data" type="button" onclick="editData(`' . route('pembelian_detail.show', $data->id) . '`,`'.route('pembelian_detail.ubah',$data->id).'`)" class="btn btn-xs btn-warning btn-flat"><i class="fa fa-pencil"></i></button>';

                  return $delete . $edit;
               })
         

  ->rawColumns(['aksi', 'kode_obat', 'nama_obat','lokasi','hb_grosir','harga_nonppn','subtotal','ed','diskon'])
            
   ->with([
            'total_item' => $totalItem,
            'total_subtotal' => $totalSubtotal,
        ])
            ->make(true);

    }
    
    public function store(Request $request,$id)
    {
    // dd($request->all());
     DB::beginTransaction();
     try{
$harga_ppn     = null;
$harga_nonppn  = null;

// Konversi format harga menjadi float
$ppn = $request->filled('harga_ppn') ? parseHarga($request->harga_ppn) : null;
$nonppn = $request->filled('harga_nonppn') ? parseHarga($request->harga_nonppn) : null;

if ($request->harga_ppn > 0) {
    $harga_ppn = $ppn;
    $harga_nonppn = round($harga_ppn / 1.11, 0);  // PPN 10%
} elseif ($request->harga_nonppn > 0) {
    $harga_nonppn = $nonppn;
    $harga_ppn = round($harga_nonppn * 1.11, 0);
} else {
    return response()->json('Data gagal disimpan', 400);
}
// dd($harga_ppn, $harga_nonppn);
      

    $qty=$request->isi??1 * $request->jumlah??1;
$harga_beli= $harga_ppn / $qty;
         $detail = new PembelianDetail();
            $detail->pembelian_id = $id;
            $detail->produk_id = $request->produk_id;
            $detail->harga_beli = $harga_beli;
            $detail->hb_grosir = $harga_ppn;
            $detail->harga_nonppn = $harga_nonppn;
           
            $detail->satuan = $request->satuan??'pcs';
            $detail->jumlah = $request->jumlah??0;
            $detail->isi = $request->isi?? 1;
            $detail->diskon = $request->diskon ?? 0;
            $detail->lokasi_id = $request->lokasi_id ?? 0;
             $detail->ed = Carbon::parse($request->ed)->format('Y-m-d') ?? Carbon::now()->format('Y-m-d');
            $detail->batch = $request->batch ?? 0;
            $detail->subtotal = $harga_ppn * $request->jumlah - (($request->diskon * $request->jumlah) / 100 * $harga_ppn);
            $detail->save();
            
         DB::commit();  
            return response()->json('Data berhasil disimpan', 200);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json('Data gagal disimpan', 400);

        }
        }

   function show($id)
   {
        $detail = PembelianDetail::with(['produk','lokasi'])->find($id);
        if (!$detail) {
            return response()->json('Data tidak ditemukan', 404);
        }

        return response()->json($detail, 200);
    
   }

    public function ubah(Request $request, $id)
    {
       $harga_ppn     = null;
$harga_nonppn  = null;

// Konversi format harga menjadi float
$ppn = $request->filled('harga_ppn') ? parseHarga($request->harga_ppn) : null;
$nonppn = $request->filled('harga_nonppn') ? parseHarga($request->harga_nonppn) : null;

if ($request->harga_ppn > 0) {
    $harga_ppn = $ppn;
    $harga_nonppn = round($harga_ppn / 1.11, 0);  // PPN 10%
} elseif ($request->harga_nonppn > 0) {
    $harga_nonppn = $nonppn;
    $harga_ppn = round($harga_nonppn * 1.11, 0);
} else {
    return response()->json('Data gagal disimpan', 400);
}
    $qty=$request->isi??1 * $request->jumlah??1;
$harga_beli= $harga_ppn / $qty;
         $detail = PembelianDetail::find($id);
            $detail->produk_id = $request->produk_id;
            $detail->harga_beli = $harga_beli;
            $detail->hb_grosir = $harga_ppn;
            $detail->harga_nonppn = $harga_nonppn;
           
            $detail->satuan = $request->satuan??'pcs';
            $detail->jumlah = $request->jumlah??0;
            $detail->isi = $request->isi?? 1;
            $detail->diskon = $request->diskon ?? 0;
            $detail->lokasi_id = $request->lokasi_id ?? 0;
             $detail->ed = Carbon::parse($request->ed)->format('Y-m-d') ?? Carbon::now()->format('Y-m-d');
            $detail->batch = $request->batch ?? 0;
            $detail->subtotal = $harga_ppn * $request->jumlah - (($request->diskon * $request->jumlah) / 100 * $harga_ppn);
            $detail->update();

            return response()->json('Data berhasil disimpan', 200);
    }

   

    public function destroy($id)
    {
        $detail = PembelianDetail::find($id);
        $detail->delete();

        return response(null, 204);


    }



}
