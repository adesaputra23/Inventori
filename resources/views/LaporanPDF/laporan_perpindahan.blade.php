@php
 use App\Persediaan;
@endphp
@extends('layouts_v2.master_page')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data {{$title}}</h5>
                    <hr>
                    @include('LaporanPDF.form_filter')
                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Tanggal Perpindahan</th>
                                    <th>Kode Barang Perpindahan</th>
                                    <th>Kode Barang</th>
                                    <th>Kode Ruang</th>
                                    <th>No Surat Perpindahan</th>
                                    {{-- <th>Uraian</th> --}}
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($listData as $item => $data)
                                    <tr>
                                        <td class="text-center">{{$item+1}}</td>
                                        <td style="width: 15%">{{\Carbon\Carbon::parse($data->tanggal_perpindahan)->format('d M Y')}}</td>
                                        <td>{{$data->kode_perpindahan}}</td>
                                        <td>{{Persediaan::gnerateKode($data->id_persediaan)}} - {{$data->persediaan->nama_barang}}</td>
                                        <td>{{$data->kode_ruang}} - {{$data->ruang->nama_ruang}}</td>
                                        <td>{{$data->no_surat_perpindahan}}</td>
                                        {{-- <td>{{$data->uraian_perpindahan ?? '-'}}</td> --}}
                                        <td>{{$data->ket_perpindahan ?? '-'}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@push('costum-js')
<script>
    $('#zero_config').DataTable();
    $('#btn-filter').on('click', function(e){
        $('#form-filter').attr('action', "{{route('laporan.perpindahan.barang.pdf')}}");
    })
    $('#btn-cetak-pdf').on('click', function(e){
        $('#form-filter').attr('action', "{{route('laporan.perpindahan.barang.pdf')}}");
    })
</script>
@endpush
@endsection