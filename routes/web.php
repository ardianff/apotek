<?php

use App\Http\Controllers\{
        DashboardController,
        ApotekerController,
        KategoriController,
        GolonganController,
        JenisController,
        JasaController,
        RacikanController,
        DiskonController,
        LokasiController,
        LaporanController,
        ProdukController,
        MetodeController,
        DetailobatController,
        MemberController,
        PengeluaranController,
        PembelianController,
        PembelianDetailController,
        PenjualanController,
        PenjualanDetailController,
        SettingController,
        KomenController,
        SupplierController,
        UserController,
        ImportController,
        ReturController,
        ReturDetailController,
        ReturPembelianController,
        ReturPembelianDetailController,
        Kartu_stockController,
        TransferController,
        Transfer_detailController,
        ExportController,
        SOController,
        ManajemenController,
};
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
        return redirect()->route('login');
});


Route::group(['middleware' => 'auth'], function () {
        Route::get('/komen', [KomenController::class, 'index'])->name('komen.index');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/stokout/data', [DashboardController::class, 'stok'])->name('stokout.data');
        Route::get('/ed/data', [DashboardController::class, 'ed'])->name('ed.data');

        Route::group(['middleware' => 'level:3'], function () {
                Route::get('manajemen/dashboard', [ManajemenController::class, 'index'])->name('manajemen.dashboard');

        });

        Route::group(['middleware' => 'level:1'], function () {

            Route::get('/produk/pindah', [ProdukController::class, 'pindah'])->name('produk.pindah');
                // produk
                Route::get('/produk/data', [ProdukController::class, 'data'])->name('produk.data');
                Route::resource('/produk', ProdukController::class);
                Route::post('/produk/delete-selected', [ProdukController::class, 'deleteSelected'])->name('produk.delete_selected');
                Route::post('/produk/cetak-barcode', [ProdukController::class, 'cetakBarcode'])->name('produk.cetak_barcode');

                Route::get('/export/produk', [ExportController::class, 'produkexport'])->name('export.produk');
                Route::get('/export/penjualan/{awal}/{akhir}', [ExportController::class, 'penjualanexport'])->name('export.penjualan');
                Route::get('/export/penjualanproduk/{awal}/{akhir}', [ExportController::class, 'exportpenjualanproduk'])->name('export.penjualanproduk');
                Route::get('/export/pembelian/{awal}/{akhir}', [ExportController::class, 'pembelianexport'])->name('export.pembelian');
                Route::get('/export/stock/{id}', [ExportController::class, 'stockexport'])->name('export.stock');
                Route::get('/export/fastmove/{awal}/{akhir}/{limit}', [ExportController::class, 'fastmoveexport'])->name('export.fastmove');

                Route::get('/export/detailObat', [ExportController::class, 'detailObat'])->name('export.detailObat');

                Route::post('/import/produk', [ImportController::class, 'produk'])->name('import.produk');

                Route::get('/kategori/data', [KategoriController::class, 'data'])->name('kategori.data');
                Route::resource('/kategori', KategoriController::class);

                Route::get('/apoteker/data', [ApotekerController::class, 'data'])->name('apoteker.data');
                Route::resource('/apoteker', ApotekerController::class);

                Route::get('/golongan/data', [GolonganController::class, 'data'])->name('golongan.data');
                Route::resource('/golongan', GolonganController::class);

                Route::get('/jenis/data', [JenisController::class, 'data'])->name('jenis.data');
                Route::resource('/jenis', JenisController::class);

                Route::get('/racikan/data', [RacikanController::class, 'data'])->name('racikan.data');
                Route::get('/racikan/detail/{id}', [RacikanController::class, 'detail'])->name('racikan.detail');
                Route::post('/racikan/add', [RacikanController::class, 'add'])->name('racikan.add');
                Route::delete('/racikan/delete_detail/{id}', [RacikanController::class, 'delete_detail'])->name('racikan.delete_detail');
                Route::resource('/racikan', RacikanController::class);

                Route::get('/jasa/data', [JasaController::class, 'data'])->name('jasa.data');
                Route::resource('/jasa', JasaController::class);
                Route::get('/diskon/data', [DiskonController::class, 'data'])->name('diskon.data');
                Route::resource('/diskon', DiskonController::class);

                Route::get('/lokasi/data', [LokasiController::class, 'data'])->name('lokasi.data');
                Route::resource('/lokasi', LokasiController::class);



                // detail obat
                Route::get('/detailObat/data', [DetailobatController::class, 'data'])->name('detailObat.data');
                Route::get('/cekharga', [DetailobatController::class, 'cekharga'])->name('detailObat.cekharga');
                
                Route::post('/detailObat/stock/{id}', [DetailobatController::class, 'stock'])->name('detailObat.stock');
                Route::get('/detailObat/detail/{id}', [DetailobatController::class, 'detail'])->name('detailObat.detail');
                Route::get('/detailObat/transfer', [DetailobatController::class, 'transfer'])->name('detailObat.transfer');
                
                
                Route::post('/detailObat/delete-selected', [DetailobatController::class, 'deleteSelected'])->name('detailObat.delete_selected');
                Route::post('/detailObat/cetak-barcode', [DetailobatController::class, 'cetakBarcode'])->name('detailObat.cetak_barcode');
                Route::resource('/detailObat', DetailobatController::class)->except('create');

                Route::get('/member/data', [MemberController::class, 'data'])->name('member.data');
                Route::post('/member/cetak-member', [MemberController::class, 'cetakMember'])->name('member.cetak_member');
                Route::resource('/member', MemberController::class);

                Route::get('/supplier/data', [SupplierController::class, 'data'])->name('supplier.data');
                Route::resource('/supplier', SupplierController::class);

                Route::get('/metode/data', [MetodeController::class, 'data'])->name('metode.data');
                Route::resource('/metode', MetodeController::class);

                Route::get('/pengeluaran/data', [PengeluaranController::class, 'data'])->name('pengeluaran.data');
                Route::resource('/pengeluaran', PengeluaranController::class);

                // Pembelian
                Route::get('/pembelian/data', [PembelianController::class, 'data'])->name('pembelian.data');
                Route::get('/pembelian/cancel/{id}', [PembelianController::class, 'cancel'])->name('pembelian.cancel');
                Route::get('/pembelian/lanjutkan/{id}', [PembelianController::class, 'lanjutkan'])->name('pembelian.lanjutkan');

                Route::get('/pembelian/perobat', [PembelianController::class, 'perobat'])->name('pembelian.perobat');
                Route::get('/pembelian/buat', [PembelianController::class, 'buat'])->name('pembelian.buat');
                Route::post('/pembelian/simpan', [PembelianController::class, 'simpan'])->name('pembelian.simpan');

                Route::post('/pembelian/create', [PembelianController::class, 'create'])->name('pembelian.create');

                Route::resource('/pembelian', PembelianController::class)
                        ->except('create');
                Route::post('/pembelian/lunas', [PembelianController::class, 'lunas'])->name('pembelian.lunas');


                Route::get('/pembelianObat/tampil', [PembelianController::class, 'tampilkanObat'])->name('pembelianObat.tampil');


                // PEMBELIAN DETAIL
                Route::get('/pembelian_detail/data/{id}', [PembelianDetailController::class, 'data'])->name('pembelian_detail.data');
                Route::post('/pembelian_detail/store/{id}', [PembelianDetailController::class, 'store'])->name('pembelian_detail.store');

                Route::get('/pembelian_detail/show/{id}', [PembelianDetailController::class, 'show'])->name('pembelian_detail.show');

            
                Route::post('/pembelian_detail/ubah/{id}', [PembelianDetailController::class, 'ubah'])->name('pembelian_detail.ubah');
            
                Route::resource('/pembelian_detail', PembelianDetailController::class)
                        ->except('create','store','edit','update','show');

                // RETUR PEMBELIAN
                Route::resource('/retur_pembelian', ReturPembelianController::class)->except('index','create','store');
                Route::get('/retur_pembelian/data/{id}', [ReturPembelianController::class, 'data'])->name('retur_pembelian.data');

               
                Route::get('/retur_pembelian/index/{id}', [ReturPembelianController::class, 'index'])->name('retur_pembelian.index');
               
                Route::get('/retur_pembelian/cancel/{id}', [ReturPembelianController::class, 'cancel'])->name('retur_pembelian.cancel');
                Route::get('/retur_pembelian/create/{id}', [ReturPembelianController::class, 'create'])->name('retur_pembelian.create');
                Route::get('/retur_pembelian/store/{id}', [ReturPembelianController::class, 'store'])->name('retur_pembelian.store');

              
                       


                // RETUR PENJUALAN
                // Route::resource('/retur', ReturController::class)->except('create','show','edit');
                Route::get('/retur/penjualan/{id}', [ReturController::class, 'penjualan'])->name('retur.penjualan');
                Route::post('/retur/penjualan_delete/{id}', [ReturController::class, 'penjualan_delete'])->name('retur.penjualan_delete');
                Route::post('/retur/penjualan_edit/{id}', [ReturController::class, 'penjualan_edit'])->name('retur.penjualan_edit');
                Route::get('/retur/penjualan_show/{id}', [ReturController::class, 'penjualan_show'])->name('retur.penjualan_show');
                Route::get('/retur/create/{id}/{item}', [ReturController::class, 'create'])->name('retur.create');


                Route::get('/retur/loadform/{total}/{sebelumnya}/{diterima}', [ReturController::class, 'loadForm'])->name('retur.load_form');
                Route::post('/retur/simpan', [ReturController::class, 'simpan'])->name('retur.simpan');




                //PENJUALAN
                Route::get('/penjualan/data/{awal}/{akhir}', [PenjualanController::class, 'data'])->name('penjualan.data');

                Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');

                Route::get('/penjualan/detail', [PenjualanDetailController::class, 'detail'])->name('penjualan.detail');

                Route::get('/penjualan/now', [PenjualanDetailController::class, 'penjualnow'])->name('penjualan.now');

                Route::get('/penjualan/{id}', [PenjualanController::class, 'show'])->name('penjualan.show');
                Route::get('/penjualan/lanjutkan/{id}', [PenjualanController::class, 'lanjutkan'])->name('penjualan.lanjutkan');
                Route::get('/penjualan/cancel/{id}', [PenjualanController::class, 'cancel'])->name('penjualan.cancel');
                Route::get('/penjualan/printUlang/{id}', [PenjualanController::class, 'printUlang'])->name('penjualan.printUlang');
                Route::get('/penjualan/retur/{id}', [PenjualanController::class, 'retur'])->name('penjualan.retur');

                Route::delete('/penjualan/{id}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');

                // penjualan pershift
                Route::get('/pershift', [LaporanController::class, 'penjualan_pershift'])->name('pershift.index');
                Route::get('/pershift/data', [LaporanController::class, 'data_pershift'])->name('pershift.data');
                Route::get('/pershift/show', [LaporanController::class, 'show_pershift'])->name('pershift.show');
                Route::put('/pershift/destroy', [LaporanController::class, 'destroy_pershift'])->name('pershift.destroy');

                // Laporan Penjualan Detail
                Route::get('detailPenjualan', [LaporanController::class, 'detailPenjualan'])->name('detailPenjualan.index');


                // Laporan Penjualan Per Obat
                Route::get('penjualanPerobat', [LaporanController::class, 'penjualanPerobat'])->name('penjualanPerobat');
                Route::get('penjualanPerobat/data/{tanggalAwal}/{tanggalAkhir}', [LaporanController::class, 'dataPenjualanPerobat'])->name('penjualanPerobat.data');



                // Laporan Pembelian Detail
                Route::get('detailPembelian', [LaporanController::class, 'detailPembelian'])->name('detailPembelian.index');

                Route::get('laporan/stock', [LaporanController::class, 'stock'])->name('laporan.stock');
                Route::get('laporan/datastock/{id}', [LaporanController::class, 'datastock'])->name('laporan.datastock');


                // transfer
                Route::get('/transfer/data', [TransferController::class, 'data'])->name('transfer.data');

                Route::post('/transfer/store', [TransferController::class, 'store'])->name('transfer.store');

                Route::get('/transfer/delete', [TransferController::class, 'delete'])->name('transfer.delete');

                Route::get('/transfer/create/{id}', [TransferController::class, 'create'])->name('transfer.create');
                Route::get('/transfer/show/{id}', [TransferController::class, 'show'])->name('transfer.show');
                Route::get('/transfer/index', [TransferController::class, 'index'])->name('transfer.index');

                // transfer detail
                Route::get('/transfer_detail/index', [Transfer_detailController::class, 'index'])->name('transfer_detail.index');

                Route::get('/transfer_detail/data/{id}', [Transfer_detailController::class, 'data'])->name('transfer_detail.data');

                Route::put('/transfer_detail/update/{id}', [Transfer_detailController::class, 'update'])->name('transfer_detail.update');

                Route::post('/transfer_detail/store', [Transfer_detailController::class, 'store'])->name('transfer_detail.store');

                Route::delete('/transfer_detail/destroy', [Transfer_detailController::class, 'destroy'])->name('transfer_detail.destroy');

                // kartu stock
                Route::get('/kartu_stock/index', [Kartu_stockController::class, 'index'])->name('kartu_stock.index');
        });

        Route::get('/produk/kartu_stock/{id}', [ProdukController::class, 'kartu_stock'])->name('produk.kartu_stock');
        Route::get('/produk/data_kartu_stock/{id}', [ProdukController::class, 'data_kartu_stock'])->name('produk.data_kartu_stock');

        Route::group(['middleware' => 'level:1,2'], function () {
                Route::get('/transaksi/baru', [PenjualanController::class, 'create'])->name('transaksi.baru');
                Route::post('/transaksi/simpan', [PenjualanController::class, 'store'])->name('transaksi.simpan');
                Route::get('/transaksi/selesai', [PenjualanController::class, 'selesai'])->name('transaksi.selesai');
                Route::get('/transaksi/nota-kecil', [PenjualanController::class, 'notaKecil'])->name('transaksi.nota_kecil');
                Route::get('/transaksi/nota-besar', [PenjualanController::class, 'notaBesar'])->name('transaksi.nota_besar');

                Route::get('/transaksi/{id}/data', [PenjualanDetailController::class, 'data'])->name('transaksi.data');
                Route::get('/transaksi/loadkasir/{tunai}/{nontunai}/{total_awal}', [PenjualanController::class, 'loadkasir'])->name('transaksi.loadkasir');

                Route::get('/transaksi/loadform/{diskon}/{jasa}/{total}/{diterima}', [PenjualanDetailController::class, 'loadForm'])->name('transaksi.load_form');


                Route::resource('/transaksi', PenjualanDetailController::class)
                        ->except('create', 'show', 'edit');

                Route::post('/shift/buka-kasir', [PenjualanController::class, 'buka_kasir'])->name('shift.buka_kasir');
                Route::post('/shift/tutup_kasir', [PenjualanController::class, 'tutup_kasir'])->name('shift.tutup_kasir');
                Route::get('/tutup_kasir/print/{id}', [PenjualanController::class, 'print_tutup'])->name('tutup_kasir.print');
                Route::get('/tutup_kasir/nota_kecil/{id}', [PenjualanController::class, 'nota_tutup'])->name('tutup_kasir.nota_kecil');
                Route::get('/shift/get_kasir/{id}', [PenjualanController::class, 'get_kasir'])->name('shift.get_kasir');
                Route::get('/shift/buka', [PenjualanController::class, 'buka'])->name('shift.buka');

                Route::get('/penjualan/totalPenjualan', [PenjualanController::class, 'totalPenjualan'])->name('penjualan.total_penjualan');
        });

        Route::group(['middleware' => 'level:1'], function () {
                Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
                Route::get('/laporan/piutang', [LaporanController::class, 'piutang'])->name('laporan.piutang');
                Route::get('/laporan/data/{awal}/{akhir}', [LaporanController::class, 'data'])->name('laporan.data');
                Route::get('/laporanPiutang/data/{awal}/{akhir}', [LaporanController::class, 'datapiutang'])->name('laporanPiutang.data');
                Route::get('/laporan/pdf/{awal}/{akhir}', [LaporanController::class, 'exportPDF'])->name('laporan.export_pdf');

                Route::get('/user/data', [UserController::class, 'data'])->name('user.data');
                Route::resource('/user', UserController::class);

                Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
                Route::get('/setting/first', [SettingController::class, 'show'])->name('setting.show');
                Route::post('/setting', [SettingController::class, 'update'])->name('setting.update');

                Route::get('/so', [SOController::class, 'index'])->name('so.index');
                Route::get('/so/proses', [SOController::class, 'proses'])->name('so.proses');
                Route::get('/so/selisih', [SOController::class, 'selisih'])->name('so.selisih');
                Route::get('/so/masuk', [SOController::class, 'masuk'])->name('so.masuk');
                Route::get('/so/data/{id_lok}', [SOController::class, 'data'])->name('so.data');
                Route::put('/so/create/{id}', [SOController::class, 'create'])->name('so.create');
                Route::post('/so/tambah/{id}', [SOController::class, 'tambah'])->name('so.tambah');
                Route::get('/so/detail/{id}', [SOController::class, 'detail'])->name('so.detail');
                Route::get('/so/delbatch/{id}', [SOController::class, 'delbatch'])->name('so.delbatch');
                Route::get('/so/export', [SOController::class, 'export'])->name('so.export');
                Route::get('/so/koding', [SOController::class, 'koding'])->name('so.koding');

                Route::get('/laporan/fasmove', [LaporanController::class, 'fasmove'])->name('laporan.fasmove');
        });

        Route::group(['middleware' => 'level:1,2'], function () {
                Route::get('/profil', [UserController::class, 'profil'])->name('user.profil');
                Route::post('/profil', [UserController::class, 'updateProfil'])->name('user.update_profil');
        });
});
