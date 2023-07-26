<form id="form-filter" class="row g-3 mb-4" method="GET">
    @csrf
    <div class="col-auto">
        <label for="tanggal_start" class="form-label">Tanggal Mulai</label>
        <input type="date" class="form-control" id="tanggal_start" name="tanggal_start" placeholder="Tanggal Mulai" value="{{Request::get('tanggal_start')}}">
    </div>
    <div class="col-auto">
        <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
        <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" placeholder="Tanggal Akhir" value="{{Request::get('tanggal_akhir')}}">
    </div>
    <div class="col-auto">
        <button type="submit" id="btn-filter" class="btn btn-success mb-3" style="margin-top: 28px" name="filter" value="filter">Filter</button>
        <button type="submit" id="btn-cetak-pdf" class="btn btn-primary mb-3" style="margin-top: 28px" formtarget="_blank" name="cetakPdf" value="cetakPdf">Cetak PDF</button>
    </div>
</form>