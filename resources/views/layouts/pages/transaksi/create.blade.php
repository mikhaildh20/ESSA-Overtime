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

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <h1>Tambah Pengajuan Lembur</h1>
    <form action="{{ route('pengajuan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="jenis-pengajuan">Jenis Pengajuan <span class="required">*</span></label>
            <select class="form-control" id="jenis-pengajuan" name="jenis-pengajuan" required>
                <option value="">-- Pilih Jenis Pengajuan --</option>
                @foreach($dto as $d)
                    <option value="{{ $d->jpj_id }}" 
                        {{ old('jenis-pengajuan') == $d->jpj_id ? 'selected' : '' }}>
                        {{ $d->jpj_name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group">
            <label for="keterangan">Keterangan <span class="required">*</span></label>
            
            <!-- Display error message for keterangan -->
            @error('keterangan')
                <div class="alert alert-danger small">{{ $message }}</div>
            @enderror

            <textarea name="keterangan" class="form-control" id="keterangan" rows="4" required>{{ old('keterangan') }}</textarea>
            <div id="charCount" class="form-text text-muted mt-1">0/100 karakter</div> <!-- Menampilkan jumlah karakter -->
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="bukti-pdf">Bukti Penunjang PDF <span class="required">*</span></label>
                <input type="file" class="form-control-file" id="bukti-pdf" name="bukti-pdf" required>
            </div>
            <div class="form-group col-md-6">
                <label for="bukti-excel">Bukti Penunjang Excel</label>
                <input type="file" class="form-control-file" id="bukti-excel" name="bukti-excel">
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



    <script>
        const keteranganField = document.getElementById('keterangan');
        const charCountDisplay = document.getElementById('charCount');

        // Get the initial length of the old value or empty string
        const initialCharCount = {{ old('keterangan') ? strlen(old('keterangan')) : 0 }};
        
        // Display initial character count
        charCountDisplay.textContent = `${initialCharCount}/100 karakter`;

        keteranganField.addEventListener('input', function() {
            const charCount = keteranganField.value.length;
            charCountDisplay.textContent = `${charCount}/100 karakter`;
        });
    </script>
@endsection