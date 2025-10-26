<?php

namespace App\Imports;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Produk;
use Str;

use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;


class import_produk implements ToCollection, WithHeadingRow, WithCalculatedFormulas
{

 
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        // dd($collection);
        

        foreach($collection as $key => $row){
            if(!empty($row['kode_obat'])){

                $pro=Produk::where('kode_obat',$row[4])->first();
                if (empty($pro)) {
                    # code...
                    dd($pro);
                // Produk::create([
                    
                //     'id'=>$row['id'],
                //     'cabang_id'=>$row['cabang_id'],
                //     'kategori_id'=>$row['kategori_id'],
                //     'kode_obat'=>$row['kode_obat'],
                //     'nama_obat'=>$row['nama_obat'],
                //     'racikan'=>$row['racikan'],
                //     'merk'=>$row['merk'],
                //     'satuan'=>$row['satuan'],
                //     'stock'=>$row['stock'],
                //     'isi'=>$row['isi'],
                //     'stok_minim'=>$row['stok_minim'],
                //     'diskon'=>$row['diskon'],
                //     'harga_beli'=>$row['harga_beli'],
                //     'harga_jual'=>$row['harga_jual'],
                //     'diskon'=>$row['diskon'],
                //     'lokasi_id'=>$row['lokasi_id'],
                //     'golongan_id'=>$row['golongan_id'],
                //     'jenis_id'=>$row['jenis_id'],
                //     'ed'=>$row['ed'],
                //     'batch'=>$row['batch'],
                //     'toleransi'=>$row['toleransi'],
                //     'lokasi'=>$row['lokasi'],
                //     'konsi'=>$row['konsi'],
                //     'kegunaan'=>$row['kegunaan'],
                //     'kandungan'=>$row['kandungan'],
                //     'dosis'=>$row['dosis'],
                //     'efek'=>$row['efek'],
                //     'zat'=>$row['zat'],
                //     'created_at'=>$row['created_at'],
                //     'updated_at'=>$row['updated_at'],
                   
                //     ]);
                }else{
                    // return $collection;
                }


               }


        }
    }
}
