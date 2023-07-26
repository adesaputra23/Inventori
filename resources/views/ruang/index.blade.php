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
                                    <th>Keterangan</th>
                                    <th style="width: 15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($listData as $key => $data)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$data->kode_ruang}}</td>
                                        <td>{{$data->nama_ruang}}</td>
                                        <td class="@if ($data->ket_ruang === null) text-center @endif">{{$data->ket_ruang ?? '-'}}</td>
                                        <td class="text-center">
                                            <button id="btn-edit" data-kode="{{$data->kode_ruang}}" type="button" class="btn-edit btn btn-warning btn-sm">Edit</button>
                                            <button type="button" data-kode="{{$data->kode_ruang}}" class="btn btn-danger btn-sm text-white btn-hapus">Hapus</button>
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
    $('#zero_config').DataTable();
    $('#btn-tambah').on('click', function(e){
        var params = {
            title : 'Tambah Data Ruang',
            aksi : 'tambah', 
            data : null,
        };
        setDataformInput(params);
    });
    $('.btn-edit').on('click', function(e){
        var kode = $(this).data('kode');
        $.ajax({
            type: "get",
            url: "{{route('ruang.get.kode')}}",
            data: {
                kode: kode,
                type: 'edit',
                _token: "{{ csrf_token() }}",
            },
            dataType: "json",
            success: function (response) {
                var params = {
                    title : 'Edit Data Ruang',
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
        $('#form-modal-hapus').attr('action', "{{route('ruang.hapus.ruang')}}");
        $('#data_uniq').val(kode);
    });
    function setDataformInput(params) {
        var isData = params.data != null ? params.data : '';
        $('#add-modal').modal('show', true);
        $('#add-modal .modal-title').text(params.title);
        $('#add-modal .modal-body').empty();
        $('#form-modal').attr('action', "{{route('ruang.add.ruang')}}");
        var setHtml = `
            <input type="hidden" class="form-control" id="aksi" name="aksi" value="${params.aksi}">
            <div class="form-group row">
                <label for="fname" class="col-sm-2 control-label col-form-label">Kode</label>
                <div class="col-sm-10">
                    <input ${isData.kode_ruang == undefined ? '' :'readonly'} name="kode" type="text" class="form-control" id="kode"
                        placeholder="Masukan Kode Ruangan" value="${isData.kode_ruang ?? ''}">
                </div>
            </div>
            <div class="form-group row">
                <label for="fname" class="col-sm-2 control-label col-form-label">Nama Ruangan</label>
                <div class="col-sm-10">
                    <input name="nama_ruangan" type="text" class="form-control" id="nama_ruangan"
                        placeholder="Masukan Nama Ruangan" value="${isData.nama_ruang ?? ''}">
                </div>
            </div>
            <div class="form-group row">
                <label for="cono1" class="col-sm-2 control-label col-form-label">Keterangan</label>
                <div class="col-sm-10">
                    <textarea class="form-control" placeholder="Masukan Keterangan" id="keterangan" name="keterangan">${isData.ket_ruang ?? ''}</textarea>
                </div>
            </div>
        `;
        $('#add-modal .modal-body').html(setHtml);
        $.validator.addMethod("remote", 
            function(value, element) {
                var result = false;
                console.log(value);
                $.ajax({
                    type:"GET",
                    async: false,
                    url: "{{route('ruang.get.kode')}}", // script to validate in server side
                    data: {
                        kode: value,
                        type: 'validasi',
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
                kode: {
                    required: true,
                    remote: params.aksi == 'tambah' ? true : false,
                },
                nama_ruangan : "required",
            },
        });
    }
</script>
@endpush
@endsection