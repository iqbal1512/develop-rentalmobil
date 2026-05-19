<?php $title = 'Manajemen Pembayaran'; ?>
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
  <div class="card-header d-flex justify-content-between align-items-center bg-white text-dark">
    <h5 class="mb-0"><i class="bi bi-credit-card-2-front-fill text-info me-2"></i> Daftar Pembayaran</h5>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
        <table class="table table-hover mb-0 datatable" id="tblPembayaran">
          <thead class="table-hover">
            <tr>
              <th class="text-center" style="width: 50px;">#</th> <th>Customer</th> <th>Jenis</th> <th>Metode</th> <th>Tgl Bayar</th> <th>Jumlah</th> <th>Kwitansi</th> <th class="text-center">Bukti</th> <th class="text-center">Status</th> <th class="text-center">Aksi</th> </tr>
          </thead>
          <tbody>
            <?php if (!empty($pembayarans)): ?>
                <?php foreach ($pembayarans as $i => $b): ?>
                <tr>
                  <td class="text-center"><?= $i + 1 ?></td> <td class="fw-bold text-dark"><?= esc($b['nama_customer'] ?? '-') ?></td> <td> <span class="badge border border-info text-info small" style="font-size: 10px;">
                        <?= strtoupper(str_replace('_',' ',$b['jenis_pembayaran'])) ?>
                    </span>
                  </td>
                  <td> <span class="badge <?= $b['metode_bayar'] === 'transfer' ? 'bg-info text-dark' : 'bg-success' ?> small">
                      <?= ucfirst($b['metode_bayar'] ?? 'tunai') ?>
                    </span>
                  </td>
                  <td class="text-muted small"><?= date('d/m/Y', strtotime($b['tgl_bayar'])) ?></td> <td class="text-success fw-bold">Rp<?= number_format($b['jumlah_bayar'], 0, ',', '.') ?></td> <td class="text-muted small"><?= esc($b['no_kwitansi'] ?? '-') ?></td> <td class="text-center"> <?php if (!empty($b['bukti_transfer'])): ?>
                    <a href="<?= base_url('uploads/pembayaran/' . $b['bukti_transfer']) ?>" target="_blank" class="btn btn-xs btn-outline-info py-0 px-2" style="font-size: 11px;">
                      <i class="bi bi-image"></i> Lihat
                    </a>
                    <?php else: ?>
                    <span class="text-muted small">-</span>
                    <?php endif; ?>
                  </td>

                  <td class="text-center"> <?php 
                        $status = $b['status_verifikasi'] ?? 'menunggu';
                        $sv = [
                            'menunggu'      => 'bg-warning text-dark',
                            'terverifikasi' => 'bg-success',
                            'ditolak'       => 'bg-danger'
                        ][$status] ?? 'bg-secondary';
                    ?>
                    <span class="badge <?= $sv ?>"><?= ucfirst($status) ?></span>
                  </td>

                  <td class="text-center"> <div class="btn-group">
                      <a href="<?= base_url('pembayaran/detail/' . $b['id_pembayaran']) ?>" class="btn btn-sm btn-outline-info" title="Detail">
                        <i class="bi bi-eye-fill"></i>
                      </a>

                      <?php if ($status === 'menunggu'): ?>
                        <a href="<?= base_url('pembayaran/verifikasi/' . $b['id_pembayaran']) ?>" 
                           class="btn btn-sm btn-outline-success" 
                           onclick="return confirm('Verifikasi pembayaran ini?')" title="Verifikasi">
                          <i class="bi bi-check-lg"></i>
                        </a>
                        <a href="<?= base_url('pembayaran/tolak/' . $b['id_pembayaran']) ?>" 
                           class="btn btn-sm btn-outline-danger" 
                           onclick="return confirm('Tolak pembayaran ini?')" title="Tolak">
                          <i class="bi bi-x-lg"></i>
                        </a>
                      <?php endif; ?>

                      <?php if ($status === 'terverifikasi'): ?>
                        <a href="<?= base_url('pembayaran/cetak-kwitansi/' . $b['id_pembayaran']) ?>" target="_blank" class="btn btn-sm btn-outline-primary" title="Cetak Kwitansi">
                          <i class="bi bi-printer-fill"></i>
                        </a>
                      <?php endif; ?>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                  <td colspan="10" class="text-center p-5 text-muted">Belum ada data pembayaran masuk.</td>
                </tr>
            <?php endif; ?>
          </tbody>
        </table>
    </div>
  </div>
</div>

<?= view('templates/footer') ?>