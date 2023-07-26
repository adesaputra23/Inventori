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
                                    <th>Kode Perpindahan</th>
                                    <th>Kode Brang</th>
                                    <th>Nama Ruangan</th>
                                    <th>No Surat Perpindahan</th>
                                    <th>Tanggal Perpindahan</th>
                                    <th>Keterangan Perpindahan</th>
                                    @if (!User::isKepala())
                                        <th style="width: 15%">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($listPerpindahan as $key => $data)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$data->kode_perpindahan}}</td>
                                        <td>{{Persediaan::gnerateKode($data->id_persediaan)}}</td>
                                        <td>{{$data->ruang->nama_ruang}}</td>
                                        <td>{{$data->no_surat_perpindahan}}</td>
                                        <td style="width: 15%">{{\Carbon\Carbon::parse($data->tanggal_perpindahan)->format('d M Y')}}</td>
                                        <td class="@if ($data->ket_perpindahan == null) text-center @endif">{{$data->ket_perpindahan ?? '-'}}</td>
                                        @if (!User::isKepala())
                                            <td class="text-center">
                                                <button data-kode="{{$data->kode_perpindahan}}" id="btn-edit" type="button" class="btn-edit btn btn-warning btn-sm">Edit</button>
                                                <button data-kode="{{$data->kode_perpindahan}}" type="button" class="btn btn-danger btn-sm text-white btn-hapus">Hapus</button>
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
    $('#zero_config').DataTable();
    var persediaanBarang = {!! json_encode($persediaanBarang) !!};
    var persediaanBarangUpdate = {!! json_encode($persediaanBarangUpdate) !!};
    var ruangan = {!! json_encode($getRuangan) !!};
    $('#btn-tambah').on('click', function(e){
        var params = {
            title : 'Tambah Data Perpindahan',
            aksi : 'tambah', 
            data : null,
        };
        setDataformInput(params);
    });
    $('.btn-edit').on('click', function(e){
        var kode = $(this).data('kode');
        $.ajax({
            type: "get",
            url: "{{route('perpindahan.get.kode')}}",
            data: {
                kode: kode,
                type: "getKode",
                _token: "{{ csrf_token() }}",
            },
            dataType: "json",
            success: function (response) {
                var params = {
                    title : 'Edit Data Barang Perpindahan',
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
        console.log(persediaanBarang);
        $('#add-modal').modal('show', true);
        $('#add-modal .modal-title').text(params.title);
        $('#add-modal .modal-body').empty();
        $('#form-modal').attr('action', "{{route('perpindahan.add.perpindahan')}}");
        var setHtml = `
            <input type="hidden" class="form-control" id="aksi" name="aksi" value="${params.aksi}">
            <div class="form-group row">
                <label for="fname" class="col-sm-3 control-label col-form-label">Kode Perpindahan</label>
                <div class="col-sm-9">
                    <input ${isData.kode_perpindahan === undefined ? '' : 'readonly'} name="kode" type="text" class="form-control" id="kode"
                        placeholder="Masukan Kode Perpindahan Barang" value="${isData.kode_perpindahan ?? ''}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 control-label col-form-label">Kode Barang</label>
                <div class="col-md-9">
                    <select name="persediaan" class="form-select shadow-none" id="persediaan">
                        <option value="" disabled selected>Pilih Persediaan Barang</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 control-label col-form-label">Kode Ruang</label>
                <div class="col-md-9">
                    <select name="ruangan" class="form-select shadow-none" id="ruangan">
                        <option value="" disabled selected>Pilih Ruangan</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="fname" class="col-sm-3 control-label col-form-label">Tanggal Perpindahan</label>
                <div class="col-sm-9">
                    <input name="tanggal" type="date" class="form-control" id="tanggal"
                        placeholder="Masukan Tanggal Keluar" value="${isData.tanggal_perpindahan !== undefined ? moment(isData.tanggal_perpindahan).format('YYYY-MM-DD') : ''}">
                </div>
            </div>
            <div class="form-group row">
                <label for="fname" class="col-sm-3 control-label col-form-label">No Surat Perpindahan</label>
                <div class="col-sm-9">
                    <input name="no_surat" type="text" class="form-control" id="no_surat"
                        placeholder="Masukan No Surat Perpindahan" value="${isData.no_surat_perpindahan !== undefined ? isData.no_surat_perpindahan : ''}">
                </div>
            </div>
            <div class="form-group row">
                <label for="cono1" class="col-sm-3 control-label col-form-label">Uraian Perpindahan</label>
                <div class="col-sm-9">
                    <textarea class="form-control" placeholder="Masukan Uraian Perpindahan" id="uraian" name="uraian">${isData.uraian_perpindahan !== undefined ? isData.uraian_perpindahan : ''}</textarea>
                </div>
            </div>
            <div class="form-group row">
                <label for="cono1" class="col-sm-3 control-label col-form-label">Keterangan Perpindahan</label>
                <div class="col-sm-9">
                    <textarea class="form-control" placeholder="Masukan Keterangan Perpindahan" id="keterangan" name="keterangan">${isData.ket_perpindahan !== undefined ? isData.ket_perpindahan : ''}</textarea>
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

        $.each(ruangan, function (key,val) { 
            $('#ruangan')
                .append($(`<option ${isData.kode_ruang !== undefined && isData.kode_ruang === val.kode_ruang ? 'selected' : ''}></option>`)
                .attr("value", val.kode_ruang)
                .text(val.kode_ruang + ' - ' + val.nama_ruang)); 
        }); 

        $.validator.addMethod("remote", 
            function(value, element) {
                var type = 'validasi';
                var result = false;
                $.ajax({
                    type:"GET",
                    async: false,
                    url: "{{route('perpindahan.get.kode')}}", // script to validate in server side
                    data: {
                        kode: value,
                        isType: type,
                        type: "getRemote",
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(data) {
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
                ruangan: "required",
                tanggal: "required",
                no_surat: "required",
            },
        });

    }

    $('.btn-hapus').on('click', function(e){
        var kode = $(this).data('kode');
        $('#modal-hapus').modal('show', true);
        $('#form-modal-hapus').attr('action', "{{route('perpindahan.hapus.perpindahan')}}");
        $('#data_uniq').val(kode);
    });

</script>
@endpush
@endsection