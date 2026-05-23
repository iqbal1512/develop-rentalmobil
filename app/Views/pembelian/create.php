<?php $title = 'Tambah Pembelian'; ?>
<?= view('templates/header') ?>

<div class="card" style="max-width: 900px; margin: 0 auto;">
    <div class="card-header">
        <h5><i class="bi bi-cart-plus-fill" style="color: var(--primary);"></i> Tambah Transaksi Pembelian</h5>
        <a href="<?= base_url('pembelian') ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
    <div class="card-body">
        <p style="color: var(--gray-500); font-size: 0.875rem; margin-bottom: 1.5rem;">Lengkapi formulir di bawah ini untuk mencatat transaksi pembelian unit kendaraan baru.</p>

        <?php if (session()->getFlashdata('errors')) : ?>
            <div class="alert alert-danger" style="color: #a94442; background-color: #f2dede; border-color: #ebccd1; padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 4px;">
                <ul style="margin-bottom: 0;">
                <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
                </ul>
            </div>
        <?php endif ?>

        <form action="<?= base_url('pembelian/store') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="row">
                <div class="col-md-6 form-group">
                    <label class="form-label">Supplier <span class="text-danger">*</span></label>
                    <input type="text" name="supplier_input" id="supplierInput" list="supplierList" class="form-control" placeholder="Pilih atau ketik supplier baru..." value="<?= old('supplier_input') ?>" required autocomplete="off">
                    <datalist id="supplierList">
                        <?php if(!empty($suppliers)): ?>
                            <?php foreach ($suppliers as $s): ?>
                                <option value="<?= esc($s['nama_supplier']) ?>"></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </datalist>
                </div>

                <div class="col-md-6 form-group">
                    <label class="form-label">Unit Mobil <span class="text-danger">*</span></label>
                    <input type="text" name="mobil_input" id="mobilInput" list="mobilList" class="form-control" placeholder="Pilih atau ketik mobil baru..." value="<?= old('mobil_input') ?>" required autocomplete="off">
                    <datalist id="mobilList">
                        <?php if(!empty($mobils)): ?>
                            <?php foreach ($mobils as $m): ?>
                                <option value="<?= esc($m['nama_mobil']) ?> (<?= esc($m['warna']) ?>)" data-harga="<?= $m['harga_beli'] ?>"></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </datalist>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6 form-group">
                    <label class="form-label">Tanggal Beli <span class="text-danger">*</span></label>
                    <input type="date" name="tgl_pembelian" class="form-control" value="<?= old('tgl_pembelian', date('Y-m-d')) ?>" required>
                </div>
                <div class="col-md-6 form-group">
                    <label class="form-label">No. Kwitansi</label>
                    <input type="text" name="no_kwitansi" class="form-control" placeholder="KWT-001" value="<?= old('no_kwitansi') ?>">
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6 form-group">
                    <label class="form-label">Metode Bayar <span class="text-danger">*</span></label>
                    <select name="metode_bayar" id="metode_bayar" class="form-control" required>
                        <option value="tunai" <?= old('metode_bayar') === 'tunai' ? 'selected' : '' ?>>Tunai</option>
                        <option value="transfer" <?= old('metode_bayar') === 'transfer' ? 'selected' : '' ?>>Transfer Bank</option>
                    </select>
                </div>
                <div class="col-md-6 form-group" id="group_bukti" style="display: none;">
                    <label class="form-label">Bukti Transfer</label>
                    <input type="file" name="bukti_transfer" class="form-control">
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-4 form-group">
                    <label class="form-label">Jumlah Unit <span class="text-danger">*</span></label>
                    <input type="number" name="jumlah_pembelian" id="qty" class="form-control" value="<?= old('jumlah_pembelian', 1) ?>" min="1" required>
                </div>
                <div class="col-md-4 form-group">
                    <label class="form-label">Harga Satuan (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="harga_beli" id="harga_beli" class="form-control" value="<?= old('harga_beli', 0) ?>" required>
                </div>
                <div class="col-md-4 form-group">
                    <label class="form-label text-success" style="font-weight: 600;">Total Harga</label>
                    <input type="number" name="total_harga" id="total_harga" class="form-control text-success" style="font-weight: 600;" readonly>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12 form-group">
                    <label class="form-label">Keterangan Kondisi</label>
                    <textarea name="keterangan_kondisi" class="form-control" rows="2" placeholder="Catatan kondisi unit..."><?= old('keterangan_kondisi') ?></textarea>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4 pt-4" style="border-top: 1px solid var(--gray-200);">
                <a href="<?= base_url('pembelian') ?>" class="btn btn-secondary">Batalkan</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check2-circle"></i> Simpan Transaksi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobilInput = document.getElementById('mobilInput');
        const mobilList = document.getElementById('mobilList');
        const qtyInput = document.getElementById('qty');
        const hargaInput = document.getElementById('harga_beli');
        const totalInput = document.getElementById('total_harga');
        
        // Tambahan Script penampil upload file bukti transfer
        const metodeBayar = document.getElementById('metode_bayar');
        const groupBukti = document.getElementById('group_bukti');

        function toggleBukti() {
            if (metodeBayar.value === 'transfer') {
                groupBukti.style.display = 'block';
            } else {
                groupBukti.style.display = 'none';
            }
        }
        metodeBayar.addEventListener('change', toggleBukti);
        toggleBukti();

        function hitungTotal() {
            const qty = parseInt(qtyInput.value) || 0;
            const harga = parseInt(hargaInput.value) || 0;
            const total = qty * harga;
            totalInput.value = total;

            let preview = document.getElementById('total_harga_preview');
            if (!preview) {
                preview = document.createElement('div');
                preview.id = 'total_harga_preview';
                preview.className = 'mt-2 text-success fw-bold small';
                totalInput.parentNode.appendChild(preview);
            }
            preview.innerHTML = `<i class="bi bi-tags-fill me-1"></i> Terbilang: ` + formatRupiah(total);
        }

        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
        }

        mobilInput.addEventListener('input', function() {
            const val = this.value;
            const option = Array.from(mobilList.options).find(opt => opt.value === val);
            if (option) {
                const hargaDefault = option.getAttribute('data-harga');
                if (hargaDefault) {
                    hargaInput.value = hargaDefault;
                }
            }
            hitungTotal();
        });

        qtyInput.addEventListener('input', hitungTotal);
        hargaInput.addEventListener('input', hitungTotal);
        
        hitungTotal();
    });
</script>

<?= view('templates/footer') ?>