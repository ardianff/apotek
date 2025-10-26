<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Racikan;
use App\Models\Produk;
use App\Models\Racikan_detail;

class RacikanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $produk=Produk::all();
        return view('racikan.index',compact('produk'));
    }

    public function data()
    {
       $racikan = Racikan::where('cabang_id',auth()->user()->cabang_id)->get();

        return datatables()
            ->of($racikan)
            ->addIndexColumn()
        
            ->addColumn('aksi', function ($racikan) {
                return '
                <div class="btn-group">
                    <a href="'.url('racikan/detail',$racikan->id).'" class="btn btn-sm btn-success btn-flat"><i class="fa fa-list"></i></a>
                    <button onclick="editForm(`'. route('racikan.update',$racikan->id) .'`)" class="btn btn-sm btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button onclick="deleteData(`'. route('racikan.destroy',$racikan->id) .'`)" class="btn btn-sm btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
       
       $prd = Produk::orderBy('id','DESC')->first(); 
       $produk=new Produk();
       $produk->kode_obat='RCK'. tambah_nol_didepan((int)$prd->id +1, 6);
       $produk->nama_obat =$request->nama_racikan;
       $produk->cabang_id=auth()->user()->cabang_id;
       $produk->kategori_id =0;
       $produk->golongan_id =0;
       $produk->jenis_id =0;
       $produk->lokasi_id =0;
       $produk->racikan =1;
       $produk->stock =10000;
       $produk->satuan ='pcs';
       $produk->merk =0;
       $produk->margin =0;
       $produk->diskon =0;
       $produk->harga_beli =str_replace('.','',$request->harga);
       $produk->harga_jual =str_replace('.','',$request->harga);
       $produk->kandungan =$request->ket_racikan;
       $produk->save();

       $racikan = new Racikan();
       $racikan->nama_racikan =$request->nama_racikan;
       $racikan->cabang_id =auth()->user()->cabang_id;
       $racikan->kode_rck =$produk->id;
       $racikan->harga = str_replace('.','',$request->harga);
       $racikan->ket_racikan =$request->ket_racikan;
       $racikan->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $racikan = Racikan::find($id);

        return response()->json($racikan);
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
     * @param  \Illuminate\Http\Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       $racikan = Racikan::find($id);
       $racikan->nama_racikan =$request->nama_racikan;
       $racikan->harga = str_replace('.','',$request->harga);
       $racikan->ket_racikan =$request->ket_racikan;
       $racikan->update();

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
        
        $detail=Racikan_detail::where('racikan_id',$id)->get();
        foreach ($detail as $value) {
            $value->delete();
        }
        
        $racikan = Racikan::find($id);
       $racikan->delete();

        return response(null, 204);
    }

    public function detail($id)
    {
        $rck=Racikan::find($id);
        $produk=Produk::where('cabang_id',auth()->user()->cabang_id)->get();
        $racikan=Racikan_detail::with('produk')->where('racikan_id',$id)->get();

        // return $racikan;
        return view('racikan.detail',compact('racikan','rck','produk'));
    }

    public function add(Request $request)
    {
       $racikan = new Racikan_detail();
       $racikan->racikan_id =$request->racikan_id;
       $racikan->produk_id =$request->produk_id;
       $racikan->jumlah =$request->jumlah;
       $racikan->save();

       $id=$request->racikan_id;
       return redirect()->route('racikan.detail',$id);
    }

    public function delete_detail($id)
    {
        $racikan = Racikan_detail::find($id);
        $rck_id=$racikan->racikan_id;
        $racikan->delete();

        
        return response(null, 204);
 
        // return redirect()->route('racikan.detail',$rck_id);
        // return redirect()->to('racikan.detail',$rck_id);
    }
}
