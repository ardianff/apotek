<?php

namespace App\Imports;

use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Produk;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\DetailObat;
use Str;

class ImportObat implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $pembelian = Pembelian::create([
            'cabang_id' => auth()->user()->cabang_id,
            'supplier_id' => 1,
            'user_id' => auth()->user()->id,
            'no_faktur' => '2172025',
            'tgl_faktur' => date('Y-m-d'),
            'no_ref' => '2172025',
            'tempo' => date('Y-m-d'),
            'metode_id' => 1,
            'total_item' => 1,
            'total_harga' => 1,
            'diskon' => 0,
            'potongan' => 0,
            'bayar' => 0,
            'status' => 0,
            'tgl_pelunasan' => date('Y-m-d'),
            'pelunasan_oleh' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    
        $detail_obat = [];
    
        foreach ($collection as $row) {
            $cek=Produk::where('nama_obat',$row['nama_obat'])->first();
            if ($cek) {
            $produk=Produk::where('nama_obat',$row['nama_obat'])->first();
                
            
        }else{
            $produk = Produk::Create(
                [
                    'kategori_id' => 1,
                    'kode_obat' => 'PD' . tambah_nol_didepan((int) $row['id'], 7),
                    'nama_obat' => $row['nama_obat'],
                    'racikan' => 0,
                    'merk' => $row['merk'],
                    'satuan' => $row['satuan'],
                    'isi' => $row['isi'],
                    'stok_minim' => $row['stok_minim'],
                    'margin' => $row['margin'],
                    'lokasi_id' => 1,
                    'golongan_id' => 1,
                    'jenis_id' => 1,
                    'toleransi' =>0,
                    'kegunaan' =>0,
                    'kandungan' =>0,
                    'dosis' =>0,
                    'efek' =>0,
                    'zat' =>0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    
            $detail_beli_item = PembelianDetail::create([
                'pembelian_id' => $pembelian->id,
                'produk_id' => $produk->id,
                'harga_nonppn' => $row['harga_beli'],
                'harga_beli' => $row['harga_beli'],
                'hb_grosir' => $row['harga_beli'],
                'diskon' => 0,
                'jumlah' => $row['stock'],
                'isi' => $row['isi'],
                'subtotal' => $row['stock'],
                'ed' => $row['ed'],
                'batch' => $row['batch'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    
            $detail_obat[] = [
                'produk_id' => $produk->id,
                'cabang_id' => $row['cabang_id'],
                'stock' => $row['stock'],
                'beli_id' => $pembelian->id,
                'detailbeli_id' => $detail_beli_item->id, // Menggunakan ID yang sudah disimpan
                'harga_beli' => $row['harga_beli'],
                'harga_jual' => $row['harga_jual'],
                'diskon' => 0,
                'ed' => $row['ed'],
                'batch' => $row['batch'],
                'konsi' =>0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
    
        DetailObat::insert($detail_obat); // Batch insert untuk efisiensi
    }
    
}
