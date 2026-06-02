<?php $title = 'Edit Pemesanan'; ?>
<?= view('templates/header') ?>

<div class="card shadow-sm bg-white border-light text-dark" style="max-width:800px;margin:20px auto">
  <div class="card-header bg-white border-light d-flex justify-content-between align-items-center">
    <h5 class="mb-0 text-dark"><i class="bi bi-pencil-square text-warning me-2"></i> Edit Pemesanan</h5>
    <a href="<?= base_url('pemesanan') ?>" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
  </div>
  <div class="card-body">

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger p-2">
            <ul class="mb-0">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('pemesanan/update/' . $pemesanan['id_pemesanan']) ?>" method="POST">
      <?= csrf_field() ?>
      <div class="row g-3">
        
        <div class="col-md-6">
          <div class="form-group">
            <label class="form-label text-dark fw-bold">Customer</label>
            <select name="id_customer" class="form-select bg-white text-dark border-secondary-subtle" required>
              <?php foreach ($customers as $c): ?>
              <option value="<?= $c['id_customer'] ?>" <?= $pemesanan['id_customer'] == $c['id_customer'] ? 'selected' : '' ?>>
                <?= esc($c['nama']) ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label class="form-label text-dark fw-bold">Mobil</label>
            <select name="id_mobil" class="form-select bg-white text-dark border-secondary-subtle" required>
              <?php foreach ($mobils as $m): ?>
              <option value="<?= $m['id_mobil'] ?>" <?= $pemesanan['id_mobil'] == $m['id_mobil'] ? 'selected' : '' ?>>
                <?= esc($m['merek']) ?> - <?= esc($m['nama_mobil']) ?> (<?= esc($m['no_polisi'] ?? 'No Polisi Kosong') ?>)
              </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label class="form-label text-dark fw-bold">Tanggal Pesan</label>
            <input type="date" name="tgl_pesan" class="form-control bg-white text-dark border-secondary-subtle" value="<?= esc(date('Y-m-d', strtotime($pemesanan['tgl_pesan']))) ?>" required>
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label class="form-label text-dark fw-bold">Harga Jadi (Rp)</label>
            <input type="text" name="harga_jadi" class="form-control bg-white text-dark border-secondary-subtle mask-rupiah" value="<?= number_format($pemesanan['harga_jadi'] ?? 0, 0, '', '.') ?>" required>
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label class="form-label text-dark fw-bold">Nilai Tanda Jadi (Rp)</label>
            <input type="text" name="nilai_tanda_jadi" class="form-control bg-white text-dark border-secondary-subtle mask-rupiah" value="<?= number_format($pemesanan['nilai_tanda_jadi'] ?? 0, 0, '', '.') ?>" required>
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label class="form-label text-dark fw-bold">Status Pemesanan</label>
            <select name="status_pemesanan" class="form-select bg-white text-dark border-secondary-subtle">
              <?php foreach (['menunggu','dp_masuk','diproses','selesai','dibatalkan'] as $st): ?>
              <option value="<?= $st ?>" <?= $pemesanan['status_pemesanan'] === $st ? 'selected' : '' ?>>
                <?= esc(strtoupper($st)) ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

      </div>

      <div class="d-flex justify-content-end gap-2 mt-4">
        <a href="<?= base_url('pemesanan') ?>" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-warning"><i class="bi bi-save2"></i> Update Pemesanan</button>
      </div>
    </form>
  </div>
</div>

<?= view('templates/footer') ?>