<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Diskon;

class DiskonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('diskon.index');
    }

    public function data()
    {
        $diskon = Diskon::where('cabang_id',auth()->user()->cabang_id)->orderBy('id', 'desc')->get();

        return datatables()
            ->of($diskon)
            ->addIndexColumn()
            ->addColumn('nominal', function ($diskon) {
                return $diskon->nominal.'%';
            })
            ->addColumn('status', function ($diskon) {
                $aktif = ($diskon->status == '1' ? '<span class="btn btn-success btn-sm">Aktif</span>': '<span class="btn btn-danger btn-sm">NonAktif</span>');
                return $aktif;
            })
            ->addColumn('aksi', function ($diskon) {
                return '
                <div class="btn-group">
                    <button onclick="editForm(`'. route('diskon.update', $diskon->id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button onclick="deleteData(`'. route('diskon.destroy', $diskon->id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi','status','nominal'])
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
        $diskon = new Diskon();
        $diskon->kode = $request->kode;
        $diskon->cabang_id = auth()->user()->cabang_id;
        $diskon->nominal = str_replace('.','',$request->nominal);
        $diskon->ket_diskon = $request->ket_diskon;
        $diskon->status = $request->status;
        $diskon->save();

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
        $diskon = Diskon::find($id);

        return response()->json($diskon);
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
        $diskon = Diskon::find($id);
        $diskon->kode = $request->kode;
        $diskon->nominal = str_replace('.','',$request->nominal);
        $diskon->ket_diskon = $request->ket_diskon;
        $diskon->status = $request->status;
        $diskon->update();

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
        $diskon = Diskon::find($id);
        $diskon->delete();

        return response(null, 204);
    }
}
