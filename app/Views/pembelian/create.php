<?php $title = 'Tambah Pembelian'; ?>
<?= view('templates/header') ?>

<div class="card shadow-lg bg-white border-light text-dark" style="max-width:900px; margin:20px auto;">
    <div class="card-header bg-white border-light d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-info"><i class="bi bi-cart-plus-fill"></i> Tambah Transaksi Pembelian</h5>
        <a href="<?= base_url('pembelian') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
    <div class="card-body">
        <form action="<?= base_url('pembelian/store') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold text-dark">Supplier <span class="text-danger">*</span></label>
                    <select name="id_supplier" class="form-select bg-white text-dark border-light " required>
                        <option value="">-- Pilih Supplier --</option>
                        <?php if(!empty($suppliers)): ?>
                            <?php foreach ($suppliers as $s): ?>
                                <option value="<?= $s['id_supplier'] ?>" <?= old('id_supplier') == $s['id_supplier'] ? 'selected' : '' ?>>
                                    <?= esc($s['nama_supplier']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold text-dark">Unit Mobil <span class="text-danger">*</span></label>
                    <select name="id_mobil" id="mobilSelect" class="form-select bg-white text-dark border-light " required>
                        <option value="">-- Pilih Unit --</option>
                        <?php if(!empty($mobils)): ?>
                            <?php foreach ($mobils as $m): ?>
                                <option value="<?= $m['id_mobil'] ?>" data-harga="<?= $m['harga_beli'] ?>" <?= old('id_mobil') == $m['id_mobil'] ? 'selected' : '' ?>>
                                    <?= esc($m['nama_mobil']) ?> (<?= esc($m['warna']) ?>)
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-dark">Tanggal Beli</label>
                    <input type="date" name="tgl_pembelian" class="form-control bg-white text-dark border-light " value="<?= old('tgl_pembelian', date('Y-m-d')) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-dark">No. Kwitansi</label>
                    <input type="text" name="no_kwitansi" class="form-control bg-white text-dark border-light " placeholder="KWT-001" value="<?= old('no_kwitansi') ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label text-dark">Jumlah Unit</label>
                    <input type="number" name="jumlah_pembelian" id="qty" class="form-control bg-white text-dark border-light " value="<?= old('jumlah_pembelian', 1) ?>" min="1" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-dark">Harga Satuan (Rp)</label>
                    <input type="number" name="harga_beli" id="harga_beli" class="form-control bg-white text-dark border-light " value="<?= old('harga_beli', 0) ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-success fw-bold">Total Harga</label>
                    <input type="number" name="total_harga" id="total_harga" class="form-control bg-white text-success fw-bold border-light " readonly>
                </div>

                <div class="col-12 mt-4 text-end">
                    <hr class="border-light">
                    <button type="reset" class="btn btn-secondary px-4">Reset</button>
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save"></i> Simpan Transaksi</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobilSelect = document.getElementById('mobilSelect');
        const qtyInput = document.getElementById('qty');
        const hargaInput = document.getElementById('harga_beli');
        const totalInput = document.getElementById('total_harga');

        function hitungTotal() {
            const qty = parseInt(qtyInput.value) || 0;
            const harga = parseInt(hargaInput.value) || 0;
            totalInput.value = qty * harga;
        }

        mobilSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const hargaDefault = selectedOption.getAttribute('data-harga');
            if (hargaDefault) {
                hargaInput.value = hargaDefault;
            }
            hitungTotal();
        });

        qtyInput.addEventListener('input', hitungTotal);
        hargaInput.addEventListener('input', hitungTotal);
        
        // Jalankan saat pertama load
        hitungTotal();
    });
</script>

<?= view('templates/footer') ?>
