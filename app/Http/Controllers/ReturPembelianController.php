<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Retur_pembelian;
use App\Models\Retur_pembelian_detail;
use App\Models\Produk;
use App\Models\DetailObat;
use App\Models\Supplier;
use App\Models\Kartu_stock;

class ReturPembelianController extends Controller
{
    public function index($id)
    {
        // return $id;
        $retur_pembelian = Retur_pembelian::with(['pembelian'])->where('pembelian_id',$id)->first();
        if ($retur_pembelian) {
            
            $retur_pembelian = Retur_pembelian::with(['pembelian'])->where('pembelian_id',$id)->first();
            return view('retur.pembelian.index',compact('retur_pembelian'));
        }else{
           return redirect()->route('retur_pembelian.create',$id);
        }
    }

    public function data($id)
    {

        $retur_pembelian = Retur_pembelian_detail::with(['produk','detailobat'])->where('retur_pembelian_id',$id)->get();
        
        return datatables()
            ->of($retur_pembelian)
            ->addIndexColumn()

          ->addColumn('kd_obat',function($retur_pembelian){
            return $retur_pembelian->produk->kode_obat;
          })
          ->addColumn('nama_obat',function($retur_pembelian){
            return $retur_pembelian->produk->nama_obat;
          })
          ->addColumn('ed',function($retur_pembelian){
            return $retur_pembelian->detailobat->ed;
          })
          ->addColumn('batch',function($retur_pembelian){
            return $retur_pembelian->detailobat->batch;
          })
         
            ->addColumn('harga_beli', function ($retur_pembelian) {
                return 'Rp. '. format_uang($retur_pembelian->harga_beli);
            })
            ->addColumn('diskon', function ($retur_pembelian) {
                return $retur_pembelian->diskon.' %';
            })
          
            ->addColumn('aksi', function ($retur_pembelian) {
                return '
                <div class="btn-group">
                    <button onclick="edit(`'. route('retur_pembelian.update', $retur_pembelian->id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-edit"></i></button>
                    
                </div>
                ';
            })
            ->rawColumns(['harga_beli','kd_obat','nama_obat','ed','batch','aksi','diskon'])
            ->make(true);
    }

    public function create($id)
    {
        $pembelian=Pembelian::find($id);
            $retur=new Retur_pembelian();
            $retur->pembelian_id=$pembelian->id;
            $retur->supplier_id=$pembelian->supplier_id;
            $retur->no_faktur=$pembelian->no_faktur;
            $retur->total_item=$pembelian->total_item;
            $retur->total_harga=$pembelian->total_harga;
            $retur->total_retur=0;
            $retur->cabang_id=auth()->user()->cabang_id;
            $retur->save();

            $beli=PembelianDetail::where('pembelian_id',$id)->get();
            foreach ($beli as $key => $value) {
                # code...
                $obat=DetailObat::where('detailbeli_id',$value->id)->first();

                $detail= new Retur_pembelian_detail();
                $detail->retur_pembelian_id=$retur->id;
                $detail->pembelian_detail_id=$value->id;
                $detail->produk_id=$value->produk_id;
                $detail->obat_id=$obat->id;
                $detail->harga_beli=$value->hb_grosir;
                $detail->diskon=$value->diskon;
                $detail->jumlah=$value->jumlah;
                $detail->subtotal=$value->subtotal;
                $detail->jumlah_retur=0;
                $detail->save();
            }

            $retur_pembelian = Retur_pembelian::with(['pembelian','supplier'])->find($retur->id);
            return view('retur.pembelian.index',compact('retur_pembelian'));
       
    }

   
    public function store($id)
    {
        $jumlah_retur=Retur_pembelian_detail::where('retur_pembelian_id',$id)->sum('jumlah_retur');
        
        $retur_pembelian = Retur_pembelian::findOrFail($id);
        $retur_pembelian->total_retur=$jumlah_retur;
        $retur_pembelian->save();
       
        $pembelian=Pembelian::find($retur_pembelian->pembelian_id);
        $pembelian->total_item =$retur_pembelian->total_item;
        $pembelian->total_harga = $retur_pembelian->total_harga;
        $bayar=($retur_pembelian->total_harga - (($retur_pembelian->total_harga * $pembelian->diskon) / 100));
        $potongan= $bayar-$pembelian->potongan;
        $pembelian->bayar = $bayar;
        $pembelian->potongan = $potongan;
        $pembelian->update();
        
        $beli=Retur_pembelian_detail::where('retur_pembelian_id',$id)->get();
        foreach ($beli as $key => $value) {
          
            $detail=PembelianDetail::find($value->pembelian_detail_id);
            $detail->jumlah=$value->jumlah;
            $detail->subtotal=$value->subtotal;
            $detail->update();

            $obat=DetailObat::find($value->obat_id);
            $obat->stock -=$value->jumlah;
            $obat->update();
        }


        return redirect()->route('pembelian.index');
    }

   
   function cancel($id) 
   {
    $retur=Retur_pembelian::find($id);
    if ($retur->total_retur==0) {
       $retur_detail=Retur_pembelian_detail::where('retur_pembelian_id',$retur->id)->delete();
       $retur->delete();
       return redirect()->route('pembelian.index');
    }else{
        return redirect()->route('pembelian.index');

    }
   }

    

    public function show($id)
    {
       $retur=Retur_pembelian_detail::with('produk','detailobat')->find($id);
       return response()->json($retur);
      
    }

    public function update(Request $request, $id)
{

    // return $request;
    $retur = Retur_pembelian_detail::find($id);
    if (!$retur) {
        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

    $jumlah = $retur->jumlah - $request->jumlah_retur;
    $sub = $jumlah * ($retur->harga_beli - (($retur->harga_beli * $retur->diskon) / 100));
    $retur->jumlah_retur += $request->jumlah_retur;
    $retur->jumlah = $jumlah;
    $retur->subtotal = $sub;
    $retur->update();

    // return $retur;
    $total = Retur_pembelian_detail::where('retur_pembelian_id', $retur->retur_pembelian_id)->sum('subtotal');

    $retur_pembelian = Retur_pembelian::find($retur->retur_pembelian_id);
    if ($retur_pembelian) {
        $retur_pembelian->total_item = Retur_pembelian_detail::where('retur_pembelian_id', $retur->retur_pembelian_id)->sum('jumlah');
        $retur_pembelian->total_harga = $total;
        $retur_pembelian->total_retur = Retur_pembelian_detail::where('retur_pembelian_id', $retur->retur_pembelian_id)->sum('jumlah_retur');
        $retur_pembelian->update();
    }

    // return response()->json('Data berhasil disimpan', 200);

    return back()->with('Data berhasil disimpan', 200);


 
}

       

    public function destroy($id)
    {
        $retur_pembelian = retur_Pembelian::find($id);
        $detail    = retur_PembelianDetail::where('retur_pembelian_id', $retur_pembelian->id)->get();
        foreach ($detail as $item) {
            $produk = Produk::find($item->produk_id);
            if ($produk) {
                $produk->stock -= $item->jumlah;
                $produk->update();
            }
            $item->delete();
        }

        $retur_pembelian->delete();

        return response(null, 204);
    }
}
