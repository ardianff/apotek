<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Produk;
use DB;
use Excel;
use PDF;
use file;
use Carbon\Carbon;


class ImportController extends Controller
{
    public function produk(Request $Request)
    {
        $vali = $this->validate($Request, [
            'data_obat' => 'mimes:xlsx,xls'
            
        ]);

if(!empty($vali)){




    Excel::Import(new \App\Imports\ImportObat,$Request->file('data_obat'));


return back()->with(['success'=>'Sukses Mengimport']);
}else{


 return back()->with(['warning' => 'Format File harus .xlsx/.xls']);
}


    }

    
}