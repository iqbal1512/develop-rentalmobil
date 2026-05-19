<?php $title = 'Manajemen Pembayaran Pembelian'; ?>
<?= view('templates/header') ?>

<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if (session()->getFlashdata('warning')): ?>
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= session()->getFlashdata('warning') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-x-circle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<!-- Tabs Navigation -->
<ul class="nav nav-tabs mb-4" id="pembayaranTabs" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="unpaid-tab" data-bs-toggle="tab" data-bs-target="#unpaid" type="button" role="tab" aria-controls="unpaid" aria-selected="true">
      <i class="bi bi-hourglass-split me-2"></i> Belum Dibayar (<?= count($belumBayar) ?>)
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="paid-tab" data-bs-toggle="tab" data-bs-target="#paid" type="button" role="tab" aria-controls="paid" aria-selected="false">
      <i class="bi bi-check-circle-fill me-2"></i> Riwayat Pembayaran (<?= count($riwayat) ?>)
    </button>
  </li>
</ul>

<div class="tab-content" id="pembayaranTabsContent">
  
  <!-- Tab 1: Belum Dibayar -->
  <div class="tab-pane fade show active" id="unpaid" role="tabpanel" aria-labelledby="unpaid-tab">
    <div class="card shadow-sm">
      <div class="card-header bg-white text-dark py-3">
        <h5 class="mb-0"><i class="bi bi-cash-stack text-warning me-2"></i> Pembelian Menunggu Pembayaran</h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0 datatable" id="tblBelumBayar">
            <thead>
              <tr>
                <th class="text-center" style="width: 50px;">#</th>
                <th>Supplier</th>
                <th>Mobil</th>
                <th>Tgl Transaksi</th>
                <th>Jumlah Unit</th>
                <th>Total Harga</th>
                <th>Status</th>
                <th class="text-center" style="width: 150px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($belumBayar)): ?>
                <?php $i = 1; foreach ($belumBayar as $p): ?>
                <tr>
                  <td class="text-center"><?= $i++ ?></td>
                  <td><?= esc($p['nama_supplier'] ?? 'Tidak Terdata') ?></td>
                  <td class="fw-bold text-dark"><?= esc($p['nama_mobil']) ?></td>
                  <td class="text-muted small"><?= date('d/m/Y', strtotime($p['tgl_pembelian'])) ?></td>
                  <td><?= $p['jumlah_pembelian'] ?> Unit</td>
                  <td class="text-danger fw-bold">Rp<?= number_format($p['total_harga'], 0, ',', '.') ?></td>
                  <td>
                    <span class="badge bg-warning text-dark">Pending / Proses</span>
                  </td>
                  <td class="text-center">
                    <a href="<?= base_url('pembayaran_pembelian/create/' . $p['id_pembelian']) ?>" class="btn btn-sm btn-primary">
                      <i class="bi bi-wallet2"></i> Input Pembayaran
                    </a>
                  </td>
                </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="8" class="text-center p-5 text-muted">Semua transaksi pembelian telah lunas dibayar.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Tab 2: Riwayat Pembayaran -->
  <div class="tab-pane fade" id="paid" role="tabpanel" aria-labelledby="paid-tab">
    <div class="card shadow-sm">
      <div class="card-header bg-white text-dark py-3">
        <h5 class="mb-0"><i class="bi bi-check-all text-success me-2"></i> Riwayat Pembayaran Lunas</h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0 datatable" id="tblRiwayatBayar">
            <thead>
              <tr>
                <th class="text-center" style="width: 50px;">#</th>
                <th>No. Kwitansi</th>
                <th>Supplier</th>
                <th>Mobil</th>
                <th>Metode</th>
                <th>Jumlah Bayar</th>
                <th class="text-center">Bukti Transfer</th>
                <th class="text-center" style="width: 150px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($riwayat)): ?>
                <?php $j = 1; foreach ($riwayat as $r): ?>
                <tr>
                  <td class="text-center"><?= $j++ ?></td>
                  <td class="text-info fw-bold"><?= esc($r['no_kwitansi'] ?? '-') ?></td>
                  <td><?= esc($r['nama_supplier'] ?? 'Tidak Terdata') ?></td>
                  <td class="fw-bold text-dark"><?= esc($r['nama_mobil']) ?></td>
                  <td>
                    <span class="badge <?= $r['metode_bayar'] === 'transfer' ? 'bg-info text-dark' : 'bg-success' ?> text-uppercase small">
                      <?= esc($r['metode_bayar']) ?>
                    </span>
                  </td>
                  <td class="text-success fw-bold">Rp<?= number_format($r['total_harga'], 0, ',', '.') ?></td>
                  <td class="text-center">
                    <?php if (!empty($r['bukti_transfer'])): ?>
                      <a href="<?= base_url('uploads/bukti/' . $r['bukti_transfer']) ?>" target="_blank" class="btn btn-xs btn-outline-info py-0 px-2" style="font-size: 11px;">
                        <i class="bi bi-image"></i> Lihat Bukti
                      </a>
                    <?php else: ?>
                      <span class="text-muted small">- (Tunai)</span>
                    <?php endif; ?>
                  </td>
                  <td class="text-center">
                    <div class="btn-group">
                      <a href="<?= base_url('pembayaran_pembelian/detail/' . $r['id_pembelian']) ?>" class="btn btn-sm btn-outline-info" title="Detail">
                        <i class="bi bi-eye-fill"></i> Detail
                      </a>
                      <a href="<?= base_url('pembayaran_pembelian/cetak-kwitansi/' . $r['id_pembelian']) ?>" target="_blank" class="btn btn-sm btn-outline-primary" title="Cetak Kwitansi">
                        <i class="bi bi-printer-fill"></i> Kwitansi
                      </a>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="8" class="text-center p-5 text-muted">Belum ada riwayat pembayaran yang tercatat.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</div>

<?= view('templates/footer') ?>
