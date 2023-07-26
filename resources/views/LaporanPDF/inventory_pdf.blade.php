@php
    use App\Persediaan;
@endphp
<table class="tableData">
    <thead>
        <tr class="text-center">
            <th rowspan="2">No</th>
            <th rowspan="2">Kode Barang</th>
            <th rowspan="2">Nama Barang</th>
            <th rowspan="2">SKPD</th>
            <th rowspan="2">No Surat</th>
            <th colspan="3">Barang - Barang</th>
            <th rowspan="2">Harga Satuan</th>
            <th colspan="3">Jumlah Harga Barang Diterima/Keluar/Sisa</th>
            <th rowspan="2">Tanggal Persdiaan</th>
            <th rowspan="2">Keterangan</th>
        </tr>
        <tr>
            <th>Masuk</th>
            <th>Keluar</th>
            <th>Sisa</th>
            <th>Bertambah</th>
            <th>Berkurang</th>
            <th>Sisa</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($listData as $item => $data)
            <tr>
                <td class="text-center">{{$item+1}}</td>
                <td>{{Persediaan::gnerateKode($data['id_persediaan'])}}</td>
                <td>{{$data['nama_barang']}}</td>
                <td>{{$data['skpd']}}</td>
                <td>{{$data['persediaan_no_surat']}}</td>
                <td>{{$data['jumlah_masuk']}}</td>
                <td>{{$data['jumlah_keluar']}}</td>
                <td>{{$data['jumlah_sisa']}}</td>
                <td>{{'Rp.'.number_format($data['harga_satuan'], 0, '', '.')}}</td>
                <td>{{'Rp.'.number_format($data['harga_tambah'], 0, '', '.')}}</td>
                <td>{{'Rp.'.number_format($data['harga_kurang'], 0, '', '.')}}</td>
                <td>{{'Rp.'.number_format($data['harga_sisa'], 0, '', '.')}}</td>
                <td style="width: 15%">{{\Carbon\Carbon::parse($data['tanggal_persediaan'])->format('d M Y')}}</td>
                <td>{{$data['ket_persediaan']}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
