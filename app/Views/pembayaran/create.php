<?php $title = 'Input Pembayaran'; ?>
<?= view('templates/header') ?>

<div class="card shadow-sm">
  <div class="card-header bg-white text-dark">
    <h5 class="mb-0"><i class="bi bi-cash-coin text-success me-2"></i> Input Pembayaran Penjualan</h5>
  </div>
  <div class="card-body">
    <form action="<?= base_url('pembayaran/store') ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="hidden" name="id_penjualan" value="<?= $penjualan['id_penjualan'] ?>">
      <input type="hidden" name="id_pemesanan" value="<?= $penjualan['id_pemesanan'] ?>">

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label text-dark">Jenis Pembayaran</label>
          <select name="jenis_pembayaran" class="form-select bg-white text-dark border-light" required>
            <option value="dp">DP (Down Payment)</option>
            <option value="pelunasan">Pelunasan</option>
            <option value="cicilan">Cicilan</option>
            <option value="bukti_pesan">Tanda Jadi / Bukti Pesan</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label text-dark">Metode Pembayaran</label>
          <select name="metode_bayar" class="form-select bg-white text-dark border-light" required onchange="toggleBukti(this.value)">
            <option value="tunai">Tunai</option>
            <option value="transfer">Transfer Bank</option>
          </select>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label text-dark">Tanggal Bayar</label>
          <input type="date" name="tgl_bayar" class="form-control bg-white text-dark border-light" required>
        </div>
        <div class="col-md-6">
          <label class="form-label text-dark">Jumlah Bayar</label>
          <div class="input-group">
            <span class="input-group-text bg-white text-dark border-light">Rp</span>
            <input type="text" name="jumlah_bayar" class="form-control bg-white text-dark border-light mask-rupiah" required>
          </div>
        </div>
      </div>

      <div class="mb-3 d-none" id="buktiGroup">
        <label class="form-label text-dark">Bukti Transfer (Jika Transfer)</label>
        <input type="file" name="bukti_transfer" class="form-control bg-white text-dark border-light" accept="image/*">
        <small class="text-muted">Upload gambar bukti transfer atau struk.</small>
      </div>

      <div class="mb-3">
        <label class="form-label text-dark">Keterangan / Catatan</label>
        <textarea name="keterangan" class="form-control bg-white text-dark border-light" rows="3"></textarea>
      </div>

      <div class="text-end mt-4">
        <a href="<?= base_url('penjualan') ?>" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Proses Pembayaran</button>
      </div>
    </form>
  </div>
</div>

<script>
function toggleBukti(val) {
    if(val === 'transfer') {
        document.getElementById('buktiGroup').classList.remove('d-none');
        document.querySelector('[name="bukti_transfer"]').setAttribute('required', 'required');
    } else {
        document.getElementById('buktiGroup').classList.add('d-none');
        document.querySelector('[name="bukti_transfer"]').removeAttribute('required');
    }
}
</script>

<?= view('templates/footer') ?>
