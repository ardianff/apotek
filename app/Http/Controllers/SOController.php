<?php

namespace App\Http\Controllers;

use App\Models\StokOpname;
use App\Models\Produk;
use App\Models\DetailObat;
use App\Models\PenjualanDetail;
use App\Models\Penjualan;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Cabang;
use App\Models\Kartu_stock;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use DB;
use PDF;
use Carbon\Carbon;


class SOController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cab=auth()->user()->cabang_id;
        if ($request->has('lokasi_id')) {
        
            $id_lok=$request->lokasi_id;
            if ($id_lok=='semua') {
                $loka='Semua Lokasi';
            }else{
                $lok=Lokasi::find($id_lok);
                $loka=$lok->name;

            }
        }else {
            
            $id_lok='semua';
            $loka='Semua Lokasi';

            
        }
        $lokasi=Lokasi::where('cabang_id',$cab)->get();
        $mon=date('m-Y');

        $produk=DetailObat::with(['lokasi','obat'])->where('cabang_id',$cab)->where('lokasi_id',$id_lok)->orderBy('produk_id','ASC')->get();

        // return $produk;

        return view('SO.index',compact('lokasi','id_lok','loka'));
    }

    public function data($id_lok)
    {
        $cab=auth()->user()->cabang_id;
        $mon=date('m-Y');
         if ($id_lok=='semua') {
         $produk = DetailObat::select('id','produk_id','cabang_id','lokasi_id','satuan','stock','ed','batch','harga_jual')
    ->with([
        'lokasi:id,name',
        'obat:id,nama_obat,kode_obat,satuan,margin',
        'stokOP:id,detail_obat_id,stokSO'
    ])
    ->where('cabang_id', $cab)
    ->orderBy('produk_id','ASC')
    ->get();
        }else {
           $produk = DetailObat::select('id','produk_id','cabang_id','lokasi_id','satuan','stock','ed','batch','harga_jual')
    ->with([
        'lokasi:id,name',
        'obat:id,nama_obat,kode_obat,satuan,margin',
        'stokOP:id,detail_obat_id,stokSO'
    ])
    ->where('cabang_id', $cab)
    ->where('lokasi_id',$id_lok)
    ->orderBy('produk_id','ASC')
    ->get();
           
        }

        return datatables()
        ->of($produk)
        ->addIndexColumn()
        ->addColumn('nama_obat',function ($produk){
            return $produk->obat->nama_obat??0;
        })
        ->addColumn('ed',function ($produk){
             return date('d-m-Y', strtotime($produk->ed));
        })
        ->addColumn('kode_obat',function ($produk){
            return $produk->obat->kode_obat??0;
        })
        ->addColumn('satuan',function ($produk){
            return $produk->obat->satuan??0;
        })
        ->addColumn('margin',function ($produk){
            return $produk->obat->margin??0;
        })
        ->addColumn('lokasi',function ($produk){
            return $produk->lokasi->name??0;
        })
      
        ->addColumn('harga_jual',function ($produk){
            return format_uang($produk->harga_jual);
        })

       
       
       ->addColumn('stokop', function ($produk) {
    $value = $produk->stokOP->stokSO ?? '';
    return '<input type="number" class="form-control input-sm input-stokop" data-id="' . $produk->id . '" value="' . $value . '">';
})
       ->addColumn('aksi', function ($produk) {

    return ' <button type="button" onclick="editForm(`'. route('detailObat.update', $produk->id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>';
})

        ->rawColumns(['aksi','harga_beli','harga_jual','lokasi','kode_obat','nama_obat','satuan','margin','stokop','ed'])
        ->make(true);
    }
//   <a data-toggle="tooltip" data-placement="top" title="Detail Obat" type="button" href="' . route('detailObat.detail', $produk->id) . '" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-list"></i></a>

//                 <a data-toggle="tooltip" data-placement="top" title="Detail Obat" type="button" href="' . route('so.detail', $produk->id) . '" class="btn btn-sm btn-warning btn-flat"><i class="fa fa-pencil"></i></a>
 
public function proses(Request $request)
{
    $request->validate([
        'id' => 'required|integer',
        'stok_so' => 'required|numeric'
    ]);

    $detail = DetailObat::find($request->id);

    if (!$detail) {
        return response()->json(['success' => false, 'message' => 'Data tidak ditemukan']);
    }
$user_id = auth()->user()->id;
    // Update atau insert stok opname
   $stok = StokOpname::updateOrCreate(
    // Kondisi untuk mencari stok yang sudah ada
    [
        'detail_obat_id' => $detail->id,
        'produk_id' => $detail->produk_id,
        'cabang_id' => $detail->cabang_id,
        'bulan' => date('m-Y'), // atau $mon jika sudah didefinisikan
    ],
    // Data yang akan diupdate atau ditambahkan
    [
        'stokSO' => $request->stok_so,
        'stok_awal' => $detail->stock,
        'selisih' => $request->stok_so - $detail->stock,
        'user_id' => auth()->id(),
    ]
);
$detail->stock=$request->stok_so;
$detail->update();

return response()->json([
    'success' => true,
    'message' => 'Stok opname berhasil disimpan.'
]);

}







    public function create(Request $request,$id)
    {
        $cab=auth()->user()->cabang_id;
        $produk=Produk::find($id);
        $mon=date('m-Y');
    
        $tok=StokOpname::where('cabang_id',$cab)->where('produk_id',$id)->first();
        $tok->stokSO=$request->stok;
        if ($request->stok>=$produk->stock) {
            $sel=$request->stok-$produk->stock;
           }else {
            $sel=$produk->stock-$request->stok;
           }
       $tok->selisih=$sel;
       $tok->update();

    }
    public function detail($id)
{
$produk=Produk::with('lokasi')->find($id);
$obat=DetailObat::where('produk_id',$id)->get();
        return view('SO.edit',compact('produk','obat'));
}


    public function tambah(Request $request,$id)
    {
        
        DB::beginTransaction();
        try {
            $del=DetailObat::where('produk_id',$id)->delete();
            // return $request;
           
            //code...
        
        $cab=auth()->user()->cabang_id;
        $stok=array_sum($request->stok);

        $produk=Produk::find($id);
        $produk->nama_obat=$request->nama_obat;
        $produk->satuan=$request->satuan;
        $produk->stock=$stok;
        $produk->harga_beli=str_replace('.','',$request->harga_beli);
        $produk->margin=$request->margin;
        $produk->harga_jual=str_replace('.','',$request->harga_jual);
        $produk->update();

    foreach ($request->ed as $key=>$value) {

        $obat= new DetailObat();
        $obat->produk_id=$produk->id;
        $obat->cabang_id=$cab;
        $obat->lokasi_id=$produk->lokasi_id;
        $obat->stock=$request->stok[$key];
        $obat->ed= $request->ed[$key];
        $obat->batch=$request->batch[$key]??0;
        $obat->beli_id=0;
        $obat->save();

    }
    DB::commit();
return back()->with('success');
} catch (\Throwable $th) {
    DB::rollback();
    return back()->with('danger');
    //throw $th;
}
    }

//     public function proses()
//     {
       
// DB::beginTransaction();
// try {

// $cab=auth()->user()->cabang_id;
// $produk=Produk::where('cabang_id',$cab)->get();
// foreach ($produk as $key => $value) {
//     $obat=DetailObat::where('produk_id',$value->id)->sum('stock');
//     $ganti=Produk::find($value->id);
//     $ganti->stock=$obat;
//     $ganti->update();




//            }
      
   
// DB::commit();
// return 'ok';
// } catch (\Throwable $th) {
//     DB::rollback();
//     return 'gagal';
// }
        
        
//     }
   

    public function masuk(Request $request)
    {
        DB::beginTransaction();
        try {
            //code...
       
        $cab=auth()->user()->cabang_id;

        $produk=Produk::where('cabang_id',$cab)->get();
        foreach ($produk as $key => $value) {

            $obat=DetailObat::where('produk_id',$value->id)->first();
            if ($obat) {
                # code...
                $obat->stock=$value->stock;
                $obat->update();
            }else{
                $obatin=new DetailObat();
                $obatin->produk_id=$value->id;
                $obatin->lokasi_id=$value->lokasi_id;
                $obatin->ed=$value->ed;
                $obatin->batch=$value->batch?? 0;
                $obatin->stock=$value->stock;
                $obatin->cabang_id=$cab;
                $obatin->beli_id=0;
                $obatin->save();
            }


        }
        DB::commit();
        return 'sukses';
    } catch (\Throwable $th) {
        //throw $th;
        DB::rollback();
        return 'gagal';
    }
    }

    public function selisih()
    {
    $so=StokOpname::all();
    
    foreach ($so as  $value) {
        $hap=StokOpname::find($value->id);
        $selisih=$hap->stokSO -$hap->stok_awal;
        $hap->selisih=$selisih;
        $hap->update();
        
        
    }
    return 'berhasil';

    }
public function export()
{
    $cabang=Cabang::where('id',auth()->user()->cabang_id)->first();
   $cab=auth()->user()->cabang_id;
        $mon=date('m-Y');
    $data=StokOpname::with(['obat','produk'])->where('cabang_id',$cab)->get();
            // $produk=DetailObat::with(['lokasi','obat','stokOP'])->where('cabang_id',$cab)->orderBy('produk_id','ASC')->get();
    $pdf  = PDF::loadView('SO.pdf', compact('data','cabang'));
    // $pdf->setPaper('a4', 'potrait');
    
    // return $pdf->stream('data-SO-'.$cabang->nama_cabang. date('Y-m-d-his') .'.pdf');
    return view('SO.pdf',compact('data','cabang'));
}

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\sc  $sc
     * @return \Illuminate\Http\Response
     */
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\sc  $sc
     * @return \Illuminate\Http\Response
     */
    public function edit(sc $sc)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\sc  $sc
     * @return \Illuminate\Http\Response
     */
    // public function produk()
    // {
    //     return view('so.produk');
    // }
    // public function dataproduk()
    // {
    //     $cab=auth()->user()->cabang_id;

    //     $produk=Produk::where('cabang_id',$cab)->orderBy('nama_obat','ASC')->get();


    //     return datatables()
    //     ->of($produk)
    //     ->addIndexColumn()
    //     ->addColumn('lokasi',function ($produk){
    //         return $produk->lokasi->name??0;
    //     })
    //     ->addColumn('stokSO', function ($produk) {
    //         return '<input type="number" class=" stok" data-id="'. $produk->id .'" value="'. ($produk->stokop->stokSO??0).'">';
    //         // return $produk->so->stokSO??0;
    //     })
    //     ->rawColumns(['stokSO'])
    //     ->make(true);
    // }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\sc  $sc
     * @return \Illuminate\Http\Response
     */
    public function delbatch($id)
    {
       $del=DetailObat::where('id',$id)->delete();
       return back()->with('sucses');
    }
    
    public function koding(Request $request)
    {
        DB::beginTransaction();
        try {
            $penj=PenjualanDetail::where('penjualan_id','34559')->get();
            foreach ($penj as $key => $value) {
                $obat=DetailObat::where('produk_id',$value->produk_id)->first();
                $penjual=PenjualanDetail::find($value->id);
                $penjual->obat_id=$obat->id;
                $penjual->update();
                
            }
            return 'sukses';
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return 'gaal';
        }

    }
}
