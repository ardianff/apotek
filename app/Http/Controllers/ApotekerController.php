<?php

namespace App\Http\Controllers;

use App\Models\Apoteker;
use App\Models\Setting;
use Illuminate\Http\Request;
use PDF;

class ApotekerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('apoteker.index');
    }

    public function data()
    {
        $apoteker = Apoteker::orderBy('id_apoteker')->get();

        return datatables()
            ->of($apoteker)
            ->addIndexColumn()
           
            ->addColumn('no_sipa', function ($apoteker) {
                return '<span class="label label-success">'. $apoteker->no_sipa .'<span>';
            })
            ->addColumn('no_stra', function ($apoteker) {
                return '<span class="label label-success">'. $apoteker->no_stra .'<span>';
            })
            ->addColumn('aksi', function ($apoteker) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`'. route('apoteker.update', $apoteker->id_apoteker) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`'. route('apoteker.destroy', $apoteker->id_apoteker) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'select_all','no_sipa', 'no_stra'])
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

        $apoteker = new Apoteker();
        $apoteker->nama = $request->nama;
        $apoteker->no_sipa = $request->no_sipa;
        $apoteker->no_stra = $request->no_stra;
        $apoteker->mulai_tgs = $request->mulai_tgs;
        $apoteker->no_stra = $request->no_stra;
        $apoteker->jabatan = $request->jabatan;
        $apoteker->tlpn = $request->tlpn;
        $apoteker->alamat = $request->alamat;
        $apoteker->stt = $request->stt;
        $apoteker->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $apoteker = Apoteker::find($id);

        return response()->json($apoteker);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $apoteker = Apoteker::find($id)->update($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $apoteker = Apoteker::find($id);
        $apoteker->delete();

        return response(null, 204);
    }

    public function cetakapoteker(Request $request)
    {
        $dataapoteker = collect(array());
        foreach ($request->id_apoteker as $id) {
            $apoteker = Apoteker::find($id);
            $dataapoteker[] = $apoteker;
        }

        $dataapoteker = $dataapoteker->chunk(2);
        $setting    = Setting::first();

        $no  = 1;
        $pdf = PDF::loadView('apoteker.cetak', compact('dataapoteker', 'no', 'setting'));
        $pdf->setPaper(array(0, 0, 566.93, 850.39), 'potrait');
        return $pdf->stream('apoteker.pdf');
    }
}
