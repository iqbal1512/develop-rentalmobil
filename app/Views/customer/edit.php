<?php $title = 'Edit Customer'; ?>
<?= view('templates/header') ?>

<div class="card shadow-sm bg-white border-light text-dark" style="max-width:700px;margin:0 auto">
  <div class="card-header bg-white border-light text-white">
    <h5><i class="bi bi-pencil-square text-warning"></i> Edit Customer</h5>
    <a href="<?= base_url('customer') ?>" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
  </div>
  <div class="card-body">
    <form action="<?= base_url('customer/update/' . $customer['id_customer']) ?>" method="POST" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <div class="row">
        <div class="col-6"><div class="form-group">
          <label class="form-label text-dark">Nama Lengkap</label>
          <input type="text" name="nama" class="form-control bg-white text-dark border-light " value="<?= esc($customer['nama']) ?>" required>
        </div></div>
        <div class="col-6"><div class="form-group">
          <label class="form-label text-dark">No. KTP</label>
          <input type="text" name="no_ktp" class="form-control bg-white text-dark border-light " value="<?= esc($customer['no_ktp']) ?>" required>
        </div></div>
        <div class="col-6"><div class="form-group">
          <label class="form-label text-dark">Telepon</label>
          <input type="text" name="telepon" class="form-control bg-white text-dark border-light " value="<?= esc($customer['telepon'] ?? '') ?>">
        </div></div>
        <div class="col-6"><div class="form-group">
          <label class="form-label text-dark">Email</label>
          <input type="email" name="email" class="form-control bg-white text-dark border-light " value="<?= esc($customer['email'] ?? '') ?>">
        </div></div>
        <div class="col-6"><div class="form-group">
          <label class="form-label text-dark">Kode Pos</label>
          <input type="text" name="no_zip" class="form-control bg-white text-dark border-light " value="<?= esc($customer['no_zip'] ?? '') ?>">
        </div></div>
        <div class="col-12"><div class="form-group">
          <label class="form-label text-dark">Alamat</label>
          <textarea name="alamat" class="form-control bg-white text-dark border-light " rows="3" required><?= esc($customer['alamat']) ?></textarea>
        </div></div>
        
        <div class="col-12"><div class="form-group mt-2">
          <label class="form-label text-dark">Upload Foto KTP Baru (Opsional)</label>
          <input type="file" name="foto_ktp" class="form-control bg-white text-dark border-light " accept="image/*">
          <div class="form-text text-secondary">Abaikan jika tidak ingin mengubah foto. Format: JPG, PNG, WEBP. Maks 2MB.</div>
          
          <?php if (!empty($customer['foto_ktp'])): ?>
            <div class="mt-3">
              <span class="d-block text-secondary mb-1">Foto Saat Ini:</span>
              <img src="<?= base_url('uploads/ktp/' . $customer['foto_ktp']) ?>" alt="KTP" style="max-height: 150px; border-radius: 8px; border: 1px solid var(--border)">
            </div>
          <?php endif; ?>
        </div></div>
      </div>
      <div class="d-flex justify-content-end gap-2" style="margin-top:8px">
        <a href="<?= base_url('customer') ?>" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-warning"><i class="bi bi-save2"></i> Update Customer</button>
      </div>
    </form>
  </div>
</div>

<?= view('templates/footer') ?>
