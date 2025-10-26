<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jasa;

class JasaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('jasa.index');
    }

    public function data()
    {
        $jasa = Jasa::where('cabang_id',auth()->user()->cabang_id)->orderBy('id', 'desc')->get();

        return datatables()
            ->of($jasa)
            ->addIndexColumn()
            ->addColumn('nominal', function ($jasa) {
                return 'Rp  '. format_uang($jasa->nominal);
            })
            ->addColumn('aksi', function ($jasa) {
                return '
                <div class="btn-group">
                    <button onclick="editForm(`'. route('jasa.update', $jasa->id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button onclick="deleteData(`'. route('jasa.destroy', $jasa->id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
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
        $jasa = new Jasa();
        $jasa->cabang_id = auth()->user()->cabang_id;
        $jasa->nama_jasa = $request->nama_jasa;
        $jasa->nominal = str_replace('.','',$request->nominal);
        $jasa->ket_jasa = $request->ket_jasa;
        $jasa->save();

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
        $jasa = Jasa::find($id);

        return response()->json($jasa);
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
        $jasa = Jasa::find($id);
        $jasa->nama_jasa = $request->nama_jasa;
        $jasa->nominal = str_replace('.','',$request->nominal);
        $jasa->ket_jasa = $request->ket_jasa;
        $jasa->update();

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
        $jasa = Jasa::find($id);
        $jasa->delete();

        return response(null, 204);
    }
}
