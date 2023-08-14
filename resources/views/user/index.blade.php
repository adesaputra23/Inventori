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
                                    <th style="width: 20%">Aksi</th>
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
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <button {{$disabled}} data-nik={{$data->nik}} id="btn-edit" type="button" class="btn-edit btn btn-warning btn-sm">Edit</button>
                                                <button {{$disabled}} data-nik={{$data->nik}} type="button" class="btn btn-danger btn-sm text-white btn-hapus">Hapus</button>
                                                <button data-nik={{$data->nik}} type="button" class="btn btn-success btn-sm text-white btn-ubah-password">Edit Pass</button>
                                            </div>
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

{{-- Modal Hapus --}}
<div class="modal" id="modal-ubah-pass" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Ubah Password</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="form-modal-ubah-pass" action="{{ route('user.ubah.password') }}" method="post">
            @csrf
            <div class="modal-body">
                <input name="nik_ubah_pass" type="hidden" class="form-control" id="nik_ubah_pass">
                <div class="form-group row">
                    <label for="fname" class="col-sm-3 control-label col-form-label">Password</label>
                    <div class="col-sm-9">
                        <input name="pass" type="password" class="form-control" id="pass"
                            placeholder="Masukan Password">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="fname" class="col-sm-3 control-label col-form-label">Konnfirmasi Password</label>
                    <div class="col-sm-9">
                        <input name="konfirmasiPass" type="password" class="form-control" id="konfirmasiPass"
                            placeholder="Masukan Konfirmasi Password">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </form>
        </div>
    </div>
</div>

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

    $('.btn-ubah-password').on('click', function(e){
        $('#modal-ubah-pass').modal('show', true);
        var nikUbahPass = $(this).data('nik');
        $('#nik_ubah_pass').val(nikUbahPass);
        $("#form-modal-ubah-pass").validate({
            rules: {
                pass: {
                    required: true,
                },
                konfirmasiPass: {
                    required: true,
                    equalTo: "#pass"
                }
            },
            messages: {
                pass: {
                    required: "Password tidak boleh kosong!",
                },
                konfirmasiPass: {
                    required: "Konfirmasi password tidak boleh kosong!",
                    equalTo: "Konfirmasi password salah!",
                }
            }
        });
    });
</script>
@endpush
@endsection