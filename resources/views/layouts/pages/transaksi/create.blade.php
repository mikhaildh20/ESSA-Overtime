@extends('layouts.app')

@section('content')
    <style>
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .form-group .required {
            color: red;
        }
        .buttons {
            display: flex;
            justify-content: flex-start;
        }
        .buttons button {
            margin-right: 10px;
        }
        .form-control-file {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            width: 100%;
        }
    </style>

    <h1>Tambah Pengajuan Lembur</h1>
    <form action="{{ route('pengajuan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="jen_id">Jenis Pengajuan <span class="required">*</span></label>
            <select class="form-control" id="jen_id" name="jen_id" required>
                <option value="" >-- Pilih Jenis Pengajuan --</option>
                @foreach($jenisPengajuan as $jenis)
                    <option value="{{ $jenis->jen_id }}" id="jen_id" name="jen_id">{{ $jenis->jen_nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="pjn_deskripsi">Keterangan <span class="required">*</span></label>
            <textarea class="form-control" id="pjn_deskripsi" name="pjn_deskripsi" rows="4" required></textarea>
        </div>
        <div class="form-group">
            <label for="kry_id">Karyawan <span class="required">*</span></label>
            <select class="form-control" id="kry_id" name="kry_id" required>
                <option value="">-- Pilih Karyawan --</option>
                @foreach($karyawan as $karyawanItem)
                    <option value="{{ $karyawanItem->kry_id }}" id="kry_id" name="kry_id">{{ $karyawanItem->kry_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="pjn_pdf_proof">Bukti Penunjang PDF <span class="required">*</span></label>
                <input type="file" class="form-control-file" id="pjn_pdf_proof" name="pjn_pdf_proof" accept=".pdf" required>
            </div>
            <div class="form-group col-md-6">
                <label for="pjn_excel_proof">Bukti Penunjang Excel</label>
                <input type="file" class="form-control-file" id="pjn_excel_proof" accept=".xlsx" name="pjn_excel_proof">
            </div>
        </div>
        <div class="buttons mt-3">
            <button type="button" class="btn btn-secondary">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
@endsection
