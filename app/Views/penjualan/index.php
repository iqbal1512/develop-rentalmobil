<?php $title = 'Transaksi Penjualan'; ?>
<?= view('templates/header') ?>

<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<div class="card shadow-sm">
  <div class="card-header d-flex justify-content-between align-items-center bg-white text-dark py-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-receipt-cutoff text-info me-2"></i> Daftar Berkas Transaksi Penjualan</h5>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 datatable" id="tblPenjualan" style="font-size: 13px;">
          <thead class="table-light text-secondary fw-semibold">
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th>Customer</th>
              <th>Mobil</th>
              <th>Tgl Jual</th>
              <th>Total Kesepakatan</th>
              <th>Total Dibayar</th>
              <th>Sisa Tagihan</th>
              <th class="text-center">Progress STNK</th>
              <th class="text-center">Progress BPKB</th>
              <th class="text-center">Status Lunas</th>
              <th class="text-center" style="width: 150px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($penjualan)): ?>
                <?php foreach ($penjualan as $i => $p): ?>
                <tr>
                  <td class="text-center text-muted"><?= $i + 1 ?></td>
                  <td>
                    <div class="fw-bold text-dark"><?= esc($p['nama_customer'] ?? '-') ?></div>
                    <small class="text-muted"><?= esc($p['telepon'] ?? '-') ?></small>
                  </td>
                  <td>
                    <div class="text-dark fw-semibold"><?= esc($p['nama_mobil'] ?? '-') ?></div>
                    <small class="text-muted"><?= esc($p['tipe'] ?? '-') ?> / <?= esc($p['warna'] ?? '-') ?></small>
                  </td>
                  <td class="text-muted"><?= date('d/m/Y', strtotime($p['tgl_penjualan'])) ?></td>
                  <td class="fw-semibold text-dark">Rp<?= number_format($p['total_harga'], 0, ',', '.') ?></td>
                  <td class="text-success fw-semibold">Rp<?= number_format($p['total_dibayar'], 0, ',', '.') ?></td>
                  <td class="<?= $p['sisa_tagihan'] > 0 ? 'text-danger fw-bold' : 'text-success fw-semibold' ?>">
                    Rp<?= number_format($p['sisa_tagihan'], 0, ',', '.') ?>
                  </td>
                  
                  <td class="text-center">
                    <?php 
                      $cs = [
                        'belum'   => 'bg-danger-subtle text-danger border border-danger-subtle',
                        'proses'  => 'bg-warning-subtle text-dark border border-warning-subtle',
                        'selesai' => 'bg-success-subtle text-success border border-success-subtle'
                      ][$p['proses_stnk']] ?? 'bg-secondary'; 
                    ?>
                    <span class="badge <?= $cs ?> px-2 py-1"><?= strtoupper($p['proses_stnk']) ?></span>
                  </td>
                  
                  <td class="text-center">
                    <?php 
                      $cb = [
                        'belum'   => 'bg-danger-subtle text-danger border border-danger-subtle',
                        'proses'  => 'bg-warning-subtle text-dark border border-warning-subtle',
                        'selesai' => 'bg-success-subtle text-success border border-success-subtle'
                      ][$p['proses_bpkb']] ?? 'bg-secondary'; 
                    ?>
                    <span class="badge <?= $cb ?> px-2 py-1"><?= strtoupper($p['proses_bpkb']) ?></span>
                  </td>

                  <td class="text-center">
                    <span class="badge <?= $p['status_lunas'] === 'lunas' ? 'bg-success' : 'bg-warning text-dark' ?> px-2 py-1">
                      <?= $p['status_lunas'] === 'lunas' ? 'LUNAS' : 'BELUM LUNAS' ?>
                    </span>
                  </td>

                  <td class="text-center">
                    <div class="btn-group gap-1">
                      <a href="<?= base_url('penjualan/detail/' . $p['id_penjualan']) ?>" class="btn btn-sm btn-outline-info" title="Lihat Detail & Progress">
                        <i class="bi bi-eye-fill"></i>
                      </a>
                      
                      <a href="<?= base_url('pembayaran/create/' . $p['id_penjualan']) ?>" class="btn btn-sm btn-outline-success" title="Input Kas Masuk / Pelunasan">
                        <i class="bi bi-cash-coin"></i>
                      </a>
                      
                      <a href="<?= base_url('penyerahan/create/' . $p['id_penjualan']) ?>" class="btn btn-sm btn-outline-primary" title="Proses Penyerahan Mobil">
                        <i class="bi bi-box-seam-fill"></i>
                      </a>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                  <td colspan="11" class="text-center p-5 text-muted">
                    <i class="bi bi-inbox fs-3 d-block mb-2 text-secondary"></i>
                    Belum ada lembar berkas transaksi penjualan yang terekam di showroom.
                  </td>
                </tr>
            <?php endif; ?>
          </tbody>
        </table>
    </div>
  </div>
</div>

<?= view('templates/header') ?>