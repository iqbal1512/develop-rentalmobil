<?php $title = 'Transaksi Penjualan'; ?>
<?= view('templates/header') ?>

<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<div class="card shadow-sm">
  <div class="card-header d-flex justify-content-between align-items-center bg-white text-dark">
    <h5 class="mb-0"><i class="bi bi-receipt-cutoff text-info me-2"></i> Daftar Transaksi Penjualan</h5>
    </div>
  <div class="card-body p-0">
    <div class="table-responsive">
        <table class="table table-hover table-hover mb-0 datatable" id="tblPenjualan">
          <thead class="table-hover">
            <tr>
              <th class="text-center">#</th>
              <th>Customer</th>
              <th>Mobil</th>
              <th>Tgl Jual</th>
              <th>Total</th>
              <th>Dibayar</th>
              <th>Sisa</th>
              <th>STNK</th>
              <th>BPKB</th>
              <th>Lunas?</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($penjualans)): ?>
                <?php foreach ($penjualans as $i => $p): ?>
                <tr>
                  <td class="text-center"><?= $i + 1 ?></td>
                  <td class="fw-bold text-dark"><?= esc($p['nama_customer'] ?? '-') ?></td>
                  <td class="text-info small"><?= esc($p['nama_mobil'] ?? '-') ?></td>
                  <td class="text-muted small"><?= date('d/m/Y', strtotime($p['tgl_penjualan'])) ?></td>
                  <td class="fw-bold">Rp<?= number_format($p['total_harga'], 0, ',', '.') ?></td>
                  <td class="text-success">Rp<?= number_format($p['total_dibayar'], 0, ',', '.') ?></td>
                  <td class="<?= $p['sisa_tagihan'] > 0 ? 'text-danger fw-bold' : 'text-success' ?>">
                    Rp<?= number_format($p['sisa_tagihan'], 0, ',', '.') ?>
                  </td>
                  
                  <td>
                    <?php $cs = ['belum'=>'bg-danger','proses'=>'bg-warning text-dark','selesai'=>'bg-success'][$p['proses_stnk']] ?? 'bg-secondary'; ?>
                    <span class="badge <?= $cs ?> small"><?= ucfirst($p['proses_stnk']) ?></span>
                  </td>
                  
                  <td>
                    <?php $cb = ['belum'=>'bg-danger','proses'=>'bg-warning text-dark','selesai'=>'bg-success'][$p['proses_bpkb']] ?? 'bg-secondary'; ?>
                    <span class="badge <?= $cb ?> small"><?= ucfirst($p['proses_bpkb']) ?></span>
                  </td>

                  <td>
                    <span class="badge <?= $p['status_lunas'] === 'lunas' ? 'bg-success' : 'bg-warning text-dark' ?>">
                      <?= $p['status_lunas'] === 'lunas' ? 'LUNAS' : 'PENDING' ?>
                    </span>
                  </td>

                  <td class="text-center">
                    <div class="btn-group">
                      <a href="<?= base_url('penjualan/detail/' . $p['id_penjualan']) ?>" class="btn btn-sm btn-outline-info" title="Detail">
                        <i class="bi bi-eye-fill"></i>
                      </a>
                      <a href="<?= base_url('pembayaran/create/' . $p['id_penjualan']) ?>" class="btn btn-sm btn-outline-success" title="Tambah Pembayaran">
                        <i class="bi bi-credit-card-fill"></i>
                      </a>
                      <?php if (!in_array($p['id_penjualan'], array_column($penyerahans ?? [], 'id_penjualan'))): ?>
                      <a href="<?= base_url('penyerahan/create/' . $p['id_penjualan']) ?>" class="btn btn-sm btn-primary" title="Penyerahan">
                        <i class="bi bi-box-seam-fill"></i>
                      </a>
                      <?php endif; ?>
                      <a href="<?= base_url('penjualan/edit/' . $p['id_penjualan']) ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                        <i class="bi bi-pencil-fill"></i>
                      </a>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                  <td colspan="11" class="text-center p-5 text-muted">Belum ada transaksi penjualan terekam.</td>
                </tr>
            <?php endif; ?>
          </tbody>
        </table>
    </div>
  </div>
</div>

<?= view('templates/footer') ?>