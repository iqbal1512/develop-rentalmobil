<?php $title = 'Input Pembayaran'; ?>
<?= view('templates/header') ?>

<div class="card" style="max-width: 900px; margin: 0 auto;">
  <div class="card-header">
    <h5><i class="bi bi-cash-coin" style="color: var(--primary);"></i> Input Pembayaran Penjualan</h5>
    <a href="<?= base_url('penjualan') ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
  </div>
  <div class="card-body">
    <p style="color: var(--gray-500); font-size: 0.875rem; margin-bottom: 1.5rem;">Lengkapi formulir di bawah ini untuk mencatat pembayaran transaksi penjualan.</p>

    <form action="<?= base_url('pembayaran/store') ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="hidden" name="id_penjualan" value="<?= $penjualan['id_penjualan'] ?>">
      <input type="hidden" name="id_pemesanan" value="<?= $penjualan['id_pemesanan'] ?>">

      <div class="row">
        <div class="col-md-6 form-group">
          <label class="form-label">Jenis Pembayaran</label>
          <select name="jenis_pembayaran" class="form-select" required>
            <option value="dp">DP (Down Payment)</option>
            <option value="pelunasan">Pelunasan</option>
            <option value="cicilan">Cicilan</option>
            <option value="bukti_pesan">Tanda Jadi / Bukti Pesan</option>
          </select>
        </div>
        <div class="col-md-6 form-group">
          <label class="form-label">Metode Pembayaran</label>
          <select name="metode_bayar" class="form-select" required onchange="toggleBukti(this.value)">
            <option value="tunai">Tunai</option>
            <option value="transfer">Transfer Bank</option>
          </select>
        </div>
      </div>

      <div class="row mt-3">
        <div class="col-md-6 form-group">
          <label class="form-label">Tanggal Bayar</label>
          <input type="date" name="tgl_bayar" class="form-control" required>
        </div>
        <div class="col-md-6 form-group">
          <label class="form-label">Jumlah Bayar</label>
          <div class="input-group">
            <span class="input-group-text">Rp</span>
            <input type="text" name="jumlah_bayar" class="form-control mask-rupiah" required>
          </div>
        </div>
      </div>

      <div class="row mt-3 d-none" id="buktiGroup">
        <div class="col-md-12 form-group">
          <label class="form-label">Bukti Transfer (Jika Transfer)</label>
          <input type="file" name="bukti_transfer" class="form-control" accept="image/*">
          <div class="form-text text-secondary">Upload gambar bukti transfer atau struk. Format: JPG, PNG. Maks 2MB.</div>
        </div>
      </div>

      <div class="row mt-3">
        <div class="col-md-12 form-group">
          <label class="form-label">Keterangan / Catatan</label>
          <textarea name="keterangan" class="form-control" rows="3" placeholder="Tambahkan catatan pembayaran jika ada..."></textarea>
        </div>
      </div>

      <div class="d-flex justify-content-between align-items-center mt-4 pt-4" style="border-top: 1px solid var(--gray-200);">
        <a href="<?= base_url('penjualan') ?>" class="btn btn-secondary">Batalkan</a>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle"></i> Proses Pembayaran</button>
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
