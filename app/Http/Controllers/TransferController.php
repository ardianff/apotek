<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Pengeluaran;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\Cabang;
use App\Models\Transfer;
use App\Models\Transfer_detail;
use App\Models\Setting;
use App\Models\Shift;
use App\Models\Jasa;
use App\Models\Laporan_kasir;
use App\Models\Kartu_stock;
use Illuminate\Http\Request;
use PDF;
use DB;
use Auth;

class TransferController extends Controller
{
    public function index()
    {
        // $transfer = Transfer::with('penerima')->where('pengirim', Auth::user()->cabang_id)->get();
        // return $transfer;
        $cabang=Cabang::all();
        return view('transfer.index',compact('cabang'));
    }

    public function data()
    {
        // ->where('pengirim', Auth::user()->cabang_id)
        $transfer = Transfer::with('cabang')->where('pengirim', Auth::user()->cabang_id)->get();

        return datatables()
            ->of($transfer)
            ->addIndexColumn()
            ->addColumn('nama_cabang', function ($transfer) {
                return $transfer->cabang->nama_cabang;
            })
            ->addColumn('aksi', function ($transfer) {
                return '
                <div class="btn-group">
                    <button onclick="showDetail(`'. route('transfer.show', $transfer->id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></button>
                    <button onclick="deleteData(`'. route('transfer.delete', $transfer->id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi','nama_cabang'])
            ->make(true);
    }

    public function create($id)
    {
        $transfer = new Transfer();
        $transfer->pengirim = Auth()->user()->cabang_id;
        $transfer->penerima = $id;
        $transfer->total_item  = 0;
        $transfer->tanggal = date('d-m-Y');
        $transfer->ket      = '';
        $transfer->save();

        $cabang=Cabang::find($id);
        session(['transfer' => $transfer->id]);
        session(['cabang' => $id]);

        return redirect()->route('transfer_detail.index');
    }

    public function store(Request $request)
    {
        $total=Transfer_detail::where('transfer_id', $request->transfer_id)->sum('jumlah');
        $tf=Transfer::find($request->transfer_id);
        $tf->ket=$request->ket;
        $tf->total_item=$total;
        $tf->update();
        $detail = Transfer_detail::where('transfer_id', $tf->id)->get();
        foreach ($detail as $item) {
        $produk = Produk::find($item->produk_id);
        $produk->stock -= $item->jumlah;
        $produk->update();
//         $id_produk=$produk->id_produk;
// $stock= new Kartu_stock();
// $stock->id_produk=$id_produk;
// $stock->jumlah= $produk->stok;
// $stock->ket_stock='di ubah oleh '.auth()->user()->name;
// $stock->transaksi='transaksi kasir ';
// $stock->perubahan='obat berkurang '.$item->jumlah;
// $stock->save();
        }
        return redirect()->route('transfer.index')->with('berhasil', 'your message,here');
    }

    public function show($id)
    {
        $detail = Transfer_detail::with('produk')->where('transfer_id', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('kode_obat', function ($detail) {
                return '<span class="label label-success">'. $detail->produk->kode_obat .'</span>';
            })
            ->addColumn('nama_obat', function ($detail) {
                return $detail->produk->nama_obat;
            })
           
            ->addColumn('satuan', function ($detail) {
                return $detail->produk->satuan;
            })
           
            ->rawColumns(['kode_obat'])
            ->make(true);
    }

    public function destroy($id)
    {
        $penjualan = Penjualan::find($id);
        $detail    = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->get();
        foreach ($detail as $item) {
            $produk = Produk::find($item->id_produk);
            if ($produk) {
                $produk->stok += $item->jumlah;
                $produk->update();
            }

            $item->delete();
        }

        $penjualan->delete();

        return response(null, 204);
    }

       
}
