<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\Racikan;
use App\Models\Jasa;
use App\Models\DetailObat;
use App\Models\Metode;
use App\Models\Diskon;
use App\Models\Setting;
use Illuminate\Http\Request;
use DB;

class PenjualanDetailController extends Controller
{
    public function index()
    {
        $obat = Produk::with(['lokasi'])->where('cabang_id', auth()->user()->cabang_id)->orderBy('nama_obat','ASC')->get();
        $jasa = Jasa::where('cabang_id', auth()->user()->cabang_id)->get();
        $member = Member::where('cabang_id', auth()->user()->cabang_id)->get();
        $debit = Metode::where('jenis','Debit')->get();
        $kredit = Metode::where('jenis','Kredit')->get();
        $pot = Diskon::where('cabang_id', auth()->user()->cabang_id)->where('status', '1')->get();
        $diskon = Setting::where('cabang_id', auth()->user()->cabang_id)->first()->diskon ?? 0;

        // Cek apakah ada transaksi yang sedang berjalan
        // $penjualan_id = session('penjualan');
        $cekada = Penjualan::find(session('penjualan'));

        if ($cekada) {
            $penjualan = Penjualan::with('jasa')->find($cekada->id);
            $penjualan_id = $penjualan->id;

            return view('penjualan_detail.index', compact('obat', 'pot', 'penjualan_id', 'penjualan', 'jasa', 'debit','kredit', 'member'));
        } else {
            if (auth()->user()->level == 1) {
                return redirect()->route('transaksi.baru');
            } else {
                return redirect()->route('home');
            }
        }
    }

    public function data($id)
    {
        $detail = PenjualanDetail::with(['prodk'])
            ->where('penjualan_id', $id)
            ->get();

        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $row = array();
            $row['kode_produk'] = '<span class="label label-success">' . $item->prodk['kode_obat'] ?? 0 . '</span';
            $row['nama_produk'] = $item->prodk['nama_obat'] ?? 0;
            $row['harga_jual']  = 'Rp. ' . format_uang($item->harga_jual);
            $row['jumlah']      = '<input type="number" class="form-control input-sm quantity" data-id="' . $item->id . '" value="' . $item->jumlah . '">';
            $row['diskon']      = $item->diskon . '%';
            $row['subtotal']    = 'Rp. ' . format_uang($item->subtotal);
            $row['aksi']        = '<div class="btn-group">
                                    <button onclick="deleteData(`' . route('transaksi.destroy', $item->id) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </div>';
            $data[] = $row;

            $subtot = $item->harga_jual * $item->jumlah - (($item->diskon * $item->jumlah) / 100 * $item->harga_jual);
            $total_item += $item->jumlah;
            $totil = round($subtot);
            $total += $totil;
        }
        $data[] = [
            'kode_produk' => '
                <div class="total hide">' . $total . '</div>
                <div class="total_item hide">' . $total_item . '</div>',
            'nama_produk' => '',
            'harga_jual'  => '',
            'jumlah'      => '',
            'diskon'      => '',
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
        $produk = Produk::where('id', $request->produk_id)->first();
        if (!$produk) {
            return response()->json('Data gagal disimpan', 400);
        }
        DB::beginTransaction();
        try {
            //code...
            $detail = new PenjualanDetail();
            $detail->penjualan_id = $request->penjualan_id;
            $detail->produk_id = $produk->id;
            $detail->harga_beli = $produk->harga_beli;
            $detail->harga_jual = $produk->harga_jual;
            $detail->jumlah = 1;
            $detail->diskon = $produk->diskon;
            $detail->subtotal = $produk->harga_jual - ($produk->diskon / 100 * $produk->harga_jual);
            $detail->laba = $produk->harga_beli - $produk->harga_jual - ($produk->diskon / 100 * $produk->harga_jual);
            $detail->save();

            DB::commit();

            return response()->json('Data berhasil disimpan', 200);
        } catch (\Throwable $e) {
            DB::rollback();
            return response()->json('Data gagal disimpan', 400);
        }
    }


    public function update(Request $request, $id)
    {
        $detail = PenjualanDetail::find($id);
        $detail->jumlah = $request->jumlah;
        $detail->subtotal = $detail->harga_jual * $request->jumlah - (($detail->diskon * $request->jumlah) / 100 * $detail->harga_jual);;
        $detail->update();
    }

    public function destroy($id)
    {
        $detail = PenjualanDetail::find($id);
        $detail->delete();

        return response(null, 204);
    }

    public function loadForm($diskon = 0, $jasa = 0, $total = 0, $diterima = 0)
    {
        $bayar   = $total - ($diskon / 100 * $total) + $jasa;


        $total_harga = ceil($bayar / 100) * 100;
        $tot = ceil($total / 100) * 100;

        $terima = str_replace('.', '', $diterima);
        $kembali = ($terima != 0) ? $terima - $total_harga : 0;
        $data    = [
            'totalrp' => format_uang($tot),
            'bayar' => $total_harga,
            'bayarrp' => format_uang($total_harga),
            'terbilang' => ucwords(terbilang($total_harga) . ' Rupiah'),
            'kembalirp' => format_uang($kembali),
            'kembali_terbilang' => ucwords(terbilang($kembali) . ' Rupiah'),
        ];

        return response()->json($data);
    }
}
