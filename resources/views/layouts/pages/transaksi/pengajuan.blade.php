@extends ('layouts.app')

@section('content')
<style>
        .search-container {
            margin-bottom: 20px;
        }
    </style>

    <!-- Container -->
    <div class="container-fluid my-5">
        <h1 class="mb-4">Pengajuan Lembur</h1>

        @if(session('role') == 2)
            <!-- Export Excel Button -->
            <a href="" class="btn btn-success mb-3">
                <i class="fas fa-file-excel"></i> Ekspor Excel
            </a>
        
            <!-- Export PDF Button -->
            <a href="{{ route('pengajuan.pdf') }}" class="btn btn-danger mb-3">
                <i class="fas fa-file-pdf"></i> Ekspor PDF
            </a>
        @endif


        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <!-- Search and Filter -->
        <div class="search-container">
            <form action="{{ route('pengajuan.index') }}" method="GET">
                <div class="input-group">
                @if(session('role') == 1)
                    <!-- Create Button -->
                    <a href="{{ route('pengajuan.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Tambah
                    </a>
                @endif
                    <input type="text" id="searchInput" class="form-control" placeholder="Pencarian" name="search" value="{{ request()->input('search') }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <ul class="dropdown-menu" style="padding-left: 10px; padding-right: 10px;">
                        <li>
                            <label for="sort" style="margin-bottom: 5px; font-weight: bold;">Urutkan</label>
                            <select name="sort" id="sort" class="form-select" style="width: 100%; padding-left: 10px; padding-right: 10px;">
                                <option value="">-- Pilih Urutan --</option>
                                <option value="asc" {{ $sort === 'asc' ? 'selected' : '' }}>A-Z [↑]</option>
                                <option value="desc" {{ $sort === 'desc' ? 'selected' : '' }}>Z-A [↓]</option>
                            </select>
                        </li>
                        @php
                            $options = [];
                            if (session('role') == '2') {
                                $options = [
                                    ['value' => '2', 'label' => 'Pending'],
                                    ['value' => '3', 'label' => 'Terverifikasi'],
                                    ['value' => '4', 'label' => 'Ditolak'],
                                ];
                            } else {
                                $options = [
                                    ['value' => '1', 'label' => 'Draft'],
                                    ['value' => '2', 'label' => 'Menunggu Approval'],
                                    ['value' => '3', 'label' => 'Terverifikasi'],
                                    ['value' => '4', 'label' => 'Ditolak'],
                                ];
                            }
                        @endphp

                        <li>
                            <label for="sort-status" class="form-label" style="font-weight: bold;">Status</label>
                            <select id="sort-status" name="sort-status" class="form-select">
                                <option value="">-- Pilih Status --</option>
                                @foreach ($options as $option)
                                    <option value="{{ $option['value'] }}" {{ $sortStatus === $option['value'] ? 'selected' : '' }}>
                                        {{ $option['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </li>
                    </ul>   
                </div>
            </form>
        </div>

        <!-- Table -->
        <table class="table table-bordered table-striped" id="jabatanTable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>ID Karyawan </th>
                    <th>Nama</th>
                    <th>Jenis Pengajuan</th>
                    <th>Tanggal Buat</th>
                    <th>Status</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($dto as $d)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $d->kry_id_alternative }}</td>
                    <td>{{ $d->kry_name }}</td>
                    <td>{{ $d->jpj_name }}</td>
                    <td>{{ $d->pjn_tanggal ?? 'Belum diajukan' }}</td>
                    <td>
                    @if ($d->pjn_status === '1' && session('role') == 1)
                        Draft
                    @elseif ($d->pjn_status === '2')
                        {{ session('role') == 1 ? 'Menunggu Approval HRD' : 'Pending' }}
                    @elseif ($d->pjn_status === '3')
                        {{ session('role') == 1 ? 'Terverifikasi HRD' : 'Terverifikasi' }}
                    @elseif ($d->pjn_status === '4')
                        Ditolak
                    @endif
                    </td>
                    <td>
                        @if($d->pjn_status == '1' && session('role') == 1)    
                        <form id="kirimForm" action="{{ route('pengajuan.update_status', $d->pjn_id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PUT')
                            <button type="button" class="btn btn-link btn-sm" title="Kirim" data-toggle="modal" data-target="#confirmKirimModal">
                                <i class="fa fa-paper-plane"></i>
                            </button>
                        </form>
                        <a href="{{ route('pengajuan.edit', $d->pjn_id) }}" class="btn btn-link btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                        <form id="hapusForm" action="{{ route('pengajuan.destroy', $d->pjn_id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-link btn-sm" title="Hapus" data-toggle="modal" data-target="#confirmHapusModal">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                        @elseif($d->pjn_status == '2' && session('role') == 2)
                            <button type="button" class="btn btn-link btn-sm" title="Approve" data-bs-toggle="modal" 
                            data-bs-target="#confirmModal" 
                            data-action="{{ route('pengajuan.update_status', ['id' => $d->pjn_id, 'decision' => 3]) }}" 
                            data-decision="3">
                                <i class="fa fa-check"></i>
                            </button>
                            <button type="button" class="btn btn-link btn-sm" title="Tolak" data-bs-toggle="modal" 
                            data-bs-target="#confirmModal" 
                            data-action="{{ route('pengajuan.update_status', ['id' => $d->pjn_id, 'decision' => 4]) }}" 
                            data-decision="4">
                                <i class="fa fa-times"></i>
                            </button>
                        @endif
                        <a href="{{ route('pengajuan.show', $d->pjn_id) }}" class="btn btn-link btn-sm" title="Detail"><i class="fa fa-bars"></i></a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada data.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $pagination->links('vendor.pagination.bootstrap-5') }}
        </div>
    </div>

    @if(session('role') == 1)
        <!-- konfirmasi kirim -->
        <div class="modal fade" id="confirmKirimModal" tabindex="-1" aria-labelledby="confirmKirimModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmKirimModalLabel">Konfirmasi Kirim</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin mengirim pengajuan ini?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" id="confirmKirimBtn">Kirim</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- konfirmasi hapus -->
        <div class="modal fade" id="confirmHapusModal" tabindex="-1" aria-labelledby="confirmHapusModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmHapusModalLabel">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin menghapus pengajuan ini?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger" id="confirmHapusBtn">Hapus</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.getElementById('confirmKirimBtn').addEventListener('click', function() {
                // Submit the form after confirmation
                document.getElementById('kirimForm').submit();
            });

            document.getElementById('confirmHapusBtn').addEventListener('click', function() {
                // Submit the form after confirmation
                document.getElementById('hapusForm').submit();
            });
        </script>
    @endif

    @if(session('role') == 2)
        <!-- feedback -->
        <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmModalLabel">Tambahkan catatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="approvalForm" method="POST">
                        <div class="modal-body">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="decision" id="decision">
                            <label for="review" class="form-label" id="reviewLabel">Catatan untuk diterima</label>
                            <textarea class="form-control" name="review" id="review" rows="5" placeholder="Masukkan catatan disini.."></textarea>
                            <div id="charCount" class="form-text text-muted mt-1">0/10 karakter</div> <!-- Menampilkan jumlah karakter -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="confirmKirimBtn">Kirim</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            const modal = document.getElementById('confirmModal');
            modal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const action = button.getAttribute('data-action');
                const decision = button.getAttribute('data-decision');

                const form = document.getElementById('approvalForm');
                form.action = action;

                const input = document.getElementById('decision');
                input.value = decision;

                const message = document.getElementById('reviewLabel');
                if (decision === '3') {
                    message.textContent = 'Catatan untuk diterima (opsional):';
                } else {
                    message.textContent = 'Catatan untuk ditolak:';
                }
            });

            modal.addEventListener('hide.bs.modal', function(event) {
                const text = document.getElementById('review');
                text.value = '';
            });

            const keteranganField = document.getElementById('review');
            const charCountDisplay = document.getElementById('charCount');

            // Get the initial length of the old value or empty string
            const initialCharCount = {{ old('review') ? strlen(old('review')) : 0 }};

            // Display initial character count
            charCountDisplay.textContent = `${initialCharCount}/10 karakter`;

            keteranganField.addEventListener('input', function (event) {
                const charCount = keteranganField.value.length;

                // Prevent input if character limit is exceeded
                if (charCount > 100) {
                    // Check if the key pressed is not Backspace (key code 8)
                    const isBackspace = event.inputType === 'deleteContentBackward';
                    if (!isBackspace) {
                        // Remove extra characters
                        keteranganField.value = keteranganField.value.substring(0, 100);
                    }
                }

                // Update character count display
                const updatedCharCount = keteranganField.value.length; // Ensure accurate count
                charCountDisplay.textContent = `${updatedCharCount}/10 karakter`;
            });
        </script>
    @endif
@endsection