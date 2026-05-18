<!-- Modal Edit Pengguna -->
<div id="modalEditPengguna" class="modal-overlay">
    <div class="modal-content" style="max-width: 550px; overflow-y: auto; max-height: 90vh;">
        <div class="modal-header" style="border-bottom: none; padding-bottom: 0;">
            <h3 class="modal-title" style="display: none;">Edit Pengguna</h3>
            <button class="close-modal" type="button" onclick="document.getElementById('modalEditPengguna').style.display='none'" style="position: absolute; right: 250px; top: 20px;">&times;</button>
        </div>
        
        <form id="formEditPengguna" action="#" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="input-group">
                <label>Nama Lengkap</label>
                <div class="input-icon-wrapper">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" id="edit_username" required>
                </div>
            </div>
            
            <div class="input-group">
                <label>NIP</label>
                <div class="input-icon-wrapper">
                    <i class="fas fa-id-card"></i>
                    <input type="text" name="nip" id="edit_nip" required>
                </div>
            </div>

            <div class="input-group">
                <label>Jabatan</label>
                <div class="input-icon-wrapper">
                    <i class="fas fa-wrench"></i>
                    <input type="text" name="jabatan" id="edit_jabatan">
                </div>
            </div>
            
            <div class="input-group">
                <label>Email</label>
                <div class="input-icon-wrapper">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" id="edit_email" required>
                </div>
            </div>
            
            <div class="input-group">
                <label>Password <small style="color: #9ca3af; font-weight: normal;">(Kosongkan jika tidak ingin diubah)</small></label>
                <div class="input-icon-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" id="edit_password" placeholder="Tulis password baru di sini...">
                </div>
            </div>
            
            <div class="input-group">
                <label>Role</label>
                <div class="input-icon-wrapper">
                    <select name="role" id="edit_role" class="role-select" required>
                        <option value="pegawai">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 25px;">
                <!-- Hidden file input triggered by the green button -->
                <input type="file" id="edit_ttd_upload" name="ttd" accept="image/*" style="display: none;">
                <button type="button" id="btn_edit_ttd_upload" class="btn-upload-ttd" onclick="document.getElementById('edit_ttd_upload').click();">
                    <span id="edit_ttd_btn_text">Upload Tandatangan</span> <i class="fas fa-arrow-alt-circle-up"></i>
                </button>
                
                <button type="submit" class="btn-save" style="padding: 10px 25px; margin: 0; height: 100%;">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
            <div style="font-size: 0.8rem; color: #9ca3af; margin-top: 8px;">
                *Jika tidak diupload, tanda tangan lama akan dipertahankan.
            </div>
        </form>
    </div>
</div>

<script>
    // Menampilkan nama file yang dipilih pada tombol upload di modal edit
    document.getElementById('edit_ttd_upload').addEventListener('change', function(e) {
        var fileName = e.target.files[0] ? e.target.files[0].name : 'Upload Tandatangan';
        if (fileName.length > 25 && fileName !== 'Upload Tandatangan') {
            fileName = fileName.substring(0, 22) + '...';
        }
        document.getElementById('edit_ttd_btn_text').textContent = fileName;
    });

    // Fungsi untuk membuka modal edit dan mengisi datanya
    function openEditModal(user) {
        document.getElementById('modalEditPengguna').style.display = 'flex';
        
        // Atur action form ke rute update yang sesuai
        document.getElementById('formEditPengguna').action = '/admin/pengguna/' + user.id;
        
        // Isi form dengan data user
        document.getElementById('edit_username').value = user.username || user.name || '';
        document.getElementById('edit_nip').value = user.nip || '';
        document.getElementById('edit_email').value = user.email || '';
        document.getElementById('edit_jabatan').value = user.jabatan || '';
        document.getElementById('edit_role').value = user.role || 'pegawai';
        
        // Reset kolom password dan info file
        document.getElementById('edit_password').value = '';
        document.getElementById('edit_ttd_btn_text').textContent = 'Upload Tandatangan';
        document.getElementById('edit_ttd_upload').value = '';
    }
</script>
