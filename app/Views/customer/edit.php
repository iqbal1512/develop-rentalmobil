<?php $title = 'Edit Customer'; ?>
<?= view('templates/header') ?>

<div class="card shadow-sm bg-white border-0 text-dark" style="max-width: 750px; margin: 0 auto">
  <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom">
    <h5 class="mb-0 fw-bold text-dark">
      <i class="bi bi-pencil-square text-warning me-1"></i> Edit Data Customer
    </h5>
    <a href="<?= base_url('customer') ?>" class="btn btn-outline-secondary btn-sm">
      <i class="bi bi-arrow-left"></i> Kembali
    </a>
  </div>
  
  <div class="card-body p-4">
    
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger pb-1" role="alert">
            <h6 class="fw-bold mb-2"><i class="bi bi-x-circle-fill me-1"></i> Perubahan Gagal Disimpan:</h6>
            <ul class="mb-2" style="font-size: 13px;">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('customer/update/' . $customer['id_customer']) ?>" method="POST">
      <?= csrf_field() ?>
      
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label fw-semibold text-dark mb-1">Nama Lengkap</label>
          <input type="text" name="nama" class="form-control bg-white text-dark border-secondary" value="<?= esc($customer['nama']) ?>" required>
        </div>
        
        <div class="col-md-6">
          <label class="form-label fw-semibold text-dark mb-1">No. KTP (16 Digit)</label>
          <input type="text" name="no_ktp" class="form-control bg-white text-dark border-secondary font-monospace" value="<?= esc($customer['no_ktp']) ?>" maxlength="16" required>
        </div>
        
        <div class="col-md-6">
          <label class="form-label fw-semibold text-dark mb-1">Nomor Telepon / WA</label>
          <input type="text" name="telepon" class="form-control bg-white text-dark border-secondary" value="<?= esc($customer['telepon'] ?? '') ?>" required>
        </div>
        
        <div class="col-md-6">
          <label class="form-label fw-semibold text-dark mb-1">Alamat Email</label>
          <input type="email" name="email" class="form-control bg-white text-dark border-secondary" value="<?= esc($customer['email'] ?? '') ?>">
        </div>
        
        <div class="col-12">
          <label class="form-label fw-semibold text-dark mb-1">Alamat Domisili KTP</label>
          <textarea name="alamat" class="form-control bg-white text-dark border-secondary" rows="3" required><?= esc($customer['alamat']) ?></textarea>
        </div>
      </div>

      <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
        <a href="<?= base_url('customer') ?>" class="btn btn-outline-secondary px-3">Batal</a>
        <button type="submit" class="btn btn-warning px-4 fw-semibold text-dark">
          <i class="bi bi-check2-circle me-1"></i> Simpan Perubahan
        </button>
      </div>
    </form>
  </div>
</div>

<?= view('templates/footer') ?>