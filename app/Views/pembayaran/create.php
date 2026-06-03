<?php $title = 'Input Pembayaran'; ?>
<?= view('templates/header') ?>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 bg-white text-dark">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-cash-coin text-success me-2"></i> Form Input Pembayaran Kas Masuk</h5>
            </div>
            <div class="card-body">
                <form action="<?= base_url('pembayaran/store') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id_penjualan" value="<?= $penjualan['id_penjualan'] ?>">
                    <input type="hidden" name="id_pemesanan" value="<?= $penjualan['id_pemesanan'] ?>">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Bayar</label>
                            <input type="date" name="tgl_bayar" class="form-control border-secondary" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jenis Alokasi Pembayaran</label>
                            <select name="jenis_pembayaran" class="form-select border-secondary" required>
                                <option value="dp">Uang Muka / Tambahan DP</option>
                                <option value="cicilan" selected>Angsuran / Cicilan Berskala</option>
                                <option value="pelunasan">Pelunasan Total Nota</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-success">Jumlah Uang Masuk (Rp)</label>
                            <input type="text" name="jumlah_bayar" id="jumlahBayar" class="form-control border-success mask-rupiah fw-bold fs-5 text-dark" placeholder="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Metode Pembayaran</label>
                            <select name="metode_pembayaran" id="metodeBayar" class="form-select border-secondary" required>
                                <option value="tunai">Cash / Tunai di Kasir</option>
                                <option value="transfer">Transfer Bank (Perlu Verifikasi)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 p-3 bg-light border rounded d-none" id="panelBuktiTransfer">
                        <label class="form-label fw-semibold"><i class="bi bi-image me-1"></i> Unggah Gambar Bukti Transfer Pasien / Customer</label>
                        <input type="file" name="bukti_transfer" class="form-control border-secondary" accept="image/*">
                        <div class="form-text small text-muted">Format berkas disarankan: JPG, JPEG, atau PNG. Maksimal 2MB.</div>
                    </div>

                    <div class="mt-3">
                        <label class="form-label fw-semibold">Keterangan Tambahan / Catatan Berita</label>
                        <textarea name="keterangan" class="form-control border-secondary" rows="2" placeholder="Contoh: Transfer via m-Banking BCA atas nama PT..."></textarea>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <a href="<?= base_url('penjualan/detail/' . $penjualan['id_penjualan']) ?>" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-success px-4 fw-semibold"><i class="bi bi-check-circle"></i> Rekam Pembayaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0 bg-dark text-white position-sticky" style="top: 20px;">
            <div class="card-header border-bottom border-secondary py-3 bg-transparent">
                <h6 class="mb-0 fw-bold text-warning"><i class="bi bi-exclamation-triangle-fill me-2"></i> Batas Sisa Piutang Tagihan</h6>
            </div>
            <div class="card-body" style="font-size: 13.5px;">
                <div class="mb-3">
                    <span class="text-secondary d-block">Nama Customer:</span>
                    <span class="fw-bold fs-6 text-light"><?= esc($penjualan['nama_customer']) ?></span>
                </div>
                <div class="mb-3">
                    <span class="text-secondary d-block">Unit Mobil:</span>
                    <span class="fw-bold text-light"><?= esc($penjualan['nama_mobil']) ?></span>
                </div>
                <hr class="border-secondary">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary">Total Nilai Jual:</span>
                    <span class="fw-semibold">Rp<?= number_format($penjualan['total_harga'], 0, ',', '.') ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2 text-success">
                    <span>Dana Masuk Sah:</span>
                    <span class="fw-semibold">Rp<?= number_format($penjualan['total_dibayar'], 0, ',', '.') ?></span>
                </div>
                <div class="d-flex justify-content-between pt-2 border-top border-secondary text-danger fw-bold fs-6">
                    <span>Sisa Wajib Bayar:</span>
                    <span>Rp<?= number_format($penjualan['sisa_tagihan'], 0, ',', '.') ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectMetode = document.getElementById('metodeBayar');
    const panelBukti   = document.getElementById('panelBuktiTransfer');

    selectMetode.addEventListener('change', function() {
        if (this.value === 'transfer') {
            panelBukti.classList.remove('d-none');
        } else {
            panelBukti.classList.add('d-none');
        }
    });
});
</script>

<?= view('templates/footer') ?>