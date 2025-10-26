<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Golongan;
use App\Models\Jenis;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kartu_stock;
use PDF;
use DB;

class Kartu_stockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index($id_produk)
    {
        // $kartu=Kartu_stock::leftJoin('produk','id_produk'.$request->id_produk,'kartu_stock'.$request->id_produk)
        // ->select('kartu_stock.*', 'nama_produk')
        // ->orderBy('created_ad', 'asc')
        // ->get();
        $kartu=Kartu_stock::where('id_produk',$id_produk)->get();

        return datatables()
            ->of($kartu)

            ->make(true);
    }

    
}
