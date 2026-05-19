<?php $title = 'Edit Pemesanan'; ?>
<?= view('templates/header') ?>

<div class="card shadow-sm bg-white border-light text-dark" style="max-width:800px;margin:0 auto">
  <div class="card-header bg-white border-light text-white">
    <h5><i class="bi bi-pencil-square text-warning"></i> Edit Pemesanan</h5>
    <a href="<?= base_url('pemesanan') ?>" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
  </div>
  <div class="card-body">
    <form action="<?= base_url('pemesanan/update/' . $pemesanan['id_pemesanan']) ?>" method="POST">
      <?= csrf_field() ?>
      <div class="row">
        <div class="col-6"><div class="form-group">
          <label class="form-label text-dark">Customer</label>
          <select name="id_customer" class="form-select bg-white text-dark border-light " required>
            <?php foreach ($customers as $c): ?>
            <option value="<?= $c['id_customer'] ?>" <?= $pemesanan['id_customer'] == $c['id_customer'] ? 'selected' : '' ?>>
              <?= esc($c['nama']) ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div></div>
        <div class="col-6"><div class="form-group">
          <label class="form-label text-dark">Mobil</label>
          <select name="id_mobil" class="form-select bg-white text-dark border-light " required>
            <?php foreach ($mobils as $m): ?>
            <option value="<?= $m['id_mobil'] ?>" <?= $pemesanan['id_mobil'] == $m['id_mobil'] ? 'selected' : '' ?>>
              <?= esc($m['nama_mobil']) ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div></div>
        <div class="col-4"><div class="form-group">
          <label class="form-label text-dark">Harga Jual Jadi (Rp)</label>
          <input type="number" name="harga_jual_jadi" class="form-control bg-white text-dark border-light " value="<?= $pemesanan['harga_jual_jadi'] ?>" min="0" required>
        </div></div>
        <div class="col-4"><div class="form-group">
          <label class="form-label text-dark">Nominal DP</label>
          <input type="number" name="nominal_dp" class="form-control bg-white text-dark border-light " value="<?= $pemesanan['nominal_dp'] ?>" min="0">
        </div></div>
        <div class="col-4"><div class="form-group">
          <label class="form-label text-dark">DP Awal Dibayar</label>
          <input type="number" name="dp_awal_dibayar" class="form-control bg-white text-dark border-light " value="<?= $pemesanan['dp_awal_dibayar'] ?>" min="0">
        </div></div>
        <div class="col-6"><div class="form-group">
          <label class="form-label text-dark">KTP Diterima</label>
          <select name="ktp_diterima" class="form-select bg-white text-dark border-light ">
            <option value="0" <?= !$pemesanan['ktp_diterima'] ? 'selected' : '' ?>>Belum</option>
            <option value="1" <?= $pemesanan['ktp_diterima'] ? 'selected' : '' ?>>Sudah</option>
          </select>
        </div></div>
        <div class="col-6"><div class="form-group">
          <label class="form-label text-dark">Status Pemesanan</label>
          <select name="status_pemesanan" class="form-select bg-white text-dark border-light ">
            <?php foreach (['menunggu','dp_masuk','diproses','selesai','batal'] as $st): ?>
            <option value="<?= $st ?>" <?= $pemesanan['status_pemesanan'] === $st ? 'selected' : '' ?>>
              <?= ucfirst(str_replace('_',' ',$st)) ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div></div>
        <div class="col-12"><div class="form-group">
          <label class="form-label text-dark">Catatan</label>
          <textarea name="catatan" class="form-control bg-white text-dark border-light " rows="2"><?= esc($pemesanan['catatan'] ?? '') ?></textarea>
        </div></div>
      </div>
      <div class="d-flex justify-content-end gap-2" style="margin-top:8px">
        <a href="<?= base_url('pemesanan') ?>" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-warning"><i class="bi bi-save2"></i> Update Pemesanan</button>
      </div>
    </form>
  </div>
</div>

<?= view('templates/footer') ?>
