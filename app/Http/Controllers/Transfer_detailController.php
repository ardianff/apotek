<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use App\Models\Transfer_detail;
use App\Models\Produk;
use App\Models\Cabang;
use App\Models\DetailObat;
use App\Models\kartu_stock;
use App\Models\Lokasi;
use App\Models\Metode;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Carbon\Carbon;

class Transfer_detailController extends Controller
{
    public function index()
    {
        $transfer = Transfer::find(session('transfer'));
        $produk = Produk::where('cabang_id',auth()->user()->cabang_id)->orderBy('nama_obat')->get();
        $cabang = Cabang::find(session('cabang'));

        if (! $cabang) {
            abort(404);
        }

        return view('transfer_detail.index', compact('transfer', 'produk', 'cabang'));
        
    }

    public function data($id)
    {
        $detail = Transfer_detail::with('produk')
            ->where('transfer_id', $id)
            ->get();
        $data = array();

        foreach ($detail as $item) {
            $row = array();
            $row['kode_produk'] = '<span class="label label-success">'. $item->produk['kode_obat'] .'</span';
            $row['nama_produk'] = $item->produk['nama_obat'];
            $row['satuan']  = $item->produk->satuan;
            $row['jumlah']      = '<input type="number" class=" quantity" data-id="'. $item->id .'" value="'. $item->jumlah .'">';
          
            $row['ed']      = date('d-m-Y',strtotime($item->produk->ed));
            $row['batch']      = $item->produk->batch;
            
            $row['aksi']        = '<div class="btn-group">
                                    <button onclick="deleteData(`'. route('transfer_detail.destroy', $item->id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </div>';
            $data[] = $row;

          
        }
        $data[] = [
            'kode_produk' => '',
            'nama_produk' => '',
            'satuan'  => '',
            'jumlah'      => '',
            'ed'      => '',
            'batch'      => '',
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
        $produk = Produk::where('id', $request->id_produk)->first();
        if (! $produk) {
            return response()->json('Data gagal disimpan', 400);
        }

        $detail = new Transfer_detail();
        $detail->transfer_id = $request->transfer_id;
        $detail->produk_id = $produk->id;
        $detail->jumlah = 1;
        $detail->ed = $produk->ed;
        $detail->batch = $produk->batch;
        $detail->save();

        return response()->json('Data berhasil disimpan', 200);
    }

  
    public function update(Request $request, $id)
    {
        $detail = Transfer_detail::find($id);
        $detail->jumlah = $request->jumlah;
        $detail->update();
    }
    
   
    public function destroy($id)
    {
        $detail = Transfer_detail::find($id);
        $detail->delete();

        return response(null, 204);


    }

    
}
