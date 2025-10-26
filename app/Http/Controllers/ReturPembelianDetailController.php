<?php

namespace App\Http\Controllers;

use App\Models\Retur_pembelian;
use App\Models\Retur_pembelian_detail;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Produk;
use App\Models\kartu_stock;
use App\Models\Lokasi;
use App\Models\Metode;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReturPembelianDetailController extends Controller
{
    public function index()
    {
        $id_retur=session('id_retur');
        $pembelian=Pembelian::with('supplier')->where('id',$id_retur)->first();
        $produk=Produk::where('cabang_id',auth()->user()->cabang_id)->get();
        $daftar=PembelianDetail::with('produk')->where('pembelian_id',session('daftar'))->get();

        if (! $daftar) {
            abort(404);
        }
        return view('retur.pembelian_index',compact('produk','id_retur','daftar'));
    }

    public function data($id)
    {
        $detail = Retur_pembelian_detail::with(['produk','beli'])
        ->where('retur_pembelian_id', $id)
        ->get();
    $data = array();
    $total = 0;
    $total_item = 0;

    foreach ($detail as $item) {
        $row = array();
        $row['kode_produk'] = '<span class="label label-success">'. $item->produk['kode_obat'] .'</span';
        $row['nama_produk'] = $item->produk['nama_obat'];
        $row['harga_beli']  = 'Rp. '. format_uang($item->harga_beli);
        $row['jumlah']      = '<input type="number" class=" quantity" data-id="'. $item->id .'" value="'. $item->jumlah .'">';
        $row['diskon']      =  $item->diskon;
        $row['ed']      = $item->beli->ed;
        $row['batch']      = $item->beli->batch;
        $row['subtotal']    = 'Rp. '. format_uang($item->subtotal);
        $row['aksi']        = '<div class="btn-group">
                                <button onclick="deleteData(`'. route('retur_pembelian_detail.destroy', $item->id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                            </div>';
        $data[] = $row;

        $total += $item->harga_beli * $item->jumlah ;
    }
    $data[] = [
        'kode_produk' => '',
            
        'nama_produk' => '',
        'harga_beli'  => '',
        'jumlah'      => '',
        'diskon'      => '',
        'ed'      => '',
        'batch'      => '',
        'subtotal'    => '',
        'aksi'        => '',
    ];

    return datatables()
        ->of($data)
        ->addIndexColumn()
        ->rawColumns(['aksi', 'kode_produk', 'jumlah'])
        ->make(true);
    }
    public function store(Request $request)
    {
        $beli=PembelianDetail::find($request->detail_id);
        if (! $beli) {
         return response()->json('Gagal Menyimpan', 400);
     }
         $produk = Produk::where('id', $beli->produk_id)->first();
         if (! $produk) {
             return response()->json('Produk tidak ditemukan', 400);
         }
 
         $detail = new Retur_pembelian_detail();
         $detail->retur_pembelian_id = $request->retur_id;
         $detail->produk_id = $produk->id;
         $detail->pembelian_detail_id = $beli->id;
         $detail->harga_beli = $beli->harga_beli;
         $detail->diskon = $beli->diskon;
         $detail->jumlah = 1;
         $detail->subtotal = $beli->harga_beli * 1 - (($beli->diskon * 1) / 100 * $beli->harga_beli);
         $detail->save();

       
         return response()->json('Data berhasil disimpan', 200);
    }

  
    public function update(Request $request, $id)
    {
        $detail = Retur_pembelian_detail::find($id);
        $detail->jumlah = $request->jumlah;
        $detail->subtotal = $detail->harga_beli * $request->jumlah - ($request->diskon * $request->jumlah * $detail->harga_beli/100);
        $detail->update();


    }
    
  
    public function destroy($id)
    {
        $detail = Retur_pembelian_detail::find($id);
        $detail->delete();

        return response(null, 204);


    }

   
}
