@extends('layouts_v2.master_page')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data {{$title}}</h5>
                    <hr>
                    @include('alert_message.alert')
                    <button type="button" id="btn-tambah" class="btn btn-warning btn-sm mb-3">Tambah Data</button>
                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Type</th>
                                    <th>Tanggal Buat</th>
                                    <th style="width: 15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list_data as $key => $data)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td>{{$data->kode}}</td>
                                        <td>{{$data->nama_kategori}}</td>
                                        <td>{{$mapKategori[$data->type]}}</td>
                                        <td>{{$data->created_at}}</td>
                                        <td class="text-center">
                                            <button data-kode="{{$data->kode}}" id="btn-edit" type="button" class="btn-edit btn btn-warning btn-sm">Edit</button>
                                            <button data-kode="{{$data->kode}}" type="button" class="btn btn-danger btn-sm text-white btn-hapus">Hapus</button>
                                        </td>
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
    var katgoriMap = {!! json_encode($mapKategori) !!};
    $('#zero_config').DataTable();
    $('#btn-tambah').on('click', function(e){
        var params = {
            title : 'Tambah Data Kategori',
            aksi : 'tambah', 
            data : null,
        };
        setDataformInput(params);
    });
    $('.btn-edit').on('click', function(e){
        var kode = $(this).data('kode');
        $.ajax({
            type: "get",
            url: "{{route('kategori.get.kode')}}",
            data: {
                kode: kode,
                type: "getKode",
                _token: "{{ csrf_token() }}",
            },
            dataType: "json",
            success: function (response) {
                var params = {
                    title : 'Edit Data Kategori',
                    aksi : 'edit', 
                    data : response,
                };
                setDataformInput(params);
            }
        });
    });

    $('.btn-hapus').on('click', function(e){
        var kode = $(this).data('kode');
        $('#modal-hapus').modal('show', true);
        $('#form-modal-hapus').attr('action', "{{route('kategori.hapus.kategori')}}");
        $('#data_uniq').val(kode);
    });

    function setDataformInput(params) {
        var isData = params.data != null ? params.data : '';
        $('#add-modal').modal('show', true);
        $('#add-modal .modal-title').text(params.title);
        $('#add-modal .modal-body').empty();
        $('#form-modal').attr('action', "{{route('kategori.add.kategori')}}");
        var setHtml = `
            <input type="hidden" class="form-control" id="aksi" name="aksi" value="${params.aksi}">
            <div class="form-group row">
                <label for="fname" class="col-sm-2 control-label col-form-label">Kode</label>
                <div class="col-sm-10">
                    <input ${isData.kode == undefined ? '' :'readonly'} name="kode" type="number" class="form-control" id="kode"
                        placeholder="Masukan Kode" value="${isData.kode ?? ''}">
                </div>
            </div>
            <div class="form-group row">
                <label for="fname" class="col-sm-2 control-label col-form-label">Nama Kategori</label>
                <div class="col-sm-10">
                    <input name="nama_kategori" type="text" class="form-control" id="nama_kategori"
                        placeholder="Masukan Nama Kategori" value="${isData.nama_kategori ?? ''}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2 control-label col-form-label">Type Kategori</label>
                <div class="col-md-10">
                    <select name="type" class="form-select shadow-none" id="type">
                        <option value="" disabled selected>Pilih Type Kategori</option>
                    </select>
                </div>
            </div>
        `;
        $('#add-modal .modal-body').html(setHtml);

        $.each(katgoriMap, function (key,val) { 
            $('#type')
                .append($(`<option ${isData.type != undefined && isData.type == key ? 'selected' : ''} ></option>`)
                .attr("value", key)
                .text(val)); 
        });

        $.validator.addMethod("remote", 
            function(value, element) {
                var type = $('#type').val();
                var result = false;
                $.ajax({
                    type:"GET",
                    async: false,
                    url: "{{route('kategori.get.kode')}}", // script to validate in server side
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
                nama_kategori : "required",
                type: "required",
            },
        });

    }
</script>
@endpush
@endsection