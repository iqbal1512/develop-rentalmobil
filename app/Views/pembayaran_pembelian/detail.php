<?php $title = 'Detail Pembayaran Pembelian'; ?>
<?= view('templates/header') ?>

<div class="page-content">
  <div class="card" style="max-width: 900px; margin: 0 auto;">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5><i class="bi bi-eye-fill" style="color: var(--primary);"></i> Detail Pembayaran Pembelian</h5>
      <div>
        <a href="<?= base_url('pembayaran_pembelian') ?>" class="btn btn-secondary me-2">
          <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <a href="<?= base_url('pembayaran_pembelian/cetak-kwitansi/' . $pembelian['id_pembelian']) ?>" target="_blank" class="btn btn-primary">
          <i class="bi bi-printer-fill"></i> Cetak Kwitansi
        </a>
      </div>
    </div>
    
    <div class="card-body">
      <!-- Status Badge -->
      <div class="text-end mb-3">
        <span class="badge bg-success p-2" style="font-size: 0.9rem;">
          <i class="bi bi-patch-check-fill"></i> Pembayaran Selesai / Lunas
        </span>
      </div>

      <div class="row">
        <!-- Rincian Pembayaran -->
        <div class="col-md-6 mb-4">
          <h6 class="mb-3" style="font-weight: 600; color: var(--primary); border-left: 3px solid var(--primary); padding-left: 10px;">Informasi Pembayaran</h6>
          <table class="table table-striped table-bordered text-dark">
            <tr>
              <th style="width: 160px; font-size: 0.875rem;">No. Kwitansi</th>
              <td class="text-info fw-bold" style="font-size: 0.875rem;"><?= esc($pembelian['no_kwitansi'] ?? '-') ?></td>
            </tr>
            <tr>
              <th style="font-size: 0.875rem;">Tanggal Pembelian</th>
              <td style="font-size: 0.875rem;"><?= date('d M Y', strtotime($pembelian['tgl_pembelian'])) ?></td>
            </tr>
            <tr>
              <th style="font-size: 0.875rem;">Metode Bayar</th>
              <td class="text-uppercase fw-bold" style="font-size: 0.875rem;"><?= esc($pembelian['metode_bayar'] ?? 'tunai') ?></td>
            </tr>
            <tr>
              <th style="font-size: 0.875rem;">Total Nominal</th>
              <td class="text-success fw-bold" style="font-size: 0.875rem;">Rp<?= number_format($pembelian['total_harga'], 0, ',', '.') ?></td>
            </tr>
            <tr>
              <th style="font-size: 0.875rem;">Keterangan Kondisi</th>
              <td style="font-size: 0.875rem;"><?= nl2br(esc($pembelian['keterangan_kondisi'] ?? '-')) ?></td>
            </tr>
            <tr>
              <th style="font-size: 0.875rem;">Operator Input</th>
              <td style="font-size: 0.875rem;"><?= esc($pembelian['nama_user'] ?? 'Administrator') ?></td>
            </tr>
          </table>
        </div>

        <!-- Rincian Supplier & Unit -->
        <div class="col-md-6 mb-4">
          <h6 class="mb-3" style="font-weight: 600; color: var(--primary); border-left: 3px solid var(--primary); padding-left: 10px;">Rincian Supplier & Unit Mobil</h6>
          <table class="table table-striped table-bordered text-dark">
            <tr>
              <th style="width: 160px; font-size: 0.875rem;">Nama Supplier</th>
              <td style="font-size: 0.875rem;" class="fw-bold text-uppercase"><?= esc($pembelian['nama_supplier'] ?? 'Tidak Terdata') ?></td>
            </tr>
            <tr>
              <th style="font-size: 0.875rem;">Unit Kendaraan</th>
              <td style="font-size: 0.875rem;" class="fw-bold"><?= esc($pembelian['nama_mobil']) ?> (<?= esc($pembelian['warna'] ?? '-') ?>)</td>
            </tr>
            <tr>
              <th style="font-size: 0.875rem;">Tipe</th>
              <td style="font-size: 0.875rem;"><?= esc($pembelian['tipe'] ?? '-') ?></td>
            </tr>
            <tr>
              <th style="font-size: 0.875rem;">Harga Satuan</th>
              <td style="font-size: 0.875rem;">Rp<?= number_format($pembelian['harga_beli'], 0, ',', '.') ?></td>
            </tr>
            <tr>
              <th style="font-size: 0.875rem;">Jumlah Pembelian</th>
              <td style="font-size: 0.875rem;"><?= $pembelian['jumlah_pembelian'] ?> Unit</td>
            </tr>
          </table>
        </div>
      </div>

      <!-- Bukti Transfer -->
      <?php if ($pembelian['metode_bayar'] === 'transfer' && !empty($pembelian['bukti_transfer'])): ?>
      <div class="row mt-2">
        <div class="col-12">
          <h6 class="mb-3" style="font-weight: 600; color: var(--primary); border-left: 3px solid var(--primary); padding-left: 10px;">Bukti Upload Transfer Bank</h6>
          <div class="p-3 bg-light rounded text-center border border-light">
            <a href="<?= base_url('uploads/bukti/' . $pembelian['bukti_transfer']) ?>" target="_blank">
              <img src="<?= base_url('uploads/bukti/' . $pembelian['bukti_transfer']) ?>" alt="Bukti Transfer" class="img-fluid rounded" style="max-height: 400px; border: 1px solid var(--gray-200);">
            </a>
            <p class="text-muted small mt-2">Klik gambar untuk melihat dalam ukuran penuh.</p>
          </div>
        </div>
      </div>
      <?php endif; ?>

    </div>
  </div>
</div>

<?= view('templates/footer') ?>
