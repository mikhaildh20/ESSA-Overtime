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

    <h1>Ubah Pengajuan Lembur</h1>
        <form>
            <div class="form-group">
                <label for="jenis-pengajuan">Jenis Pengajuan <span class="required">*</span></label>
                <select class="form-control" id="jenis-pengajuan" required>
                    <option value="">-- Pilih Jenis Pengajuan --</option>
                </select>
            </div>
            <div class="form-group">
                <label for="keterangan">Keterangan <span class="required">*</span></label>
                <textarea class="form-control" id="keterangan" rows="4" required></textarea>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="bukti-pdf">Bukti Penunjang PDF <span class="required">*</span></label>
                    <input type="file" class="form-control-file" id="bukti-pdf" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="bukti-excel">Bukti Penunjang Excel</label>
                    <input type="file" class="form-control-file" id="bukti-excel">
                </div>
            </div>
            <div class="d-flex justify-content-start mt-3">
                <a href="{{ route('pengajuan.index') }}" class="btn btn-secondary mr-2">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>

@endsection