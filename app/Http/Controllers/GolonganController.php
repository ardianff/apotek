<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Golongan;

class GolonganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('golongan.index');
    }

    public function data()
    {
        $golongan = Golongan::orderBy('id', 'desc')->get();

        return datatables()
            ->of($golongan)
            ->addIndexColumn()
            ->addColumn('aksi', function ($golongan) {
                return '
                <div class="btn-group">
                    <button onclick="editForm(`'. route('golongan.update', $golongan->id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button onclick="deleteData(`'. route('golongan.destroy', $golongan->id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
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
        $golongan = new Golongan();
        $golongan->cabang_id = auth()->user()->cabang_id;
        $golongan->nama_gol = $request->nama_gol;
        $golongan->ket_gol = $request->ket_gol;
        $golongan->save();

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
        $golongan = Golongan::find($id);

        return response()->json($golongan);
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
        $golongan = Golongan::find($id);
        $golongan->nama_gol = $request->nama_gol;
        $golongan->ket_gol = $request->ket_gol;
        $golongan->update();

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
        $golongan = Golongan::find($id);
        $golongan->delete();

        return response(null, 204);
    }
}
