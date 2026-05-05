@extends('layouts.master')

@section('title', 'Info BPS Kota Sukabumi - ALPHA')
@section('header_title', 'Info BPS Kota Sukabumi')

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

        .info-panel {
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

        .table-info {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }

        .table-info th,
        .table-info td {
            border: 1px solid #e5e7eb;
            padding: 12px 15px;
            text-align: center;
            color: #6b7280;
            font-size: 0.95rem;
            vertical-align: middle;
        }

        .table-info th {
            background-color: #374151;
            color: #ffffff;
            font-weight: 600;
            border-color: #374151;
        }

        .icon-image {
            color: #6b7280;
            font-size: 1.5rem;
        }

        .icon-delete {
            color: #dc2626;
            cursor: pointer;
            font-size: 1.2rem;
            transition: opacity 0.2s;
            background: none;
            border: none;
            padding: 0;
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
        }

        .btn-save {
            padding: 10px 15px;
            background: #374151;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
        }
    </style>

    <div class="page-container">
        <!-- Tambah Info Button -->
        <button class="btn-tambah" onclick="openModalTambah()">
            Tambah Info
        </button>

        @if ($errors->any())
            <div style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; font-weight: 500;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; font-weight: 500;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Card Daftar Info -->
        <div class="info-panel">
            <h2 class="panel-title">Daftar Info</h2>

            <div class="table-responsive">
                <table class="table-info">
                    <thead>
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th style="width: 180px;">Tanggal</th>
                            <th>Judul</th>
                            <th style="width: 150px;">Foto</th>
                            <th style="width: 100px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($infos as $index => $info)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($info->tanggal)->translatedFormat('d F Y') }}</td>
                            <td>{{ $info->judul }}</td>
                            <td>
                                @if($info->foto)
                                    <a href="javascript:void(0)" onclick="openModalFoto('{{ asset('storage/' . $info->foto) }}')" title="Lihat Foto">
                                        <i class="fas fa-image icon-image" style="color: #3b82f6;"></i>
                                    </a>
                                @else
                                    <i class="fas fa-image icon-image"></i>
                                @endif
                            </td>
                            <td>
                                <form id="form-delete-{{ $info->id }}" action="{{ route('admin.info-bps.destroy', $info->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="icon-delete" title="Hapus" onclick="panggilModalKonfirmasi('Hapus Info?', 'Apakah Anda yakin ingin menghapus informasi ini? Data dan foto yang terkait akan dihapus permanen.', 'fas fa-trash-alt', '#dc2626', 'Ya, Hapus', function() { document.getElementById('form-delete-{{ $info->id }}').submit(); })"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="padding: 20px; font-style: italic;">Belum ada info.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Info -->
    <div id="modalTambahInfo" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Tambah Info</h3>
                <button class="close-modal" onclick="closeModalTambah()">&times;</button>
            </div>
            <form action="{{ route('admin.info-bps.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" required value="{{ old('tanggal') }}">
                </div>
                <div class="form-group">
                    <label for="judul">Judul</label>
                    <input type="text" name="judul" id="judul" placeholder="Masukkan judul info" required value="{{ old('judul') }}">
                </div>
                <div class="form-group">
                    <label for="foto">Foto</label>
                    <input type="file" name="foto" id="foto" accept="image/*" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeModalTambah()">Batal</button>
                    <button type="submit" class="btn-save">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Lihat Foto -->
    <div id="modalLihatFoto" class="modal-overlay">
        <div class="modal-content" style="max-width: 600px; text-align: center;">
            <div class="modal-header">
                <h3 class="modal-title">Foto Informasi</h3>
                <button class="close-modal" onclick="closeModalFoto()">&times;</button>
            </div>
            <img id="previewFoto" src="" alt="Foto Info" style="max-width: 100%; max-height: 70vh; border-radius: 8px; object-fit: contain;">
        </div>
    </div>

    <script>
        function openModalTambah() {
            document.getElementById('modalTambahInfo').style.display = 'flex';
        }

        function closeModalTambah() {
            document.getElementById('modalTambahInfo').style.display = 'none';
        }

        function openModalFoto(imageUrl) {
            document.getElementById('previewFoto').src = imageUrl;
            document.getElementById('modalLihatFoto').style.display = 'flex';
        }

        function closeModalFoto() {
            document.getElementById('modalLihatFoto').style.display = 'none';
            document.getElementById('previewFoto').src = '';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            var modalTambah = document.getElementById('modalTambahInfo');
            var modalFoto = document.getElementById('modalLihatFoto');
            if (event.target == modalTambah) {
                closeModalTambah();
            }
            if (event.target == modalFoto) {
                closeModalFoto();
            }
        }
    </script>
@endsection
