<?php $title = 'Transaksi Pembelian Mobil'; ?>
<?= view('templates/header') ?>

<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle-fill"></i> <?= session()->getFlashdata('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<div class="card shadow-sm">
  <div class="card-header d-flex justify-content-between align-items-center bg-white text-dark">
    <h5 class="mb-0"><i class="bi bi-cart-plus-fill text-info me-2"></i> Daftar Pembelian Mobil</h5>
    <a href="<?= base_url('pembelian/create') ?>" class="btn btn-primary btn-sm">
      <i class="bi bi-plus-lg"></i> Tambah Pembelian
    </a>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
       <table class="table table-hover table-hover mb-0 datatable" id="tabelBaruPembelian">
          <thead class="table-hover">
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th>No. Kwitansi</th>
              <th>Supplier</th>
              <th>Mobil</th>
              <th>Tgl Beli</th>
              <th>Total Harga</th>
              <th>Metode</th>
              <th>Status</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($pembelian)): ?>
                <?php foreach ($pembelian as $i => $p): ?>
                <tr>
                  <td class="text-center"><?= $i + 1 ?></td>
                  <td class="text-info fw-bold"><?= esc($p['no_kwitansi'] ?? '-') ?></td>
                  <td><?= esc($p['nama_supplier'] ?? 'Tidak Terdata') ?></td>
                  <td class="fw-bold text-dark"><?= esc($p['nama_mobil'] ?? 'Mobil Terhapus') ?></td>
                  <td class="text-muted small">
                    <?= isset($p['tgl_pembelian']) ? date('d/m/Y', strtotime($p['tgl_pembelian'])) : '-' ?>
                  </td>
                  <td class="text-success fw-bold">Rp<?= number_format($p['total_harga'] ?? 0, 0, ',', '.') ?></td>
                  <td>
                    <span class="badge border border-light text-uppercase small">
                      <?= esc($p['metode_bayar'] ?? 'tunai') ?>
                    </span>
                  </td>
                  <td>
                    <?php 
                    $status = $p['status_pembelian'] ?? 'proses';
                    $badgeClass = [
                        'proses'  => 'bg-warning text-dark',
                        'selesai' => 'bg-success',
                        'batal'   => 'bg-danger'
                    ][$status] ?? 'bg-secondary';
                    ?>
                    <span class="badge <?= $badgeClass ?>"><?= ucfirst($status) ?></span>
                  </td>
                  <td class="text-center">
                    <div class="btn-group">
                      <?php if ($status === 'proses'): ?>
                        <a href="<?= base_url('pembayaran_pembelian/create/' . $p['id_pembelian']) ?>" class="btn btn-sm btn-outline-success" title="Bayar Sekarang">
                          <i class="bi bi-credit-card-fill"></i>
                        </a>
                        <a href="<?= base_url('pembelian/edit/' . $p['id_pembelian']) ?>" class="btn btn-sm btn-outline-warning">
                          <i class="bi bi-pencil-fill"></i>
                        </a>
                      <?php endif; ?>
                      <a href="<?= base_url('pembelian/delete/' . $p['id_pembelian']) ?>" class="btn btn-sm btn-outline-danger confirm-delete" title="Hapus">
                        <i class="bi bi-trash3-fill"></i>
                      </a>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                  <td colspan="9" class="text-center p-5 text-muted">Belum ada riwayat pembelian unit.</td>
                </tr>
            <?php endif; ?>
          </tbody>
        </table>
    </div>
  </div>
</div>

<?= view('templates/footer') ?>