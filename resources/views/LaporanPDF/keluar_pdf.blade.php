@php
    use App\Persediaan;
@endphp
<table class="tableData">
    <thead>
        <tr class="text-center">
            <th>No</th>
            <th>Tanggal Keluar</th>
            <th>Kode Barang Keluar</th>
            <th>Kode Barang</th>
            <th>Jumlah Keluar</th>
            <th>No Surat Keluar</th>
            <th>Uraian</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($listData as $item => $data)
            <tr>
                <td class="text-center">{{$item+1}}</td>
                <td style="width: 15%">{{\Carbon\Carbon::parse($data->tanggal_keluar)->format('d M Y')}}</td>
                <td>{{$data->kode_barang_keluar}}</td>
                <td>{{Persediaan::gnerateKode($data->id_persediaan)}} - {{$data->persediaan->nama_barang}}</td>
                <td>{{$data->jumlah_keluar}}</td>
                <td>{{$data->no_surat_keluar}}</td>
                <td>{{$data->uraian ?? '-'}}</td>
                <td>{{$data->ket_keluar ?? '-'}}</td>
            </tr>
        @endforeach
    </tbody>
</table>