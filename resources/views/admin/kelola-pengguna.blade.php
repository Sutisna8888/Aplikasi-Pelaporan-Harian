@extends('layouts.master')

@section('title', 'Kelola Pengguna - ALPHA')
@section('header_title', 'Kelola Pengguna')

@section('content')
    <style>
        .pengguna-panel {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .panel-header-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .search-box {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-input {
            padding: 6px 12px 8px 35px;
            border: 1px solid #d1d5db;
            border-radius: 20px;
            outline: none;
            font-size: 0.88rem;
            width: 280px;
            color: #4b5563;
            transition: border-color 0.2s;
        }

        .search-input:focus {
            border-color: #3b82f6;
        }

        .search-icon {
            position: absolute;
            left: 15px;
            color: #9ca3af;
            z-index: 2;
        }

        .autocomplete-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            max-height: 250px;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 5px;
            display: none;
        }

        .autocomplete-item {
            padding: 10px 15px;
            cursor: pointer;
            border-bottom: 1px solid #f3f4f6;
            transition: background 0.2s;
            text-align: left;
        }

        .autocomplete-item:last-child {
            border-bottom: none;
        }

        .autocomplete-item:hover {
            background: #f3f4f6;
        }

        .autocomplete-item-name {
            font-weight: 500;
            color: #374151;
        }

        .autocomplete-item-nip {
            font-size: 0.85rem;
            color: #6b7280;
        }

        .btn-cari {
            background: #374151;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 20px;
            margin-left: 10px;
            cursor: pointer;
            font-weight: 450;
            transition: background 0.2s;
        }

        .btn-cari:hover {
            background: #1f2937;
        }

        .btn-tambah {
            background: #374151;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s;
        }

        .btn-tambah:hover {
            background: #1f2937;
        }

        .table-responsive {
            overflow-x: auto;
            border-radius: 10px;
        }

        .table-pengguna {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }

        .table-pengguna th,
        .table-pengguna td {
            border: 1px solid #e5e7eb;
            padding: 11px;
            text-align: center;
            color: #6b7280;
            font-size: 0.95rem;
        }

        .table-pengguna th {
            background-color: #4b5563; /* Dark gray */
            color: #ffffff; /* White text */
            font-weight: 600;
            font-size: 0.95rem;
            border-color: #4b5563;
      
        }

        .table-pengguna td:nth-child(2),
        .table-pengguna td:nth-child(3),
        .table-pengguna td:nth-child(4),
        .table-pengguna td:nth-child(5) {
            text-align: left;
        }

        .table-pengguna td:nth-child(4) {
            text-decoration: underline;
            color: #6b7280;
        }

        .action-icons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .icon-edit {
            color: #2563eb;
            cursor: pointer;
        }

        .icon-delete {
            color: #dc2626;
            cursor: pointer;
        }

        .ttd-placeholder {
            font-size: 1.5rem;
            color: #4b5563;
            opacity: 0.7;
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
            color: #cfd6e1ff;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #4b5563;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.95rem;
            outline: none;
        }

        .form-group input:focus,
        .form-group select:focus {
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

    <div class="pengguna-panel">
        <div class="panel-header-flex">
            <form action="{{ route('admin.pengguna.index') }}" method="GET" class="search-box" id="filterForm">
                <div style="position: relative; display: inline-block;">
                    <i class="fas fa-search search-icon" style="top: 50%; transform: translateY(-50%);"></i>
                    <input type="text" name="search" id="search" class="search-input" placeholder="Cari pengguna"
                        value="{{ request('search') }}" autocomplete="off" style="padding-right: 35px;">
                    <button type="button" id="clearSearchBtn" title="Reset Pencarian"
                        style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: transparent; border: none; color: #9ca3af; cursor: pointer; display: {{ request('search') ? 'block' : 'none' }}; padding: 0; outline: none;">
                        <i class="fas fa-times"></i>
                    </button>
                    <div id="autocomplete-results" class="autocomplete-dropdown"></div>
                </div>
                <button type="submit" class="btn-cari">Cari</button>
            </form>

            <button class="btn-tambah" onclick="document.getElementById('modalTambahPengguna').style.display='flex'">
                <i class="fas fa-plus"></i> Tambah pengguna
            </button>
        </div>

        @if (session('success'))
            <div id="success-alert"
                style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 500; transition: opacity 0.5s ease-out;">
                <i class="fas fa-check-circle" style="margin-right: 8px;"></i> {{ session('success') }}
            </div>
            <script>
                setTimeout(function () {
                    var alertBox = document.getElementById('success-alert');
                    if (alertBox) {
                        alertBox.style.opacity = '0';
                        setTimeout(function () { alertBox.style.display = 'none'; }, 500); // Tunggu animasi fade out selesai
                    }
                }, 3000); 
            </script>
        @endif

        <div class="table-responsive">
            <table class="table-pengguna">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>NIP</th>
                        <th>Email</th>
                        <th>Jabatan</th>
                        <th>Role</th>
                        <th>Tanda Tangan</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $user->name ?? $user->username }}</td>
                            <td>{{ $user->nip ?? '-' }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->jabatan ?? '-' }}</td>
                            <td>{{ ucfirst($user->role) }}</td>
                            <td>
                                @if($user->ttd)
                                    <a href="javascript:void(0)" onclick="openModalFoto('{{ asset('storage/' . $user->ttd) }}')" title="Lihat Tanda Tangan" style="display: inline-block; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.2)'" onmouseout="this.style.transform='scale(1)'">
                                        <img src="{{ asset('storage/' . $user->ttd) }}" alt="TTD" style="height: 30px; border-radius: 4px; box-shadow: 0 1px 2px rgba(0,0,0,0.1); border: 1px solid #e5e7eb; display: block; margin: 0 auto;">
                                    </a>
                                @else
                                    <span style="color: #9ca3af; font-style: italic; font-size: 0.65rem;">Belum ada</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-icons">
                                    <a href="javascript:void(0)" onclick='openEditModal(@json($user))' class="icon-edit"
                                        title="Edit"><i class="fas fa-edit"></i></a>
                                    <form id="form-delete-{{ $user->id }}"
                                        action="{{ route('admin.pengguna.destroy', $user->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="icon-delete" title="Hapus"
                                            style="background: none; border: none; padding: 0; font: inherit;"
                                            onclick="panggilModalKonfirmasi('Hapus Pengguna?', 'Apakah Anda yakin ingin menghapus pengguna ini secara permanen?', 'fas fa-exclamation-triangle', '#dc2626', 'Hapus', function() { document.getElementById('form-delete-{{ $user->id }}').submit(); })">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 30px; font-style: italic;">
                                @if(request('search'))
                                    Pengguna dengan kata kunci "{{ request('search') }}" tidak ditemukan.
                                @else
                                    Belum ada pengguna yang terdaftar.
                                @endif
                            </td>
                        </tr>
                    @endforelse

                    {{-- Placeholder empty rows just to match the visual design grid if data is few --}}
                    @for($i = count($users); $i < 10; $i++)
                        <tr>
                            <td>&nbsp;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>

    @include('admin.tambah-pengguna')
    @include('admin.edit-pengguna')

    <!-- Modal Foto Tanda Tangan -->
    <div id="modalFoto" class="modal-overlay" style="z-index: 10000; background: rgba(0,0,0,0.85) !important; backdrop-filter: blur(5px);" onclick="closeModalFoto(event)">
        <div style="position: relative; max-width: 80%; text-align: center;">
            <button type="button" onclick="document.getElementById('modalFoto').style.display='none'" style="position: absolute; top: -40px; right: -40px; color: white; background: none; border: none; font-size: 2rem; cursor: pointer; outline: none;">&times;</button>
            <img id="modalFotoImage" src="" alt="Tanda Tangan" style="max-width: 100%; max-height: 80vh; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.5); display: block; margin: 0 auto;">
        </div>
    </div>

    <script>
        function openModalFoto(src) {
            document.getElementById('modalFotoImage').src = src;
            document.getElementById('modalFoto').style.display = 'flex';
        }

        function closeModalFoto(e) {
            if (e.target.id === 'modalFoto') {
                document.getElementById('modalFoto').style.display = 'none';
            }
        }
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('search');
            const autocompleteResults = document.getElementById('autocomplete-results');
            const clearSearchBtn = document.getElementById('clearSearchBtn');
            let timeoutId;

            searchInput.addEventListener('input', function () {
                clearTimeout(timeoutId);
                const query = this.value;

                if (query.length > 0) {
                    clearSearchBtn.style.display = 'block';
                } else {
                    clearSearchBtn.style.display = 'none';
                }

                if (query.length < 2) {
                    autocompleteResults.style.display = 'none';
                    return;
                }

                timeoutId = setTimeout(() => {
                    fetch(`{{ route('admin.pengguna.searchUsers') }}?q=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            autocompleteResults.innerHTML = '';
                            if (data.length > 0) {
                                data.forEach(user => {
                                    const item = document.createElement('div');
                                    item.className = 'autocomplete-item';
                                    item.innerHTML = `
                                            <div class="autocomplete-item-name">${user.username}</div>
                                            <div class="autocomplete-item-nip">NIP: ${user.nip}</div>
                                        `;
                                    item.addEventListener('click', () => {
                                        searchInput.value = user.username;
                                        autocompleteResults.style.display = 'none';
                                        clearSearchBtn.style.display = 'block';
                                    });
                                    autocompleteResults.appendChild(item);
                                });
                                autocompleteResults.style.display = 'block';
                            } else {
                                autocompleteResults.style.display = 'none';
                            }
                        });
                }, 300);
            });
            
            if (clearSearchBtn) {
                clearSearchBtn.addEventListener('click', function () {
                    searchInput.value = '';
                    clearSearchBtn.style.display = 'none';
                    autocompleteResults.style.display = 'none';
                    document.getElementById('filterForm').submit();
                });
            }
            
            document.addEventListener('click', function (e) {
                if (!searchInput.contains(e.target) && !autocompleteResults.contains(e.target)) {
                    autocompleteResults.style.display = 'none';
                }
            });
        });
    </script>
@endsection