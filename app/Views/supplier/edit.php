<?php $title = 'Edit Supplier'; ?>
<?= view('templates/header') ?>

<div class="card shadow-sm bg-white border-light text-dark" style="max-width:700px;margin:0 auto">
  <div class="card-header bg-white border-light text-white">
    <h5><i class="bi bi-pencil-square text-warning"></i> Edit Supplier</h5>
    <a href="<?= base_url('supplier') ?>" class="btn btn-secondary btn-sm">
      <i class="bi bi-arrow-left"></i> Kembali
    </a>
  </div>
  <div class="card-body">
    <form action="<?= base_url('supplier/update/' . $supplier['id_supplier']) ?>" method="POST">
      <?= csrf_field() ?>

      <div class="row">
        <div class="col-12">
          <div class="form-group">
            <label class="form-label text-dark">Nama Supplier <span style="color:var(--danger)">*</span></label>
            <input type="text" name="nama_supplier" class="form-control bg-white text-dark border-light "
              value="<?= esc($supplier['nama_supplier']) ?>" required>
          </div>
        </div>
        <div class="col-6">
          <div class="form-group">
            <label class="form-label text-dark">Telepon</label>
            <input type="text" name="telepon" class="form-control bg-white text-dark border-light " value="<?= esc($supplier['telepon'] ?? '') ?>">
          </div>
        </div>
        <div class="col-6">
          <div class="form-group">
            <label class="form-label text-dark">No. HP</label>
            <input type="text" name="no_hp" class="form-control bg-white text-dark border-light " value="<?= esc($supplier['no_hp'] ?? '') ?>">
          </div>
        </div>
        <div class="col-12">
          <div class="form-group">
            <label class="form-label text-dark">Email</label>
            <input type="email" name="email" class="form-control bg-white text-dark border-light " value="<?= esc($supplier['email'] ?? '') ?>">
          </div>
        </div>
        <div class="col-12">
          <div class="form-group">
            <label class="form-label text-dark">Alamat <span style="color:var(--danger)">*</span></label>
            <textarea name="alamat" class="form-control bg-white text-dark border-light " rows="3" required><?= esc($supplier['alamat']) ?></textarea>
          </div>
        </div>
      </div>

      <div class="d-flex justify-content-end gap-2" style="margin-top:8px">
        <a href="<?= base_url('supplier') ?>" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-warning">
          <i class="bi bi-save2"></i> Update Supplier
        </button>
      </div>
    </form>
  </div>
</div>

<?= view('templates/footer') ?>
