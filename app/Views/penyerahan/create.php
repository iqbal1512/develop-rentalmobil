<?php $title = 'Buat Penyerahan Mobil'; ?>
<?= view('templates/header') ?>

<div class="card shadow-sm bg-white border-light text-dark">
  <div class="card-header bg-white text-dark border-light">
    <h5 class="mb-0"><i class="bi bi-box-seam-fill text-info me-2"></i> Form Penyerahan Mobil</h5>
  </div>
  <div class="card-body">
    <form action="<?= base_url('penyerahan/store') ?>" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="id_penjualan" value="<?= $penjualan['id_penjualan'] ?>">

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label text-dark">Metode Penyerahan</label>
          <select name="metode_serah" class="form-select bg-white text-dark border-light" required onchange="toggleAlamat(this.value)">
            <option value="diambil">Diambil di Showroom</option>
            <option value="diantar">Diantar ke Alamat</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label text-dark">Kondisi Serah</label>
          <select name="kondisi_serah" class="form-select bg-white text-dark border-light" required>
            <option value="baik">Baik</option>
            <option value="cacat">Cacat Minor</option>
            <option value="rusak">Rusak</option>
          </select>
        </div>
      </div>

      <div class="mb-3 d-none" id="alamatGroup">
        <label class="form-label text-dark">Alamat Antar</label>
        <textarea name="alamat_antar" class="form-control bg-white text-dark border-light" rows="2"></textarea>
      </div>

      <div class="row mb-3">
        <div class="col-md-4">
          <label class="form-label text-dark">Tanggal Serah Unit</label>
          <input type="date" name="tgl_serah_unit" class="form-control bg-white text-dark border-light" required>
        </div>
        <div class="col-md-4">
          <label class="form-label text-dark">Tanggal Serah STNK (Opsional)</label>
          <input type="date" name="tgl_serah_stnk" class="form-control bg-white text-dark border-light">
        </div>
        <div class="col-md-4">
          <label class="form-label text-dark">Tanggal Serah BPKB (Opsional)</label>
          <input type="date" name="tgl_serah_bpkb" class="form-control bg-white text-dark border-light">
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label text-dark">Catatan Petugas</label>
          <textarea name="catatan_petugas" class="form-control bg-white text-dark border-light" rows="3"></textarea>
        </div>
        <div class="col-md-6">
          <label class="form-label text-dark">Estimasi Layan (Jam/Hari)</label>
          <input type="text" name="estimasi_layan" class="form-control bg-white text-dark border-light">
        </div>
      </div>

      <div class="text-end mt-4">
        <a href="<?= base_url('penjualan') ?>" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Penyerahan</button>
      </div>
    </form>
  </div>
</div>

<script>
function toggleAlamat(val) {
    if(val === 'diantar') {
        document.getElementById('alamatGroup').classList.remove('d-none');
        document.querySelector('[name="alamat_antar"]').setAttribute('required', 'required');
    } else {
        document.getElementById('alamatGroup').classList.add('d-none');
        document.querySelector('[name="alamat_antar"]').removeAttribute('required');
    }
}
</script>

<?= view('templates/footer') ?>
