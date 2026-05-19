<?php $title = 'Buat Penyerahan Mobil'; ?>
<?= view('templates/header') ?>

<div class="card" style="max-width: 900px; margin: 0 auto;">
  <div class="card-header">
    <h5><i class="bi bi-box-seam-fill" style="color: var(--primary);"></i> Form Penyerahan Mobil</h5>
    <a href="<?= base_url('penjualan') ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
  </div>
  <div class="card-body">
    <p style="color: var(--gray-500); font-size: 0.875rem; margin-bottom: 1.5rem;">Lengkapi formulir di bawah ini untuk mencatat penyerahan unit mobil.</p>

    <form action="<?= base_url('penyerahan/store') ?>" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="id_penjualan" value="<?= $penjualan['id_penjualan'] ?>">

      <div class="row">
        <div class="col-md-6 form-group">
          <label class="form-label">Metode Penyerahan</label>
          <select name="metode_serah" class="form-select" required onchange="toggleAlamat(this.value)">
            <option value="diambil">Diambil di Showroom</option>
            <option value="diantar">Diantar ke Alamat</option>
          </select>
        </div>
        <div class="col-md-6 form-group">
          <label class="form-label">Kondisi Serah</label>
          <select name="kondisi_serah" class="form-select" required>
            <option value="baik">Baik</option>
            <option value="cacat">Cacat Minor</option>
            <option value="rusak">Rusak</option>
          </select>
        </div>
      </div>

      <div class="row mt-3 d-none" id="alamatGroup">
        <div class="col-md-12 form-group">
          <label class="form-label">Alamat Antar</label>
          <textarea name="alamat_antar" class="form-control" rows="2" placeholder="Alamat pengiriman unit..."></textarea>
        </div>
      </div>

      <div class="row mt-3">
        <div class="col-md-4 form-group">
          <label class="form-label">Tanggal Serah Unit</label>
          <input type="date" name="tgl_serah_unit" class="form-control" required>
        </div>
        <div class="col-md-4 form-group">
          <label class="form-label">Tanggal Serah STNK (Opsional)</label>
          <input type="date" name="tgl_serah_stnk" class="form-control">
        </div>
        <div class="col-md-4 form-group">
          <label class="form-label">Tanggal Serah BPKB (Opsional)</label>
          <input type="date" name="tgl_serah_bpkb" class="form-control">
        </div>
      </div>

      <div class="row mt-3">
        <div class="col-md-6 form-group">
          <label class="form-label">Catatan Petugas</label>
          <textarea name="catatan_petugas" class="form-control" rows="3" placeholder="Detail kondisi penyerahan atau catatan tambahan..."></textarea>
        </div>
        <div class="col-md-6 form-group">
          <label class="form-label">Estimasi Layan (Jam/Hari)</label>
          <input type="text" name="estimasi_layan" class="form-control" placeholder="Misal: 2 jam / 1 hari">
        </div>
      </div>

      <div class="d-flex justify-content-between align-items-center mt-4 pt-4" style="border-top: 1px solid var(--gray-200);">
        <a href="<?= base_url('penjualan') ?>" class="btn btn-secondary">Batalkan</a>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle"></i> Simpan Penyerahan</button>
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
