<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\DetailObat;
use App\Models\Golongan;
use App\Models\Jenis;
use App\Models\Lokasi;
use App\Models\Supplier;
use App\Models\Cabang;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kartu_stock;
use App\Exports\ProdukExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use DB;
use Carbon\Carbon;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kategori = Kategori::all();
        $golongan = Golongan::all();
        $lokasi = Lokasi::where('cabang_id', auth()->user()->cabang_id)->get();
        $jenis = Jenis::all();
        $supli = Supplier::all();
        // collection(Profile::all());

        return view('produk.index', compact('kategori', 'golongan', 'jenis', 'supli', 'lokasi'));
    }

    public function data()
    {
       // Ambil total stok per produk hanya dari cabang user
$stokData = DetailObat::where('cabang_id', auth()->user()->cabang_id)
    ->selectRaw('produk_id, SUM(stock) as total_stok')
    ->groupBy('produk_id')
    ->pluck('total_stok', 'produk_id'); // FIX: tambahkan 'produk_id'

// Ambil semua produk di cabang user
$produk = Produk::with(['lokasi', 'kategori', 'obat', 'cabang'])
    ->where('cabang_id', auth()->user()->cabang_id)
    ->orderBy('nama_obat', 'ASC')
    ->get();

// Tambahkan stock ke setiap produk
foreach ($produk as $barang) {
    $barang->stock_produk = $stokData[$barang->id] ?? 0;
}

        // $produk=Produk::all();
        return datatables()
            ->of($produk)
            ->addIndexColumn()
            ->addColumn('select_all', function ($produk) {
                return '
                    <input type="checkbox" name="id[]" value="' . $produk->id . '">
                ';
            })
            ->addColumn('kode_obat', function ($produk) {
                return '<span class="label label-success">' . $produk->kode_obat . '</span>';
            })
       
            ->addColumn('kategori', function ($produk) {
                return $produk->kategori->name ?? 0;
            })
            ->addColumn('stock_produk', function ($produk) {
                return $produk->stock_produk ;
            })
         

            ->addColumn('aksi', function ($produk) {
                

                  $update='  <button type="button" data-toggle="tooltip" data-placement="top" title="ubah data" onclick="editForm(`' . route('produk.update', $produk->id) . '`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>';

                  $delete=' <button data-toggle="tooltip" data-placement="top" title="hapus data" type="button" onclick="deleteData(`' . route('produk.destroy', $produk->id) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>';

                  $stock=' <a data-toggle="tooltip" data-placement="top" title="kartu stock" type="button" href="' . route('produk.kartu_stock', $produk->id) . '" class="btn btn-xs btn-success btn-flat"><i class="fa fa-book"></i></a>';

                  $detail=' <a data-toggle="tooltip" data-placement="top" title="Detail Obat" type="button" href="' . route('detailObat.detail', $produk->id) . '" class="btn btn-xs btn-primary btn-flat"><i class="fa fa-list"></i></a>';
            if (auth()->user()->level==100) {
                return  $update. ' ' . $detail . ' ' . $stock .' '. $delete;
            }else{

                return  $update. ' ' . $detail . ' ' . $stock;
            }

            })
            ->rawColumns(['aksi', 'kode_obat', 'select_all', 'lokasi', 'ed','stock_produk', 'kategori'])
            ->make(true);
    }

    /**   <a data-toggle="tooltip" data-placement="top" title="list obat" type="button" href="'.route('detailObat.detail', $produk->id).'" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-list"></i></a>
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function kartu_stock($id)
    {
        $produk = Produk::find($id);
        $id = $produk->id;
        return view('produk.kartu_stock', compact('id', 'produk'));
    }

    public function data_kartu_stock($id)
    {
        $kartu = Kartu_stock::with('user')->where('cabang_id', auth()->user()->cabang_id)->where('produk_id', $id)->get();

        return datatables()
            ->of($kartu)
            ->addIndexColumn()
            ->addColumn('user_id', function ($kartu) {
                return $kartu->user->name ?? 0;
            })
            ->addColumn('created_at', function ($kartu) {
                return $kartu->created_at ?? 0;
            })
            ->rawColumns(['user_id', 'created_at'])
            ->make(true);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $prd = Produk::latest()->first();
        $kode = 'PD'. tambah_nol_didepan((int)$prd->id , 7);
        DB::beginTransaction();
        try {
            $produk = new Produk();
            $produk->kategori_id = $request->kategori_id;
            $produk->kode_obat = $kode;
            $produk->cabang_id = $request->cabang_id;
            $produk->nama_obat = $request->nama_obat;
            $produk->satuan = $request->satuan;
            $produk->isi = $request->isi??1;
            $produk->stok_minim = $request->stock_minim??1;
            $produk->margin = $request->margin;
            $produk->golongan_id = $request->golongan_id;
            $produk->jenis_id = $request->jenis_id;
            $produk->merk = $request->merk;
            $produk->kandungan = $request->kandungan;
            $produk->kegunaan = $request->kegunaan;
            $produk->dosis = $request->dosis;
            $produk->efek = $request->efek;
            $produk->zat = $request->zat;
            $produk->save();
    DB::commit();
            return response()->json('Data berhasil disimpan', 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json('Data gagal disimpan', 400);

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $produk = Produk::with(['jenis', 'golongan', 'kategori',])->find($id);

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
       

        $produk = Produk::find($id);
        $produk->kategori_id = $request->kategori_id;
            $produk->nama_obat = $request->nama_obat;
            $produk->satuan = $request->satuan;
            $produk->isi = $request->isi??1;
            $produk->stok_minim = $request->stock_minim??1;
            $produk->margin = $request->margin;
            $produk->golongan_id = $request->golongan_id;
            $produk->jenis_id = $request->jenis_id;
            $produk->merk = $request->merk;
            $produk->kandungan = $request->kandungan;
            $produk->kegunaan = $request->kegunaan;
            $produk->dosis = $request->dosis;
            $produk->efek = $request->efek;
            $produk->zat = $request->zat;

        $produk->update();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $produk = Produk::find($id);
        // $cek = DetailObat::where('produk_id', $produk->id)->count();
        // if ($cek > 0) {
        //     return response()->json('Produk tidak bisa dihapus, karena sudah ada di detail obat', 400);
        // }else {
        //     # code...
        // }
       
        $produk->delete();

        return response(null, 204);
    }

    public function deleteSelected(Request $request)
    {
        foreach ($request->id_produk as $id) {
            $produk = Produk::find($id);
            $produk->delete();
        }

        return response(null, 204);
    }

    public function cetakBarcode(Request $request)
    {
        $dataproduk = array();
        foreach ($request->id as $id) {
            $produk = Produk::find($id);
            $dataproduk[] = $produk;
        }

        $no  = 1;
        $pdf = PDF::loadView('produk.barcode', compact('dataproduk', 'no'));
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream('produk.pdf');
    }
    public function exportExcel()
    {
        $date = Carbon::now()->format('d-m-Y h:i:s');
        // $apt=Setting::where('cabang_id',auth()->user()->cabang_id)->first();
        // $id=auth()->user()->cabang_id;
        // return Excel::Download(new ProdukExport($id),'data_obat.xlsx');
        return $date;
    }

    function pindah()  {

        $produk=Produk::all();

        foreach ($produk as $key => $value) {

            $obat=new DetailObat();
            $obat->produk_id=$value->id;
            $obat->cabang_id=$value->cabang_id;
            $obat->lokasi_id=$value->lokasi_id;
            $obat->stock=$value->stock;
            $obat->ed=$value->ed;
            $obat->batch=$value->batch;
            $obat->beli_id=0;
            $obat->save();
        }
        return 'sukses input semuanya';
    }


    function clean()
    {
        
        $produk=Produk::with(['obat','cabang'])->get();
        $cabangs = Cabang::all();
$data = [];

foreach ($produk as $barang) {
    $stok = DetailObat::where('produk_id',$barang->id)->sum('stock');
    $row = [
        'kode_obat' => $barang->kode_obat,
        'nama_obat' => $barang->nama_obat,
        'jumlah_stock' => $stok? $stok : 0,
    ];

    
    $data[] = $row;
}

       return $data;
    }
}
