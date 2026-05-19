<?php $title = 'Edit Pembelian'; ?>
<?= view('templates/header') ?>

<div class="card" style="max-width: 900px; margin: 0 auto;">
  <div class="card-header">
    <h5><i class="bi bi-pencil-square" style="color: var(--primary);"></i> Edit Transaksi Pembelian</h5>
    <a href="<?= base_url('pembelian') ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
  </div>
  <div class="card-body">
    <p style="color: var(--gray-500); font-size: 0.875rem; margin-bottom: 1.5rem;">Perbarui informasi transaksi pembelian unit kendaraan di bawah ini.</p>

    <form action="<?= base_url('pembelian/update/' . $pembelian['id_pembelian']) ?>" method="POST">
      <?= csrf_field() ?>
      <div class="row">
        <div class="col-md-6 form-group">
          <label class="form-label">Supplier</label>
          <select name="id_supplier" class="form-select" required>
            <?php foreach ($suppliers as $s): ?>
            <option value="<?= $s['id_supplier'] ?>" <?= $pembelian['id_supplier'] == $s['id_supplier'] ? 'selected' : '' ?>>
              <?= esc($s['nama_supplier']) ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-6 form-group">
          <label class="form-label">Mobil</label>
          <select name="id_mobil" class="form-select" required>
            <?php foreach ($mobils as $m): ?>
            <option value="<?= $m['id_mobil'] ?>" <?= $pembelian['id_mobil'] == $m['id_mobil'] ? 'selected' : '' ?>>
              <?= esc($m['nama_mobil']) ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="row mt-3">
        <div class="col-md-6 form-group">
          <label class="form-label">Tanggal Pembelian</label>
          <input type="date" name="tgl_pembelian" class="form-control" value="<?= $pembelian['tgl_pembelian'] ?>" required>
        </div>

        <div class="col-md-6 form-group">
          <label class="form-label">Jumlah Unit</label>
          <input type="number" name="jumlah_pembelian" class="form-control" value="<?= $pembelian['jumlah_pembelian'] ?>" min="1" required>
        </div>
      </div>

      <div class="row mt-3">
        <div class="col-md-6 form-group">
          <label class="form-label">Harga Beli (Rp)</label>
          <input type="number" name="harga_beli" class="form-control" value="<?= $pembelian['harga_beli'] ?>" min="0" required>
        </div>

        <div class="col-md-6 form-group">
          <label class="form-label text-success" style="font-weight: 600;">Total Harga (Rp)</label>
          <input type="number" name="total_harga" class="form-control text-success" style="font-weight: 600;" value="<?= $pembelian['total_harga'] ?>" min="0" required>
        </div>
      </div>

      <div class="row mt-3">
        <div class="col-md-6 form-group">
          <label class="form-label">Metode Bayar</label>
          <select name="metode_bayar" class="form-select">
            <option value="tunai" <?= $pembelian['metode_bayar'] === 'tunai' ? 'selected' : '' ?>>Tunai</option>
            <option value="transfer" <?= $pembelian['metode_bayar'] === 'transfer' ? 'selected' : '' ?>>Transfer</option>
          </select>
        </div>

        <div class="col-md-6 form-group">
          <label class="form-label">Status Pembelian</label>
          <select name="status_pembelian" class="form-select">
            <option value="proses" <?= $pembelian['status_pembelian'] === 'proses' ? 'selected' : '' ?>>Proses</option>
            <option value="selesai" <?= $pembelian['status_pembelian'] === 'selesai' ? 'selected' : '' ?>>Selesai</option>
            <option value="batal" <?= $pembelian['status_pembelian'] === 'batal' ? 'selected' : '' ?>>Batal</option>
          </select>
        </div>
      </div>

      <div class="row mt-3">
        <div class="col-md-12 form-group">
          <label class="form-label">Keterangan Kondisi</label>
          <textarea name="keterangan_kondisi" class="form-control" rows="3"><?= esc($pembelian['keterangan_kondisi'] ?? '') ?></textarea>
        </div>
      </div>

      <div class="d-flex justify-content-between align-items-center mt-4 pt-4" style="border-top: 1px solid var(--gray-200);">
        <a href="<?= base_url('pembelian') ?>" class="btn btn-secondary">Batalkan</a>
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-check2-circle"></i> Perbarui Transaksi
        </button>
      </div>
    </form>
  </div>
</div>

<?= view('templates/footer') ?>
