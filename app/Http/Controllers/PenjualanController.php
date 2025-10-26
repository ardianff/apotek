<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use App\Models\Penjualan;
use App\Models\Pengeluaran;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\Metode;
use App\Models\Racikan;
use App\Models\Retur;
use App\Models\Racikan_detail;
use App\Models\Setting;
use App\Models\Shift;
use App\Models\User;
use App\Models\DetailObat;
use App\Models\Jasa;
use App\Models\Laporan_kasir;
use App\Models\Kartu_stock;
use Illuminate\Http\Request;
use PDF;
use DB;
use Carbon\Carbon;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->tanggal_akhir && $request->tanggal_awal !="" ) {
            $tanggalAwal = new Carbon($request->tanggal_awal);
            $tanggalAkhir =new Carbon($request->tanggal_akhir);
        }else{
            $tanggalAwal = Carbon::now()->startOfMonth();
            $tanggalAkhir = Carbon::today()->addDays(1);
        }
        
       
        // $penjualan = Penjualan::where('cabang_id',auth()->user()->cabang_id)->whereBetween('created_at',[$tanggalAwal,$tanggalAkhir])->orderBy('id', 'desc')->get();
        // return  $penjualan;
        return view('penjualan.index', compact('tanggalAwal', 'tanggalAkhir'));
    }

    public function data($awal, $akhir)
    {
        $penjualan = Penjualan::with('metod')->where('cabang_id',auth()->user()->cabang_id)->whereBetween('created_at',[$awal,$akhir])->orderBy('id', 'desc')->get();

        return datatables()
            ->of($penjualan)
            ->addIndexColumn()
            ->addColumn('total_item', function ($penjualan) {
                return format_uang($penjualan->total_item);
            })
            ->addColumn('total_harga', function ($penjualan) {
                return 'Rp. '. format_uang($penjualan->bayar);
            })
            ->addColumn('diterima', function ($penjualan) {
                return 'Rp. '. format_uang($penjualan->diterima);
            })
            ->addColumn('tanggal', function ($penjualan) {
                return tanggal_indonesia($penjualan->created_at, false);
            })
           
            ->addColumn('diskon', function ($penjualan) {
                return $penjualan->diskon . '%';
            })
            ->addColumn('metode', function ($penjualan) {
                return $penjualan->metod->metode??0 ;
            })
            ->addColumn('status', function ($penjualan) {
                $status = ( $penjualan->status == 'Lunas' ? '<span class="btn btn-success btn-xs" > '.$penjualan->status.'</span>' : ($penjualan->status == 'Belum Lunas' ? '<span class="btn btn-danger btn-xs" >'.$penjualan->status.'</span>' :'<span class="btn btn-warning btn-xs" >'.$penjualan->status.'</span>'));
                    return $status;
            })
          
            ->addColumn('aksi', function ($penjualan) {
                return '
              
            <a href="'.route('penjualan.printUlang',$penjualan->id).'"  class="btn btn-sm btn-success btn-flat" target="_blank"><i class="fa fa-print"></i></a>

            <a href="'.route('retur.penjualan',$penjualan->id).'"  class="btn btn-sm btn-warning btn-flat" target="_blank"><i class="fa fa-recycle"></i></a>
        
            <button onclick="showDetail(`'. route('penjualan.show', $penjualan->id) .'`)" class="btn btn-sm btn-info btn-flat"><i class="fa fa-eye"></i></button>
            
            <a href="'.route('penjualan.lanjutkan',$penjualan->id).'"  class="btn btn-sm btn-success btn-flat" target="_blank"><i class="fa fa-arrow-circle-right"></i></a>

            <button onclick="deleteData(`'. route('penjualan.destroy', $penjualan->id) .'`)" class="btn btn-sm btn-danger btn-flat"><i class="fa fa-trash"></i></button>
        
                ';
            })
            ->rawColumns(['aksi', 'kode_member','status','metode'])
            ->make(true);
    }

    public function create()
    {
        $kasir=Laporan_kasir::where('cabang_id',auth()->user()->cabang_id)->latest()->first();
        if ($kasir) {
            
        if ($kasir->status == 'tutup') {
           return redirect()->route('shift.buka');
        }else {
            
    //   $shift=Laporan_kasir::where('id_kasir',$kasir->id_kasir)->pluck('id_shift');
        $penjualan = new Penjualan();
        $penjualan->member_id = 0;
        $penjualan->no_nota=0;
        $penjualan->cabang_id=$kasir->cabang_id;
        $penjualan->shift_id=$kasir->shift_id;
        $penjualan->kasir_id=$kasir->id;
        $penjualan->total_item = 0;
        $penjualan->total_harga = 0;
        $penjualan->diskon = 0;
        $penjualan->jasa = 0;
        $penjualan->bayar = 0;
        $penjualan->metode_id = 0;
        $penjualan->status = 'Belum Selesai';
        // $penjualan->diterima = '';
        $penjualan->user_id = auth()->user()->id;
        $penjualan->save();
        // $id=$penjualan->id;
        session(['penjualan' => $penjualan->id]);
        return redirect()->route('transaksi.index');
    }
}else{
    return redirect()->route('shift.buka');

}

    }

    public function store1(Request $request)
    {
        $detail = PenjualanDetail::where('penjualan_id', $request->penjualan_id)->get();
        $ad=[];
        foreach ($detail as $key => $value) {
            $produk=Produk::find($value->produk_id);

           

                $obat=DetailObat::where('produk_id',$produk->id)->where('stock','>=',0)->orderBy('ed','ASC')->first();
$jml=$value->jumlah;
                $sisa=$jml-$obat->stock;

                if ($sisa >= 0) {
                    $obat=DetailObat::where('produk_id',$produk->id)->where('stock','>',0)->orderBy('ed','ASC')->first();
                    $obat->stock =0;
                    $obat->update();
                    $jml=$sisa;
                    do{
                        $obat=DetailObat::where('produk_id',$produk->id)->where('stock','>',0)->orderBy('ed','ASC')->first();
                        $sisa=$jml-$obat->stock;
                        $obat->stock-=$sisa;
                        $obat->update();
                        $jml=$sisa;
                    }while($jml>0);
                }else{
                    $obat=DetailObat::where('produk_id',$produk->id)->where('stock','>',0)->orderBy('ed','ASC')->first();
                    $stk=$sisa+$obat->stock;
                     $obat->stock -=$stk;
                    $obat->update();
                    $sisa=$stk;
                }
                
           
            $ad=$jml;
                }
        // $angka=15;
        // do{

        // } while ($angka<=0);
        // $ari=25;
        return $ad;
    }

    public function store(Request $request)
    {   $terima=str_replace('.','',$request->diterima);
        if ($terima<$request->bayar) {
           return back()->with('gagal','uang tidak boleh kurang');
        }else if( Empty($request->metode_id)){
            return back()->with('gagal','Metode Pembayaran Belum di isi');
        }
        $diterima=str_replace('.','',$request->diterima);

        
 DB::beginTransaction();
        try{

        $penjualan = Penjualan::findOrFail($request->penjualan_id);
        $penjualan->member_id = $request->member_id;
        $penjualan->no_nota='TR'. tambah_nol_didepan((int)$penjualan->id , 9);
        $penjualan->total_item = $request->total_item;
        $penjualan->total_harga = $request->total;
        $penjualan->diskon = $request->diskon;
        $penjualan->bayar = $request->bayar;
        $penjualan->metode_id = $request->metode_id;
        $penjualan->user_id = auth()->user()->id;
        $penjualan->diterima = $diterima;
        $penjualan->status='Lunas';
        $penjualan->update();

        $detail = PenjualanDetail::where('penjualan_id', $penjualan->id)->get();
        foreach ($detail as $item) {
            $item->diskon = $request->diskon;
            $item->update();
           
            $obat = DetailObat::find($item->obat_id);
            $obat->stock -= $item->jumlah;
            $obat->update();
            
         
            $ks=Kartu_stock::where('cabang_id',auth()->user()->cabang_id)->where('produk_id',$item->produk_id)->latest()->first();
            if ($ks) {
                $sisa=$ks->sisa;
            }else {
                $sisa=0;
            }    
            $stock= new Kartu_stock();
            $stock->cabang_id=auth()->user()->cabang_id;
            $stock->produk_id=$item->produk_id;
            $stock->obat_id=$obat->id;
            $stock->batch=$obat->batch;
            $stock->stock_awal=$sisa; 
            $stock->stock_out=$item->jumlah; 
            $stock->stock_in=0; 
            $stock->sisa=$sisa-=$item->jumlah;
            $stock->ket_stock='Penjualan obat oleh '.auth()->user()->name.' jumlah '.$item->jumlah;
            $stock->user_id=auth()->user()->id;
            $stock->save();
        }
        
    
DB::commit();
//         // return $prdk;
        return redirect()->route('transaksi.selesai')->with('success', 'your message,here');
    }catch (\Exception $e) {
        DB::rollback();
        return back()->with('Data gagal disimpan', 400);

    }
}



    public function show($id)
    {
        $detail = PenjualanDetail::with('prodk')->where('penjualan_id', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('kode_obat', function ($detail) {
                return '<span class="label label-success">'. $detail->prodk->kode_obat .'</span>';
            })
            ->addColumn('nama_obat', function ($detail) {
                return $detail->prodk->nama_obat;
            })
            ->addColumn('harga_jual', function ($detail) {
                return 'Rp. '. format_uang($detail->harga_jual);
            })
            ->addColumn('jumlah', function ($detail) {
                return format_uang($detail->jumlah);
            })
            // ->addColumn('satuan', function ($detail) {
            //     return $detail->satuan;
            // })
            ->addColumn('subtotal', function ($detail) {
                return 'Rp. '. format_uang($detail->subtotal);
            })
            ->rawColumns(['kode_obat'])
            ->make(true);
    }

    public function lanjutkan($id)
    {
        $penjualan=Penjualan::find($id);
        if ($penjualan->status =='Belum Selesai') {
            session(['penjualan' => $penjualan->id]);
            return redirect()->route('transaksi.index');
        }else{
            return back()->with('gagal','penjualan sudah selesai');
        }
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

    public function cancel($id)
    {
        
            $penjualan = Penjualan::find($id);
                $detail    = PenjualanDetail::where('penjualan_id', $penjualan->id)->get();
        if ($detail) {
            # code...
            foreach ($detail as $item) {                
                $item->delete();
                
            }
        }
                
                $penjualan->delete();
            
        

        return redirect()->route('penjualan.index');
    }

    public function selesai()
    {
        $setting = Setting::where('cabang_id',auth()->user()->cabang_id)->first();
        return view('penjualan.selesai', compact('setting'));
    }

    public function printUlang($id)
    {
        $setting = Setting::where('cabang_id',auth()->user()->cabang_id)->first();
        $penjualan=Penjualan::find($id);
        if (! $penjualan) {
            abort(404);
        } if ($penjualan->status=='Belum Selesai') { 
            return back();
        }
        $detail = PenjualanDetail::with('prodk')
            ->where('penjualan_id', $penjualan->id)
            ->get();
        
        return view('penjualan.nota_kecil', compact('setting', 'penjualan', 'detail'));
    }
    public function notaKecil()
    {
        $setting = Setting::where('cabang_id',auth()->user()->cabang_id)->first();
        $penjualan = Penjualan::with(['metod','member'])->find(session('penjualan'));
        if (! $penjualan) {
            abort(404);
        }
        $detail = PenjualanDetail::with('prodk')
            ->where('penjualan_id', session('penjualan'))
            ->get();
        
        // Session::forget('penjualan');
        
        return view('penjualan.nota_kecil', compact('setting', 'penjualan', 'detail'));
    }
    

    public function notaBesar()
    {
        $setting = Setting::where('cabang_id',auth()->user()->cabang_id)->first();
        $penjualan = Penjualan::find(session('penjualan'));
        if (! $penjualan) {
            abort(404);
        }
        $detail = PenjualanDetail::with('produk')
            ->where('penjualan_id', session('penjualan'))
            ->get();

        $pdf = PDF::loadView('penjualan.nota_besar', compact('setting', 'penjualan', 'detail'));
        $pdf->setPaper(0,0,609,440, 'potrait');
        return $pdf->stream('Transaksi-'. date('Y-m-d-his') .'.pdf');
    }

    public function buka_kasir(Request $request)
    {
        $kasir=new Laporan_kasir();
        $kasir->shift_id=$request->shift;
        $kasir->cabang_id=auth()->user()->cabang_id;
        $kasir->petugas=$request->petugas;
        $kasir->yg_buka =  auth()->user()->id;
        $kasir->yg_nutup = 0;
        $kasir->saldo_awal=str_replace('.','',$request->saldo_awal);
        $kasir->status='buka';
        $kasir->waktu_buka=Carbon::now();
        $kasir->save();
    session(['shift' => $kasir->status]);

        return redirect()->route('transaksi.baru');

    }

    public function get_kasir($id)
    {
        $kasir=Laporan_kasir::with(['shift','buka'])->find($id);
        $total_jual=Penjualan::where('kasir_id',$id)->where('status','Lunas')->sum('bayar');
        $waktu_tutup=Carbon::now();
        $cash=Penjualan::where('kasir_id',$id)->where('metode_id',1)->sum('bayar');
        $pengeluaran=Pengeluaran::where('kasir_id',$id)->sum('nominal');
       $struk=Penjualan::where('kasir_id',$id)->count();
       $nontunai=Penjualan::where('kasir_id',$id)->where('metode_id','!=','1')->sum('bayar');
       $total_saldo=$cash-$pengeluaran;
       $tunai=$cash;
    //    return $total_saldo;
    // // dd($total_awal);
    // $noncash=[];
    $metode=Metode::where('id','!=','1')->get();
    foreach ($metode as $key => $value) {
        $non=Penjualan::where('kasir_id',$id)->where('metode_id',$value->id)->sum('bayar');
        
        $metode[$key]->id=$non;
    }
         return view('kasir.tutup_kasir',compact('kasir','total_jual','pengeluaran','total_saldo','tunai','nontunai','struk','waktu_tutup','metode'));
    // return $metode;
    }
   
  public function buka()
  {
    $shift=Shift::all();
    // $kasir=Laporan_kasir::all()->last();
    // session(['shift' => $kasir->status]);
    return view('kasir.buka-kasir',compact('shift'));
  }
    public function tutup_kasir(Request $request)
    {
        $kasir= Laporan_kasir::findOrFail($request->id_kasir);
        $kasir->yg_nutup = $request->yg_nutup;
        $kasir->waktu_tutup = $request->waktu_tutup;
        $kasir->total_penjualan=$request->total_penjualan;
        $kasir->tunai=$request->tunai;
        $kasir->nontunai=$request->nontunai;
        $kasir->selisih=0;
        $kasir->pengeluaran=$request->pengeluaran;
        $kasir->saldo_akhir=$request->saldo_akhir;
        $kasir->catatan=$request->catatan;
        // $kasir->penerima='';
        $kasir->status='tutup';
        $kasir->update();
        return redirect()->route('tutup_kasir.print',$kasir->id)->with('tutup','tutup kasir');


    }

    public function print_tutup($id)
    {
        $setting = Setting::where('cabang_id',auth()->user()->cabang_id)->first();
       $kasir=Laporan_kasir::find($id);

        return view('penjualan.tutup_kasir', compact('setting','kasir'));
    }

    public function nota_tutup($id)
    {
        $setting = Setting::where('cabang_id',auth()->user()->cabang_id)->first();
        $kasir = Laporan_kasir::with(['shift','buka','tutup'])->find($id);
        if (! $kasir) {
            abort(404);
        }
        $penjualan=Penjualan::where('kasir_id',$id)->pluck('id');
        $metode=Metode::where('id','!=','1')->get();
    foreach ($metode as $key => $value) {
        $non=Penjualan::where('kasir_id',$id)->where('metode_id',$value->id)->sum('bayar');
        
        $metode[$key]->id=$non;
    }
       
    $detail=PenjualanDetail::whereIn('penjualan_id', $penjualan)
    ->groupBy('produk_id')
    ->select('produk_id', DB::raw('SUM(jumlah) as total_jumlah'))
    ->get();
    $penjualan=[];
    $totali=0;
    foreach ($detail as $hey => $value) {
        # code...
        $produk=Produk::where('id',$value->produk_id)->first();

        $penjualan[]=[
            "nama_obat"=>$produk->nama_obat,
            "harga_jual"=>$produk->harga_jual,
            "jumlah"=>$value->total_jumlah,
            "total_penjualan"=>$value->total_jumlah*$produk->harga_jual,
        ];
        $totali+=$value->total_jumlah*$produk->harga_jual;
    }
            $waktu=Carbon::now();
    
    
        return view('penjualan.nota_tutup', compact('setting','kasir','waktu','metode','penjualan','totali'));
    }

    public function retur($id)
    {
       
        // return redirect()->route('retur.index',$retur->id);
    }
   
   
}
