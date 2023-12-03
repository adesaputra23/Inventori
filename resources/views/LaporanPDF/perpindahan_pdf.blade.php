@php
    use App\Persediaan;
@endphp
<table class="tableData">
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
                <td style="width: 15%">{{\Carbon\Carbon::parse($data['tanggal_perpindahan'])->format('d M Y')}}</td>
                <td>{{$data['kode_perpindahan']}}</td>
                <td>{{Persediaan::gnerateKode($data['id_persediaan'])}} - {{$data->persediaan->nama_barang}}</td>
                <td>{{$data['kode_ruang']}} - {{$data->ruang->nama_ruang}}</td>
                <td>{{$data['no_surat_perpindahan']}}</td>
                {{-- <td>{{$data['uraian_perpindahan'] ?? '-'}}</td> --}}
                <td>{{$data['ket_perpindahan'] ?? '-'}}</td>
            </tr>
        @endforeach
    </tbody>
</table>