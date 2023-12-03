@extends('layouts_v2.master_page')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data {{ $title }}</h5>
                    <hr>
                    @include('alert_message.alert')
                    <button type="button" id="btn-tambah" class="btn btn-warning btn-sm mb-3">Tambah Data</button>
                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Kode Suplier</th>
                                    <th>Nama Suplier</th>
                                    <th>Instansi</th>
                                    <th>No Telpon</th>
                                    <th>Alamat</th>
                                    <th>Tanggal Buat</th>
                                    <th style="width: 15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($listData as $key => $data)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $data->kode_suplier }}</td>
                                        <td>{{ $data->nama_suplier }}</td>
                                        <td>{{ $data->instansi }}</td>
                                        <td>{{ $data->no_telpon }}</td>
                                        <td>{{ $data->alamat }}</td>
                                        <td>{{ $data->created_at }}</td>
                                        <td class="text-center">
                                            <button id="btn-edit" data-kode="{{ $data->kode_suplier }}" type="button"
                                                class="btn-edit btn btn-warning btn-sm">Edit</button>
                                            <button type="button" data-kode="{{ $data->kode_suplier }}"
                                                class="btn btn-danger btn-sm text-white btn-hapus">Hapus</button>
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
            $('#btn-tambah').on('click', function(e) {
                var params = {
                    title: 'Tambah Data Suplier',
                    aksi: 'tambah',
                    data: null,
                };
                setDataformInput(params);
            });
            $('.btn-edit').on('click', function(e) {
                var kode = $(this).data('kode');
                $.ajax({
                    type: "get",
                    url: "{{ route('suplier.get.kode') }}",
                    data: {
                        kode: kode,
                        type: 'edit',
                        _token: "{{ csrf_token() }}",
                    },
                    dataType: "json",
                    success: function(response) {
                        var params = {
                            title: 'Edit Data Ruang',
                            aksi: 'edit',
                            data: response,
                        };
                        setDataformInput(params);
                    }
                });
            });

            $('.btn-hapus').on('click', function(e) {
                var kode = $(this).data('kode');
                $('#modal-hapus').modal('show', true);
                $('#form-modal-hapus').attr('action', "{{ route('suplier.hapus.data') }}");
                $('#data_uniq').val(kode);
            });

            function setDataformInput(params) {
                console.log(params);
                var isData = params.data != null ? params.data : '';
                $('#add-modal').modal('show', true);
                $('#add-modal .modal-title').text(params.title);
                $('#add-modal .modal-body').empty();
                $('#form-modal').attr('action', "{{ route('suplier.add.data') }}");
                var setHtml = `
                    <input type="hidden" class="form-control" id="aksi" name="aksi" value="${params.aksi}">
                    <div class="form-group row">
                        <label for="fname" class="col-sm-2 control-label col-form-label">Kode Suplier</label>
                        <div class="col-sm-10">
                            <input ${isData.kode_suplier == undefined ? '' :'readonly'} name="kode_suplier" type="text" class="form-control" id="kode_suplier"
                                placeholder="Masukan Kode Suplier" value="${isData.kode_suplier ?? ''}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fname" class="col-sm-2 control-label col-form-label">Nama Suplier</label>
                        <div class="col-sm-10">
                            <input name="nama_suplier" type="text" class="form-control" id="nama_suplier"
                                placeholder="Masukan Nama Suplier" value="${isData.nama_suplier ?? ''}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fname" class="col-sm-2 control-label col-form-label">Nama Instansi</label>
                        <div class="col-sm-10">
                            <input name="nama_instansi" type="text" class="form-control" id="nama_instansi"
                                placeholder="Masukan Nama Instansi" value="${isData.instansi ?? ''}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="fname" class="col-sm-2 control-label col-form-label">No Telpon</label>
                        <div class="col-sm-10">
                            <input name="no_telpon" type="number" class="form-control" id="no_telpon"
                                placeholder="Masukan No Telpon" value="${isData.no_telpon ?? ''}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="cono1" class="col-sm-2 control-label col-form-label">Alamat</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" placeholder="Masukan Alamat" id="alamat" name="alamat">${isData.alamat ?? ''}</textarea>
                        </div>
                    </div>
                `;
                $('#add-modal .modal-body').html(setHtml);
                $.validator.addMethod("remote",
                    function(value, element) {
                        var result = false;
                        $.ajax({
                            type: "GET",
                            async: false,
                            url: "{{ route('suplier.get.kode') }}", // script to validate in server side
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
                    rules: {
                        kode_suplier: {
                            required: true,
                            remote: params.aksi == 'tambah' ? true : false,
                        },
                        nama_suplier: "required",
                        nama_instansi: "required",
                        no_telpon: "required",
                        alamat: "required",
                    },
                });
            }
        </script>
    @endpush
@endsection
