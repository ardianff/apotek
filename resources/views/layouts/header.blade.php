<header class="main-header">
    <!-- Logo -->
    <a href="index2.html" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        {{-- @php
            $words = explode(' ', $setting->nama_perusahaan);
            $word  = '';
            foreach ($words as $w) {
                $word .= $w[0];
            }
        @endphp --}}
        <span class="logo-mini"><img src="{{ url($setting->path_logo) }}" class="image-circle" alt="User Image">{{ $setting->nama_perusahaan }}</span>
        <!-- logo for regular state and mobile devices -->
         <span class="logo-lg"> <img src="{{ url($setting->path_logo) }}" class="image-circle" alt="User Image">{{ $setting->nama_perusahaan }}</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class=""><b>{{ $setting->nama_perusahaan }}</b></span>
        </a>
        
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              
                <!-- User Account: style can be found in dropdown.less -->
                @if ($kasir->status =='buka')
                    <li>
                        <a href="{{ route('transaksi.baru') }}" target="_blank">
                            <i class="fa fa-cart-arrow-down "></i> <span>POS</span>
                        </a>
                    </li>
                    @endif
                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-bell-o"></i>
                    <span class="label label-warning">10</span>
                    </a>
                    <ul class="dropdown-menu">
                    <li class="header">You have 10 notifications</li>
                    <li>
                    
                    <ul class="menu">
                    <li>
                    <a href="#">
                    <i class="fa fa-users text-aqua"></i> 5 new members joined today
                    </a>
                    </li>
                    <li>
                    <a href="#">
                    <i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the
                    page and may cause design problems
                    </a>
                    </li>
                    <li>
                    <a href="#">
                    <i class="fa fa-users text-red"></i> 5 new members joined
                    </a>
                    </li>
                    <li>
                    <a href="#">
                    <i class="fa fa-shopping-cart text-green"></i> 25 sales made
                    </a>
                    </li>
                    <li>
                    <a href="#">
                    <i class="fa fa-user text-red"></i> You changed your username
                    </a>
                    </li>
                    </ul>
                    </li>
                    <li class="footer"><a href="#">View all</a></li>
                    </ul>
                    </li>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ url(auth()->user()->foto ?? '') }}" class="user-image img-profil"
                            alt="User Image">
                        <span class="hidden-xs">{{ auth()->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="{{ url(auth()->user()->foto ?? '') }}" class="img-circle img-profil"
                                alt="User Image">

                            <p>
                                {{ auth()->user()->name }} - {{ auth()->user()->email }}
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{ route('user.profil') }}" class="btn btn-success btn-flat">Profil</a>
                            </div>
                            <div class="pull-right">
                                <a href="#" class="btn btn-danger btn-flat"
                                    onclick="$('#logout-form').submit()">Keluar <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i> </a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

<form action="{{ route('logout') }}" method="post" id="logout-form" style="display: none;">
    @csrf
</form>