<?php $title = 'Edit Pembelian'; ?>
<?= view('templates/header') ?>

<div class="card shadow-sm bg-white border-light text-dark" style="max-width:800px;margin:0 auto">
  <div class="card-header bg-white border-light text-white">
    <h5><i class="bi bi-pencil-square text-warning"></i> Edit Transaksi Pembelian</h5>
    <a href="<?= base_url('pembelian') ?>" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
  </div>
  <div class="card-body">
    <form action="<?= base_url('pembelian/update/' . $pembelian['id_pembelian']) ?>" method="POST">
      <?= csrf_field() ?>
      <div class="row">
        <div class="col-6"><div class="form-group">
          <label class="form-label text-dark">Supplier</label>
          <select name="id_supplier" class="form-select bg-white text-dark border-light " required>
            <?php foreach ($suppliers as $s): ?>
            <option value="<?= $s['id_supplier'] ?>" <?= $pembelian['id_supplier'] == $s['id_supplier'] ? 'selected' : '' ?>>
              <?= esc($s['nama_supplier']) ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div></div>
        <div class="col-6"><div class="form-group">
          <label class="form-label text-dark">Mobil</label>
          <select name="id_mobil" class="form-select bg-white text-dark border-light " required>
            <?php foreach ($mobils as $m): ?>
            <option value="<?= $m['id_mobil'] ?>" <?= $pembelian['id_mobil'] == $m['id_mobil'] ? 'selected' : '' ?>>
              <?= esc($m['nama_mobil']) ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div></div>
        <div class="col-6"><div class="form-group">
          <label class="form-label text-dark">Tanggal Pembelian</label>
          <input type="date" name="tgl_pembelian" class="form-control bg-white text-dark border-light " value="<?= $pembelian['tgl_pembelian'] ?>" required>
        </div></div>
        <div class="col-6"><div class="form-group">
          <label class="form-label text-dark">Jumlah Unit</label>
          <input type="number" name="jumlah_pembelian" class="form-control bg-white text-dark border-light " value="<?= $pembelian['jumlah_pembelian'] ?>" min="1" required>
        </div></div>
        <div class="col-6"><div class="form-group">
          <label class="form-label text-dark">Harga Beli (Rp)</label>
          <input type="number" name="harga_beli" class="form-control bg-white text-dark border-light " value="<?= $pembelian['harga_beli'] ?>" min="0" required>
        </div></div>
        <div class="col-6"><div class="form-group">
          <label class="form-label text-dark">Total Harga (Rp)</label>
          <input type="number" name="total_harga" class="form-control bg-white text-dark border-light " value="<?= $pembelian['total_harga'] ?>" min="0" required>
        </div></div>
        <div class="col-6"><div class="form-group">
          <label class="form-label text-dark">Metode Bayar</label>
          <select name="metode_bayar" class="form-select bg-white text-dark border-light ">
            <option value="tunai" <?= $pembelian['metode_bayar'] === 'tunai' ? 'selected' : '' ?>>Tunai</option>
            <option value="transfer" <?= $pembelian['metode_bayar'] === 'transfer' ? 'selected' : '' ?>>Transfer</option>
          </select>
        </div></div>
        <div class="col-6"><div class="form-group">
          <label class="form-label text-dark">Status Pembelian</label>
          <select name="status_pembelian" class="form-select bg-white text-dark border-light ">
            <option value="proses" <?= $pembelian['status_pembelian'] === 'proses' ? 'selected' : '' ?>>Proses</option>
            <option value="selesai" <?= $pembelian['status_pembelian'] === 'selesai' ? 'selected' : '' ?>>Selesai</option>
            <option value="batal" <?= $pembelian['status_pembelian'] === 'batal' ? 'selected' : '' ?>>Batal</option>
          </select>
        </div></div>
        <div class="col-12"><div class="form-group">
          <label class="form-label text-dark">Keterangan Kondisi</label>
          <textarea name="keterangan_kondisi" class="form-control bg-white text-dark border-light " rows="3"><?= esc($pembelian['keterangan_kondisi'] ?? '') ?></textarea>
        </div></div>
      </div>
      <div class="d-flex justify-content-end gap-2" style="margin-top:8px">
        <a href="<?= base_url('pembelian') ?>" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-warning"><i class="bi bi-save2"></i> Update Pembelian</button>
      </div>
    </form>
  </div>
</div>

<?= view('templates/footer') ?>
