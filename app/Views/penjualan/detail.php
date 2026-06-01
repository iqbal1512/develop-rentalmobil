<?php $title = 'Detail Penjualan'; ?>
<?= view('templates/header') ?>

<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success"><i class="bi bi-check-circle-fill"></i> <?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="row">
  <!-- Detail Card -->
  <div class="col-8">
    <div class="card shadow-sm bg-white border-light text-dark">
      <div class="card-header bg-white text-dark border-light">
        <h5><i class="bi bi-receipt-cutoff text-accent"></i> Detail Penjualan #<?= $penjualan['id_penjualan'] ?></h5>
        <a href="<?= base_url('penjualan') ?>" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-6">
            <table class="table table-borderless text-dark" style="width:100%;font-size:13.5px">
              <tr><td class="text-muted" style="padding:7px 0;width:130px">Customer</td><td class="fw-600"><?= esc($penjualan['nama_customer'] ?? '-') ?></td></tr>
              <tr><td class="text-muted" style="padding:7px 0">Mobil</td><td class="fw-600"><?= esc($penjualan['nama_mobil'] ?? '-') ?></td></tr>
              <tr><td class="text-muted" style="padding:7px 0">Tgl Penjualan</td><td><?= date('d M Y', strtotime($penjualan['tgl_penjualan'])) ?></td></tr>
              <tr><td class="text-muted" style="padding:7px 0">Status Lulus</td>
                <td>
                  <?php $sl=['proses'=>'badge-warning','lulus'=>'badge-success','gagal'=>'badge-danger'][$penjualan['status_lulus']]??'badge-muted'; ?>
                  <span class="badge <?= $sl ?>"><?= ucfirst($penjualan['status_lulus']) ?></span>
                </td>
              </tr>
            </table>
          </div>
          <div class="col-6">
            <table class="table table-borderless text-dark" style="width:100%;font-size:13.5px">
              <tr><td class="text-muted" style="padding:7px 0;width:130px">Total Harga</td><td class="fw-600">Rp<?= number_format($penjualan['total_harga'],0,',','.') ?></td></tr>
              <tr><td class="text-muted" style="padding:7px 0">Dibayar</td><td class="text-success fw-600">Rp<?= number_format($penjualan['total_dibayar'],0,',','.') ?></td></tr>
              <tr><td class="text-muted" style="padding:7px 0">Sisa Tagihan</td>
                <td class="<?= $penjualan['sisa_tagihan'] > 0 ? 'text-danger' : 'text-success' ?> fw-600">
                  Rp<?= number_format($penjualan['sisa_tagihan'],0,',','.') ?>
                </td>
              </tr>
              <tr><td class="text-muted" style="padding:7px 0">Status Lunas</td>
                <td>
                  <span class="badge <?= $penjualan['status_lunas'] === 'lunas' ? 'badge-success' : 'badge-warning' ?>">
                    <?= $penjualan['status_lunas'] === 'lunas' ? 'Lunas' : 'Belum Lunas' ?>
                  </span>
                </td>
              </tr>
            </table>
          </div>
        </div>

        <div class="divider"></div>

        <!-- Progress Dokumen -->
        <h6 class="text-secondary" style="font-size:12px;letter-spacing:1px;text-transform:uppercase;margin-bottom:12px">Progress Dokumen</h6>
        <div class="row">
          <div class="col-6">
            <div style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:var(--radius-sm);padding:14px">
              <div class="d-flex justify-content-between align-items-center">
                <span class="text-secondary">STNK <span class="text-muted fs-12">(~2 minggu)</span></span>
                <?php $cs=['belum'=>'badge-danger','proses'=>'badge-warning','selesai'=>'badge-success'][$penjualan['proses_stnk']]??'badge-muted'; ?>
                <span class="badge <?= $cs ?>"><?= ucfirst($penjualan['proses_stnk']) ?></span>
              </div>
              <?php if ($penjualan['proses_stnk'] !== 'selesai'): ?>
              <a href="<?= base_url('penjualan/update-stnk/' . $penjualan['id_penjualan']) ?>" class="btn btn-sm btn-info w-100" style="margin-top:8px">
                <i class="bi bi-arrow-up-circle"></i> Update Status STNK
              </a>
              <?php endif; ?>
            </div>
          </div>
          <div class="col-6">
            <div style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:var(--radius-sm);padding:14px">
              <div class="d-flex justify-content-between align-items-center">
                <span class="text-secondary">BPKB <span class="text-muted fs-12">(~2 bulan)</span></span>
                <?php $cb=['belum'=>'badge-danger','proses'=>'badge-warning','selesai'=>'badge-success'][$penjualan['proses_bpkb']]??'badge-muted'; ?>
                <span class="badge <?= $cb ?>"><?= ucfirst($penjualan['proses_bpkb']) ?></span>
              </div>
              <?php if ($penjualan['proses_bpkb'] !== 'selesai'): ?>
              <a href="<?= base_url('penjualan/update-bpkb/' . $penjualan['id_penjualan']) ?>" class="btn btn-sm btn-info w-100" style="margin-top:8px">
                <i class="bi bi-arrow-up-circle"></i> Update Status BPKB
              </a>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <!-- Riwayat Pembayaran -->
        <div class="divider"></div>
        <h6 class="text-secondary" style="font-size:12px;letter-spacing:1px;text-transform:uppercase;margin-bottom:12px">Riwayat Pembayaran</h6>
        <?php if (!empty($pembayaran)): ?>
        <table class="table table-hover table-hover" style="font-size:13px">
          <thead>
            <tr>
              <th>Tgl Bayar</th>
              <th>Jenis</th>
              <th>Metode</th>
              <th>Jumlah</th>
              <th>No. Kwitansi</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($pembayaran as $b): ?>
            <tr>
              <td><?= date('d/m/Y', strtotime($b['tgl_bayar'])) ?></td>
              <td><span class="badge badge-accent"><?= ucfirst(str_replace('_',' ',$b['jenis_pembayaran'])) ?></span></td>
              <td><span class="badge <?= $b['metode_bayar']==='transfer'?'badge-info':'badge-success' ?>"><?= ucfirst($b['metode_bayar']) ?></span></td>
              <td class="text-success fw-600">Rp<?= number_format($b['jumlah_bayar'],0,',','.') ?></td>
              <td class="text-muted"><?= esc($b['no_kwitansi'] ?? '-') ?></td>
              <td>
                <?php $sv=['menunggu'=>'badge-warning','terverifikasi'=>'badge-success','ditolak'=>'badge-danger'][$b['status_verifikasi']]??'badge-muted'; ?>
                <span class="badge <?= $sv ?>"><?= ucfirst($b['status_verifikasi']) ?></span>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php else: ?>
        <p class="text-muted" style="text-align:center;padding:16px 0">Belum ada riwayat pembayaran</p>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Aksi Panel -->
  <div class="col-4">
    <div class="card shadow-sm bg-white border-light text-dark">
      <div class="card-header bg-white text-dark border-light"><h5><i class="bi bi-lightning-fill text-warning"></i> Aksi Cepat</h5></div>
      <div class="card-body">
        <a href="<?= base_url('pembayaran/create/' . $penjualan['id_penjualan']) ?>" class="btn btn-success w-100" style="margin-bottom:10px">
          <i class="bi bi-credit-card-fill"></i> Tambah Pembayaran
        </a>
        <?php if (empty($penyerahan ?? null)): ?>
        <a href="<?= base_url('penyerahan/create/' . $penjualan['id_penjualan']) ?>" class="btn btn-primary w-100" style="margin-bottom:10px">
          <i class="bi bi-box-seam-fill"></i> Buat Penyerahan
        </a>
        <?php else: ?>
        <a href="<?= base_url('penyerahan/edit/' . $penyerahan['id_penyerahan']) ?>" class="btn btn-info w-100" style="margin-bottom:10px">
          <i class="bi bi-box-seam-fill"></i> Edit Penyerahan
        </a>
        <?php endif; ?>
        <a href="<?= base_url('penjualan/edit/' . $penjualan['id_penjualan']) ?>" class="btn btn-warning w-100">
          <i class="bi bi-pencil-fill"></i> Edit Penjualan
        </a>
      </div>
    </div>
  </div>
</div>

<?= view('templates/footer') ?>
