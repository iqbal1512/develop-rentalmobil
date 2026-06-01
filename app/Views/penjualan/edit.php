<?php $title = 'Edit Penjualan'; ?>
<?= view('templates/header') ?>

<div class="card shadow-sm bg-white border-light text-dark" style="max-width:750px;margin:0 auto">
  <div class="card-header bg-white border-light text-white">
    <h5><i class="bi bi-pencil-square text-warning"></i> Edit Transaksi Penjualan</h5>
    <a href="<?= base_url('penjualan') ?>" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
  </div>
  <div class="card-body">
    <form action="<?= base_url('penjualan/update/' . $penjualan['id_penjualan']) ?>" method="POST">
      <?= csrf_field() ?>
      <div class="row">
        <div class="col-6"><div class="form-group">
          <label class="form-label text-dark">Tanggal Penjualan</label>
          <input type="date" name="tgl_penjualan" class="form-control bg-light text-dark border-light " value="<?= $penjualan['tgl_penjualan'] ?>" readonly>
        </div></div>
        <div class="col-6"><div class="form-group">
          <label class="form-label text-dark">Total Harga (Rp)</label>
          <input type="text" name="total_harga" class="form-control bg-light text-dark border-light mask-rupiah" value="<?= number_format($penjualan['total_harga'], 0, '', '.') ?>" readonly>
        </div></div>
        <div class="col-6"><div class="form-group">
          <label class="form-label text-dark">Total Dibayar (Rp)</label>
          <input type="text" name="total_dibayar" class="form-control bg-light text-dark border-light mask-rupiah" value="<?= number_format($penjualan['total_dibayar'], 0, '', '.') ?>" readonly>
        </div></div>
        <div class="col-6"><div class="form-group">
          <label class="form-label text-dark">Sisa Tagihan (Rp)</label>
          <input type="text" name="sisa_tagihan" class="form-control bg-light text-dark border-light mask-rupiah" value="<?= number_format($penjualan['sisa_tagihan'], 0, '', '.') ?>" readonly>
        </div></div>
        <div class="col-4"><div class="form-group">
          <label class="form-label text-dark">Status Lulus</label>
          <select name="status_lulus" class="form-select bg-white text-dark border-light ">
            <?php foreach (['proses','lulus','gagal'] as $s): ?>
            <option value="<?= $s ?>" <?= $penjualan['status_lulus'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
            <?php endforeach; ?>
          </select>
        </div></div>
        <div class="col-4"><div class="form-group">
          <label class="form-label text-dark">Status Lunas</label>
          <select name="status_lunas" class="form-select bg-white text-dark border-light ">
            <option value="belum_lunas" <?= $penjualan['status_lunas'] === 'belum_lunas' ? 'selected' : '' ?>>Belum Lunas</option>
            <option value="lunas" <?= $penjualan['status_lunas'] === 'lunas' ? 'selected' : '' ?>>Lunas</option>
          </select>
        </div></div>
        <div class="col-4"><div class="form-group">
          <label class="form-label text-dark">Proses STNK</label>
          <select name="proses_stnk" class="form-select bg-white text-dark border-light ">
            <?php foreach (['belum','proses','selesai'] as $s): ?>
            <option value="<?= $s ?>" <?= $penjualan['proses_stnk'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
            <?php endforeach; ?>
          </select>
        </div></div>
        <div class="col-4"><div class="form-group">
          <label class="form-label text-dark">Proses BPKB</label>
          <select name="proses_bpkb" class="form-select bg-white text-dark border-light ">
            <?php foreach (['belum','proses','selesai'] as $s): ?>
            <option value="<?= $s ?>" <?= $penjualan['proses_bpkb'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
            <?php endforeach; ?>
          </select>
        </div></div>
        <div class="col-12"><div class="form-group">
          <label class="form-label text-dark">Catatan</label>
          <textarea name="catatan" class="form-control bg-white text-dark border-light " rows="2"><?= esc($penjualan['catatan'] ?? '') ?></textarea>
        </div></div>
      </div>
      <div class="d-flex justify-content-end gap-2" style="margin-top:8px">
        <a href="<?= base_url('penjualan') ?>" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-warning"><i class="bi bi-save2"></i> Update Penjualan</button>
      </div>
    </form>
  </div>
</div>

<?= view('templates/footer') ?>
