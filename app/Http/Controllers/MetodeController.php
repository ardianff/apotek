<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Metode;

class MetodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('metode.index');
    }

    public function data()
    {
        $metode = Metode::orderBy('id', 'desc')->get();

        return datatables()
            ->of($metode)
            ->addIndexColumn()
            ->addColumn('aksi', function ($metode) {
                return '
                <div class="btn-group">
                    <button onclick="editForm(`'. route('metode.update', $metode->id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button onclick="deleteData(`'. route('metode.destroy', $metode->id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
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
        $metode = new Metode();
        $metode->cabang_id = auth()->user()->cabang_id;
        $metode->metode = $request->metode;
        $metode->jenis = $request->jenis;
        $metode->save();

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
        $metode = Metode::find($id);

        return response()->json($metode);
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
        $metode = Metode::find($id);
        $metode->metode = $request->metode;
        $metode->jenis = $request->jenis;
        $metode->update();

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
        $metode = Metode::find($id);
        $metode->delete();

        return response(null, 204);
    }
}
