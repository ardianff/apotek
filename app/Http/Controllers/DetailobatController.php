<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Golongan;
use App\Models\Lokasi;
use App\Models\Supplier;
use App\Models\Jenis;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\ProdukCabang;
use App\Models\Harga;
use App\Models\DetailObat;
use App\Models\Kartu_stock;
use App\Exports\DetailObatExport;
use PDF;
use DB;
use Carbon\Carbon;

class DetailobatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lokasi = Lokasi::where('cabang_id',auth()->user()->cabang_id)->get();
        $obat = Produk::where('cabang_id',auth()->user()->cabang_id)->get();


        return view('detailObat.index',compact('lokasi','obat'));




}
   public function transfer()
    {

    DB::transaction(function () {
        try {
           
    $cabangId = auth()->user()->cabang_id;

    // 1. Ambil produk_id yang digunakan di detail_obat cabang ini
    $produkIds = DetailObat::where('cabang_id', $cabangId)
        ->select('produk_id')
        ->distinct()
        ->pluck('produk_id');

    // 2. Ambil data produk terkait
    $produkList = Produk::whereIn('id', $produkIds)->get();

    // return $produkList;
    // 3. Salin ke produk_cabang
    foreach ($produkList as $produk) {
        ProdukCabang::updateOrCreate(
            [
                'cabang_id' => $cabangId,
                'produk_id' => $produk->id,
            ],
            [
                'kategori_id' => $produk->kategori_id,
            'kode_obat' => $produk->kode_obat,
            'nama_obat' => $produk->nama_obat,
            'racikan' => $produk->racikan,
            'isi' => $produk->isi,
            'stok_minim' => $produk->stok_minim,
            'merk' => $produk->merk,
            'satuan' => $produk->satuan,
            'lokasi_id' => $produk->lokasi_id,
            'margin' => $produk->margin,
            'golongan_id' => $produk->golongan_id,
            'jenis_id' => $produk->jenis_id,
            'toleransi' => $produk->toleransi,
            'kandungan' => $produk->kandungan,
            'kegunaan' => $produk->kegunaan,
            'zat' => $produk->zat,
            'dosis' => $produk->dosis,
            'efek' => $produk->efek,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            ]
        );
    }

    // 4. Ambil ulang semua produk_cabang setelah insert
    $produkCabangs = ProdukCabang::where('cabang_id', $cabangId)->get();

    // Buat mapping: produk_id (dari produk) → produk_cabang.id
    $produkMap = $produkCabangs->keyBy('produk_id');

    // 5. Update detail_obat dengan produk_cabang.id
    DetailObat::where('cabang_id', $cabangId)
        ->chunkById(100, function($details) use ($produkMap) {
            foreach ($details as $detail) {
                $produkCabang = $produkMap->get($detail->produk_id);
                if ($produkCabang) {
                    $detail->produk_id = $produkCabang->id;
                    $detail->save();
                }
            }
        });
 return redirect()->back()->with('success', 'Transfer produk berhasil dilakukan');
        } catch (\Exception $e) {
            return redirect()->back()->with('warning', 'Transfer produk gagal: ' . $e->getMessage());
        }
    });
        
    }

    public function detail($id)
    {
        $detail=DetailObat::with(['obat','lokasi'])->where('produk_id',$id)->where('cabang_id',auth()->user()->cabang_id)->get();
        $produk=Produk::find($id);
        $lokasi = Lokasi::where('cabang_id',auth()->user()->cabang_id)->get();
        // $obat = Produk::all();
        return view('produk.detail_obat',compact('detail','lokasi','produk'));
        // return $detail;
    }
    public function data()
    {
         $detil = DetailObat::with(['obat','lokasi'])->where('cabang_id',auth()->user()->cabang_id)
        ->where('stock','>','0')
        ->get();

        return datatables()
            ->of($detil)
            ->addIndexColumn()
            ->addColumn('select_all', function ($detil) {
                return '
                    <input type="checkbox" name="id[]" value="'. $detil->id .'">
                ';
            })
          
            ->addColumn('kode_obat', function ($detil) {
                return $detil->obat->kode_obat ??'OBATNYA DI HAPUS' ;
            })
            ->addColumn('nama_obat', function ($detil) {
                return $detil->obat->nama_obat ??'OBATNYA DI HAPUS' ;
            })
            ->addColumn('satuan', function ($detil) {
                return $detil->satuan ?? $detil->obat->satuan ?? 'belum ada satuan';
            })
            ->addColumn('lokasi', function ($detil) {
                return $detil->lokasi->name ?? 'belum ada lokasi';
            })
             ->addColumn('harga_jual', function ($detil) {
                return 'Rp '.number_format($detil->harga_jual ?? '0');
            })
             ->addColumn('harga_beli', function ($detil) {
                return 'Rp '.number_format($detil->harga_beli ?? '0');
            })


            ->addColumn('ed', function ($detil) {
                return date('d-m-Y', strtotime($detil->ed));
            })

        ->addColumn('aksi', function ($detil) {
            return '
            <div class="btn-group">
            <button type="button" onclick="editForm(`'. route('detailObat.update', $detil->id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>

            <button type="button" onclick="deleteData(`'. route('detailObat.destroy', $detil->id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>

            <button type="button" onclick="editstock(`'. route('detailObat.show', $detil->id).'`,`'. route('detailObat.stock', $detil->id).'`)" class="btn btn-xs btn-warning btn-flat"><i class="fa fa-edit"></i></button>



            </div>
            ';
        })
            ->rawColumns(['aksi','ed','lokasi', 'select_all','beli_id','nama_obat','satuan','lokasi','kode_obat'])
            ->make(true);
    }
    // <button type="button" onclick="transfer(`'. $produk->produk_id.'`,`'.$produk->id_detail_obat.'`)" class="btn btn-xs btn-success btn-flat"><i class="fa fa-share"></i></button>


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
    //    $supplier=Supplier::find($id)->first();
    //    $lokasi = Lokasi::all();
       $obat = Produk::all();

       return view('detailObat.formstock',compact('obat'));
    }
    public function stockin()
    {
       $supplier=Supplier::all();
// dd($supplier);
       return view('detailObat.stockin',compact('supplier'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
        $cab=auth()->user()->cabang_id;
$produk=Produk::find($request->produk_id);
        $obat =new DetailObat();
        $obat->produk_id=$request->produk_id;
        $obat->lokasi_id=$request->lokasi_id;
        $obat->ed=$request->ed;
        $obat->batch=$request->batch;
        $obat->stock=$request->stock;
        $obat->cabang_id=$cab;
        $obat->beli_id=0;
        $obat->konsi=0;
        // $obat->margin=$produk->margin;;
        $obat->harga_beli=str_replace('.', '', $request->harga_beli);
        $harga_jual = str_replace('.', '', $request->harga_jual);
        $diskon = $request->diskon;

        // Jika diskon ada dan > 0
        if (!empty($diskon) && $diskon > 0) {
            $harga_jual = $harga_jual - ($harga_jual * $diskon / 100);
            }

        $obat->harga_jual = $harga_jual;
        $obat->diskon=$diskon;
        $obat->save();
        DB::commit();
        return redirect()->back()->with('success', 'Data berhasil di update');
    }catch (\Exception $e) {
        DB::rollback();
        return redirect()->back()->with('warning', 'Data gagal di update');
        }
        // $request->session()->put('success', 'data berhasil disimpan');
        // return redirect()->route('detailObat.create',[ $obatin->supplier]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $produk = DetailObat::with('obat','supplier')->find($id);

        return response()->json($produk);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // return $request->satuan;
        DB::beginTransaction();
        try{
        $produk = DetailObat::find($id);
        $produk->lokasi_id=$request->lokasi_id;
        $produk->ed=$request->ed;
        $produk->batch=$request->batch;
        $produk->satuan=$request->satuan;
        $produk->harga_beli=str_replace('.', '', $request->harga_beli);
        $harga_jual = str_replace('.', '', $request->harga_jual);
        $diskon = $request->diskon;

        // Jika diskon ada dan > 0
        if (!empty($diskon) && $diskon > 0) {
            $harga_jual = $harga_jual - ($harga_jual * $diskon / 100);
            }

        $produk->harga_jual = $harga_jual;
        $produk->diskon=$diskon;
        $produk->update();
        DB::commit();
        return redirect()->back()->with('success', 'Data berhasil di update');
    }catch (\Exception $e) {
        DB::rollback();
        return redirect()->back()->with('warning', 'Data gagal di update');
        }
       
    }

    public function stock(Request $request, $id)
    {
        DB::beginTransaction();
        try{
             $produk = DetailObat::find($id);
            $produk->stock = $request->stock;
            $produk->update();



    $obat = DetailObat::find($id);
    $ks=Kartu_stock::where('cabang_id',auth()->user()->cabang_id)->where('produk_id',$obat->produk_id)->latest()->first();
    if ($ks) {
        $sisa=$ks->sisa;
    }else{
        $sisa=$obat->stock;
    }
    if ($request->stock >= 0) {
       $stock= new Kartu_stock();
        $stock->cabang_id=auth()->user()->cabang_id;
        $stock->produk_id=$obat->produk_id;
        $stock->obat_id=$obat->id;
        $stock->batch=$obat->batch;
        $stock->stock_awal=$sisa;
        $stock->stock_out=0;
        $stock->stock_in += $request->stock;
        $stock->sisa=$sisa+=$request->stock;
        $stock->ket_stock='Koreksi stock keterangan '.$request->ket_stock .' jumlah '.$request->stock .' oleh '.auth()->user()->name;
        $stock->user_id=auth()->user()->id;
        $stock->save();
    }else if ($request->stock <= 0) {
        $stock= new Kartu_stock();
        $stock->cabang_id=auth()->user()->cabang_id;
        $stock->produk_id=$obat->produk_id;
        $stock->obat_id=$obat->id;
        $stock->batch=$obat->batch;
        $stock->stock_awal=$sisa;
        $stock->stock_out += $request->stock;
        $stock->stock_in =0;
        $stock->sisa=$sisa += $request->stock;
        $stock->ket_stock='Koreksi stock keterangan '.$request->ket_stock .' jumlah '.$request->stock .' oleh '.auth()->user()->name;
        $stock->user_id=auth()->user()->id;
        $stock->save();
    }


DB::commit();
    return back()->with(['success','=>','Data berhasil disimpan']);
}catch (\Exception $e) {
    DB::rollback();
    return back()->with(['warning','=>','Data berhasil disimpan']);
    }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $produk = DetailObat::find($id);
        if ($produk->stock == 0) {

            $produk->delete();

            return back()->with('success', 'data berhasil di hapus');
        }
        return redirect()->back()->with('warning', 'stock tidak kosong tidak bisa di hapus');


    }
    public function deleteSelected(Request $request)
    {
        foreach ($request->id_produk as $id) {
            $produk = DetailObat::find($id);
        if ($produk->stock == 0) {

            $produk->delete();

        }else{

        }

    }
    return back()->with('success', 'data berhasil di hapus');

    }


function cekharga()
{
//     $harga=Harga::all();
//     foreach ($harga as $key => $value) {
//        $cek=Produk::where('nama_obat','=',$value->nama_obat)->first();
// if ($cek) {
// $value->obat_id=$cek->id;
// $value->update();

// }
//     }

$obat=DetailObat::all();
foreach ($obat as $key => $value) {
    $harga=Harga::where('obat_id',$value->produk_id)->first();
    if ($harga) {
        $value->harga_jual=$harga->harga;
        $value->update();
    }
}
    $daftar=DetailObat::where('harga_jual','!=','0')->get();

    return $daftar;
}

    public function cetakBarcode(Request $request)
    {
        $dataproduk = array();
        foreach ($request->id as $id) {
            $produk = DetailObat::find($id);
            $dataproduk[] = $produk;
        }

        $no  = 1;
        $pdf = PDF::loadView('produk.barcode', compact('dataproduk', 'no'));
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream('produk.pdf');
    }


}
