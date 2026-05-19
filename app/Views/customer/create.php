<?php $title = 'Tambah Customer'; ?>
<?= view('templates/header') ?>

<div class="card shadow-sm bg-white border-light text-dark" style="max-width:700px;margin:0 auto">
  <div class="card-header bg-white border-light text-white">
    <h5><i class="bi bi-person-plus-fill text-accent"></i> Tambah Customer Baru</h5>
    <a href="<?= base_url('customer') ?>" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
  </div>
  <div class="card-body">
    <form action="<?= base_url('customer/store') ?>" method="POST" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <div class="row">
        <div class="col-6"><div class="form-group">
          <label class="form-label text-dark">Nama Lengkap <span style="color:var(--danger)">*</span></label>
          <input type="text" name="nama" class="form-control bg-white text-dark border-light " placeholder="Nama lengkap customer" value="<?= old('nama') ?>" required>
        </div></div>
        <div class="col-6"><div class="form-group">
          <label class="form-label text-dark">No. KTP <span style="color:var(--danger)">*</span></label>
          <input type="text" name="no_ktp" class="form-control bg-white text-dark border-light " placeholder="16 digit NIK" value="<?= old('no_ktp') ?>" maxlength="20" required>
        </div></div>
        <div class="col-6"><div class="form-group">
          <label class="form-label text-dark">Telepon</label>
          <input type="text" name="telepon" class="form-control bg-white text-dark border-light " placeholder="08..." value="<?= old('telepon') ?>">
        </div></div>
        <div class="col-6"><div class="form-group">
          <label class="form-label text-dark">Email</label>
          <input type="email" name="email" class="form-control bg-white text-dark border-light " placeholder="email@contoh.com" value="<?= old('email') ?>">
        </div></div>
        <div class="col-6"><div class="form-group">
          <label class="form-label text-dark">Kode Pos</label>
          <input type="text" name="no_zip" class="form-control bg-white text-dark border-light " placeholder="12345" value="<?= old('no_zip') ?>">
        </div></div>
        <div class="col-12"><div class="form-group">
          <label class="form-label text-dark">Alamat <span style="color:var(--danger)">*</span></label>
          <textarea name="alamat" class="form-control bg-white text-dark border-light " rows="3" placeholder="Alamat lengkap..." required><?= old('alamat') ?></textarea>
        </div></div>
        <div class="col-12"><div class="form-group mt-2">
          <label class="form-label text-dark">Upload Foto KTP (Opsional)</label>
          <input type="file" name="foto_ktp" class="form-control bg-white text-dark border-light " accept="image/*">
          <div class="form-text text-secondary">Format: JPG, PNG, WEBP. Maks 2MB.</div>
        </div></div>
      </div>
      <div class="d-flex justify-content-end gap-2" style="margin-top:8px">
        <a href="<?= base_url('customer') ?>" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-primary"><i class="bi bi-save2"></i> Simpan Customer</button>
      </div>
    </form>
  </div>
</div>

<?= view('templates/footer') ?>
