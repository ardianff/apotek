<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <!-- Sidebar user panel -->
    <section class="sidebar">
    <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ url(auth()->user()->foto ?? '') }}" class="img-circle img-profil" alt="User Image">
            </div>
            <div class=" info">
                <p>{{ auth()->user()->name }} </p>
               
            </div>
        </div>
        
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
            {{-- @if (auth()->user()->level == 1) --}}
            <li class="treeview ">
                <a href="#">
                    <i class="fa fa-id-badge" aria-hidden="true"></i>
                <span>Shift</span>
                <span >
                <i class="fa fa-angle-left pull-right"></i>
                </span>
                </a>
                <ul class="treeview-menu" style="margin-left:14px;">
                    @if ($kasir)
                    
                    @if ($kasir->status =='tutup')
                    <li>
                        <a href="{{ route('shift.buka') }}" >
                            <i class="fa fa-folder-open" aria-hidden="true"></i> <span>Buka Kasir</span>
                        </a>
                    </li>
                    @endif
                    
                    @if ($kasir->status =='buka')
                    <li>
                        <a href="{{ route('shift.get_kasir',$kasir->id) }}" >
                            <i class="fa fa-folder-o" aria-hidden="true"></i> <span>Tutup Kasir</span>
                        </a>
                    </li>
                    
                    @endif
                    @else
                    <li>
                        <a href="{{ route('shift.buka') }}" >
                            <i class="fa fa-folder-open" aria-hidden="true"></i> <span>Buka Kasir</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            <li class="treeview ">
                <a href="#">
              <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                <span>Penjualan</span>
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
                </span>
                </a>
                <ul class="treeview-menu" style="margin-left:14px;">
                    @if ($kasir->status =='buka')
                    <li>
                        <a href="{{ route('transaksi.baru') }}" target="_blank">
                            <i class="fa fa-cart-arrow-down "></i> <span>POS</span>
                        </a>
                    </li>
                    @endif
                    <li>
                        <a href="{{ route('penjualan.index') }}">
                            <i class="fa fa-shopping-basket "></i> <span>Daftar Penjualan</span>
                        </a>
                    </li>
                
                    <li>
                        <a href="{{ route('pershift.index') }}">
                            <i class="fa fa-th-list"></i> <span>Daftar Penjualan per shift</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('detailPenjualan.index') }}">
                            <i class="fa fa-list-alt"></i> <span>Daftar Penjualan Detail</span>
                        </a>
                    </li>
                        <li>
                        <a href="{{ route('penjualanPerobat') }}">
                            <i class="fa fa-list-alt"></i> <span>Daftar Penjualan Perobat</span>
                        </a>
                    </li>
                </ul>
                </li>
            <li class="treeview ">
                <a href="#">
                    <i class="fa fa-shopping-basket" aria-hidden="true"></i>
                <span>Pembelian</span>
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
                </span>
                </a>
                <ul class="treeview-menu" style="margin-left:14px;">
                    <li>
                        <a href="{{ route('pembelian.index') }}">
                        <i class="fa fa-shopping-cart" aria-hidden="true"></i><span>Daftar Pembelian</span>
                        </a>
                    </li>
                   
                    <li>
                        <a href="{{ route('pembelian.perobat') }}">
                            <i class="fa fa-reorder"></i> <span>Daftar Pembelian Per Obat</span>
                        </a>
                     </li>
                             <li>
                <a href="{{ route('detailPembelian.index') }}">
                    <i class="fa fa-file-pdf-o"></i> <span>Laporan Pembelian Detail</span>
                </a>
            </li>
                   {{-- <li>
                        <a href="">
                            <i class="fa fa-list-alt"></i> <span>Daftar Pembelian per supplier</span>
                        </a>
                    </li> --}}
                    <li>
                        <a href="{{ route('supplier.index') }}">
                            <i class="fa fa-truck"></i> <span>Supplier</span>
                        </a>
                    </li>
                </ul>
                </li>
                <li class="aja">
                    <a href="{{ route('pengeluaran.index') }}">
                        <i class="fa fa-money"></i> <span>Pengeluaran</span>
                    </a>
                </li>

        
           
         
        <li class="treeview ">
            <a href="#">
                <i class="fa fa-medkit" aria-hidden="true"></i>
            <span>Master Obat</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu" style="margin-left:14px;">
            <li>
                <a href="{{ route('produk.index') }}">
                    <i class="fa fa-plus-square"></i> <span> Data Obat</span>
                </a>
            </li>
            <li>
                <a href="{{ route('detailObat.index') }}">
                    <i class="fa fa-plus-square"></i> <span> Stock Obat</span>
                </a>
            </li>
            <li>
                <a href="{{ route('produk.index') }}">
                    <i class="fa fa-calendar-minus-o"></i> <span>Obat ED</span>
                </a>
            </li>
            <li>
                <a href="{{ route('produk.index') }}">
                    <i class="fa fa-exclamation"></i> <span>Obat habis</span>
                </a>
            </li>
            <li>
                <a href="{{ route('lokasi.index') }}">
                    <i class="fa fa-map-marker"></i> <span>Lokasi Obat</span>
                </a>
            </li>
            <li>
                <a href="{{ route('kategori.index') }}">
                    <i class="fa fa-tag"></i> <span>Kategori Obat</span>
                </a>
            </li>
            <li>
                <a href="{{ route('golongan.index') }}">
                    <i class="fa fa-tags"></i> <span>Golongan Obat</span>
                </a>
            </li>
            <li>
                <a href="{{ route('jenis.index') }}">
                    <i class="fa fa-cubes"></i> <span>Jenis Obat</span>
                </a>
            </li>
          
            <li>
                <a href="{{ route('racikan.index') }}">
                    <i class="fa fa-plus-square"></i> <span> Racikan Obat</span>
                </a>
            </li>
            </ul>
        </li>
         
        <li class="treeview ">
            <a href="#">
                <i class="fa fa-file-excel-o" aria-hidden="true"></i>
            <span>Laporan</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu" style="margin-left:14px;">
            <li>
                <a href="{{ route('laporan.index') }}">
                    <i class="fa fa-file-pdf-o"></i> <span>Laporan Keseluruhan</span>
                </a>
            </li>
           
       
            <li>
                <a href="{{ route('laporan.index') }}">
                    <i class="fa fa-file-pdf-o"></i> <span>Laporan Omset</span>
                </a>
            </li>
            <li>
                <a href="{{ route('laporan.index') }}">
                    <i class="fa fa-file-pdf-o"></i> <span>Laporan Hutang</span>
                </a>
            </li>
            <li>
                <a href="{{ route('laporan.piutang') }}">
                    <i class="fa fa-file-pdf-o"></i> <span>Laporan Piutang</span>
                </a>
            </li>
           
            <li>
                <a href="{{ route('laporan.fasmove') }}">
                    <i class="fa fa-file-pdf-o"></i> <span>Laporan obat fastmoving</span>
                </a>
            </li>
            
           
        </ul>
      
            
        <li>
            <a href="{{ route('so.index') }}">
                <i class="fa fa-file-pdf-o"></i> <span>Stok Opname</span>
            </a>
        </li>
       
    </li>
    <li class="treeview ">
        <a href="#">
            <i class="fa fa-cogs" aria-hidden="true"></i>
        <span>Pengaturan</span>
        <span class="pull-right-container">
        <i class="fa fa-angle-left pull-right"></i>
        </span>
        </a>
        <ul class="treeview-menu" style="margin-left:14px;">
            <li>
                <a href="{{ route('metode.index') }}">
                    <i class="fa fa-medkit"></i> <span>Metode Pembayaran</span>
                </a>
            </li>
            <li>
                <a href="{{ route('jasa.index') }}">
                    <i class="fa fa-stethoscope"></i> <span>Jasa dan embalase</span>
                </a>
            </li>
            <li>
                <a href="{{route('diskon.index') }}">
                    <i class="fa fa-user-circle"></i> <span>Diskon</span>
                </a>
            </li>
            <li>
                <a href="{{route('apoteker.index') }}">
                    <i class="fa fa-user-md"></i> <span>Apoteker</span>
                </a>
            </li>
        
            <li>
                <a href="{{ route('member.index') }}">
                    <i class="fa fa-id-card"></i> <span>Member</span>
                </a>
            </li>
           
            <li>
                <a href="{{ route('user.index') }}">
                    <i class="fa fa-users"></i> <span>User</span>
                </a>
            </li>
            <li>
                <a href="{{ route("setting.index") }}">
                    <i class="fa fa-cogs"></i> <span>Profil Apotek</span>
                </a>
            </li>
            
        </ul>
    </li>
    {{-- @if (auth()->user()->level == 3) --}}
                
    <li>
        <a href="{{ route('laporan.stock') }}">
            <i class="fa fa-file-pdf-o"></i> <span>Laporan Stock</span>
        </a>
    </li>
    {{-- @endif --}}
{{--          
            <li>
                <a href="{{ route('transfer.index') }}">
                    <i class="fa fa-medkit"></i> <span> Transfer Obat</span>
                </a>
            </li>
         
         --}}
           
          
            {{-- @endif --}}
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>