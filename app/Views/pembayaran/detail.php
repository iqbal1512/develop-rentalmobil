<?php $title = 'Detail Pembayaran'; ?>
<?= view('templates/header') ?>

<div class="card shadow-sm">
  <div class="card-header bg-white text-dark d-flex justify-content-between align-items-center">
    <h5 class="mb-0"><i class="bi bi-info-circle text-info me-2"></i> Rincian Pembayaran</h5>
    <a href="<?= base_url('pembayaran') ?>" class="btn btn-sm btn-outline-light">Kembali</a>
  </div>
  <div class="card-body text-dark">
    <div class="row">
      <div class="col-md-6">
        <table class="table table-borderless text-dark">
          <tr><td width="30%"><strong>ID Pembayaran</strong></td><td>: #<?= $pembayaran['id_pembayaran'] ?></td></tr>
          <tr><td><strong>Jenis</strong></td><td>: <?= strtoupper(str_replace('_', ' ', $pembayaran['jenis_pembayaran'])) ?></td></tr>
          <tr><td><strong>Metode</strong></td><td>: <?= ucfirst($pembayaran['metode_bayar']) ?></td></tr>
          <tr><td><strong>Tanggal Bayar</strong></td><td>: <?= date('d M Y', strtotime($pembayaran['tgl_bayar'])) ?></td></tr>
          <tr><td><strong>Jumlah</strong></td><td>: <span class="text-success fw-bold">Rp<?= number_format($pembayaran['jumlah_bayar'], 0, ',', '.') ?></span></td></tr>
        </table>
      </div>
      <div class="col-md-6">
        <table class="table table-borderless text-dark">
          <tr><td width="30%"><strong>Status Verifikasi</strong></td>
              <td>: 
                  <?php if($pembayaran['status_verifikasi'] === 'terverifikasi'): ?>
                      <span class="badge bg-success">Terverifikasi</span>
                  <?php elseif($pembayaran['status_verifikasi'] === 'ditolak'): ?>
                      <span class="badge bg-danger">Ditolak</span>
                  <?php else: ?>
                      <span class="badge bg-warning text-dark">Menunggu</span>
                  <?php endif; ?>
              </td>
          </tr>
          <tr><td><strong>No Kwitansi</strong></td><td>: <?= $pembayaran['no_kwitansi'] ?: '-' ?></td></tr>
          <tr><td><strong>Keterangan</strong></td><td>: <?= $pembayaran['keterangan'] ?: '-' ?></td></tr>
        </table>
        
        <?php if($pembayaran['metode_bayar'] === 'transfer' && $pembayaran['bukti_transfer']): ?>
            <div class="mt-3">
                <strong>Bukti Transfer:</strong><br>
                <img src="<?= base_url('uploads/bukti/' . $pembayaran['bukti_transfer']) ?>" class="img-fluid img-thumbnail mt-2" style="max-height: 250px" alt="Bukti Transfer">
            </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?= view('templates/footer') ?>
