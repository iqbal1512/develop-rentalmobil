<?php $title = 'Detail Pemesanan'; ?>
<?= view('templates/header') ?>

<div class="row m-3">
  <div class="col-md-8">
    <div class="card shadow-sm bg-white border-light text-dark mb-3">
      <div class="card-header bg-white text-dark border-light d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-file-text-fill text-info"></i> Detail Pemesanan #<?= $pemesanan['id_pemesanan'] ?></h5>
        <a href="<?= base_url('pemesanan') ?>" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-6">
            <table class="table table-borderless text-dark mb-0" style="width:100%; font-size:13.5px">
              <tr><td class="text-muted" style="padding:6px 0; width:120px">Customer</td><td class="fw-bold"><?= esc($pemesanan['nama_customer']) ?></td></tr>
              <tr><td class="text-muted" style="padding:6px 0">No. KTP</td><td><?= esc($pemesanan['no_ktp'] ?? '-') ?></td></tr>
              <tr><td class="text-muted" style="padding:6px 0">Telepon</td><td><?= esc($pemesanan['telepon'] ?? '-') ?></td></tr>
              <tr><td class="text-muted" style="padding:6px 0">Tgl Pesan</td><td><?= date('d M Y', strtotime($pemesanan['tgl_pesan'])) ?></td></tr>
              <tr>
                <td class="text-muted" style="padding:6px 0">Jatuh Tempo</td>
                <td class="<?= (strtotime($pemesanan['tgl_jatuh_tempo']) < time() && $pemesanan['status_pemesanan'] == 'menunggu') ? 'text-danger fw-bold' : '' ?>">
                  <?= date('d M Y', strtotime($pemesanan['tgl_jatuh_tempo'])) ?>
                </td>
              </tr>
            </table>
          </div>
          <div class="col-md-6">
            <table class="table table-borderless text-dark mb-0" style="width:100%; font-size:13.5px">
              <tr><td class="text-muted" style="padding:6px 0; width:120px">Mobil</td><td class="fw-bold"><?= esc($pemesanan['nama_mobil']) ?></td></tr>
              <tr><td class="text-muted" style="padding:6px 0">Tipe / Warna</td><td><?= esc($pemesanan['tipe']) ?> / <?= esc($pemesanan['warna'] ?? '-') ?></td></tr>
              <tr><td class="text-muted" style="padding:6px 0">No. Polisi</td><td><span class="badge bg-secondary"><?= esc($pemesanan['no_polisi'] ?? '-') ?></span></td></tr>
              <tr>
                <td class="text-muted" style="padding:6px 0">Status Alur</td>
                <td>
                  <?php 
                    $sc = [
                      'menunggu'   => 'bg-warning text-dark',
                      'dp_masuk'   => 'bg-info text-dark',
                      'diproses'   => 'bg-primary',
                      'selesai'    => 'bg-success',
                      'dibatalkan' => 'bg-danger'
                    ][$pemesanan['status_pemesanan']] ?? 'bg-secondary'; 
                  ?>
                  <span class="badge <?= $sc ?>"><?= strtoupper(str_replace('_',' ',$pemesanan['status_pemesanan'])) ?></span>
                </td>
              </tr>
            </table>
          </div>
        </div>
        
        <hr class="my-4">
        
        <div class="row g-2">
          <div class="col-md-4">
            <div class="p-3 text-center rounded border bg-light">
              <div class="text-muted small mb-1">Harga Kesepakatan (Jadi)</div>
              <div class="fw-bold text-success fs-5">Rp<?= number_format($pemesanan['harga_jadi'], 0, ',', '.') ?></div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="p-3 text-center rounded border bg-light">
              <div class="text-muted small mb-1">Nilai Tanda Jadi</div>
              <div class="fw-bold text-primary fs-5">Rp<?= number_format($pemesanan['nilai_tanda_jadi'], 0, ',', '.') ?></div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="p-3 text-center rounded border bg-light">
              <div class="text-muted small mb-1">Estimasi Target DP (30%)</div>
              <?php $estimasiDp = (($pemesanan['nilai_dp_minimal'] ?? 30) / 100) * $pemesanan['harga_jadi']; ?>
              <div class="fw-bold text-warning fs-5">Rp<?= number_format($estimasiDp, 0, ',', '.') ?></div>
            </div>
          </div>
        </div>

        <?php if (!empty($pemesanan['catatan'])): ?>
          <hr class="my-3">
          <div class="p-2 rounded bg-light-subtle text-muted" style="font-size: 13px;">
            <strong>Catatan Internal:</strong> <?= esc($pemesanan['catatan']) ?>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <div class="card shadow-sm bg-white border-light text-dark">
      <div class="card-header bg-light text-dark border-light">
        <h6 class="mb-0 fw-bold"><i class="bi bi-cash-stack text-success me-1"></i> Histori Pembayaran Uang Muka (Bukti Pesan & DP)</h6>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover table-striped mb-0" style="font-size:13px">
            <thead class="table-light">
              <tr>
                <th class="text-center" style="width:50px">No</th>
                <th>Tanggal Bayar</th>
                <th>Keterangan Jenis</th>
                <th>Metode</th>
                <th class="text-end">Nominal Masuk</th>
                <th class="text-center" style="width:120px">Kwitansi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($pembayaran)): ?>
                <?php foreach ($pembayaran as $index => $pay): ?>
                  <tr>
                    <td class="text-center"><?= $index + 1 ?></td>
                    <td><?= date('d/m/Y', strtotime($pay['tgl_bayar'] ?? $pay['created_at'])) ?></td>
                    <td><span class="badge bg-light text-dark border border-secondary-subtle"><?= esc(strtoupper($pay['jenis_pembayaran'] ?? 'DP/Tanda Jadi')) ?></span></td>
                    <td><?= esc(strtoupper($pay['metode_pembayaran'] ?? 'Transfer')) ?></td>
                    <td class="text-end fw-bold text-success">Rp<?= number_format($pay['nominal'], 0, ',', '.') ?></td>
                    <td class="text-center">
                      <a href="<?= base_url('pembayaran_penjualan/cetak/' . $pay['id_pembayaran']) ?>" target="_blank" class="btn btn-sm btn-outline-dark p-1 py-0" title="Unduh Bukti Kas">
                        <i class="bi bi-download"></i> Unduh
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" class="text-center p-4 text-muted">Belum ada cicilan atau pelunasan DP yang masuk di tabel pembayaran_penjualan.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card shadow-sm bg-white border-light text-dark">
      <div class="card-header bg-white text-dark border-light">
        <h5 class="mb-0"><i class="bi bi-lightning-fill text-warning"></i> Menu Aksi Cepat</h5>
      </div>
      <div class="card-body d-flex flex-column gap-2">
        
        <a href="<?= base_url('pemesanan/cetak/' . $pemesanan['id_pemesanan']) ?>" target="_blank" class="btn btn-dark w-100">
          <i class="bi bi-printer-fill me-1"></i> Cetak Faktur Pemesanan
        </a>

        <hr class="my-1">
        
        <?php if ($pemesanan['status_pemesanan'] === 'dp_masuk'): ?>
          <a href="<?= base_url('penjualan/create/' . $pemesanan['id_pemesanan']) ?>" class="btn btn-success w-100">
            <i class="bi bi-cart-check-fill me-1"></i> Buat Berkas Penjualan
          </a>
        <?php else: ?>
          <button class="btn btn-secondary w-100 text-white-50" disabled style="cursor: not-allowed;">
            <i class="bi bi-lock-fill me-1"></i> Belum Lunas DP (Locked)
          </button>
        <?php endif; ?>
        
        <a href="<?= base_url('pemesanan/edit/' . $pemesanan['id_pemesanan']) ?>" class="btn btn-warning w-100">
          <i class="bi bi-pencil-fill me-1"></i> Edit Data Booking
        </a>
        
        <?php if (!in_array($pemesanan['status_pemesanan'], ['dibatalkan', 'selesai'])): ?>
          <a href="<?= base_url('pemesanan/batal/' . $pemesanan['id_pemesanan']) ?>" 
             onclick="return confirm('Apakah Anda yakin ingin membatalkan transaksi ini? Mobil pesanan akan dikembalikan ke status Tersedia.')" 
             class="btn btn-danger w-100">
            <i class="bi bi-x-circle-fill me-1"></i> Batalkan Pemesanan
          </a>
        <?php endif; ?>
        
      </div>
    </div>
  </div>
</div>

<?= view('templates/footer') ?>