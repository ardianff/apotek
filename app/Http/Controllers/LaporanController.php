<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Pengeluaran;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\Cabang;
use App\Models\PenjualanDetail;
use App\Models\Laporan_kasir;
use Illuminate\Http\Request;
use PDF;
use DB;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tanggalAwal = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $tanggalAkhir = date('Y-m-d');

        if ($request->has('tanggal_awal') && $request->tanggal_awal != "" && $request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
        }

        return view('laporan.index', compact('tanggalAwal', 'tanggalAkhir'));
    }

    

    public function getData($awal, $akhir)
    {
        $no = 1;
        $cabang=auth()->user()->cabang_id;
        $data = array();
        $pendapatan = 0;
        $penjualan_total = 0;
        $pembelian_total = 0;
        $pengeluaran_total = 0;

        while (strtotime($awal) <= strtotime($akhir)) {
            $tanggal = $awal;
            $awal = date('Y-m-d', strtotime("+1 day", strtotime($awal)));

            // $total_penjualan = Penjualan::where('cabang_id',$cabang)->where('created_at', 'LIKE', "%$tanggal%")->sum('bayar');
            $total_penjualan = Laporan_kasir::where('cabang_id',$cabang)->where('created_at', 'LIKE', "%$tanggal%")->sum('total_penjualan');
            $total_pembelian = Pembelian::where('cabang_id',$cabang)->where('created_at', 'LIKE', "%$tanggal%")->sum('bayar');
            $total_pengeluaran = Pengeluaran::where('cabang_id',$cabang)->where('created_at', 'LIKE', "%$tanggal%")->sum('nominal');

            // $pendapatan = $total_penjualan - $total_pembelian - $total_pengeluaran;
            $penjualan_total += $total_penjualan;
            $pembelian_total += $total_pembelian;
            $pengeluaran_total += $total_pengeluaran;

            $row = array();
            $row['DT_RowIndex'] = $no++;
            $row['tanggal'] = tanggal_indonesia($tanggal, false);
            $row['penjualan'] = format_uang($total_penjualan);
            $row['pembelian'] = format_uang($total_pembelian);
            $row['pengeluaran'] = format_uang($total_pengeluaran);
            // $row['pendapatan'] = format_uang($pendapatan);

            $data[] = $row;
        }

        $data[] = [
            'DT_RowIndex' => '',
            'tanggal' => 'Total Keseluruhan',
            'penjualan' =>  'Rp '.format_uang($penjualan_total),
            'pembelian' => 'Rp '.format_uang($pembelian_total),
            'pengeluaran' => 'Rp '.format_uang($pengeluaran_total),
            // 'pendapatan' => format_uang($total_pendapatan),
        ];

        return $data;
    }

    public function data($awal, $akhir)
    {
        $data = $this->getData($awal, $akhir);

        return datatables()
            ->of($data)
            ->make(true);
    }

    public function exportPDF($awal, $akhir)
    {
        $data = $this->getData($awal, $akhir);
        $pdf  = PDF::loadView('laporan.pdf', compact('awal', 'akhir', 'data'));
        $pdf->setPaper('a4', 'potrait');
        
        return $pdf->stream('Laporan-pendapatan-'. date('Y-m-d-his') .'.pdf');
    }

    public function piutang()
    {
        $tanggalAwal = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $tanggalAkhir = date('Y-m-d');

        if ($request->has('tanggal_awal') && $request->tanggal_awal != "" && $request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
        }

        return view('piutang.index', compact('tanggalAwal', 'tanggalAkhir'));
    }

    public function penjualan_pershift()
    {
        // $kasir=Laporan_kasir::with(['shift'])->where('cabang_id',auth()->user()->cabang_id)->get();
// return $kasir;
        return view('laporan_penjualan.pershift');
    }

    public function data_pershift()
    {
        $kasir=Laporan_kasir::with(['shift'])->where('cabang_id',auth()->user()->cabang_id)->orderBY('id','desc')->get();

        return datatables()
        ->of($kasir)
        ->addIndexColumn()
        ->addColumn('shift', function ($kasir) {
            return $kasir->shift->nama_shift??0;
        })
        ->addColumn('total_penjualan', function ($kasir) {
            return 'Rp. '. format_uang($kasir->total_penjualan)??0;
        })
        ->addColumn('tunai', function ($kasir) {
            return 'Rp. '. format_uang($kasir->tunai)??0;
        })
        ->addColumn('nontunai', function ($kasir) {
            return 'Rp. '. format_uang($kasir->nontunai)??0;
        })
        ->addColumn('pengeluaran', function ($kasir) {
            return 'Rp. '. format_uang($kasir->pengeluaran)??0;
        })
        ->addColumn('saldo_akhir', function ($kasir) {
            return 'Rp. '. format_uang($kasir->saldo_akhir)??0;
        })
        ->addColumn('tanggal', function ($kasir) {
            return tanggal_indonesia($kasir->created_at, false)??0;
        })
       
      
        ->addColumn('aksi', function ($kasir) {
            return '
            <div class="btn-group">
          
                <a href="'.route('shift.get_kasir',$kasir->id).'" class="btn btn-xs btn-success btn-flat"><i class="fa fa-print"></i></a>
                <button onclick="showDetail(`'. route('pershift.show', $kasir->id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></button>
                <button onclick="deleteData(`'. route('pershift.destroy', $kasir->id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
            </div>
            ';
        })
        ->rawColumns(['aksi', 'shift','tanggal'])
        ->make(true);
    }

    public function detailPenjualan(Request $request)
    {
        if ($request->tanggal_akhir && $request->tanggal_awal !="" ) {
            $tanggalAwal = new Carbon($request->tanggal_awal);
            $tanggalAkhir =new Carbon($request->tanggal_akhir);
        }else{
            $tanggalAwal = Carbon::yesterday();
            $tanggalAkhir = Carbon::today()->addDays(1);
        }
       
        $penj =Penjualan::with(['details','shift','user','metod'])->where('cabang_id',auth()->user()->cabang_id)->where('status','Lunas')->whereBetween('created_at', [$tanggalAwal,$tanggalAkhir])->orderBy('id','DESC')->get();

        $totali =Penjualan::where('cabang_id',auth()->user()->cabang_id)->where('status','Lunas')->whereBetween('created_at', [$tanggalAwal,$tanggalAkhir])->sum('bayar');
      $detail=[];
      foreach ($penj as $key => $value) {
          $detil=PenjualanDetail::with('prodk')->where('penjualan_id',$value->id)->get();
          $detail[]=$detil;
            
      }
      
        // return $detail;
    
    return view('laporan_penjualan.details',compact('penj','detail','totali','tanggalAwal','tanggalAkhir'));
    }

    public function detailPembelian(Request $request)
    {
        if ($request->tanggal_akhir && $request->tanggal_awal !="" ) {
            $tanggalAwal = new Carbon($request->tanggal_awal);
            $tanggalAkhir =new Carbon($request->tanggal_akhir);
        }else{
            $tanggalAwal = Carbon::yesterday();
            $tanggalAkhir = Carbon::today()->addDays(1);
        }
       
        $pemb =Pembelian::with(['pembelian_detail','supplier','user'])->where('cabang_id',auth()->user()->cabang_id)->whereBetween('created_at', [$tanggalAwal,$tanggalAkhir])->orderBy('tempo','DESC')->get();
        $totali =Pembelian::where('cabang_id',auth()->user()->cabang_id)->whereBetween('created_at', [$tanggalAwal,$tanggalAkhir])->sum('bayar');
        $detail=[];
        foreach ($pemb as $key => $value) {
            $detil=PembelianDetail::with('produk')->where('pembelian_id',$value->id)->get();
            $detail[]=$detil;
        }

        // return $tanggalAwal;
    return view('laporan_pembelian.details',compact('pemb','detail','totali','tanggalAwal','tanggalAkhir'));
    }


    public function stock(Request $request) 
    {
        if (empty($request->cabang)) {
           $id=0;
        //    $produk=Produk::with('cabang')->get();

        }else{
            $id=$request->cabang;
            // $produk=Produk::with('cabang')->where('cabang_id',$id)->get();

        }
        $cabang=Cabang::all();
        // return $produk;
        return view('laporan.stock',compact('id','cabang'));
    }
    
    
    public function datastock($id) 
    {
        if ($id==0) {
            $produk=Produk::with('cabang')->get();
        }else{

            $produk=Produk::with('cabang')->where('cabang_id',$id)->get();
        }
        return datatables()
            ->of($produk)
            ->addIndexColumn()
            ->addColumn('kode_obat', function ($produk) {
                return '<span class="label label-success">' . $produk->kode_obat . '</span>';
            })
            ->addColumn('nilai', function ($produk) {
                return "Rp ". format_uang($produk->harga_beli * $produk->stock);
            })
            ->addColumn('harga_beli', function ($produk) {
                return "Rp ". format_uang($produk->harga_beli );
            })
            ->addColumn('cabang', function ($produk) {
                return $produk->cabang->nama_cabang??0;
            })
            ->rawColumns(['kode_obat','nilai','cabang'])
            ->make(true);
    }


    public function fasmove(Request $request)
    {
        if ($request->tanggal_akhir && $request->tanggal_awal !="" ) {
            $tanggalAwal = new Carbon($request->tanggal_awal);
            $tanggalAkhir =new Carbon($request->tanggal_akhir);
            $limit=$request->limit;
        }else{
            $tanggalAwal = Carbon::yesterday();
            $tanggalAkhir = Carbon::today()->addDays(1);
            $limit=20;
        }

        $penjualan=Penjualan::where('cabang_id',auth()->user()->cabang_id)->whereBetween('created_at', [$tanggalAwal,$tanggalAkhir])->pluck('id');

        $detil=PenjualanDetail::with('prodk')->whereIn('penjualan_id',$penjualan)
        ->selectRaw("produk_id")
        ->selectRaw('SUM(jumlah) as total')
        ->orderBy('total','DESC')
        ->groupBy('produk_id')
        ->take($limit)
        ->get();

// return $detil->total;
        return view('laporan.fastmove',compact('detil','tanggalAwal','tanggalAkhir','limit'));
        
    }

    
    function penjualanPerobat(Request $request) {
        if ($request->tanggal_akhir && $request->tanggal_awal !="" ) {
            $tanggalAwal = new Carbon($request->tanggal_awal);
            $tanggalAkhir =new Carbon($request->tanggal_akhir);
        }else{
            $tanggalAwal = Carbon::yesterday();
            $tanggalAkhir = Carbon::today()->addDays(1);
        }
         
        $penj =Penjualan::where('cabang_id',auth()->user()->cabang_id)->where('status','Lunas')->whereBetween('created_at', [$tanggalAwal,$tanggalAkhir])->orderBy('id','DESC')->pluck('id');

        // $detail=PenjualanDetail::whereIn('penjualan_id', $penj)
        // ->groupBy('produk_id')
        // ->select('produk_id', DB::raw('SUM(jumlah) as total_jumlah'))
        // ->get();
        // return $detail;

        $totali=PenjualanDetail::whereIn('penjualan_id', $penj)->sum('subtotal');

        return view('laporan_penjualan.perobat',compact('tanggalAwal','tanggalAkhir','totali'));

    }

    function dataPenjualanPerobat($tanggalAwal,$tanggalAkhir) {
          
        
        $penj =Penjualan::where('cabang_id',auth()->user()->cabang_id)->where('status','Lunas')->whereBetween('created_at', [$tanggalAwal,$tanggalAkhir])->orderBy('id','DESC')->pluck('id');

        $detail=PenjualanDetail::whereIn('penjualan_id', $penj)
        ->groupBy('produk_id')
        ->select('produk_id', DB::raw('SUM(jumlah) as total_jumlah'))
        ->get();
        $penjualan=[];
        foreach ($detail as $key => $value) {
            # code...
            $produk=Produk::where('id',$value->produk_id)->first();
            if (!empty($produk)) {
                # code...
                $harga_bel=$produk->harga_jual-($produk->harga_jual*$produk->margin/100);
                $penjualan[]=[
                    "kode_obat"=>$produk->kode_obat,
                    "nama_obat"=>$produk->nama_obat,
                    "satuan"=>$produk->satuan,
                    "isi"=>$produk->isi,
                    "harga_beli"=>$harga_bel,
                    "hb_grosir"=>$produk->hb_grosir,
                    "margin"=>$produk->margin,
                    "harga_jual"=>$produk->harga_jual,
                    "hj_grosir"=>$produk->hj_grosir,
                    "jumlah"=>$value->total_jumlah,
                    "total_penjualan"=>$value->total_jumlah*$produk->harga_jual,
                    "total_modal"=>$value->total_jumlah*$harga_bel,
                   "total_laba"=>($value->total_jumlah*$produk->harga_jual)-($value->total_jumlah*$harga_bel),
                ];
            }
            }

    // $produk=Produk::all();
    // foreach ($produk as $key => $value) {
        
    // }
        return datatables()
        ->of($penjualan)
        ->addIndexColumn()
        // ->addColumn('select_all', function ($produk) {
        //     return '
        //         <input type="checkbox" name="id[]" value="' . $produk->id . '">
        //     ';
        // })
        ->addColumn('kode_obat', function ($penjualan) {
            return '<span class="label label-success">' . $penjualan['kode_obat'] . '</span>';
        })
        ->addColumn('nama_obat', function ($penjualan) {
            return $penjualan['nama_obat'] ?? 0;
        })
        ->addColumn('satuan', function ($penjualan) {
            return $penjualan['satuan'] ?? 0;
        })
        ->addColumn('isi', function ($penjualan) {
            return $penjualan['isi'] ?? 0;
        })
        ->addColumn('harga_beli', function ($penjualan) {
            return format_uang($penjualan['harga_beli']) ?? 0;
        })
  
        ->addColumn('margin', function ($penjualan) {
            return $penjualan['margin'] ?? 0;
        })
        ->addColumn('harga_jual', function ($penjualan) {
            return format_uang($penjualan['harga_jual']) ?? 0;
        })
    
        ->addColumn('jumlah', function ($penjualan) {
            return $penjualan['jumlah'] ?? 0;
        })
        ->addColumn('total_penjualan', function ($penjualan) {
            return 'Rp '.format_uang($penjualan['total_penjualan']) ?? 0;
        })
        ->addColumn('total_modal', function ($penjualan) {
            return 'Rp '.format_uang($penjualan['total_modal']) ?? 0;
        })
        ->addColumn('total_laba', function ($penjualan) {
            return 'Rp '.format_uang($penjualan['total_laba']) ;
        })
        
        ->rawColumns(['kode_obat', 'nama_obat', 'satuan', 'isi', 'harga_beli','margin','harga_jual','jumlah','total_penjualan','total_modal','total_laba'])
        ->make(true);
    }

}
