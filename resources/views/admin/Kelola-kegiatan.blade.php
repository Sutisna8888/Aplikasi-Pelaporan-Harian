@extends('layouts.master')

@section('title', 'Kelola Kegiatan - ALPHA')
@section('header_title', 'Kelola Kegiatan')

@section('content')
    <style>
        .page-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .btn-tambah {
            background: #374151; /* Dark Gray */
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s;
            width: fit-content;
        }

        .btn-tambah:hover {
            background: #1f2937;
        }

        .kegiatan-panel {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .panel-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #374151;
            margin-bottom: 20px;
        }

        .table-responsive {
            overflow-x: auto;
            border-radius: 8px;
        }

        .table-kegiatan {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;

        }

        .table-kegiatan th,
        .table-kegiatan td {
            border: 1px solid #e5e7eb;
            padding: 12px 15px;
            text-align: center;
            color: #6b7280;
            font-size: 0.95rem;
        }

        .table-kegiatan th {
            background-color: #374151; /* Match the design dark header */
            color: #ffffff;
            font-weight: 600;
            border-color: #374151;
        }

        .table-kegiatan td:nth-child(2) {
            text-align: left;
        }

        .action-icons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .icon-edit {
            color: #2563eb;
            cursor: pointer;
            font-size: 1.3rem;
            transition: opacity 0.2s;
        }

        .icon-edit:hover {
            opacity: 0.8;
        }

        .icon-delete {
            color: #dc2626;
            cursor: pointer;
            font-size: 1.3rem;
            transition: opacity 0.2s;
        }

        .icon-delete:hover {
            opacity: 0.8;
        }

        /* Modal Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(2px);
        }

        .modal-content {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 15px;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin: 0;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #9ca3af;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #4b5563;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.95rem;
            outline: none;
        }

        .form-group input:focus {
            border-color: #3b82f6;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 25px;
        }

        .btn-cancel {
            padding: 10px 15px;
            background: #f3f4f6;
            color: #4b5563;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: background 0.2s, color 0.2s, transform 0.2s;
        }

        .btn-cancel:hover {
            background: #e5e7eb;
            color: #374151;
            transform: translateY(-1px);
        }

        .btn-save {
            padding: 10px 15px;
            background: #374151;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: background 0.2s, transform 0.2s;
        }

        .btn-save:hover {
            background: #1f2937;
            transform: translateY(-1px);
        }
    </style>

    <div class="page-container">

        @if ($errors->any())
            <div id="error-alert"
                style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; font-weight: 500;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Card Daftar Kegiatan -->
        <div class="kegiatan-panel">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 class="panel-title" style="margin-bottom: 0;">Daftar Kegiatan</h2>
                <button class="btn-tambah" onclick="openModalTambah()">
                    <i class="fas fa-plus"></i> Tambah Kegiatan
                </button>
            </div>

            <div class="table-responsive">
                <table class="table-kegiatan">
                    <thead>
                        <tr>
                            <th style="width: 80px;">No</th>
                            <th>Nama Kegiatan</th>
                            <th style="width: 120px;">Status</th>
                            <th style="width: 120px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kegiatans as $index => $kegiatan)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $kegiatan->nama_kegiatan }}</td>
                                <td>
                                    @if($kegiatan->is_active)
                                        <span style="background-color: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 20px; font-size: 0.85rem; font-weight: 500;">Aktif</span>
                                    @else
                                        <span style="background-color: #f3f4f6; color: #4b5563; padding: 4px 10px; border-radius: 20px; font-size: 0.85rem; font-weight: 500;">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-icons">
                                        <a href="javascript:void(0)" onclick='openModalEdit(@json($kegiatan))' class="icon-edit"
                                            title="Edit"><i class="fas fa-edit"></i></a>
                                        <form id="form-toggle-{{ $kegiatan->id }}"
                                            action="{{ route('admin.kegiatan.toggle', $kegiatan->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('PUT')
                                            <button type="button" class="icon-delete" title="{{ $kegiatan->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                style="background: none; border: none; padding: 0; color: {{ $kegiatan->is_active ? '#dc2626' : '#10b981' }};"
                                                onclick="panggilModalKonfirmasi('Ubah Status Kegiatan?', 'Apakah Anda yakin ingin {{ $kegiatan->is_active ? 'menonaktifkan' : 'mengaktifkan' }} kegiatan ini?', 'fas fa-sync-alt', '#3b82f6', 'Ya, Ubah Status', function() { document.getElementById('form-toggle-{{ $kegiatan->id }}').submit(); })">
                                                <i class="fas {{ $kegiatan->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 30px; font-style: italic;">
                                    Belum ada kegiatan yang terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Kegiatan -->
    <div id="modalTambahKegiatan" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Tambah Kegiatan</h3>
                <button class="close-modal" onclick="closeModalTambah()">&times;</button>
            </div>
            <form action="{{ route('admin.kegiatan.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nama_kegiatan">Nama Kegiatan</label>
                    <input type="text" name="nama_kegiatan" id="nama_kegiatan" placeholder="Masukkan nama kegiatan" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeModalTambah()">Batal</button>
                    <button type="submit" class="btn-save">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Kegiatan -->
    <div id="modalEditKegiatan" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Edit Kegiatan</h3>
                <button class="close-modal" onclick="closeModalEdit()">&times;</button>
            </div>
            <form id="formEditKegiatan" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="edit_nama_kegiatan">Nama Kegiatan</label>
                    <input type="text" name="nama_kegiatan" id="edit_nama_kegiatan" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeModalEdit()">Batal</button>
                    <button type="submit" class="btn-save">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModalTambah() {
            document.getElementById('modalTambahKegiatan').style.display = 'flex';
        }

        function closeModalTambah() {
            document.getElementById('modalTambahKegiatan').style.display = 'none';
        }

        function openModalEdit(kegiatan) {
            var modal = document.getElementById('modalEditKegiatan');
            var form = document.getElementById('formEditKegiatan');
            var inputNama = document.getElementById('edit_nama_kegiatan');

            // Set action URL untuk form edit
            form.action = `/admin/kegiatan/${kegiatan.id}`;
            
            // Isi nilai input dengan data kegiatan saat ini
            inputNama.value = kegiatan.nama_kegiatan;

            modal.style.display = 'flex';
        }

        function closeModalEdit() {
            document.getElementById('modalEditKegiatan').style.display = 'none';
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            var modalTambah = document.getElementById('modalTambahKegiatan');
            var modalEdit = document.getElementById('modalEditKegiatan');
            if (event.target == modalTambah) {
                closeModalTambah();
            }
            if (event.target == modalEdit) {
                closeModalEdit();
            }
        }
    </script>
@endsection
