<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Produk;
use App\Models\DetailObat;
use App\Models\Supplier;
use App\Models\Metode;
use App\Models\Kartu_stock;
use DB;

class PembelianController extends Controller
{
    public function index()
    {
        $supplier = Supplier::orderBy('nama_supplier')->get();
                $metode = Metode::where('kategori', 'pembelian')->get();


        return view('pembelian.index', compact('metode', 'supplier'));
    }

    public function data()
    {
        $pembelian = Pembelian::with('supplier')->where('cabang_id',auth()->user()->cabang_id)->orderBy('id', 'desc')->get();

        return datatables()
            ->of($pembelian)
            ->addIndexColumn()

            ->addColumn('total_harga', function ($pembelian) {
                return 'Rp. ' . format_uang($pembelian->total_harga);
            })
            ->addColumn('bayar', function ($pembelian) {
                return 'Rp. ' . format_uang($pembelian->bayar);
            })
            ->addColumn('tanggal', function ($pembelian) {
                return tanggal_indonesia($pembelian->created_at, false);
            })
            ->addColumn('supplier', function ($pembelian) {
                return $pembelian->supplier->nama_supplier??0;
            })
            ->addColumn('diskon', function ($pembelian) {
                return $pembelian->diskon . '%';
            })
            ->addColumn('potongan', function ($pembelian) {
                return  'Rp. ' . format_uang($pembelian->potongan);
            })
            ->addColumn('status', function ($pembelian) {
                return $pembelian->status == 'Lunas' 
                    ? '<span class="btn btn-success btn-xs">Lunas</span>' 
                    : ($pembelian->status == 'Belum Lunas' 
                        ? '<span onclick="pelunasan(`'.$pembelian->id.'`)" class="btn btn-danger btn-xs">Belum Lunas</span>' 
                        : '<span class="btn btn-warning btn-xs">Belum Selesai</span>');
            })
            
            ->addColumn('aksi', function ($pembelian) {
                return '
               
                <a href="' . route('retur_pembelian.index', $pembelian->id) . '"  class="btn btn-xs btn-success btn-flat"><i class="fa fa-retweet" aria-hidden="true"></i></a>
            <a href="'.route('pembelian.lanjutkan',$pembelian->id).'"  class="btn btn-xs btn-warning btn-flat" target="_blank"><i class="fa fa-arrow-circle-right"></i></a>
                
                <button onclick="showDetail(`' . route('pembelian.show', $pembelian->id) . '`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></button>

                 <button onclick="deleteData(`' . route('pembelian.destroy', $pembelian->id) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                   
               
                ';
            })

            ->rawColumns(['aksi', 'status'])
            ->make(true);
           
    }

    public function create(Request $request)
    {
       
        $pembelian = new Pembelian();
        $pembelian->supplier_id = $request->supplier_id;
        $pembelian->cabang_id = auth()->user()->cabang_id;
        $pembelian->user_id = auth()->user()->id;
        $pembelian->total_item  = 0;
        $pembelian->total_harga = 0;
        $pembelian->diskon      = 0;
        $pembelian->potongan      = 0;
        $pembelian->no_faktur      = $request->no_faktur;
        $pembelian->no_ref      = '';
        $pembelian->tgl_pelunasan = null;
         $pembelian->tgl_faktur = date('Y-m-d', strtotime($request->tgl_faktur));
        $pembelian->tempo = date('Y-m-d', strtotime($request->tgl_tempo));
          $pembelian->metode_id = $request->metode_id;
        $pembelian->diskon_untuk = $request->diskon_untuk;
        // $pembelian->tgl_faktur      ='' ;
        $pembelian->bayar       = 0;
        $pembelian->status       = 'Belum Selesai';
        $pembelian->save();

        session(['pembelian_id' => $pembelian->id]);
        // session(['ppn' => $ppn]);
        session(['supplier_id' => $pembelian->supplier_id]);

        return redirect()->route('pembelian_detail.index');
    }

    public function store(Request $request)
    {
DB::beginTransaction();
try{

    $nom = strtotime("now");

if ($request->metode_id == 14) {
        $status = 'Lunas';
    }else {
        $status = 'Belum Lunas';
    }
    $pembelian = Pembelian::findOrFail($request->id_pembelian);
    $pembelian->total_item = $request->total_item;
        $pembelian->no_ref = $pembelian->id . $pembelian->supplier_id . $pembelian->user_id . $nom;
        $pembelian->total_harga = $request->total;
        $pembelian->diskon = $request->diskon;
        $pembelian->potongan = preg_replace('/[^\d]/', '', $request->potongan);
        $pembelian->bayar = $request->bayar;
        $pembelian->metode_id = $request->metode_id;
        $pembelian->diskon_untuk = $request->dskn_for;
        $pembelian->status = $status;
        $pembelian->no_faktur = $request->no_faktur;
        $pembelian->tgl_faktur = date('Y-m-d', strtotime($request->tgl_faktur));
        $pembelian->tempo = date('Y-m-d', strtotime($request->tempo));
        $pembelian->update();
        
        $detail = PembelianDetail::where('pembelian_id', $pembelian->id)->get();
        foreach ($detail as $item) {
            $qty = $item->jumlah * $item->isi;
            $produk = Produk::find($item->produk_id);
           

            $id_cabang=auth()->user()->cabang_id;

            $obat=new DetailObat();
            $obat->produk_id=$produk->id;
            $obat->cabang_id=$id_cabang;
            $obat->lokasi_id=$item->lokasi_id;
            $obat->satuan=$item->satuan;
            $obat->stock=$qty;
            $obat->ed=$item->ed;
            $obat->batch=$item->batch;
           if ($request->dskn_for == 'apotek') {
                // Diskon tidak berlaku → harga beli tetap
                $harga_beli = $item->harga_beli;
            } else {
                // Diskon untuk konsumen → harga beli dikurangi margin (anggap margin sebagai diskon)
                $harga_beli = $item->subtotal / $qty;
            }

            // Simpan harga beli ke obat
            $obat->harga_beli = $harga_beli;

            // Hitung harga jual berdasarkan harga beli setelah diskon (atau tidak)
            $jual = $harga_beli + ($harga_beli * $produk->margin / 100);
            $obat->harga_jual = $jual;
            $obat->beli_id=$item->pembelian_id;
            $obat->detailbeli_id=$item->id;
            $obat->diskon='0';
            $obat->konsi='0';
            $obat->save();
            
            $ks = Kartu_stock::where('cabang_id', $id_cabang)->where('produk_id', $item->produk_id)->latest()->first();
            if ($ks) {
                $sisa = $ks->sisa;
            } else {
                $sisa = 0;
            }
            $stock = new Kartu_stock();
            $stock->cabang_id = $id_cabang;
            $stock->produk_id = $item->produk_id;
            $stock->obat_id = $obat->id;
            $stock->batch = $item->batch;
            $stock->stock_awal = $sisa ?? 0;
            $stock->stock_out = 0;
            $stock->stock_in = $qty;
            $stock->sisa = $sisa += $qty;
            $stock->ket_stock = 'pembelian obat oleh ' . auth()->user()->name . ' jumlah ' . $qty;
            $stock->user_id = auth()->user()->id;
            $stock->supplier_id = $pembelian->supplier_id;
            $stock->save();
        }
DB::commit();

return redirect()->route('pembelian.index')->with('iso','Simpan data berhasil');
    }catch(\Exception $e){

        DB::rollback();

        return redirect()->route('pembelian.index')->with('ga_iso','ada yg salah');

    }

    }

    public function show($id)
    {
        $detail = PembelianDetail::with('produk')->where('pembelian_id', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('kode_obat', function ($detail) {
                return '<span class="label label-success">' . $detail->produk->kode_obat . '</span>';
            })
            ->addColumn('nama_obat', function ($detail) {
                return $detail->produk->nama_obat;
            })
            ->addColumn('harga_beli', function ($detail) {
                return 'Rp. ' . format_uang($detail->hb_grosir);
            })
            ->addColumn('jumlah', function ($detail) {
                return format_uang($detail->jumlah);
            })
            ->addColumn('diskon', function ($detail) {
                return $detail->diskon.'%';
            })
            ->addColumn('subtotal', function ($detail) {
                return 'Rp. ' . format_uang($detail->subtotal);
            })
            ->rawColumns(['kode_obat'])
            ->make(true);
    }

    public function destroy($id)
    {
        $pembelian = Pembelian::find($id);
        if ($pembelian->status=='Lunas') {
        return response(null, 204);
           
        }else{

            $detail    = PembelianDetail::where('pembelian_id', $pembelian->id)->get();
            foreach ($detail as $item) {
                $produk = DetailObat::where('detailbeli_id',$item->id)->first();
                if ($produk) {
                    $produk->stock -= $item->jumlah;
                $produk->update();
            }
            $item->delete();
        }

        $pembelian->delete();

        return response(null, 204);
                }

    }

    public function lanjutkan($id)
    {
        $pembelian=Pembelian::find($id);
        if ($pembelian->status =='Belum Selesai') {
            session(['pembelian_id' => $pembelian->id]);
            session(['supplier_id' => $pembelian->supplier_id]);
            return redirect()->route('pembelian_detail.index');
        }else{
            return back()->with('gagal','pembelian sudah selesai');
        }
    }

    public function cancel($id)
    {
        $detail = PembelianDetail::where('pembelian_id', $id)->delete();

        $pembelian = Pembelian::find($id);
        $pembelian->delete();

        return redirect()->route('pembelian.index');
    }

    public function lunas(Request $request)
    {
        DB::beginTransaction();
        try {
$tgl=date('Y-m-d',strtotime($request->tgl_pelunasan));
            $pembelian = Pembelian::find($request->id);
            $pembelian->tgl_pelunasan =$tgl;
            $pembelian->status = 'Lunas';
            $pembelian->update();
            // return $pembelian;
            DB::commit();
            return redirect()->back()->with('iso', 'Pembelian Lunas');
        }catch(\Exception $e){

            DB::rollback();

            return redirect()->back()

            ->with('ga_iso', 'Ada yang salah');

        }
    }

    public function per_supplier(Request $request){

        return view('pembelian.per_supplier');
    }
    
    public function perobat(Request $request){

        $produk=Produk::all();
        $produk_id=$request->obat;
        if ($produk_id) {
            $id=Pembelian::where('cabang_id',auth()->user()->cabang_id)->pluck('id');
            $detail=PembelianDetail::with(['pembelian','produk'])->where('produk_id',$produk_id)->whereIn('pembelian_id',$id)->orderBY('id','desc')->get();
        }else{
            $id=Pembelian::where('cabang_id',auth()->user()->cabang_id)->pluck('id');
            $detail=PembelianDetail::with(['pembelian','produk'])->whereIn('pembelian_id',$id)->orderBY('id','desc')->get();
        }
        

        return view('pembelian.perobat',compact('produk','detail','produk_id'));
    }
    
    public function tampilkanObat(Request $request){
        
        $produk=Produk::where('cabang_id',auth()->user()->cabang_id)->get();
        $detail=PembelianDetail::with('produk')->where('produk_id',$produk_id)->orderBY('id','desc')->get();
        foreach ($detail as $key => $value) {
          $pembelian=Pembelian::with('supplier')->find($value->pembelian_id);
          $detail[$key]->pembelian=$pembelian;
        }
        // return $detail;

        return view('pembelian.perobat',compact('produk','detail'));

    }


    function buat()
    {
        return view('pembelian.buat');
        
    }
}
