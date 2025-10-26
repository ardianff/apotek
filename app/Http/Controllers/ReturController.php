<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\ReturPenjualanDetail;
use App\Models\Produk;
use App\Models\ReturPenjualan;
use App\Models\Shift;
use App\Models\User;
use App\Models\DetailObat;
use App\Models\Metode;
use App\Models\Laporan_kasir;
use App\Models\Kartu_stock;
use Illuminate\Http\Request;
use PDF;
use DB;
use Carbon\Carbon;

class ReturController extends Controller
{
    public function penjualan(Request $request,$id)
    {
        $penjualan=Penjualan::findOrFail($id);
        $detail=PenjualanDetail::with(['prodk','obat'])->where('penjualan_id',$id)->get();
        if ($penjualan->status == 'Belum selesai') {
            return back();
        }
        $cek=ReturPenjualan::where('penjualan_id',$penjualan->id)->first();
        if ($cek) {
            $retur=ReturPenjualan::find($cek->id);
        }else{

            $retur=new ReturPenjualan();
            $retur->cabang_id=$penjualan->cabang_id;
        $retur->kasir_id=$penjualan->kasir_id;
        $retur->shift_id=$penjualan->shift_id;
        $retur->penjualan_id=$penjualan->id;
        $retur->total_sebelum=$penjualan->bayar;
        $retur->item_sebelum=$penjualan->total_item;
        $retur->total_sesudah=null;
        $retur->item_sesudah=null;
        $retur->ket=null;
        $retur->user_id=auth()->user()->id;
        $retur->save();
    }
    $metode=Metode::all();
        
        return view('retur.penjualan.index',compact('penjualan','detail','retur','metode'));
    }

    public function penjualan_show($id)
    {
        $penjualan=Penjualan::find($id);

        return response()->json($penjualan);

    }

    public function penjualan_edit(Request $request, $id)
    {
        $penjualan=Penjualan::find($id);
        $penjualan->metode_id=$request->metode_id;
        // $penjualan->total_item=$request->jml_item;
        // $penjualan->total_harga=$request->bayar;
        // $penjualan->bayar=$request->bayar;
        // $penjualan->diterima=$request->diterima;
        // $penjualan->diskon=$request->diskon;
        $penjualan->update();

        return response(null, 204);

        }

  

    public function penjualan_delete($id)
    {  
        $detail=PenjualanDetail::find($id);
        // $produk=Produk::find($detail->produk_id);
        // $obat=DetailObat::find($detail->obat_id);
        $retur=ReturPenjualan::where('penjualan_id',$detail->penjualan_id)->first();
        
        $retur_detail=new ReturPenjualanDetail();
        $retur_detail->retur_id=$retur->id;
        $retur_detail->penjualan_id=$detail->penjualan_id;
        $retur_detail->produk_id=$detail->produk_id;
        $retur_detail->obat_id=$detail->obat_id;
        $retur_detail->harga=$detail->harga_jual;
        $retur_detail->diskon=$detail->diskon;
        $retur_detail->jml_item=$detail->jumlah;
        $retur_detail->subtotal=$detail->subtotal;
        $retur_detail->user_id=auth()->user()->id;
        $retur_detail->save();
        
        $penjualan=Penjualan::find($detail->penjualan_id);
        $penjualan->total_item -= $detail->jumlah;
        $penjualan->total_harga -= $detail->subtotal;
        $penjualan->bayar -= $detail->subtotal;
        $penjualan->update();
        
        $retur->total_sesudah=$penjualan->total_harga;
        $retur->item_sesudah=$penjualan->total_item;
        $retur->update();

        $obat=DetailObat::find($detail->obat_id);
        $obat->stock += $detail->jumlah;
        $obat->update();

        $hapus=PenjualanDetail::find($id)->delete();

        return response(null, 204);

    }

   
  
    public function destroy($id)
    {
        
        $penjualan = Penjualan::find($id);
        $detail    = PenjualanDetail::where('penjualan_id', $id)->get();
        if ($penjualan->status =='Belum Selesai') {
            
                    foreach ($detail as $item) {                
                        $item->delete();
                        
                    }
                    
                    $penjualan->delete();
            }else{
                    
                    foreach ($detail as $item) {   
                        $produk=Produk::find($item->produk_id);
                        $produk->stock +=$item->jumlah;
                        $produk->update();             
                        $item->delete();
                        
                    }
                    
                    $penjualan->delete();
                    
                }
        

        return response(null, 204);
    }

   
   
}
