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
        $suppliers = Supplier::all(); // Ambil data supplier
        $produk = Produk::all();     // Ambil data produk
        return view('pembelian.index', compact('suppliers', 'produk'));
    }

    public function store(Request $request)
    {
        $pembelian = Pembelian::create([
            'supplier_id' => $request->supplier_id,
            'no_faktur' => $request->no_faktur,
            'tgl_faktur' => $request->tgl_faktur,
            'total_item' => 0,
            'total_bayar' => 0,
            'diskon' => 0,
            'potongan' => 0,
        ]);

        return response()->json(['success' => true, 'data' => $pembelian]);
    }

    public function storeDetail(Request $request)
    {
        $detail = PembelianDetail::create([
            'pembelian_id' => $request->pembelian_id,
            'produk_id' => $request->produk_id,
            'jumlah' => 0,
            'isi' => 0,
            'hb_grosir' => 0,
            'harga_beli' => 0,
            'harga_nonppn' => 0,
            'diskon' => 0,
            'exp_date' => Carbon::now(),
            'batch' => null,
            'subtotal' => 0,
        ]);

        return response()->json(['success' => true, 'data' => $detail]);
    }

    public function updateDetail(Request $request)
{
    $detail = PembelianDetail::find($request->id);

    if ($detail) {
        $detail->update([
            'jumlah' => $request->jumlah,
            'isi' => $request->isi,
            'hb_grosir' => $request->hb_grosir,
            'harga_beli' => $request->hb_grosir / ($request->isi ?: 1),
            'harga_nonppn' => $request->hb_grosir - ($request->ppn ?? 0),
            'diskon' => $request->diskon,
            'subtotal' => ($request->hb_grosir * $request->jumlah) - ($request->diskon / 100 * ($request->hb_grosir * $request->jumlah)),
        ]);

        // Update total item, subtotal, dan total bayar
        $pembelian = Pembelian::find($detail->pembelian_id);
        $totalSubtotal = PembelianDetail::where('pembelian_id', $pembelian->id)->sum('subtotal');
        $totalItem = PembelianDetail::where('pembelian_id', $pembelian->id)->sum('jumlah');

        $pembelian->update([
            'total_item' => $totalItem,
            'total_bayar' => $totalSubtotal - $pembelian->diskon - $pembelian->potongan,
        ]);

        return response()->json([
            'success' => true,
            'data' => $detail,
            'total_item' => $pembelian->total_item,
            'total_subtotal' => $totalSubtotal,
            'total_bayar' => $pembelian->total_bayar,
        ]);
    }

    return response()->json(['success' => false, 'message' => 'Detail not found.'], 404);
}
public function updateSummary(Request $request)
{
    $pembelian = Pembelian::find($request->pembelian_id);

    if ($pembelian) {
        $totalSubtotal = PembelianDetail::where('pembelian_id', $pembelian->id)->sum('subtotal');

        $pembelian->update([
            'diskon' => $request->diskon,
            'potongan' => $request->potongan,
            'total_bayar' => $totalSubtotal - $request->diskon - $request->potongan,
        ]);

        return response()->json([
            'success' => true,
            'total_item' => $pembelian->total_item,
            'total_subtotal' => $totalSubtotal,
            'total_bayar' => $pembelian->total_bayar,
        ]);
    }

    return response()->json(['success' => false, 'message' => 'Pembelian not found.'], 404);
}

}

