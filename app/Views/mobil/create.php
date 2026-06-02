<?php $title = 'Tambah Mobil'; ?>
<?= view('templates/header') ?>

<div class="page-content" style="padding: 1.5rem 0;">
  <div class="card" style="max-width: 900px; margin: 0 auto; border: 1px solid var(--gray-200); border-radius: 0.5rem; background: #fff;">
    <div class="card-header" style="display: flex; align-items: center; justify-content: space-between; padding: 16px 24px; background: transparent; border-bottom: 1px solid var(--gray-100);">
      <h5 style="margin: 0; font-weight: 700; font-size: 1.15rem;">
        <i class="bi bi-car-front-fill" style="color: var(--primary); margin-right: 6px;"></i> Tambah Unit Kendaraan
      </h5>
      <a href="<?= base_url('mobil') ?>" class="btn btn-secondary btn-sm" style="font-weight: 600; font-size: 0.8rem; padding: 6px 12px; border-radius: 6px;">
        <i class="bi bi-arrow-left"></i> Kembali
      </a>
    </div>
    
    <div class="card-body" style="padding: 24px;">
      <p style="color: var(--gray-500); font-size: 0.875rem; margin-bottom: 1.5rem;">Lengkapi formulir di bawah ini untuk menambahkan koleksi unit mobil baru ke dalam sistem inventori showroom.</p>
      
      <?php if (session()->getFlashdata('errors')): ?>
          <div class="alert alert-danger border-0 shadow-sm" style="border-radius: 0.5rem;">
              <h6 class="font-weight-bold mb-2"><i class="bi bi-exclamation-octagon-fill me-2"></i> Periksa kembali isian form:</h6>
              <ul class="mb-0 ps-3 small">
                  <?php foreach (session()->getFlashdata('errors') as $error): ?>
                      <li><?= esc($error) ?></li>
                  <?php endforeach; ?>
              </ul>
          </div>
      <?php endif; ?>
      
      <form action="<?= base_url('mobil/store') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        
        <h6 class="mb-3" style="font-weight: 700; color: var(--primary); border-left: 3px solid var(--primary); padding-left: 10px; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Informasi Utama</h6>
        <div class="row g-3">
          <div class="col-md-6 form-group">
             <label class="form-label" style="font-weight: 600; font-size: 0.85rem; color: #4a5568;">Nama Mobil <span class="text-danger">*</span></label>
             <input type="text" name="nama_mobil" class="form-control" placeholder="Contoh: Toyota Avanza 1.5 G" value="<?= old('nama_mobil') ?>" required>
          </div>
          <div class="col-md-6 form-group">
             <label class="form-label" style="font-weight: 600; font-size: 0.85rem; color: #4a5568;">Supplier Asal <span class="text-danger">*</span></label>
             <select name="id_supplier" class="form-control" style="cursor: pointer;" required>
                 <option value="">Pilih Supplier...</option>
                 <?php foreach ($supplier as $s): ?>
                    <option value="<?= $s['id_supplier'] ?>" <?= old('id_supplier') == $s['id_supplier'] ? 'selected' : '' ?>>
                        <?= esc($s['nama_supplier']) ?>
                    </option>
                 <?php endforeach; ?>
             </select>
          </div>
        </div>

        <h6 class="mt-4 mb-3" style="font-weight: 700; color: var(--primary); border-left: 3px solid var(--primary); padding-left: 10px; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Spesifikasi & Identitas</h6>
        <div class="row g-3">
            <div class="col-md-4 form-group">
                <label class="form-label" style="font-weight: 600; font-size: 0.85rem; color: #4a5568;">Merek / Vendor <span class="text-danger">*</span></label>
                <input type="text" name="vendor" class="form-control" placeholder="Contoh: Toyota, Honda" value="<?= old('vendor') ?>" required>
            </div>
            <div class="col-md-4 form-group">
                <label class="form-label" style="font-weight: 600; font-size: 0.85rem; color: #4a5568;">Tipe / Model <span class="text-danger">*</span></label>
                <input type="text" name="tipe" class="form-control" placeholder="Contoh: SUV, MPV, Sedan" value="<?= old('tipe') ?>" required>
            </div>
            <div class="col-md-4 form-group">
                <label class="form-label" style="font-weight: 600; font-size: 0.85rem; color: #4a5568;">Warna Dominan <span class="text-danger">*</span></label>
                <input type="text" name="warna" class="form-control" placeholder="Contoh: Hitam Metalik" value="<?= old('warna') ?>" required>
            </div>
            <div class="col-md-4 form-group">
                <label class="form-label" style="font-weight: 600; font-size: 0.85rem; color: #4a5568;">Tahun Produksi</label>
                <input type="number" name="tahun" class="form-control" placeholder="Contoh: 2023" min="1990" max="<?= date('Y') ?>" value="<?= old('tahun') ?>">
            </div>
            <div class="col-md-4 form-group">
                <label class="form-label" style="font-weight: 600; font-size: 0.85rem; color: #4a5568;">Nomor Polisi (Plat No.)</label>
                <input type="text" name="no_polisi" class="form-control" style="text-transform: uppercase;" placeholder="Contoh: B 1234 ABC" value="<?= old('no_polisi') ?>">
            </div>
            <div class="col-md-4 form-group">
                <label class="form-label" style="font-weight: 600; font-size: 0.85rem; color: #4a5568;">Stok Awal Unit <span class="text-danger">*</span></label>
                <input type="number" name="stok" class="form-control" value="<?= old('stok', 1) ?>" min="0" required>
            </div>
        </div>

        <h6 class="mt-4 mb-3" style="font-weight: 700; color: var(--primary); border-left: 3px solid var(--primary); padding-left: 10px; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Finansial & Status Jual</h6>
        <div class="row g-3">
            <div class="col-md-6 form-group">
                <label class="form-label" style="font-weight: 600; font-size: 0.85rem; color: #4a5568;">Harga Beli Showroom (Rp) <span class="text-danger">*</span></label>
                <input type="text" name="harga_beli" class="form-control mask-rupiah" placeholder="0" value="<?= old('harga_beli') ? number_format(old('harga_beli'), 0, '', '.') : '' ?>" required>
            </div>
            <div class="col-md-6 form-group">
                <label class="form-label" style="font-weight: 600; font-size: 0.85rem; color: #4a5568;">Harga Jual Konsumen (Rp) <span class="text-danger">*</span></label>
                <input type="text" name="harga_jual" class="form-control mask-rupiah" placeholder="0" value="<?= old('harga_jual') ? number_format(old('harga_jual'), 0, '', '.') : '' ?>" required>
            </div>
            <div class="col-md-6 form-group">
                <label class="form-label" style="font-weight: 600; font-size: 0.85rem; color: #4a5568;">Kondisi Kendaraan</label>
                <select name="status_mobil" class="form-control" style="cursor: pointer;">
                    <option value="bekas" <?= old('status_mobil') === 'bekas' ? 'selected' : '' ?>>Bekas / Second</option>
                    <option value="baru" <?= old('status_mobil') === 'baru' ? 'selected' : '' ?>>Baru / New</option>
                </select>
            </div>
            <div class="col-md-6 form-group">
                <label class="form-label" style="font-weight: 600; font-size: 0.85rem; color: #4a5568;">Status Ketersediaan</label>
                <select name="status_jual" class="form-control" style="cursor: pointer;">
                    <option value="tersedia" <?= old('status_jual', 'tersedia') === 'tersedia' ? 'selected' : '' ?>>Tersedia (Ready)</option>
                    <option value="dipesan" <?= old('status_jual') === 'dipesan' ? 'selected' : '' ?>>Dipesan (Booked)</option>
                    <option value="terjual" <?= old('status_jual') === 'terjual' ? 'selected' : '' ?>>Terjual (Sold)</option>
                </select>
            </div>
        </div>

        <h6 class="mt-4 mb-3" style="font-weight: 700; color: var(--primary); border-left: 3px solid var(--primary); padding-left: 10px; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Media & Dokumentasi</h6>
        <div class="row g-3">
            <div class="col-md-12 form-group">
                <label class="form-label" style="font-weight: 600; font-size: 0.85rem; color: #4a5568;">Foto Unit Kendaraan</label>
                <input type="file" name="foto" class="form-control" accept="image/jpg,image/jpeg,image/png,image/webp" id="fotoInput" style="padding: 8px 12px;">
                <p style="font-size: 0.725rem; color: var(--gray-500); margin-top: 5px; line-height: 1.3;">Maksimal ukuran file gambar adalah 2MB (Format didukung: JPG, JPEG, PNG, WEBP).</p>
                
                <div id="imagePreview" class="mt-3 d-none">
                    <span class="d-block mb-1 small text-success font-weight-bold"><i class="bi bi-image-fill"></i> Preview Foto Terpilih:</span>
                    <img src="#" alt="Preview" style="max-height: 180px; border-radius: 0.375rem; border: 1px solid var(--primary);">
                </div>
            </div>
            <div class="col-md-12 form-group">
                <label class="form-label" style="font-weight: 600; font-size: 0.85rem; color: #4a5568;">Catatan / Keterangan Tambahan</label>
                <textarea name="keterangan" class="form-control" rows="3" placeholder="Masukkan kelengkapan surat-surat, riwayat pajak, atau spesifikasi khusus lainnya..."><?= old('keterangan') ?></textarea>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4 pt-3" style="border-top: 1px solid var(--gray-200);">
            <a href="<?= base_url('mobil') ?>" class="btn btn-light" style="font-weight: 600; padding: 8px 20px; border-radius: 6px;">Batalkan</a>
            <button type="submit" class="btn btn-primary" style="font-weight: 600; padding: 8px 24px; border-radius: 6px;">
                <i class="bi bi-check2-circle me-1"></i> Simpan Data Mobil
            </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
    // Live Preview Image Handler
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

    // PENYEMPURNAAN: Event Listener Masking Otomatis Format Pemisah Ribuan Rupiah
    document.querySelectorAll('.mask-rupiah').forEach(function(input) {
        input.addEventListener('input', function(e) {
            let value = this.value.replace(/\D/g, '');
            this.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        });
    });
</script>

<?= view('templates/footer') ?>