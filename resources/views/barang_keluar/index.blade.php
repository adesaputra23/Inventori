@php
    use App\Persediaan;
    use App\User;
@endphp
@extends('layouts_v2.master_page')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data {{$title}}</h5>
                    <hr>
                    @include('alert_message.alert')
                    @if (!User::isKepala())
                        <button type="button" id="btn-tambah" class="btn btn-warning btn-sm mb-3">Tambah Data</button>
                    @endif
                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>No Surat Keluar</th>
                                    <th>Kode Persediaan Barang</th>
                                    <th>Uraian</th>
                                    <th>Keterangan</th>
                                    @if (!User::isKepala())
                                        <th style="width: 15%">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($listBarangKeluar as $key => $barangKeluar)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td style="width: 15%">{{\Carbon\Carbon::parse($barangKeluar->tanggal_keluar)->format('d M Y')}}</td>
                                        <td>{{$barangKeluar->no_surat_keluar}}</td>
                                        <td>{{Persediaan::gnerateKode($barangKeluar->id_persediaan)}}</td>
                                        <td class="@if ($barangKeluar->uraian == null) text-center @endif">{{$barangKeluar->uraian ?? '-'}}</td>
                                        <td class="@if ($barangKeluar->ket_keluar == null) text-center @endif">{{$barangKeluar->ket_keluar ?? '-'}}</td>
                                        @if (!User::isKepala())
                                            <td class="text-center">
                                                <button id="btn-edit" data-kode="{{$barangKeluar->kode_barang_keluar}}" type="button" class="btn-edit btn btn-warning btn-sm">Edit</button>
                                                <button type="button" data-kode="{{$barangKeluar->kode_barang_keluar}}" class="btn btn-danger btn-sm text-white btn-hapus">Hapus</button>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@include('modals.modals')
@push('costum-js')
<script>
    var persediaanBarang = {!! json_encode($persediaanBarang) !!};
    var persediaanBarangUpdate = {!! json_encode($persediaanBarangUpdate) !!};

    console.log(persediaanBarang);
    $('#zero_config').DataTable();
    $('#btn-tambah').on('click', function(e){
        var params = {
            title : 'Tambah Data Barang Keluar',
            aksi : 'tambah', 
            data : null,
        };
        setDataformInput(params);
    });
    $('.btn-edit').on('click', function(e){
        var kode = $(this).data('kode');
        $.ajax({
            type: "get",
            url: "{{route('keluar.get.kode')}}",
            data: {
                kode: kode,
                type: "getKode",
                _token: "{{ csrf_token() }}",
            },
            dataType: "json",
            success: function (response) {
                var params = {
                    title : 'Edit Data Barang Keluar',
                    aksi : 'edit', 
                    data : response,
                };
                setDataformInput(params);
            }
        });
    });

    function setDataformInput(params) {
        var isData = params.data != null ? params.data : '';
        var setPersediaanBarang = persediaanBarang;
        if (params.aksi === 'edit')setPersediaanBarang = persediaanBarangUpdate;
        $('#add-modal').modal('show', true);
        $('#add-modal .modal-title').text(params.title);
        $('#add-modal .modal-body').empty();
        $('#form-modal').attr('action', "{{route('keluar.add.keluar')}}");
        var setHtml = `
            <input type="hidden" class="form-control" id="aksi" name="aksi" value="${params.aksi}">
            <div class="form-group row">
                <label for="fname" class="col-sm-2 control-label col-form-label">Kode</label>
                <div class="col-sm-10">
                    <input ${isData.kode_barang_keluar === undefined ? '' : 'readonly'} name="kode" type="text" class="form-control" id="kode"
                        placeholder="Masukan Kode Barang Keluar" value="${isData.kode_barang_keluar ?? ''}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2 control-label col-form-label">Barang</label>
                <div class="col-md-10">
                    <select name="persediaan" class="form-select shadow-none" id="persediaan">
                        <option value="" disabled selected>Pilih Persediaan Barang</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="fname" class="col-sm-2 control-label col-form-label">Tanggal Keluar</label>
                <div class="col-sm-10">
                    <input name="tanggal_keluar" type="date" class="form-control" id="tanggal_keluar"
                        placeholder="Masukan Tanggal Keluar" value="${isData.tanggal_keluar !== undefined ? moment(isData.tanggal_keluar).format('YYYY-MM-DD') : ''}">
                </div>
            </div>
            <div class="form-group row">
                <label for="fname" class="col-sm-2 control-label col-form-label">No Surat</label>
                <div class="col-sm-10">
                    <input name="no_surat" type="text" class="form-control" id="no_surat"
                        placeholder="Masukan No Surat Keluar" value="${isData.no_surat_keluar !== undefined ? isData.no_surat_keluar : ''}">
                </div>
            </div>
            <div class="form-group row">
                <label for="fname" class="col-sm-2 control-label col-form-label">Jumlah</label>
                <div class="col-sm-10">
                    <input name="jumlah_barang" type="number" class="form-control" id="jumlah_barang"
                        placeholder="Masukan Jumlah Keluar Barang" value="${isData.jumlah_keluar !== undefined ? isData.jumlah_keluar : ''}">
                </div>
            </div>
            <div class="form-group row">
                <label for="cono1" class="col-sm-2 control-label col-form-label">Uraian</label>
                <div class="col-sm-10">
                    <textarea class="form-control" placeholder="Masukan Uraian" id="uraian" name="uraian">${isData.uraian !== undefined ? isData.uraian : ''}</textarea>
                </div>
            </div>
            <div class="form-group row">
                <label for="cono1" class="col-sm-2 control-label col-form-label">Keterangan</label>
                <div class="col-sm-10">
                    <textarea class="form-control" placeholder="Masukan Keterangan Keluar" id="keterangan" name="keterangan">${isData.ket_keluar !== undefined ? isData.ket_keluar : ''}</textarea>
                </div>
            </div>
        `;
        $('#add-modal .modal-body').html(setHtml);

        $.each(setPersediaanBarang, function (key,val) { 
            $('#persediaan')
                .append($(`<option ${isData.id_persediaan !== undefined && isData.id_persediaan === val.id_persediaan ? 'selected' : ''}></option>`)
                .attr("value", val.id_persediaan)
                .text(val.kode_persediaan + ' - ' + val.nama_barang)); 
        }); 

        $.validator.addMethod("remote", 
            function(value, element) {
                var type = 'validasi';
                var result = false;
                $.ajax({
                    type:"GET",
                    async: false,
                    url: "{{route('keluar.get.kode')}}", // script to validate in server side
                    data: {
                        kode: value,
                        isType: type,
                        type: "getRemote",
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        console.log(data);
                        result = (data == true) ? true : false;
                    }
                });
                // return true if username is exist in database
                return result; 
            }, 
            "This kode is already taken! Try another."
        );

        $("#form-modal").validate({
            rules:{
                kode: {
                    required: true,
                    remote: params.aksi == 'tambah' ? true : false,
                },
                persediaan : "required",
                tanggal_keluar: "required",
                no_surat: "required",
                jumlah_barang: "required",
            },
        });

    }

    $('.btn-hapus').on('click', function(e){
        var kode = $(this).data('kode');
        $('#modal-hapus').modal('show', true);
        $('#form-modal-hapus').attr('action', "{{route('keluar.hapus.keluar')}}");
        $('#data_uniq').val(kode);
    });


</script>
@endpush
@endsection