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
                                    <th>Nik</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role Level</th>
                                    <th>Tanggal Buat</th>
                                    <th style="width: 15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($listData as $item => $data)
                                    <tr>
                                        <td>{{$item+1}}</td>
                                        <td>{{$data->nik}}</td>
                                        <td>{{$data->name}}</td>
                                        <td>{{$data->email}}</td>
                                        <td class="text-center"><span class="badge bg-primary">{{$roleMap[$data->role->role]}}</span></td>
                                        <td>{{$data->created_at}}</td>
                                        <td class="text-center">
                                            @php
                                                $disabled = '';
                                                if ($data->role->role === 1)$disabled = "disabled";
                                            @endphp
                                            <button {{$disabled}} data-nik={{$data->nik}} id="btn-edit" type="button" class="btn-edit btn btn-warning btn-sm">Edit</button>
                                            <button {{$disabled}} data-nik={{$data->nik}} type="button" class="btn btn-danger btn-sm text-white btn-hapus">Hapus</button>
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
    var roleMap = {!! json_encode($roleMap) !!};
    $('#zero_config').DataTable();
    $('#btn-tambah').on('click', function(e){
        var params = {
            title : 'Tambah Data User',
            aksi : 'tambah', 
            data : null,
        };
        setDataformInput(params);
    });
    $('.btn-edit').on('click', function(e){
        var nik = $(this).data('nik');
        $.ajax({
            type: "get",
            url: "{{route('user.get.nik')}}",
            data: {
                nik: nik,
                _token: "{{ csrf_token() }}",
            },
            dataType: "json",
            success: function (response) {
                var params = {
                    title : 'Edit Data User',
                    aksi : 'edit', 
                    data : response,
                };
                setDataformInput(params);
            }
        });
    });

    $('.btn-hapus').on('click', function(e){
        var nik = $(this).data('nik');
        $('#modal-hapus').modal('show', true);
        $('#form-modal-hapus').attr('action', "{{route('user.hapus.user')}}");
        $('#data_uniq').val(nik);
    });

    function setDataformInput(params) {
        var isData = params.data != null ? params.data : '';
        $('#add-modal').modal('show', true);
        $('#add-modal .modal-title').text(params.title);
        $('#add-modal .modal-body').empty();
        $('#form-modal').attr('action', "{{route('user.add.user')}}");
        var setHtml = `
            <input type="hidden" class="form-control" id="aksi" name="aksi" value="${params.aksi}">
            <div class="form-group row">
                <label for="fname" class="col-sm-2 control-label col-form-label">Nik</label>
                <div class="col-sm-10">
                    <input ${isData.nik == undefined ? '' :'readonly'} name="nik" type="number" class="form-control" id="nik"
                        placeholder="Masukan Nik" value="${isData.nik ?? ''}">
                </div>
            </div>
            <div class="form-group row">
                <label for="fname" class="col-sm-2 control-label col-form-label">Nama Lengkap</label>
                <div class="col-sm-10">
                    <input name="nama_lengkap" type="text" class="form-control" id="nama_lengkap"
                        placeholder="Masukan Nama Lengkap" value="${isData.name ?? ''}">
                </div>
            </div>
            <div class="form-group row">
                <label for="fname" class="col-sm-2 control-label col-form-label">Email</label>
                <div class="col-sm-10">
                    <input name="email" type="email" class="form-control" id="email"
                        placeholder="Masukan Email" value="${isData.email ?? ''}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2 control-label col-form-label">Level Role</label>
                <div class="col-md-10">
                    <select name="role_level" class="form-select shadow-none" id="role_level">
                        <option value="" disabled selected>Pilih Level Role</option>
                    </select>
                </div>
            </div>
        `;
        $('#add-modal .modal-body').html(setHtml);
        $.each(roleMap, function (key,val) { 
            $('#role_level')
                .append($(`<option ${isData.role != undefined && isData.role.role == key ? 'selected' : ''} ></option>`)
                .attr("value", key)
                .text(val)); 
        });
        $.validator.addMethod("remote", 
            function(value, element) {
                var result = false;
                $.ajax({
                    type:"POST",
                    async: false,
                    url: "{{route('user.uniq.nik')}}", // script to validate in server side
                    data: {
                        nik: value,
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        result = (data == true) ? true : false;
                    }
                });
                // return true if username is exist in database
                return result; 
            }, 
            "This nik is already taken! Try another."
        );
        $("#form-modal").validate({
            rules:{
                nik: {
                    required: true,
                    remote: params.aksi == 'tambah' ? true : false,
                },
                nama_lengkap : "required",
                email: "required",
                role_level: "required",
            },
        });
    }
</script>
@endpush
@endsection