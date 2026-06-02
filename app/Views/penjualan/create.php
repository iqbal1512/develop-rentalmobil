<?php $title = 'Buat Transaksi Penjualan'; ?>
<?= view('templates/header') ?>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 bg-white text-dark">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-file-earmark-spreadsheet text-primary me-2"></i> Buat Transaksi Penjualan Baru</h5>
                <a href="<?= base_url('penjualan') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-4">Lengkapi formulir penjualan di bawah ini untuk memproses lembar administrasi dan sisa pelunasan unit kendaraan.</p>

                <form action="<?= base_url('penjualan/store') ?>" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id_pemesanan" value="<?= $pemesanan['id_pemesanan'] ?>">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-semibold text-dark">Tanggal Penjualan</label>
                                <input type="date" name="tgl_penjualan" class="form-control border-secondary" value="<?= date('Y-m-d') ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-semibold text-dark">Total Harga Kesepakatan (Rp)</label>
                                <input type="text" class="form-control bg-light border-secondary fw-semibold text-dark" value="<?= number_format($pemesanan['harga_jadi'], 0, ',', '.') ?>" readonly>
                                <input type="hidden" name="total_harga" id="totalHarga" value="<?= $pemesanan['harga_jadi'] ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-semibold text-primary">Total Sudah Dibayar / Masuk (Rp)</label>
                                <input type="text" name="total_dibayar" id="totalDibayar" class="form-control border-primary mask-rupiah fw-bold text-dark" value="<?= $pemesanan['nilai_tanda_jadi'] ? number_format($pemesanan['nilai_tanda_jadi'], 0, '', '.') : '0' ?>" min="0">
                                <div class="form-text text-muted">Ubah nominal di atas jika customer langsung menambah dana pelunasan hari ini.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-semibold text-dark">Sisa Tagihan Pelunasan (Rp)</label>
                                <input type="text" id="sisaTagihanTampil" class="form-control bg-light border-secondary fw-bold text-danger" readonly>
                                <input type="hidden" name="sisa_tagihan" id="sisaTagihan" value="<?= $pemesanan['harga_jadi'] - $pemesanan['nilai_tanda_jadi'] ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-semibold text-dark">Status Pelunasan</label>
                                <select name="status_lunas" id="statusLunas" class="form-select border-secondary">
                                    <option value="belum_lunas" <?= ($pemesanan['harga_jadi'] - $pemesanan['nilai_tanda_jadi'] > 0) ? 'selected' : '' ?>>Belum Lunas</option>
                                    <option value="lunas" <?= ($pemesanan['harga_jadi'] - $pemesanan['nilai_tanda_jadi'] <= 0) ? 'selected' : '' ?>>Lunas</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-semibold text-dark">Progress Awal STNK</label>
                                <select name="proses_stnk" class="form-select border-secondary">
                                    <option value="belum" selected>Belum (~2 minggu)</option>
                                    <option value="proses">Proses Cetak</option>
                                    <option value="selesai">Selesai / Tersedia</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label fw-semibold text-dark">Catatan Penjualan / Memo</label>
                                <textarea name="catatan" class="form-control border-secondary" rows="3" placeholder="Contoh: Pembayaran awal menggunakan nilai tanda jadi pemesanan nomor #..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <a href="<?= base_url('penjualan') ?>" class="btn btn-outline-secondary">Batalkan</a>
                        <button type="submit" class="btn btn-primary px-4 fw-semibold"><i class="bi bi-check2-circle"></i> Simpan Transaksi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0 bg-white text-dark position-sticky" style="top: 20px;">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-info-circle text-primary me-2"></i> Ringkasan Ikatan Jual</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover table-borderless align-middle mb-0" style="font-size: 13px;">
                    <tr class="border-bottom border-light">
                        <td class="p-3 text-muted">Customer</td>
                        <td class="p-3 text-end fw-bold text-dark"><?= esc($pemesanan['nama_customer'] ?? '-') ?></td>
                    </tr>
                    <tr class="border-bottom border-light">
                        <td class="p-3 text-muted">Unit Mobil</td>
                        <td class="p-3 text-end fw-bold text-dark"><?= esc($pemesanan['nama_mobil'] ?? '-') ?></td>
                    </tr>
                    <tr class="border-bottom border-light">
                        <td class="p-3 text-muted">Harga Sepakat</td>
                        <td class="p-3 text-end text-success fw-bold">Rp<?= number_format($pemesanan['harga_jadi'], 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td class="p-3 text-muted">Uang Muka / Tanda Jadi</td>
                        <td class="p-3 text-end text-primary fw-bold">Rp<?= number_format($pemesanan['nilai_tanda_jadi'], 0, ',', '.') ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // PERBAIKAN: Menyesuaikan cetakan data PHP dari harga_jual_jadi menjadi harga_jadi
    const totalHarga   = <?= (float)($pemesanan['harga_jadi'] ?? 0) ?>;
    const inputDibayar = document.getElementById('totalDibayar');
    const sisaTampil   = document.getElementById('sisaTagihanTampil');
    const sisaHidden   = document.getElementById('sisaTagihan');
    const selectLunas  = document.getElementById('statusLunas');

    function hitungSisa() {
        // Ambil angka saja dari input mask rupiah
        let dibayar = parseFloat(inputDibayar.value.replace(/[^0-9]/g, '')) || 0;
        let sisa = Math.max(0, totalHarga - dibayar);
        
        sisaTampil.value = new Intl.NumberFormat('id-ID').format(sisa);
        sisaHidden.value = sisa;

        // Otomatis ubah dropdown status kelunasan demi efisiensi kerja Kasir
        if (sisa <= 0) {
            selectLunas.value = 'lunas';
        } else {
            selectLunas.value = 'belum_lunas';
        }
    }

    inputDibayar.addEventListener('input', hitungSisa);
    hitungSisa(); // Jalankan kalkulasi trigger saat pertama kali halaman terbuka
});
</script>

<?= view('templates/footer') ?>