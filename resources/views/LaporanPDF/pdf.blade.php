<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$title}}</title>
</head>
<style>
    body{
        font-family: Arial, Helvetica, sans-serif;
    }
    .judul{
        font-family: Arial, Helvetica, sans-serif;
        text-transform: uppercase;
        text-align: center;
    }
    .tanggal{
        font-family: Arial, Helvetica, sans-serif;
        font-weight: bold;
        font-size: 0.8rem;
        text-align: center;
        margin-top: -18px;
    }
    .tableData {
        width: 100%;
        font-family: sans-serif;
        color: #232323;
        border-collapse: collapse;
        font-size: 10px;
    }
 
    .tableData th{
        border: 1px solid #999;
        padding: 4px 8px;
    }

    .tableData td {
        border: 1px solid #999;
        padding: 4px 8px;
    }
</style>
<body>
    @php
        if (empty($tanggal_start) && empty($tanggal_end)){
            $date = 'Semua Data';
        }else{
            $start = \Carbon\Carbon::parse($tanggal_start)->format('d M Y');
            $end = \Carbon\Carbon::parse($tanggal_end)->format('d M Y');
            $date = "Dari Tanggal $start Sampai Dengan $end";
        }
    @endphp
    <h5 class="judul">{{$title}}</h5>
    <p class="tanggal">{{$date}}</p>
    <table style="width: 100%" style="border: none">
        <tr>
            <td style="width: 8%; border: none;">Propinsi</td>
            <td style="border: none">:&nbsp;&nbsp; Jawa Timur</td>
        </tr>
        <tr>
            <td style="width: 8%; border: none;">Kota</td>
            <td style="border: none">:&nbsp;&nbsp; Malang</td>
        </tr>
    </table>
    <small style="color: red"><i>Sudah Tervalidasi, APBD</i></small>
    @include($includeFile)
</body>
</html>