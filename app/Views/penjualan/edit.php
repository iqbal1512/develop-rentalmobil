<?php $title = 'Edit Penjualan'; ?>
<?= view('templates/header') ?>

<div class="card shadow-sm bg-white border-light text-dark" style="max-width: 800px; margin: 20px auto;">
  <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
    <h5 class="mb-0 text-dark fw-bold"><i class="bi bi-pencil-square text-warning me-2"></i> Edit Berkas Administrasi Penjualan</h5>
    <a href="<?= base_url('penjualan') ?>" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
  </div>
  <div class="card-body">
    
    <div class="alert alert-warning border-start border-warning border-3 mb-4" role="alert" style="font-size: 13px;">
      <i class="bi bi-info-circle-fill me-2"></i>
      <strong>Informasi Sistem:</strong> Nilai nominal keuangan, status kelunasan, serta progress cetak STNK/BPKB dikunci otomatis. Perubahan data finansial wajib dilakukan melalui menu <strong>Input Pembayaran</strong>.
    </div>

    <form action="<?= base_url('penjualan/update/' . $penjualan['id_penjualan']) ?>" method="POST">
      <?= csrf_field() ?>
      
      <h6 class="text-secondary fw-bold mb-3"><i class="bi bi-cash-stack me-1"></i> 1. Ringkasan Keuangan (Locked)</h6>
      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <div class="form-group">
            <label class="form-label text-muted small fw-semibold">Tanggal Transaksi</label>
            <input type="date" class="form-control bg-light text-muted border-secondary-subtle" value="<?= $penjualan['tgl_penjualan'] ?>" readonly>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="form-label text-muted small fw-semibold">Total Harga Kesepakatan</label>
            <div class="input-group">
              <span class="input-group-text bg-secondary-subtle text-muted">Rp</span>
              <input type="text" class="form-control bg-light text-muted border-secondary-subtle" value="<?= number_format($penjualan['total_harga'], 0, ',', '.') ?>" readonly>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="form-label text-muted small fw-semibold">Total Dana Masuk</label>
            <div class="input-group">
              <span class="input-group-text bg-secondary-subtle text-muted">Rp</span>
              <input type="text" class="form-control bg-light text-success border-secondary-subtle fw-semibold" value="<?= number_format($penjualan['total_dibayar'], 0, ',', '.') ?>" readonly>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="form-label text-muted small fw-semibold">Sisa Tagihan Pelunasan</label>
            <div class="input-group">
              <span class="input-group-text bg-secondary-subtle text-muted">Rp</span>
              <input type="text" class="form-control bg-light text-danger border-secondary-subtle fw-bold" value="<?= number_format($penjualan['sisa_tagihan'], 0, ',', '.') ?>" readonly>
            </div>
          </div>
        </div>
      </div>

      <h6 class="text-primary fw-bold mb-3"><i class="bi bi-file-earmark-check me-1"></i> 2. Status Berkas & Verifikasi Penjualan</h6>
      <div class="row g-3 mb-4">
        <div class="col-md-4">
          <div class="form-group">
            <label class="form-label text-dark fw-semibold">Status Kelulusan Berkas <span class="text-danger">*</span></label>
            <select name="status_lulus" class="form-select bg-white text-dark border-secondary" required>
              <?php foreach (['proses', 'lulus', 'gagal'] as $s): ?>
              <option value="<?= $s ?>" <?= $penjualan['status_lulus'] === $s ? 'selected' : '' ?>><?= strtoupper($s) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        
        <div class="col-md-4">
          <div class="form-group">
            <label class="form-label text-muted small fw-semibold d-block mb-2">Status Pembayaran</label>
            <span class="badge <?= $penjualan['status_lunas'] === 'lunas' ? 'bg-success' : 'bg-warning text-dark' ?> fs-6 px-3 py-2 w-100 text-center">
              <?= $penjualan['status_lunas'] === 'lunas' ? 'LUNAS' : 'BELUM LUNAS' ?>
            </span>
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label class="form-label text-muted small fw-semibold d-block mb-2">Progress Dokumen Jalan</label>
            <div class="d-flex gap-1">
              <span class="badge bg-secondary-subtle text-dark border p-2 w-50 text-center">STNK: <?= ucfirst($penjualan['proses_stnk'] ?? 'belum') ?></span>
              <span class="badge bg-secondary-subtle text-dark border p-2 w-50 text-center">BPKB: <?= ucfirst($penjualan['proses_bpkb'] ?? 'belum') ?></span>
            </div>
          </div>
        </div>

        <div class="col-12">
          <div class="form-group">
            <label class="form-label text-dark fw-semibold">Catatan Transaksi / Keterangan Tambahan</label>
            <textarea name="catatan" class="form-control bg-white text-dark border-secondary" rows="3" placeholder="Masukkan catatan atau memo internal penjualan jika ada..."><?= esc($penjualan['catatan'] ?? '') ?></textarea>
          </div>
        </div>
      </div>

      <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
        <a href="<?= base_url('penjualan') ?>" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i> Batal</a>
        <button type="submit" class="btn btn-warning px-4 fw-semibold text-dark"><i class="bi bi-check2-circle text-dark"></i> Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>

<?= view('templates/footer') ?>