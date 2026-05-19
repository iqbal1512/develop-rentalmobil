<?php $title = 'Tambah Customer'; ?>
<?= view('templates/header') ?>

<div class="card" style="max-width: 900px; margin: 0 auto;">
  <div class="card-header">
    <h5><i class="bi bi-person-plus-fill" style="color: var(--primary);"></i> Tambah Customer Baru</h5>
    <a href="<?= base_url('customer') ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
  </div>
  <div class="card-body">
    <p style="color: var(--gray-500); font-size: 0.875rem; margin-bottom: 1.5rem;">Lengkapi formulir di bawah ini untuk mencatat data customer baru.</p>

    <form action="<?= base_url('customer/store') ?>" method="POST" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <div class="row">
        <div class="col-md-6 form-group">
          <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
          <input type="text" name="nama" class="form-control" placeholder="Nama lengkap customer" value="<?= old('nama') ?>" required>
        </div>
        <div class="col-md-6 form-group">
          <label class="form-label">No. KTP <span class="text-danger">*</span></label>
          <input type="text" name="no_ktp" class="form-control" placeholder="16 digit NIK" value="<?= old('no_ktp') ?>" maxlength="20" required>
        </div>
      </div>

      <div class="row mt-3">
        <div class="col-md-6 form-group">
          <label class="form-label">Telepon</label>
          <input type="text" name="telepon" class="form-control" placeholder="08..." value="<?= old('telepon') ?>">
        </div>
        <div class="col-md-6 form-group">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" placeholder="email@contoh.com" value="<?= old('email') ?>">
        </div>
      </div>

      <div class="row mt-3">
        <div class="col-md-6 form-group">
          <label class="form-label">Kode Pos</label>
          <input type="text" name="no_zip" class="form-control" placeholder="12345" value="<?= old('no_zip') ?>">
        </div>
      </div>

      <div class="row mt-3">
        <div class="col-md-12 form-group">
          <label class="form-label">Alamat <span class="text-danger">*</span></label>
          <textarea name="alamat" class="form-control" rows="3" placeholder="Alamat lengkap..." required><?= old('alamat') ?></textarea>
        </div>
      </div>

      <div class="row mt-3">
        <div class="col-md-12 form-group">
          <label class="form-label">Upload Foto KTP (Opsional)</label>
          <input type="file" name="foto_ktp" class="form-control" accept="image/*">
          <div class="form-text text-secondary">Format: JPG, PNG, WEBP. Maks 2MB.</div>
        </div>
      </div>

      <div class="d-flex justify-content-between align-items-center mt-4 pt-4" style="border-top: 1px solid var(--gray-200);">
        <a href="<?= base_url('customer') ?>" class="btn btn-secondary">Batalkan</a>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle"></i> Simpan Customer</button>
      </div>
    </form>
  </div>
</div>

<?= view('templates/footer') ?>
