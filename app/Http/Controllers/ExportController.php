<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PenjualanDetail;

use App\Models\Cabang;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Setting;
use App\Exports\ProdukExport;
use App\Exports\StockExport;
use App\Exports\PenjualanExport;
use App\Exports\PenjualanProdukExport;
use App\Exports\PembelianExport;
use App\Exports\FastmoveExport;
use App\Exports\DetailObatExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use DB;
use Carbon\Carbon;

class ExportController extends Controller
{
    public function produkexport()
    {
        $date = Carbon::now()->format('d-m-Y h:i:s');
        $apt = Setting::where('cabang_id', auth()->user()->cabang_id)->first();
        $id = auth()->user()->cabang_id;
        $name = 'data_obat_' . $apt->nama_perusahaan . '_' . $date . '.xlsx';
        return Excel::Download(new ProdukExport($id), $name);
        // return $date;
    }
    public function penjualanexport($awal,$akhir)
    {
        $tanggalAwal=$awal;
        $tanggalAkhir=$akhir;
        
      $apt = Setting::where('cabang_id', auth()->user()->cabang_id)->first();

        $name = 'data_penjualan' . $apt->nama_perusahaan . '_' . $tanggalAwal.'-'.$tanggalAkhir . '.xlsx';
        return Excel::Download(new PenjualanExport($tanggalAwal,$tanggalAkhir), $name);
    }


    public function exportpenjualanproduk($awal,$akhir)
    {
        $tanggalAwal=$awal;
        $tanggalAkhir=$akhir;
        
      $apt = Setting::where('cabang_id', auth()->user()->cabang_id)->first();

        $name = 'data_penjualan' . $apt->nama_perusahaan . '_' . $tanggalAwal.'-'.$tanggalAkhir . '.xlsx';
        return Excel::Download(new PenjualanProdukExport($tanggalAwal,$tanggalAkhir), $name);
    }

    public function pembelianexport($awal,$akhir)
    {
        $tanggalAwal=$awal;
        $tanggalAkhir=$akhir;
       
      $apt = Setting::where('cabang_id', auth()->user()->cabang_id)->first();

        $name = 'data_pembelian' . $apt->nama_perusahaan . '_' . $tanggalAwal.'-'.$tanggalAkhir . '.xlsx';
        return Excel::Download(new PembelianExport($tanggalAwal,$tanggalAkhir), $name);
    }

    public function stockexport($id)
    {
      if ($id==0) {
        $cabang="Semua Cabang";
    }else{

        $cabang=Cabang::find($id);
    }
        $name = 'data_stock' . $cabang->nama_cabang . '.xlsx';
        return Excel::Download(new StockExport($id), $name);
    }

    public function fastmoveexport($awal,$akhir,$limit)
    {
        $tanggalAwal=$awal;
        $tanggalAkhir=$akhir;
        $limit=$limit;
        
      $apt = Setting::where('cabang_id', auth()->user()->cabang_id)->first();

        $name = 'obat_fastmove' . $apt->nama_perusahaan . '_' . $tanggalAwal.'-'.$tanggalAkhir . '.xlsx';
        return Excel::Download(new FastmoveExport($tanggalAwal,$tanggalAkhir,$limit), $name);
    }

    public function detailObat()
    {
      
      $apt = Setting::where('cabang_id', auth()->user()->cabang_id)->first();

        $name = 'data_obat' . $apt->nama_perusahaan . '.xlsx';
        return Excel::Download(new DetailObatExport, $name);
    }

   
}
