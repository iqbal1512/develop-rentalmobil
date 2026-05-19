<?php $title = 'Tambah Supplier'; ?>
<?= view('templates/header') ?>

<div class="card" style="max-width: 900px; margin: 0 auto;">
  <div class="card-header">
    <h5><i class="bi bi-building-add" style="color: var(--primary);"></i> Tambah Supplier Baru</h5>
    <a href="<?= base_url('supplier') ?>" class="btn btn-secondary">
      <i class="bi bi-arrow-left"></i> Kembali
    </a>
  </div>
  <div class="card-body">
    <p style="color: var(--gray-500); font-size: 0.875rem; margin-bottom: 1.5rem;">Lengkapi formulir di bawah ini untuk mencatat data supplier baru di sistem.</p>

    <form action="<?= base_url('supplier/store') ?>" method="POST">
      <?= csrf_field() ?>

      <div class="row">
        <div class="col-md-12 form-group">
          <label class="form-label">Nama Supplier <span class="text-danger">*</span></label>
          <input type="text" name="nama_supplier" class="form-control"
            placeholder="PT. Contoh Motor" value="<?= old('nama_supplier') ?>" required>
          <?php if (isset($errors['nama_supplier'])): ?>
            <div class="invalid-feedback"><?= $errors['nama_supplier'] ?></div>
          <?php endif; ?>
        </div>
      </div>

      <div class="row mt-3">
        <div class="col-md-6 form-group">
          <label class="form-label">Telepon</label>
          <input type="text" name="telepon" class="form-control" placeholder="021-..." value="<?= old('telepon') ?>">
        </div>
        <div class="col-md-6 form-group">
          <label class="form-label">No. HP</label>
          <input type="text" name="no_hp" class="form-control" placeholder="08..." value="<?= old('no_hp') ?>">
        </div>
      </div>

      <div class="row mt-3">
        <div class="col-md-12 form-group">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" placeholder="email@supplier.com" value="<?= old('email') ?>">
        </div>
      </div>

      <div class="row mt-3">
        <div class="col-md-12 form-group">
          <label class="form-label">Alamat <span class="text-danger">*</span></label>
          <textarea name="alamat" class="form-control"
            placeholder="Alamat lengkap supplier..." rows="3" required><?= old('alamat') ?></textarea>
          <?php if (isset($errors['alamat'])): ?>
            <div class="invalid-feedback"><?= $errors['alamat'] ?></div>
          <?php endif; ?>
        </div>
      </div>

      <div class="d-flex justify-content-between align-items-center mt-4 pt-4" style="border-top: 1px solid var(--gray-200);">
        <a href="<?= base_url('supplier') ?>" class="btn btn-secondary">Batalkan</a>
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-check2-circle"></i> Simpan Supplier
        </button>
      </div>
    </form>
  </div>
</div>

<?= view('templates/footer') ?>
