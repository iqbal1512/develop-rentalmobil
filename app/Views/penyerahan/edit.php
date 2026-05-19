<?php $title = 'Edit Penyerahan Mobil'; ?>
<?= view('templates/header') ?>

<div class="card shadow-sm bg-white border-light text-dark">
  <div class="card-header bg-white text-dark border-light">
    <h5 class="mb-0"><i class="bi bi-pencil-square text-warning me-2"></i> Edit Penyerahan #<?= $penyerahan['id_penyerahan'] ?></h5>
  </div>
  <div class="card-body">
    <form action="<?= base_url('penyerahan/update/' . $penyerahan['id_penyerahan']) ?>" method="post">
      <?= csrf_field() ?>

      <div class="row mb-3">
        <div class="col-md-4">
          <label class="form-label text-dark">Tanggal Serah Unit</label>
          <input type="date" name="tgl_serah_unit" class="form-control bg-white text-dark border-light" value="<?= $penyerahan['tgl_serah_unit'] ?>" required>
        </div>
        <div class="col-md-4">
          <label class="form-label text-dark">Tanggal Serah STNK</label>
          <input type="date" name="tgl_serah_stnk" class="form-control bg-white text-dark border-light" value="<?= $penyerahan['tgl_serah_stnk'] ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label text-dark">Tanggal Serah BPKB</label>
          <input type="date" name="tgl_serah_bpkb" class="form-control bg-white text-dark border-light" value="<?= $penyerahan['tgl_serah_bpkb'] ?>">
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label text-dark">Kondisi Serah</label>
        <select name="kondisi_serah" class="form-select bg-white text-dark border-light" required>
          <option value="baik" <?= $penyerahan['kondisi_serah'] == 'baik' ? 'selected' : '' ?>>Baik</option>
          <option value="cacat" <?= $penyerahan['kondisi_serah'] == 'cacat' ? 'selected' : '' ?>>Cacat Minor</option>
          <option value="rusak" <?= $penyerahan['kondisi_serah'] == 'rusak' ? 'selected' : '' ?>>Rusak</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label text-dark">Catatan Petugas</label>
        <textarea name="catatan_petugas" class="form-control bg-white text-dark border-light" rows="3"><?= $penyerahan['catatan_petugas'] ?></textarea>
      </div>

      <div class="text-end mt-4">
        <a href="<?= base_url('penyerahan') ?>" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update Penyerahan</button>
      </div>
    </form>
  </div>
</div>

<?= view('templates/footer') ?>
