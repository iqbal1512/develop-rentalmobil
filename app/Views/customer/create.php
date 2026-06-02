<?php $title = 'Tambah Customer'; ?>
<?= view('templates/header') ?>

<div class="card shadow-sm bg-white border-0 text-dark" style="max-width: 850px; margin: 0 auto;">
  <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom">
    <h5 class="mb-0 fw-bold text-dark">
      <i class="bi bi-person-plus-fill text-primary me-1"></i> Tambah Customer Baru
    </h5>
    <a href="<?= base_url('customer') ?>" class="btn btn-outline-secondary btn-sm">
      <i class="bi bi-arrow-left"></i> Kembali
    </a>
  </div>
  
  <div class="card-body p-4">
    <p class="text-muted small mb-4">Lengkapi formulir di bawah ini untuk mencatat data customer baru ke dalam sistem master data showroom.</p>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger pb-1" role="alert">
            <h6 class="fw-bold mb-2"><i class="bi bi-x-circle-fill me-1"></i> Gagal Menyimpan Data:</h6>
            <ul class="mb-2" style="font-size: 13px;">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('customer/store') ?>" method="POST">
      <?= csrf_field() ?>
      
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label fw-semibold text-dark mb-1">Nama Lengkap <span class="text-danger">*</span></label>
          <input type="text" name="nama" class="form-control bg-white text-dark border-secondary" placeholder="Nama lengkap customer" value="<?= old('nama') ?>" required>
        </div>
        
        <div class="col-md-6">
          <label class="form-label fw-semibold text-dark mb-1">No. KTP (NIK) <span class="text-danger">*</span></label>
          <input type="text" name="no_ktp" class="form-control bg-white text-dark border-secondary font-monospace" placeholder="16 digit nomor KTP" value="<?= old('no_ktp') ?>" maxlength="16" required>
        </div>
        
        <div class="col-md-6">
          <label class="form-label fw-semibold text-dark mb-1">Nomor Telepon / WA <span class="text-danger">*</span></label>
          <input type="text" name="telepon" class="form-control bg-white text-dark border-secondary" placeholder="Contoh: 08123456789" value="<?= old('telepon') ?>" required>
        </div>
        
        <div class="col-md-6">
          <label class="form-label fw-semibold text-dark mb-1">Alamat Email</label>
          <input type="email" name="email" class="form-control bg-white text-dark border-secondary" placeholder="email@contoh.com" value="<?= old('email') ?>">
        </div>
        
        <div class="col-12">
          <label class="form-label fw-semibold text-dark mb-1">Alamat Domisili KTP <span class="text-danger">*</span></label>
          <textarea name="alamat" class="form-control bg-white text-dark border-secondary" rows="3" placeholder="Tuliskan alamat lengkap beserta RT/RW, Kelurahan, dan Kecamatan..." required><?= old('alamat') ?></textarea>
        </div>
      </div>

      <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
        <a href="<?= base_url('customer') ?>" class="btn btn-outline-secondary px-3">Batalkan</a>
        <button type="submit" class="btn btn-primary px-4 fw-semibold">
          <i class="bi bi-check2-circle me-1"></i> Simpan Customer
        </button>
      </div>
    </form>
  </div>
</div>

<?= view('templates/footer') ?>