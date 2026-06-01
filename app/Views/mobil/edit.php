<?php $title = 'Edit Mobil'; ?>
<?= view('templates/header') ?>

<div class="page-content">
  <div class="card" style="max-width: 900px; margin: 0 auto;">
    <div class="card-header">
      <h5><i class="bi bi-pencil-square" style="color: var(--primary);"></i> Edit Unit: <?= esc($mobil['nama_mobil']) ?></h5>
      <a href="<?= base_url('mobil') ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
      </a>
    </div>
    
    <div class="card-body">
      <p style="color: var(--gray-500); font-size: 0.875rem; margin-bottom: 1.5rem;">Perbarui informasi unit kendaraan yang terdaftar di sistem.</p>
      
      <form action="<?= base_url('mobil/update/' . $mobil['id_mobil']) ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        
        <h6 class="mb-3" style="font-weight: 600; color: var(--primary); border-left: 3px solid var(--primary); padding-left: 10px;">Informasi Utama</h6>
        <div class="row">
          <div class="col-md-6 form-group">
             <label class="form-label">Nama Mobil <span class="text-danger">*</span></label>
             <input type="text" name="nama_mobil" class="form-control" value="<?= esc($mobil['nama_mobil']) ?>" required>
          </div>
          <div class="col-md-6 form-group">
             <label class="form-label">Supplier Asal <span class="text-danger">*</span></label>
             <select name="id_supplier" class="form-control" required>
                 <?php foreach ($supplier as $s): ?>
                 <option value="<?= $s['id_supplier'] ?>" <?= $mobil['id_supplier'] == $s['id_supplier'] ? 'selected' : '' ?>><?= esc($s['nama_supplier']) ?></option>
                 <?php endforeach; ?>
             </select>
          </div>
        </div>

        <h6 class="mt-4 mb-3" style="font-weight: 600; color: var(--primary); border-left: 3px solid var(--primary); padding-left: 10px;">Spesifikasi & Identitas</h6>
        <div class="row">
            <div class="col-md-4 form-group">
                <label class="form-label">Merek / Vendor</label>
                <input type="text" name="vendor" class="form-control" value="<?= esc($mobil['vendor']) ?>" required>
            </div>
            <div class="col-md-4 form-group">
                <label class="form-label">Tipe / Model</label>
                <input type="text" name="tipe" class="form-control" value="<?= esc($mobil['tipe']) ?>" required>
            </div>
            <div class="col-md-4 form-group">
                <label class="form-label">Warna Dominan</label>
                <input type="text" name="warna" class="form-control" value="<?= esc($mobil['warna']) ?>" required>
            </div>
            <div class="col-md-4 form-group mt-3">
                <label class="form-label">Tahun Produksi</label>
                <input type="number" name="tahun" class="form-control" value="<?= esc($mobil['tahun']) ?>">
            </div>
            <div class="col-md-4 form-group mt-3">
                <label class="form-label">Nomor Polisi (Plat)</label>
                <input type="text" name="no_polisi" class="form-control" value="<?= esc($mobil['no_polisi']) ?>">
            </div>
            <div class="col-md-4 form-group mt-3">
                <label class="form-label">Stok Unit</label>
                <input type="number" name="stok" class="form-control" value="<?= $mobil['stok'] ?>" min="0">
            </div>
        </div>

        <h6 class="mt-4 mb-3" style="font-weight: 600; color: var(--primary); border-left: 3px solid var(--primary); padding-left: 10px;">Finansial & Status Jual</h6>
        <div class="row">
            <div class="col-md-6 form-group">
                <label class="form-label">Harga Beli Showroom (Rp) <span class="text-danger">*</span></label>
                <input type="text" name="harga_beli" class="form-control mask-rupiah" value="<?= number_format($mobil['harga_beli'], 0, '', '.') ?>" required>
            </div>
            <div class="col-md-6 form-group">
                <label class="form-label">Harga Jual Konsumen (Rp) <span class="text-danger">*</span></label>
                <input type="text" name="harga_jual" class="form-control mask-rupiah" value="<?= number_format($mobil['harga_jual'], 0, '', '.') ?>" required>
            </div>
            <div class="col-md-6 form-group mt-3">
                <label class="form-label">Kondisi Kendaraan</label>
                <select name="status_mobil" class="form-control">
                    <option value="bekas" <?= $mobil['status_mobil'] === 'bekas' ? 'selected' : '' ?>>Bekas / Second</option>
                    <option value="baru" <?= $mobil['status_mobil'] === 'baru' ? 'selected' : '' ?>>Baru / New</option>
                </select>
            </div>
            <div class="col-md-6 form-group mt-3">
                <label class="form-label">Status Ketersediaan</label>
                <select name="status_jual" class="form-control">
                    <option value="tersedia" <?= $mobil['status_jual'] === 'tersedia' ? 'selected' : '' ?>>Tersedia (Ready)</option>
                    <option value="dipesan" <?= $mobil['status_jual'] === 'dipesan' ? 'selected' : '' ?>>Dipesan (Booked)</option>
                    <option value="terjual" <?= $mobil['status_jual'] === 'terjual' ? 'selected' : '' ?>>Terjual (Sold)</option>
                </select>
            </div>
        </div>

        <h6 class="mt-4 mb-3" style="font-weight: 600; color: var(--primary); border-left: 3px solid var(--primary); padding-left: 10px;">Media & Dokumentasi</h6>
        <div class="row align-items-center">
            <div class="col-md-4 form-group text-center">
                <label class="form-label d-block">Foto Saat Ini</label>
                <?php if ($mobil['foto']): ?>
                    <img src="<?= base_url('uploads/mobil/' . $mobil['foto']) ?>" style="max-height: 150px; border-radius: 0.5rem; border: 1px solid var(--gray-200);">
                <?php else: ?>
                    <div style="padding: 2rem; background: var(--gray-50); border: 1px dashed var(--gray-300); border-radius: 0.5rem;">
                        <i class="bi bi-image" style="font-size: 2rem; color: var(--gray-400);"></i>
                        <p style="font-size: 0.75rem; color: var(--gray-500); margin: 0;">Tidak ada foto</p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-md-8 form-group">
                <label class="form-label">Ganti Foto Unit</label>
                <input type="file" name="foto" class="form-control" accept="image/*" id="fotoInput" style="padding: 10px;">
                <p style="font-size: 0.75rem; color: var(--gray-500); margin-top: 5px;">Biarkan kosong jika tidak ingin mengubah foto.</p>
                <div id="imagePreview" class="mt-3 d-none">
                    <img src="#" alt="Preview" style="max-height: 120px; border-radius: 0.5rem; border: 1px solid var(--gray-200);">
                </div>
            </div>
            <div class="col-md-12 form-group mt-3">
                <label class="form-label">Catatan / Keterangan Tambahan</label>
                <textarea name="keterangan" class="form-control" rows="4"><?= esc($mobil['keterangan'] ?? '') ?></textarea>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4 pt-4" style="border-top: 1px solid var(--gray-200);">
            <a href="<?= base_url('mobil') ?>" class="btn btn-secondary">Batalkan</a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save2"></i> Update Unit Mobil
            </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
    document.getElementById('fotoInput').addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.querySelector('#imagePreview img');
                preview.src = e.target.result;
                document.getElementById('imagePreview').classList.remove('d-none');
            }
            reader.readAsDataURL(file);
        }
    });
</script>

<?= view('templates/footer') ?>