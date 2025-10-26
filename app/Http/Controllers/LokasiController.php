<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lokasi;

class LokasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('lokasi.index');
    }

    public function data()
    {
        $lokasi = Lokasi::where('cabang_id',auth()->user()->cabang_id)->orderBy('id', 'Asc')->get();

        return datatables()
            ->of($lokasi)
            ->addIndexColumn()
            ->addColumn('aksi', function ($lokasi) {
                return '
                <div class="btn-group">
                    <button onclick="editForm(`'. route('lokasi.update', $lokasi->id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button onclick="deleteData(`'. route('lokasi.destroy', $lokasi->id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $lokasi = new Lokasi();
        $lokasi->name = $request->nama_lokasi;
        $lokasi->cabang_id = auth()->user()->cabang_id;
        
        $lokasi->save();

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
        $lokasi = Lokasi::find($id);

        return response()->json($lokasi);
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
        $lokasi = Lokasi::find($id);
        $lokasi->name = $request->nama_lokasi;
        
        $lokasi->update();

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
        $lokasi = Lokasi::find($id);
        $lokasi->delete();

        return response(null, 204);
    }
}
