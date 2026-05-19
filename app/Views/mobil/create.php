<?php $title = 'Tambah Mobil'; ?>
<?= view('templates/header') ?>

<div class="page-content">
  <div class="card" style="max-width: 900px; margin: 0 auto;">
    <div class="card-header">
      <h5><i class="bi bi-car-front-fill" style="color: var(--primary);"></i> Tambah Unit Kendaraan</h5>
      <a href="<?= base_url('mobil') ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
      </a>
    </div>
    
    <div class="card-body">
      <p style="color: var(--gray-500); font-size: 0.875rem; margin-bottom: 1.5rem;">Lengkapi formulir di bawah ini untuk menambahkan koleksi mobil baru.</p>
      
      <form action="<?= base_url('mobil/store') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        
        <h6 class="mb-3" style="font-weight: 600; color: var(--primary); border-left: 3px solid var(--primary); padding-left: 10px;">Informasi Utama</h6>
        <div class="row">
          <div class="col-md-6 form-group">
             <label class="form-label">Nama Mobil <span class="text-danger">*</span></label>
             <input type="text" name="nama_mobil" class="form-control" placeholder="Contoh: Toyota Avanza 1.5 G" value="<?= old('nama_mobil') ?>" required>
          </div>
          <div class="col-md-6 form-group">
             <label class="form-label">Supplier Asal <span class="text-danger">*</span></label>
             <select name="id_supplier" class="form-control" required>
                 <option value="">Pilih Supplier...</option>
                 <?php foreach ($supplier as $s): ?>
                 <option value="<?= $s['id_supplier'] ?>" <?= old('id_supplier') == $s['id_supplier'] ? 'selected' : '' ?>><?= esc($s['nama_supplier']) ?></option>
                 <?php endforeach; ?>
             </select>
          </div>
        </div>

        <h6 class="mt-4 mb-3" style="font-weight: 600; color: var(--primary); border-left: 3px solid var(--primary); padding-left: 10px;">Spesifikasi & Identitas</h6>
        <div class="row">
            <div class="col-md-4 form-group">
                <label class="form-label">Merek / Vendor</label>
                <input type="text" name="vendor" class="form-control" placeholder="Toyota, Honda, dll" value="<?= old('vendor') ?>" required>
            </div>
            <div class="col-md-4 form-group">
                <label class="form-label">Tipe / Model</label>
                <input type="text" name="tipe" class="form-control" placeholder="SUV, MPV, Sedan" value="<?= old('tipe') ?>" required>
            </div>
            <div class="col-md-4 form-group">
                <label class="form-label">Warna Dominan</label>
                <input type="text" name="warna" class="form-control" placeholder="Hitam, Putih, dll" value="<?= old('warna') ?>" required>
            </div>
            <div class="col-md-4 form-group mt-3">
                <label class="form-label">Tahun Produksi</label>
                <input type="number" name="tahun" class="form-control" placeholder="2022" min="1990" max="<?= date('Y') ?>" value="<?= old('tahun') ?>">
            </div>
            <div class="col-md-4 form-group mt-3">
                <label class="form-label">Nomor Polisi (Plat)</label>
                <input type="text" name="no_polisi" class="form-control" placeholder="B 1234 ABC" value="<?= old('no_polisi') ?>">
            </div>
            <div class="col-md-4 form-group mt-3">
                <label class="form-label">Stok Unit</label>
                <input type="number" name="stok" class="form-control" value="<?= old('stok', 1) ?>" min="0">
            </div>
        </div>

        <h6 class="mt-4 mb-3" style="font-weight: 600; color: var(--primary); border-left: 3px solid var(--primary); padding-left: 10px;">Finansial & Status Jual</h6>
        <div class="row">
            <div class="col-md-6 form-group">
                <label class="form-label">Harga Beli Showroom (Rp) <span class="text-danger">*</span></label>
                <input type="number" name="harga_beli" class="form-control" placeholder="0" value="<?= old('harga_beli') ?>" required>
            </div>
            <div class="col-md-6 form-group">
                <label class="form-label">Harga Jual Konsumen (Rp) <span class="text-danger">*</span></label>
                <input type="number" name="harga_jual" class="form-control" placeholder="0" value="<?= old('harga_jual') ?>" required>
            </div>
            <div class="col-md-6 form-group mt-3">
                <label class="form-label">Kondisi Kendaraan</label>
                <select name="status_mobil" class="form-control">
                    <option value="bekas" <?= old('status_mobil') === 'bekas' ? 'selected' : '' ?>>Bekas / Second</option>
                    <option value="baru" <?= old('status_mobil') === 'baru' ? 'selected' : '' ?>>Baru / New</option>
                </select>
            </div>
            <div class="col-md-6 form-group mt-3">
                <label class="form-label">Status Ketersediaan</label>
                <select name="status_jual" class="form-control">
                    <option value="tersedia" <?= old('status_jual', 'tersedia') === 'tersedia' ? 'selected' : '' ?>>Tersedia (Ready)</option>
                    <option value="dipesan" <?= old('status_jual') === 'dipesan' ? 'selected' : '' ?>>Dipesan (Booked)</option>
                    <option value="terjual" <?= old('status_jual') === 'terjual' ? 'selected' : '' ?>>Terjual (Sold)</option>
                </select>
            </div>
        </div>

        <h6 class="mt-4 mb-3" style="font-weight: 600; color: var(--primary); border-left: 3px solid var(--primary); padding-left: 10px;">Media & Dokumentasi</h6>
        <div class="row">
            <div class="col-md-12 form-group">
                <label class="form-label">Foto Unit Kendaraan</label>
                <input type="file" name="foto" class="form-control" accept="image/*" id="fotoInput" style="padding: 10px;">
                <p style="font-size: 0.75rem; color: var(--gray-500); margin-top: 5px;">Disarankan format: JPG, PNG, atau WEBP. Maks 2MB.</p>
                <div id="imagePreview" class="mt-3 d-none">
                    <img src="#" alt="Preview" style="max-height: 200px; border-radius: 0.5rem; border: 1px solid var(--gray-200);">
                </div>
            </div>
            <div class="col-md-12 form-group mt-3">
                <label class="form-label">Catatan / Keterangan Tambahan</label>
                <textarea name="keterangan" class="form-control" rows="4" placeholder="Detail fitur, riwayat servis..."><?= old('keterangan') ?></textarea>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4 pt-4" style="border-top: 1px solid var(--gray-200);">
            <a href="<?= base_url('mobil') ?>" class="btn btn-secondary">Batalkan</a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check2-circle"></i> Simpan Data Mobil
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