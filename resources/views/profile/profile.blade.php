@php
    $foto = '../../assets/images/users/1.jpg';
    if ($user->images !== null) $foto = asset('img_profile/'.$user->images);
    $pegawai = $user->pegawai;
    if (empty($pegawai)) $pegawai = '';
@endphp
@extends('layouts_v2.master_page')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{$user->nik}} - {{$user->name}}</h5>
                    <hr>
                    @include('alert_message.alert')
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card shadow-sm p-3 mb-5 bg-body rounded" style="width: 16rem;">
                                <div class="card-body">
                                    <div class="text-center">
                                        <img src="{{$foto}}" alt="user" class="rounded" width="150">
                                        <button data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-warning btn-sm mt-2">Ubah Gambar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="card shadow-sm p-3 mb-5 bg-body rounded">
                                <form action="{{ route('update.profile', ['nik' => $user->nik]) }}" method="post">
                                    @csrf
                                    <div class="card-body">
                                        <h5 class="card-title">My Profile</h5>
                                        <hr>
                                        <div class="form-group row">
                                            <label for="fname" class="col-sm text-end control-label col-form-label">User Name</label>
                                            <div class="col-sm-9">
                                                <input value="{{$user->name}}" type="text" class="form-control" id="userName" name="userName" placeholder="User Name">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="fname" class="col-sm text-end control-label col-form-label">NIK</label>
                                            <div class="col-sm-9">
                                                <input value="{{$user->nik}}" type="text" class="form-control" id="nik" name="nik" placeholder="NIK" readonly style="background-color:#cdcdcd">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="fname" class="col-sm text-end control-label col-form-label">Nama Pegawai</label>
                                            <div class="col-sm-9">
                                                <input value="{{$pegawai->nama_pegawai ?? ''}}" type="text" class="form-control" id="nama" name="nama" placeholder="Nama Pegawai">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="fname" class="col-sm text-end control-label col-form-label">Email</label>
                                            <div class="col-sm-9">
                                                <input value="{{$user->email}}" type="text" class="form-control" id="email" name="email" placeholder="Email">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="fname" class="col-sm text-end control-label col-form-label">Jenis Kelamin</label>
                                            <div class="col-sm-9">
                                                <select class="form-select" aria-label="Default select example" id="jenis_kelamin" name="jenis_kelamin">
                                                    <option value="{{NULL}}" @if (!empty($pegawai) && $pegawai->jenis_kelamin == NULL) selected @endif>Pilih Jenis Kelamin</option>
                                                    <option value="1" @if (!empty($pegawai) && $pegawai->jenis_kelamin === 1) selected @endif>Laki-Laki</option>
                                                    <option value="2" @if (!empty($pegawai) && $pegawai->jenis_kelamin == 2) selected @endif>Perempuan</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="fname" class="col-sm text-end control-label col-form-label">Tempat Lahir</label>
                                            <div class="col-sm-9">
                                                <input value="{{$pegawai->tempat_lahir ?? ''}}" type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" placeholder="Tempat Lahir">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="fname" class="col-sm text-end control-label col-form-label">Tanggal Lahir</label>
                                            <div class="col-sm-9">
                                                <input value="{{$pegawai->tanggal_lahir ?? ''}}" type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" placeholder="Tanggal Lahir">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="fname" class="col-sm text-end control-label col-form-label">Pendidikan Terakhir</label>
                                            <div class="col-sm-9">
                                                <input value="{{$pegawai->pendidikan_terakhir ?? ''}}" type="text" class="form-control" id="pend_terakhir" name="pend_terakhir" placeholder="Pendidikan Terakhir">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="cono1"
                                                class="col-sm text-end control-label col-form-label">Alamat</label>
                                            <div class="col-sm-9">
                                                <textarea name="alamat" id="alamat" class="form-control" placeholder="Alamat">{{$pegawai->alamat ?? ''}}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="cono1"
                                                class="col-sm text-end control-label col-form-label">No Telpon</label>
                                            <div class="col-sm-9">
                                                <input value="{{$pegawai->no_telpon ?? ''}}" type="number" name="no_tlpn" id="no_tlpn" class="form-control" placeholder="No Telpon">
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-success mt-2 btn-sm float-end">Ubah Data</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Upload Images -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Upload Gambar</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('upload.gambar', ['nik'=>$user->nik]) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="col-md-9">
                        <div class="custom-file">
                            <input name="file" id="file" type="file" class="custom-file-input" required>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
        </div>
    </div>
    @push('costum-js')
        <script>
            var foto = "{!! $user->images !!}";
            if (foto.length !== 0) {
                var splitFoto = foto.split('.');
                const fileInput = document.querySelector('input[type="file"]');
                const myFile = new File([foto], foto, {
                    type: splitFoto[splitFoto.length - 1],
                    lastModified: new Date(),
                });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(myFile);
                fileInput.files = dataTransfer.files;
            }
        </script>
    @endpush
@endsection