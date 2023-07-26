@php
    use App\User;
    $url_name = url()->current();
    $admin = User::isAdmin();
    $staft = User::isStaft();
    $kepala = User::isKepala();
@endphp
<aside class="left-sidebar" data-sidebarbg="skin5">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav" class="pt-0">

                {{-- home --}}
                <li class="sidebar-item @if ($url_name == route('home')) selected @endif"> 
                    <a class="sidebar-link waves-effect waves-dark sidebar-link @if ($url_name == route('home')) active @endif" href="{{route('home')}}" aria-expanded="false">
                        <i class="mdi mdi-view-dashboard"></i>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>

                <label for="" class="ms-2 text-white fw-light mt-2">
                    Data Master
                </label>

                @if ($admin || $staft)
                    {{-- ruangan --}}
                    <li class="sidebar-item  @if ($url_name == route('ruang.index')) selected @endif"> 
                        <a class="sidebar-link waves-effect waves-dark sidebar-link @if ($url_name == route('ruang.index')) active @endif"
                            href="{{route('ruang.index')}}" aria-expanded="false"><i class="fas fa-warehouse"></i>
                            <span class="hide-menu">Ruangan</span>
                        </a>
                    </li>
                @endif

                @if ($admin || $staft)
                    {{-- kategori --}}
                    <li class="sidebar-item @if ($url_name == route('kategori.index')) selected @endif"> 
                        <a class="sidebar-link waves-effect waves-dark sidebar-link @if ($url_name == route('kategori.index')) active @endif"
                            href="{{route('kategori.index')}}" aria-expanded="false"><i class="fas fa-bars"></i>
                            <span class="hide-menu">Kategori</span>
                        </a>
                    </li>
                @endif

                @if ($admin || $staft || $kepala)
                    {{-- persediaan barang --}}
                    <li class="sidebar-item @if ($url_name == route('persediaan.index')) selected @endif"> 
                        <a class="sidebar-link waves-effect waves-dark sidebar-link @if ($url_name == route('persediaan.index')) active @endif"
                            href="{{route('persediaan.index')}}" aria-expanded="false"><i class="fas fa-box"></i>
                            <span class="hide-menu">Persediaan Barang</span>
                        </a>
                    </li>
                @endif

                @if ($admin || $staft || $kepala)
                    {{-- barang keluar --}}
                    <li class="sidebar-item @if ($url_name == route('keluar.index')) selected @endif"> 
                        <a class="sidebar-link waves-effect waves-dark sidebar-link @if ($url_name == route('keluar.index')) active @endif"
                            href="{{route('keluar.index')}}" aria-expanded="false"><i class="fas fa-box-open"></i>
                            <span class="hide-menu">Barang Keluar</span>
                        </a>
                    </li>
                @endif

                @if ($admin || $staft || $kepala)
                    {{-- perpindahan barang --}}
                    <li class="sidebar-item @if ($url_name == route('perpindahan.index')) selected @endif"> 
                        <a class="sidebar-link waves-effect waves-dark sidebar-link @if ($url_name == route('perpindahan.index')) active @endif"
                            href="{{route('perpindahan.index')}}" aria-expanded="false"><i class="fas fa-dolly"></i>
                            <span class="hide-menu">Perpindahan Barang</span>
                        </a>
                    </li>
                @endif

                <label for="" class="ms-2 text-white fw-light mt-2">
                    Data Laporan
                </label>

                @if ($admin || $staft || $kepala)
                    {{-- laporan inventory --}}
                    <li class="sidebar-item @if ($url_name == route('laporan.inventory.pdf')) selected @endif"> 
                        <a class="sidebar-link waves-effect waves-dark sidebar-link @if ($url_name == route('laporan.inventory.pdf')) active @endif"
                            href="{{route('laporan.inventory.pdf')}}" aria-expanded="false"><i class="fas fa-indent"></i>
                            <span class="hide-menu">Inventaris</span>
                        </a>
                    </li>
                @endif

                @if ($admin || $staft || $kepala)
                    {{-- laporan persediaan barang --}}
                    <li class="sidebar-item @if ($url_name == route('laporan.persediaan.barang.pdf')) selected @endif"> 
                        <a class="sidebar-link waves-effect waves-dark sidebar-link @if ($url_name == route('laporan.persediaan.barang.pdf')) active @endif"
                            href="{{route('laporan.persediaan.barang.pdf')}}" aria-expanded="false"><i class="fas fa-indent"></i>
                            <span class="hide-menu">Persediaan Barang</span>
                        </a>
                    </li>
                @endif

                @if ($admin || $staft || $kepala)
                    {{-- laporan barang keluar --}}
                    <li class="sidebar-item @if ($url_name == route('laporan.barang.keluar.pdf')) selected @endif"> 
                        <a class="sidebar-link waves-effect waves-dark sidebar-link @if ($url_name == route('laporan.barang.keluar.pdf')) active @endif"
                            href="{{route('laporan.barang.keluar.pdf')}}" aria-expanded="false"><i class="fas fa-indent"></i>
                            <span class="hide-menu">Barang Keluar</span>
                        </a>
                    </li>
                @endif

                @if ($admin || $staft || $kepala)
                    {{-- laporan perpindahan barang --}}
                    <li class="sidebar-item @if ($url_name == route('laporan.perpindahan.barang.pdf')) selected @endif"> 
                        <a class="sidebar-link waves-effect waves-dark sidebar-link @if ($url_name == route('laporan.perpindahan.barang.pdf')) active @endif"
                            href="{{route('laporan.perpindahan.barang.pdf')}}" aria-expanded="false"><i class="fas fa-indent"></i>
                            <span class="hide-menu">Perpindahan</span>
                        </a>
                    </li>
                @endif

                @if ($admin)
                    <label for="" class="ms-2 text-white fw-light mt-2">
                        Data Pengguna
                    </label>
                    {{-- pengguna --}}
                    <li class="sidebar-item @if ($url_name == route('user.index')) selected @endif"> 
                        <a class="sidebar-link waves-effect waves-dark sidebar-link @if ($url_name == route('user.index')) active @endif"
                            href="{{route('user.index')}}" aria-expanded="false"><i class="fas fa-users"></i>
                            <span class="hide-menu">Pengguna</span>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>