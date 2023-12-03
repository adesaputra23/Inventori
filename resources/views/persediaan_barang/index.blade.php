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
                                    <th>SKPD</th>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Jumlah</th>
                                    <th>Sisa</th>
                                    <th>Harga Satuan</th>
                                    <th>Total Harga</th>
                                    <th>Suplier</th>
                                    <th>Keterangan</th>
                                    @if (!User::isKepala())
                                        <th style="width: 15%">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($listPersediaan as $key => $data)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td>{{$data->skpd}}</td>
                                        <td>{{Persediaan::gnerateKode($data->id_persediaan)}}</td>
                                        <td>{{$data->nama_barang}}</td>
                                        <td class="text-center">{{$data->jumlah_masuk}}</td>
                                        <td class="text-center">{{$data->jumlah_sisa == null ? $data->jumlah_masuk : $data->jumlah_sisa }}</td>
                                        <td>{{$data->harga_satuan}}</td>
                                        <td>{{$data->jumlah_masuk * $data->harga_satuan}}</td>
                                        <td>{{$data->suplier->instansi}}</td>
                                        <td>{{$data->ket_persediaan}}</td>
                                        @if (!User::isKepala())
                                            <td class="text-center">
                                                <button id="btn-edit" data-id_persediaan="{{$data->id_persediaan}}" type="button" class="btn-edit btn btn-warning btn-sm btn-edit">Edit</button>
                                                <button type="button" data-id_persediaan="{{$data->id_persediaan}}" class="btn btn-danger btn-sm text-white btn-hapus">Hapus</button>
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
    var listKategori = {!! json_encode($listKategori) !!};
    var listSuplier = {!! json_encode($listSuplier) !!};
    const Golongan = 1;
    const Bidang = 2;
    const Kelompok = 3;
    const SubKelompok = 4;
    const SubSubKelompok = 5;
    $('#zero_config').DataTable();
    $('#btn-tambah').on('click', function(e){
        var params = {
            title : 'Tambah Data Barang Masuk',
            aksi : 'tambah', 
            data : null,
        };
        setDataformInput(params);
    });

    $('.btn-edit').on('click', function(e){
        var idPersediaan = $(this).data('id_persediaan');
        $.ajax({
            type: "get",
            url: "{{route('persediaan.get.id_persediaan')}}",
            data: {
                idPersediaan: idPersediaan,
                _token: "{{ csrf_token() }}",
            },
            dataType: "json",
            success: function (response) {
                console.log(response);
                var params = {
                    title : 'Edit Data Barang Masuk',
                    aksi : 'edit', 
                    data : response,
                };
                setDataformInput(params);
            }
        });
    });

    function setDataformInput(params) {
        var isData = params.data != null ? params.data : '';
        var getkodeBarang = isData.kodeBarang != null ? isData.kodeBarang : '';
        var splitKodeBarang = getkodeBarang.split('.');
        var persediaanBarang = isData.persediaan != null ? isData.persediaan : '';
        const date = persediaanBarang.tanggal_persediaan == undefined ? '' : moment(persediaanBarang.tanggal_persediaan).format('YYYY-MM-DD');
        const fotoBarang = isData.persediaan != null ? isData.persediaan.foto_barang : '' ;
        const splitFotoBarang = fotoBarang != undefined ? fotoBarang.split('.') : '';
        console.log(splitKodeBarang);
        $('#add-modal').modal('show', true);
        $('#add-modal .modal-title').text(params.title);
        $('#add-modal .modal-body').empty();
        $('#form-modal').attr('action', "{{route('persediaan.add.persediaan')}}");
        $('#form-modal').attr('enctype', "multipart/form-data");
        var setHtml = `
            <input type="hidden" class="form-control" id="aksi" name="aksi" value="${params.aksi}">
            <input type="hidden" class="form-control" id="id_persediaan" name="id_persediaan" value="${persediaanBarang.id_persediaan ?? ''}">
            <div class="form-group row">
                <label for="fname" class="col-sm-2 control-label col-form-label">Katgeori</label>
                <div class="col-sm-10">
                    <div class="row">
                        <div class="col-md-6">
                            <select class="form-select shadow-none" id="kode_golongan" name="kode_golongan">
                                <option value="" disabled selected>Kategori</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="kode_registrasi" name="kode_registrasi"
                                placeholder="Registrasi" value="">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-9">
                            <input readonly type="text" class="form-control" id="format_kode" name="format_kode"
                            placeholder="Format Kode Barang" value="">
                        </div>
                        <div class="col-md-3">
                            <button id="btn-gnerate-kode" type="button" class="btn btn-primary w-100">Generate Kode</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label for="fname" class="col-sm-2 control-label col-form-label">Nama</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="nama_barang" name="nama_barang"
                        placeholder="Masukan Nama Barang" value="${persediaanBarang.nama_barang ?? ''}">
                </div>
            </div>

            <div class="form-group row">
                <label for="fname" class="col-sm-2 control-label col-form-label">SKPD</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="skpd" name="skpd"
                        placeholder="Masukan SKPD" value="${persediaanBarang.skpd ?? ''}">
                </div>
            </div>

            <div class="form-group row">
                <label for="fname" class="col-sm-2 control-label col-form-label">No Surat</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="no_surat" name="no_surat"
                        placeholder="Masukan No Surat" value="${persediaanBarang.persediaan_no_surat ?? ''}">
                </div>
            </div>

            <div class="form-group row">
                <label for="fname" class="col-sm-2 control-label col-form-label">Jumlah Masuk</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" id="jumlah_masuk" name="jumlah_masuk"
                        placeholder="Masukan Jumlah Masuk Barang" value="${persediaanBarang.jumlah_masuk ?? ''}">
                </div>
            </div>

            <div class="form-group row">
                <label for="fname" class="col-sm-2 control-label col-form-label">Harga Satuan</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" id="harga_satuan" name="harga_satuan"
                        placeholder="Masukan Harga Satuan" value="${persediaanBarang.harga_satuan ?? ''}">
                </div>
            </div>

            <div class="form-group row">
                <label for="cono1" class="col-sm-2 control-label col-form-label">Keterangan</label>
                <div class="col-sm-10">
                    <textarea class="form-control" placeholder="Masukan Keterangan" id="keterangan" name="keterangan">${persediaanBarang.ket_persediaan ?? ''}</textarea>
                </div>
            </div>

            <div class="form-group row">
                <label for="fname" class="col-sm-2 control-label col-form-label">Tanggal</label>
                <div class="col-sm-10">
                    <input type="date" class="form-control" id="tanggal_persediaan" name="tanggal_persediaan"
                        placeholder="Masukan Harga Satuan" value="${date ?? ''}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-2 control-label col-form-label">Suplier</label>
                <div class="col-md-10">
                    <select name="suplier" class="form-select shadow-none" id="suplier">
                        <option value="" disabled selected>Pilih Suplier</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 control-label col-form-label">Upload Gambar</label>
                <div class="col-md-10">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input w-100" id="file_gambar" name="file_gambar" value="">
                    </div>
                </div>
            </div>

        `;
        $('#add-modal .modal-body').html(setHtml);
        $.each(listKategori, function (key,val) { 
            $('#kode_golongan')
                .append($(`<option ${splitKodeBarang[0] != undefined && splitKodeBarang[0] == val.kode ? 'selected' : ''}></option>`)
                .attr("value", val.kode)
                .text(val.kode + ' - ' + val.nama_kategori)); 
        });

        $.each(listSuplier, function (key,val) { 
            $('#suplier')
                .append($(`<option ${persediaanBarang.suplier != undefined && persediaanBarang.suplier.kode_suplier ? 'selected' : ''}></option>`)
                .attr("value", val.kode_suplier)
                .text(val.kode_suplier + ' - ' + val.instansi)); 
        });

        // <div class="col-md-2">
        //     <select class="form-select shadow-none" id="kode_bidang" name="kode_bidang">
        //         <option value="" disabled selected>Bidang</option>
        //     </select>
        // </div>
        // <div class="col-md-2">
        //     <select class="form-select shadow-none" id="kode_kelompok" name="kode_kelompok">
        //         <option value="" disabled selected>Kelompok</option>
        //     </select>
        // </div>
        // <div class="col-md-2">
        //     <select class="form-select shadow-none" id="kode_sub_kelompok" name="kode_sub_kelompok">
        //         <option value="" disabled selected>Sub Kelompok</option>
        //     </select>
        // </div>
        // <div class="col-md-2">
        //     <select class="form-select shadow-none" id="kode_sub_sub_kelompok" name="kode_sub_sub_kelompok">
        //         <option value="" disabled selected>Sub-Sub Kelompok</option>
        //     </select>
        // </div>

        // $.each(listKategori[Bidang], function (key,val) { 
        //     $('#kode_bidang')
        //         .append($(`<option ${splitKodeBarang[1] != undefined && splitKodeBarang[1] == val.kode ? 'selected' : ''}></option>`)
        //         .attr("value", val.kode)
        //         .text(val.nama_kategori)); 
        // });
        // $.each(listKategori[Kelompok], function (key,val) { 
        //     $('#kode_kelompok')
        //         .append($(`<option ${splitKodeBarang[2] != undefined && splitKodeBarang[2] == val.kode ? 'selected' : ''}></option>`)
        //         .attr("value", val.kode)
        //         .text(val.nama_kategori)); 
        // });
        // $.each(listKategori[SubKelompok], function (key,val) { 
        //     $('#kode_sub_kelompok')
        //         .append($(`<option ${splitKodeBarang[3] != undefined && splitKodeBarang[3] == val.kode ? 'selected' : ''}></option>`)
        //         .attr("value", val.kode)
        //         .text(val.nama_kategori)); 
        // });
        // $.each(listKategori[SubSubKelompok], function (key,val) { 
        //     $('#kode_sub_sub_kelompok')
        //         .append($(`<option ${splitKodeBarang[4] != undefined && splitKodeBarang[4] == val.kode ? 'selected' : ''}></option>`)
        //         .attr("value", val.kode)
        //         .text(val.nama_kategori)); 
        // });   

        $('#format_kode').val(getkodeBarang);
        $('#kode_registrasi').val(splitKodeBarang[1] ?? '');
        $('#btn-gnerate-kode').on('click', function(e){
            // var kode_golongan = $('#kode_golongan').val();
            // var kode_bidang = $('#kode_bidang').val();
            // var kode_kelompok = $('#kode_kelompok').val();
            // var kode_sub_kelompok = $('#kode_sub_kelompok').val();
            // var kode_sub_sub_kelompok = $('#kode_sub_sub_kelompok').val();
            // var kode_registrasi = $('#kode_registrasi').val();
            // if (kode_golongan == null || kode_bidang == null || kode_kelompok == null || kode_sub_kelompok == null || kode_sub_sub_kelompok == null || kode_registrasi == '') {
            //     alert('Harap lengkapi inputan kode!');
            //     return false;
            // }

            var kode_golongan = $('#kode_golongan').val();;
            var kode_registrasi = $('#kode_registrasi').val();
            if (kode_golongan == null || kode_registrasi == '') {
                alert('Harap lengkapi inputan kode!');
                return false;
            }

            // var kode_gnerate = kode_golongan + '.' + kode_bidang + '.' + kode_kelompok + '.' + kode_sub_kelompok + '.' + kode_sub_sub_kelompok + '.' + kode_registrasi;
            var kode_gnerate = kode_golongan + '.' + kode_registrasi;
            $('#format_kode').val(kode_gnerate);
        });

        $("#form-modal").validate({
            rules:{
                file_gambar: {
                    extension: "jpg|png|jpeg"
                },
                format_kode: "required",
                nama_barang: "required",
                format_kode: "required",
                skpd: "required",
                no_surat: "required",
                jumlah_masuk: "required",
                harga_satuan: "required",
                keterangan: "required",
                tanggal_persedaian: "required",
            },
        });

        if (params.aksi === "edit" && splitFotoBarang !== '') {
            // Get a reference to our file input
            const fileInput = document.querySelector('input[type="file"]');
            // Create a new File object
            const myFile = new File([fotoBarang], fotoBarang, {
                type: splitFotoBarang[splitFotoBarang.length - 1],
                lastModified: new Date(),
            });
            // Now let's create a DataTransfer to get a FileList
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(myFile);
            fileInput.files = dataTransfer.files;
        }

    }

    $('.btn-hapus').on('click', function(e){
        var id_persediaan = $(this).data('id_persediaan');
        $('#modal-hapus').modal('show', true);
        $('#form-modal-hapus').attr('action', "{{route('persediaan.hapus.persediaan')}}");
        $('#data_uniq').val(id_persediaan);
    });

</script>
@endpush
@endsection