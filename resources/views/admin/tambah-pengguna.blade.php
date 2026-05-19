<!-- Modal Tambah Pengguna -->
<div id="modalTambahPengguna" class="modal-overlay">
    <div class="modal-content" style="max-width: 550px; overflow-y: auto; max-height: 90vh;">
        <div class="modal-header" style="border-bottom: none; padding-bottom: 0;">
            <h3 class="modal-title" style="display: none;">Tambah Pengguna Baru</h3>
            <button class="close-modal" onclick="document.getElementById('modalTambahPengguna').style.display='none'" style="position: absolute; right: 250px; top: 20px;">&times;</button>
        </div>
        
        <form action="{{ route('admin.pengguna.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            @if ($errors->any())
                <div style="background: #fee2e2; color: #b91c1c; padding: 15px; border-radius: 8px; margin-bottom: 15px; font-size: 0.9rem;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <style>
                .input-group {
                    position: relative;
                    margin-bottom: 15px;
                }
                .input-group label {
                    display: block;
                    margin-bottom: 5px;
                    color: #4b5563;
                    font-weight: 500;
                    font-size: 0.95rem;
                }
                .input-icon-wrapper {
                    position: relative;
                    display: flex;
                    align-items: center;
                }
                .input-icon-wrapper i {
                    position: absolute;
                    left: 15px;
                    color: #6b7280;
                }
                .input-icon-wrapper input, .input-icon-wrapper select {
                    width: 100%;
                    padding: 10px 15px 10px 40px;
                    border: 1px solid #d1d5db;
                    border-radius: 8px;
                    outline: none;
                    font-size: 0.95rem;
                    color: #374151;
                }
                .input-icon-wrapper input:focus, .input-icon-wrapper select:focus {
                    border-color: #3b82f6;
                }
                .role-select {
                    padding-left: 15px !important; /* No icon on left for select based on design */
                }
                
                .btn-upload-ttd {
                    background-color: #86efac; /* Light green */
                    color: white;
                    border: none;
                    padding: 10px 20px;
                    border-radius: 6px;
                    cursor: pointer;
                    font-weight: 600;
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    transition: background 0.15s ease, transform 0.12s ease;
                }
                .btn-upload-ttd:hover {
                    background-color: #4ade80;
                    transform: translateY(-2px);
                }

                /* Submit button for modal - hover effects */
                .btn-save {
                    background: #374151;
                    color: #fff;
                    border: none;
                    padding: 10px 25px;
                    border-radius: 6px;
                    cursor: pointer;
                    font-weight: 600;
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    transition: background 0.12s ease, transform 0.12s ease, box-shadow 0.12s ease;
                }
                .btn-save:hover {
                    background: #2f3842;
                    transform: translateY(-2px);
                    box-shadow: 0 8px 20px rgba(47,56,66,0.15);
                }
                .btn-save:active {
                    transform: translateY(0);
                }
            </style>

            <div class="input-group">
                <label>Nama Lengkap</label>
                <div class="input-icon-wrapper">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" value="{{ old('username') }}" required>
                </div>
            </div>
            
            <div class="input-group">
                <label>NIP</label>
                <div class="input-icon-wrapper">
                    <i class="fas fa-id-card"></i>
                    <input type="text" name="nip" value="{{ old('nip') }}" required>
                </div>
            </div>

            <div class="input-group">
                <label>Jabatan</label>
                <div class="input-icon-wrapper">
                    <i class="fas fa-wrench"></i>
                    <input type="text" name="jabatan" value="{{ old('jabatan') }}">
                </div>
            </div>
            
            <div class="input-group">
                <label>Email</label>
                <div class="input-icon-wrapper">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </div>
            </div>
            
            <div class="input-group">
                <label>Password</label>
                <div class="input-icon-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" required>
                </div>
            </div>
            
            <div class="input-group">
                <label>Role</label>
                <div class="input-icon-wrapper">
                    <select name="role" class="role-select" required>
                        <option value="pegawai" {{ old('role') == 'pegawai' ? 'selected' : '' }}>User</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 25px;">
                <!-- Hidden file input triggered by the green button -->
                <input type="file" id="ttd_upload" name="ttd" accept="image/*" style="display: none;">
                <button type="button" id="btn_ttd_upload" class="btn-upload-ttd" onclick="document.getElementById('ttd_upload').click();">
                    <span id="ttd_btn_text">Upload Tandatangan</span> <i class="fas fa-arrow-alt-circle-up"></i>
                </button>
                
                <button type="submit" class="btn-save" style="padding: 10px 25px; margin: 0; height: 100%;">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Menampilkan nama file pada tombol upload
    document.getElementById('ttd_upload').addEventListener('change', function(e) {
        var fileName = e.target.files[0] ? e.target.files[0].name : 'Upload Tandatangan';
        if (fileName.length > 25 && fileName !== 'Upload Tandatangan') {
            fileName = fileName.substring(0, 22) + '...';
        }
        document.getElementById('ttd_btn_text').textContent = fileName;
    });

    // Jika ada error validasi pada form Tambah Pengguna (bukan form Edit/PUT)
    @if($errors->any() && !old('_method'))
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('modalTambahPengguna').style.display = 'flex';
        });
    @endif
</script>
