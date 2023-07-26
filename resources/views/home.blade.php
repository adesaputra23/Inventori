@php
    use App\User;
@endphp
@extends('layouts_v2.master_page')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Dashboard</h5>
                    <hr>
                    <div class="row">

                        @if (User::isAdmin() || User::isStaft())
                            <!-- Column -->
                            <div class="col-md-6 col-lg-4 col-xlg-3">
                                <div class="card card-hover">
                                    <div class="box bg-cyan text-center">
                                        <h1 class="font-light text-white"><i class="fas fa-warehouse"></i></h1>
                                        <h6 class="text-white">Jumlah Ruangan : {{$countRuang}}</h6>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (User::isAdmin() || User::isStaft())
                            <!-- Column -->
                            <div class="col-md-6 col-lg-4 col-xlg-3">
                                <div class="card card-hover">
                                    <div class="box bg-success text-center">
                                        <h1 class="font-light text-white"><i class="fas fa-bars"></i></h1>
                                        <h6 class="text-white">Jumlah Kategori : {{$countKategori}}</h6>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Column -->
                        <div class="col-md-6 col-lg-4 col-xlg-3">
                            <div class="card card-hover">
                                <div class="box bg-warning text-center">
                                    <h1 class="font-light text-white"><i class="fas fa-box"></i></h1>
                                    <h6 class="text-white">Jumlah Persediaan Barang : {{$countPersediaan}}</h6>
                                </div>
                            </div>
                        </div>

                        <!-- Column -->
                        <div class="col-md-6 col-lg-4 col-xlg-3">
                            <div class="card card-hover">
                                <div class="box bg-danger text-center">
                                    <h1 class="font-light text-white"><i class="fas fa-box-open"></i></h1>
                                    <h6 class="text-white">Jumlah Barang Keluar : {{$countBarangKeluar}}</h6>
                                </div>
                            </div>
                        </div>

                        <!-- Column -->
                        <div class="col-md-6 col-lg-4 col-xlg-3">
                            <div class="card card-hover">
                                <div class="box bg-info text-center">
                                    <h1 class="font-light text-white"><i class="fas fa-dolly"></i></h1>
                                    <h6 class="text-white">Jumlah Perpindahan Barang : {{$countPerpindahanBarang}}</h6>
                                </div>
                            </div>
                        </div>

                        @if (User::isAdmin())
                            <!-- Column -->
                            <div class="col-md-6 col-lg-4 col-xlg-3">
                                <div class="card card-hover">
                                    <div class="box bg-cyan text-center">
                                        <h1 class="font-light text-white"><i class="mdi mdi-relative-scale"></i></h1>
                                        <h6 class="text-white">Jumlah User : {{$countUser}}</h6>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection