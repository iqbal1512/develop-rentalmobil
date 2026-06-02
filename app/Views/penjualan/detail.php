<?php $title = 'Detail Penjualan'; ?>
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

<div class="row g-4">
  <div class="col-lg-8">
    <div class="card shadow-sm bg-white border-light text-dark mb-4">
      <div class="card-header bg-white text-dark d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold"><i class="bi bi-receipt-cutoff text-info me-2"></i> Lembar Penjualan #<?= $penjualan['id_penjualan'] ?></h5>
        <a href="<?= base_url('penjualan') ?>" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-6">
            <table class="table table-borderless align-middle mb-0 text-dark" style="font-size:13.5px">
              <tr>
                <td class="text-muted py-2" style="width:120px">Customer</td>
                <td class="fw-bold text-dark">: <?= esc($penjualan['nama_customer'] ?? '-') ?></td>
              </tr>
              <tr>
                <td class="text-muted py-2">Unit Mobil</td>
                <td class="fw-bold text-primary">: <?= esc($penjualan['nama_mobil'] ?? '-') ?> <small class="text-muted fw-normal">(<?= esc($penjualan['no_polisi'] ?? '-') ?>)</small></td>
              </tr>
              <tr>
                <td class="text-muted py-2">Tanggal Transaksi</td>
                <td>: <?= date('d M Y', strtotime($penjualan['tgl_penjualan'])) ?></td>
              </tr>
              <tr>
                <td class="text-muted py-2">Status Lulus</td>
                <td>: 
                  <?php 
                    $sl = [
                      'proses' => 'bg-warning text-dark',
                      'lulus'  => 'bg-success',
                      'gagal'  => 'bg-danger'
                    ][$penjualan['status_lulus']] ?? 'bg-secondary'; 
                  ?>
                  <span class="badge <?= $sl ?>"><?= strtoupper($penjualan['status_lulus']) ?></span>
                </td>
              </tr>
            </table>
          </div>
          <div class="col-md-6">
            <table class="table table-borderless align-middle mb-0 text-dark" style="font-size:13.5px">
              <tr>
                <td class="text-muted py-2" style="width:120px">Total Kesepakatan</td>
                <td class="fw-bold text-dark">: Rp<?= number_format($penjualan['total_harga'], 0, ',', '.') ?></td>
              </tr>
              <tr>
                <td class="text-muted py-2">Total Dibayar</td>
                <td class="text-success fw-bold">: Rp<?= number_format($penjualan['total_dibayar'], 0, ',', '.') ?></td>
              </tr>
              <tr>
                <td class="text-muted py-2">Sisa Tagihan</td>
                <td class="<?= $penjualan['sisa_tagihan'] > 0 ? 'text-danger fw-bold' : 'text-success fw-bold' ?>">
                  : Rp<?= number_format($penjualan['sisa_tagihan'], 0, ',', '.') ?>
                </td>
              </tr>
              <tr>
                <td class="text-muted py-2">Status Lunas</td>
                <td>: 
                  <span class="badge <?= $penjualan['status_lunas'] === 'lunas' ? 'bg-success' : 'bg-warning text-dark' ?>">
                    <?= $penjualan['status_lunas'] === 'lunas' ? 'LUNAS' : 'BELUM LUNAS' ?>
                  </span>
                </td>
              </tr>
            </table>
          </div>
        </div>

        <hr class="my-4 text-muted">

        <h6 class="text-secondary fw-bold text-uppercase mb-3" style="font-size:12px; letter-spacing:1px;"><i class="bi bi-file-earmark-medical me-1"></i> Progress Dokumen Kendaraan</h6>
        <div class="row g-3 mb-2">
          <div class="col-md-6">
            <div class="p-3 border rounded bg-light">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-dark fw-semibold">STNK <small class="text-muted block-sm">(Estimasi ~2 Minggu)</small></span>
                <?php 
                  $cs = [
                    'belum'   => 'bg-danger-subtle text-danger border border-danger-subtle',
                    'proses'  => 'bg-warning-subtle text-dark border border-warning-subtle',
                    'selesai' => 'bg-success-subtle text-success border border-success-subtle'
                  ][$penjualan['proses_stnk']] ?? 'bg-secondary'; 
                ?>
                <span class="badge <?= $cs ?>"><?= strtoupper($penjualan['proses_stnk']) ?></span>
              </div>
              <a href="<?= base_url('penjualan/updateStnk/' . $penjualan['id_penjualan']) ?>" class="btn btn-sm btn-outline-dark w-100">
                <i class="bi bi-arrow-repeat me-1"></i> Toggle Status Progress STNK
              </a>
            </div>
          </div>
          
          <div class="col-md-6">
            <div class="p-3 border rounded bg-light">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-dark fw-semibold">BPKB <small class="text-muted block-sm">(Estimasi ~2 Bulan)</small></span>
                <?php 
                  $cb = [
                    'belum'   => 'bg-danger-subtle text-danger border border-danger-subtle',
                    'proses'  => 'bg-warning-subtle text-dark border border-warning-subtle',
                    'selesai' => 'bg-success-subtle text-success border border-success-subtle'
                  ][$penjualan['proses_bpkb']] ?? 'bg-secondary'; 
                ?>
                <span class="badge <?= $cb ?>"><?= strtoupper($penjualan['proses_bpkb']) ?></span>
              </div>
              <a href="<?= base_url('penjualan/updateBpkb/' . $penjualan['id_penjualan']) ?>" class="btn btn-sm btn-outline-dark w-100">
                <i class="bi bi-arrow-repeat me-1"></i> Toggle Status Progress BPKB
              </a>
            </div>
          </div>
        </div>

        <?php if(!empty($penjualan['catatan'])): ?>
          <div class="mt-3 p-2 bg-light border-start border-3 border-info rounded-end text-muted" style="font-size:13px;">
             <strong>Catatan internal:</strong> <?= esc($penjualan['catatan']) ?>
          </div>
        <?php endif; ?>

        <hr class="my-4 text-muted">

        <h6 class="text-secondary fw-bold text-uppercase mb-3" style="font-size:12px; letter-spacing:1px;"><i class="bi bi-clock-history me-1"></i> Histori Aliran Dana Pembayaran</h6>
        <?php if (!empty($pembayaran)): ?>
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" style="font-size:13px">
            <thead class="table-light text-secondary">
              <tr>
                <th>Tgl Bayar</th>
                <th>Jenis Tagihan</th>
                <th>Metode</th>
                <th>Jumlah Masuk</th>
                <th>No. Kwitansi</th>
                <th class="text-center">Status Verifikasi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($pembayaran as $b): ?>
              <tr>
                <td class="text-muted"><?= date('d/m/Y', strtotime($b['tgl_bayar'])) ?></td>
                <td><span class="badge bg-secondary-subtle text-dark border"><?= esc(strtoupper(str_replace('_',' ',$b['jenis_pembayaran']))) ?></span></td>
                <td><span class="badge <?= $b['metode_bayar'] === 'transfer' ? 'bg-info-subtle text-info border border-info-subtle' : 'bg-success-subtle text-success border border-success-subtle' ?>"><?= esc(strtoupper($b['metode_bayar'])) ?></span></td>
                <td class="text-success fw-bold">Rp<?= number_format($b['jumlah_bayar'], 0, ',', '.') ?></td>
                <td class="text-muted font-monospace"><?= esc($b['no_kwitansi'] ?? '-') ?></td>
                <td class="text-center">
                  <?php 
                    $sv = [
                      'menunggu'     => 'bg-warning text-dark',
                      'terverifikasi'=> 'bg-success',
                      'ditolak'      => 'bg-danger'
                    ][$b['status_verifikasi']] ?? 'bg-secondary'; 
                  ?>
                  <span class="badge <?= $sv ?>"><?= strtoupper($b['status_verifikasi']) ?></span>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php else: ?>
        <div class="text-center text-muted p-4 border rounded bg-light">
          <i class="bi bi-wallet2 d-block fs-4 mb-1 text-secondary"></i>
          Belum ada rekaman cicilan pelunasan kas masuk untuk transaksi ini.
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card shadow-sm bg-white border-light text-dark position-sticky" style="top: 20px;">
      <div class="card-header bg-white text-dark py-3">
        <h5 class="mb-0 fw-bold"><i class="bi bi-lightning-charge-fill text-warning me-1"></i> Aksi Administrasi</h5>
      </div>
      <div class="card-body d-flex flex-column gap-2">
        
        <a href="<?= base_url('pembayaran/create/' . $penjualan['id_penjualan']) ?>" class="btn btn-success py-2 fw-semibold w-100">
          <i class="bi bi-cash-coin me-1"></i> Input Angsuran Baru
        </a>
        
        <?php if (empty($penyerahan)): ?>
        <a href="<?= base_url('penyerahan/create/' . $penjualan['id_penjualan']) ?>" class="btn btn-primary py-2 fw-semibold w-100">
          <i class="bi bi-box-seam-fill me-1"></i> Terbitkan Berkas BAST
        </a>
        <?php else: ?>
        <a href="<?= base_url('penyerahan/edit/' . $penyerahan['id_penyerahan']) ?>" class="btn btn-outline-primary py-2 fw-semibold w-100">
          <i class="bi bi-pencil-square me-1"></i> Atur Berkas BAST
        </a>
        <?php endif; ?>
        
        <a href="<?= base_url('penjualan/edit/' . $penjualan['id_penjualan']) ?>" class="btn btn-warning py-2 fw-semibold w-100 text-dark">
          <i class="bi bi-pencil-fill text-dark me-1"></i> Ubah Catatan / Kelulusan
        </a>

        <div class="border-top my-2"></div>

        <a href="<?= base_url('penjualan/cetak/' . $penjualan['id_penjualan']) ?>" target="_blank" class="btn btn-outline-secondary py-2 fw-semibold w-100">
          <i class="bi bi-printer-fill me-1"></i> Cetak Invoice Resmi (PDF)
        </a>
        
      </div>
    </div>
  </div>
</div>

<?= view('templates/footer') ?>