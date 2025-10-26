<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jenis;

class JenisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('jenis.index');
    }

    public function data()
    {
        $jenis = Jenis::orderBy('id', 'desc')->get();

        return datatables()
            ->of($jenis)
            ->addIndexColumn()
            ->addColumn('aksi', function ($jenis) {
                return '
                <div class="btn-group">
                    <button onclick="editForm(`'. route('jenis.update', $jenis->id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button onclick="deleteData(`'. route('jenis.destroy', $jenis->id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
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
        $jenis = new Jenis();
        $jenis->cabang_id = auth()->user()->cabang_id;
        $jenis->nama_jenis = $request->nama_jenis;
        $jenis->ket_jenis = $request->ket_jenis;
        $jenis->save();

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
        $jenis = Jenis::find($id);

        return response()->json($jenis);
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
        $jenis = Jenis::find($id);
        $jenis->nama_jenis = $request->nama_jenis;
        $jenis->ket_jenis = $request->ket_jenis;
        $jenis->update();

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
        $Jenis = Jenis::find($id);
        $Jenis->delete();

        return response(null, 204);
    }
}
