<?= $this->extend('layout/v_template'); ?> <!-- Gunakan extend agar sidebar muncul -->

<?= $this->section('isi'); ?>
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-file-earmark-spreadsheet" style="color: var(--primary);"></i> Buat Transaksi Penjualan</h5>
                <a href="<?= base_url('penjualan') ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>
            <div class="card-body">
                <p style="color: var(--gray-500); font-size: 0.875rem; margin-bottom: 1.5rem;">Lengkapi formulir penjualan untuk memproses pelunasan unit kendaraan.</p>

                <form action="<?= base_url('penjualan/store') ?>" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id_pemesanan" value="<?= $pemesanan['id_pemesanan'] ?>">

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="form-label">Tanggal Penjualan</label>
                            <input type="date" name="tgl_penjualan" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Total Harga (Rp)</label>
                            <input type="text" class="form-control" value="<?= number_format($pemesanan['harga_jual_jadi'], 0, ',', '.') ?>" readonly style="background:var(--bg-primary)">
                            <input type="hidden" name="total_harga" id="totalHarga" value="<?= $pemesanan['harga_jual_jadi'] ?>">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6 form-group">
                            <label class="form-label text-primary">Total Sudah Dibayar (Rp)</label>
                            <input type="number" name="total_dibayar" id="totalDibayar" class="form-control" value="<?= $pemesanan['dp_awal_dibayar'] ?>" min="0">
                            <div class="form-text">Termasuk DP awal yang sudah masuk.</div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Sisa Tagihan (Rp)</label>
                            <input type="text" id="sisaTagihanTampil" class="form-control" readonly style="background:var(--bg-primary)">
                            <input type="hidden" name="sisa_tagihan" id="sisaTagihan" value="<?= $pemesanan['harga_jual_jadi'] - $pemesanan['dp_awal_dibayar'] ?>">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6 form-group">
                            <label class="form-label">Status Pelunasan</label>
                            <select name="status_lunas" id="statusLunas" class="form-select">
                                <option value="belum_lunas" <?= ($pemesanan['harga_jual_jadi'] - $pemesanan['dp_awal_dibayar'] > 0) ? 'selected' : '' ?>>Belum Lunas</option>
                                <option value="lunas" <?= ($pemesanan['harga_jual_jadi'] - $pemesanan['dp_awal_dibayar'] <= 0) ? 'selected' : '' ?>>Lunas</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Proses STNK</label>
                            <select name="proses_stnk" class="form-select">
                                <option value="belum">Belum (~2 minggu)</option>
                                <option value="proses">Proses</option>
                                <option value="selesai">Selesai</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12 form-group">
                            <label class="form-label">Catatan Penjualan</label>
                            <textarea name="catatan" class="form-control" rows="2" placeholder="Contoh: Pelunasan via transfer BCA..."></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4 pt-4" style="border-top: 1px solid var(--gray-200);">
                        <a href="<?= base_url('penjualan') ?>" class="btn btn-secondary">Batalkan</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle"></i> Simpan Transaksi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar Info Pemesanan -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-info-circle" style="color: var(--primary);"></i> Ringkasan</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover table-borderless mb-0" style="font-size: 13px;">
                    <tr class="border-bottom border-light">
                        <td class="p-3 text-secondary">Customer</td>
                        <td class="p-3 text-end fw-bold"><?= esc($pemesanan['nama_customer']) ?></td>
                    </tr>
                    <tr class="border-bottom border-light">
                        <td class="p-3 text-secondary">Unit Mobil</td>
                        <td class="p-3 text-end fw-bold"><?= esc($pemesanan['nama_mobil']) ?></td>
                    </tr>
                    <tr class="border-bottom border-light">
                        <td class="p-3 text-secondary">Harga Sepakat</td>
                        <td class="p-3 text-end text-success fw-bold">Rp<?= number_format($pemesanan['harga_jual_jadi'],0,',','.') ?></td>
                    </tr>
                    <tr>
                        <td class="p-3 text-secondary">DP Masuk</td>
                        <td class="p-3 text-end text-primary">Rp<?= number_format($pemesanan['dp_awal_dibayar'],0,',','.') ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const totalHarga   = <?= $pemesanan['harga_jual_jadi'] ?>;
    const inputDibayar = document.getElementById('totalDibayar');
    const sisaTampil   = document.getElementById('sisaTagihanTampil');
    const sisaHidden   = document.getElementById('sisaTagihan');
    const selectLunas  = document.getElementById('statusLunas');

    function hitungSisa() {
        let dibayar = parseFloat(inputDibayar.value) || 0;
        let sisa = Math.max(0, totalHarga - dibayar);
        
        sisaTampil.value = new Intl.NumberFormat('id-ID').format(sisa);
        sisaHidden.value = sisa;

        // Otomatis ganti status dropdown jika sisa 0
        if (sisa <= 0) {
            selectLunas.value = 'lunas';
        } else {
            selectLunas.value = 'belum_lunas';
        }
    }

    inputDibayar.addEventListener('input', hitungSisa);
    hitungSisa(); // Jalankan saat halaman pertama load
});
</script>
<?= $this->endSection(); ?>