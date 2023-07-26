@php
    use App\Persediaan;
@endphp
<table class="tableData">
    <thead>
        <tr class="text-center">
            <th>No</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>SKPD</th>
            <th>No Surat</th>
            <th>Masuk</th>
            <th>Harga Satuan</th>
            <th>Tanggal Persdiaan</th>
            <th>Keterangan</th>
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
                <td>{{'Rp.'.number_format($data['harga_satuan'], 0, '', '.')}}</td>
                <td style="width: 15%">{{\Carbon\Carbon::parse($data['tanggal_persediaan'])->format('d M Y')}}</td>
                <td>{{$data['ket_persediaan']}}</td>
            </tr>
        @endforeach
    </tbody>
</table>