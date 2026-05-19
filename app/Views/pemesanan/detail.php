<?php $title = 'Detail Pemesanan'; ?>
<?= view('templates/header') ?>

<div class="row">
  <!-- Info Pemesanan -->
  <div class="col-8">
    <div class="card shadow-sm bg-white border-light text-dark">
      <div class="card-header bg-white text-dark border-light">
        <h5><i class="bi bi-file-text-fill text-accent"></i> Detail Pemesanan #<?= $pemesanan['id_pemesanan'] ?></h5>
        <a href="<?= base_url('pemesanan') ?>" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-6">
            <table class="table table-borderless text-dark" style="width:100%;font-size:13.5px">
              <tr><td class="text-muted" style="padding:6px 0;width:140px">Customer</td><td class="fw-600"><?= esc($pemesanan['nama_customer']) ?></td></tr>
              <tr><td class="text-muted" style="padding:6px 0">No. KTP</td><td><?= esc($pemesanan['no_ktp']) ?></td></tr>
              <tr><td class="text-muted" style="padding:6px 0">Telepon</td><td><?= esc($pemesanan['telepon_customer'] ?? '-') ?></td></tr>
              <tr><td class="text-muted" style="padding:6px 0">Tgl Pesan</td><td><?= date('d M Y', strtotime($pemesanan['tgl_pesan'])) ?></td></tr>
              <tr><td class="text-muted" style="padding:6px 0">Jatuh Tempo</td>
                <td class="<?= strtotime($pemesanan['tgl_jatuh_tempo']) < time() ? 'text-danger fw-600' : '' ?>">
                  <?= date('d M Y', strtotime($pemesanan['tgl_jatuh_tempo'])) ?>
                </td>
              </tr>
            </table>
          </div>
          <div class="col-6">
            <table class="table table-borderless text-dark" style="width:100%;font-size:13.5px">
              <tr><td class="text-muted" style="padding:6px 0;width:140px">Mobil</td><td class="fw-600"><?= esc($pemesanan['nama_mobil']) ?></td></tr>
              <tr><td class="text-muted" style="padding:6px 0">Warna</td><td><?= esc($pemesanan['warna_mobil'] ?? '-') ?></td></tr>
              <tr><td class="text-muted" style="padding:6px 0">Harga Jual</td><td>Rp<?= number_format($pemesanan['harga_jual'],0,',','.') ?></td></tr>
              <tr><td class="text-muted" style="padding:6px 0">Harga Jadi</td><td class="text-success fw-600">Rp<?= number_format($pemesanan['harga_jual_jadi'],0,',','.') ?></td></tr>
              <tr><td class="text-muted" style="padding:6px 0">Status</td>
                <td>
                  <?php $sc = ['menunggu'=>'badge-warning','dp_masuk'=>'badge-info','diproses'=>'badge-accent','selesai'=>'badge-success','batal'=>'badge-danger'][$pemesanan['status_pemesanan']] ?? 'badge-muted'; ?>
                  <span class="badge <?= $sc ?>"><?= ucfirst(str_replace('_',' ',$pemesanan['status_pemesanan'])) ?></span>
                </td>
              </tr>
            </table>
          </div>
        </div>
        <div class="divider"></div>
        <div class="row">
          <div class="col-4">
            <div style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:var(--radius-sm);padding:14px;text-align:center">
              <div class="text-muted fs-12">Nominal DP (30%)</div>
              <div class="fw-700 text-warning" style="font-size:16px">Rp<?= number_format($pemesanan['nominal_dp'],0,',','.') ?></div>
            </div>
          </div>
          <div class="col-4">
            <div style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:var(--radius-sm);padding:14px;text-align:center">
              <div class="text-muted fs-12">DP Dibayar</div>
              <div class="fw-700 text-success" style="font-size:16px">Rp<?= number_format($pemesanan['dp_awal_dibayar'],0,',','.') ?></div>
            </div>
          </div>
          <div class="col-4">
            <div style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:var(--radius-sm);padding:14px;text-align:center">
              <div class="text-muted fs-12">Sisa DP</div>
              <div class="fw-700 text-danger" style="font-size:16px">Rp<?= number_format($pemesanan['sisa_dp_internal'],0,',','.') ?></div>
            </div>
          </div>
        </div>
        <?php if ($pemesanan['catatan']): ?>
        <div class="divider"></div>
        <div><span class="text-muted fs-12">Catatan:</span> <?= esc($pemesanan['catatan']) ?></div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Aksi -->
  <div class="col-4">
    <div class="card shadow-sm bg-white border-light text-dark">
      <div class="card-header bg-white text-dark border-light"><h5><i class="bi bi-lightning-fill text-warning"></i> Aksi</h5></div>
      <div class="card-body">
        <?php if (in_array($pemesanan['status_pemesanan'], ['menunggu','dp_masuk'])): ?>
        <a href="<?= base_url('penjualan/create/' . $pemesanan['id_pemesanan']) ?>" class="btn btn-success w-100" style="margin-bottom:10px">
          <i class="bi bi-cart-check-fill"></i> Buat Transaksi Penjualan
        </a>
        <?php endif; ?>
        <a href="<?= base_url('pemesanan/edit/' . $pemesanan['id_pemesanan']) ?>" class="btn btn-warning w-100" style="margin-bottom:10px">
          <i class="bi bi-pencil-fill"></i> Edit Pemesanan
        </a>
        <?php if ($pemesanan['status_pemesanan'] !== 'batal' && $pemesanan['status_pemesanan'] !== 'selesai'): ?>
        <button onclick="confirmAction('<?= base_url('pemesanan/batal/' . $pemesanan['id_pemesanan']) ?>','Batalkan?','Bukti pesanan hangus.','warning','Ya, Batalkan','#ef4444')"
          class="btn btn-danger w-100">
          <i class="bi bi-x-circle-fill"></i> Batalkan Pemesanan
        </button>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?= view('templates/footer') ?>
