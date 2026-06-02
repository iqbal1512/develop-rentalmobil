<?php $title = 'Tambah Pemesanan'; ?>
<?= view('templates/header') ?>

<div class="card shadow-sm bg-white text-dark" style="max-width: 900px; margin: 20px auto;">
  <div class="card-header bg-white d-flex justify-content-between align-items-center">
    <h5 class="mb-0 text-dark"><i class="bi bi-calendar-plus-fill text-primary me-2"></i> Tambah Pemesanan Mobil</h5>
    <a href="<?= base_url('pemesanan') ?>" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
  </div>
  <div class="card-body">
    
    <div class="alert alert-info border-start border-info border-3 mb-4" role="alert">
      <i class="bi bi-info-circle-fill me-2"></i>
      <strong>Alur Pemesanan Baru:</strong> Pilih Customer & Unit Mobil → Tentukan Harga Sepakat (Deal) → Isi Nominal Uang Tanda Jadi untuk Mengunci Status Mobil.
    </div>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger p-2 mb-3">
            <ul class="mb-0">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('pemesanan/store') ?>" method="POST">
      <?= csrf_field() ?>

      <h6 class="text-primary fw-bold mb-3"><i class="bi bi-person-badge me-1"></i> 1. Data Customer & Unit Mobil</h6>
      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <div class="form-group">
            <label class="form-label text-secondary fw-semibold">Pilih Customer <span class="text-danger">*</span></label>
            <select name="id_customer" class="form-select bg-white text-dark" required>
              <option value="">-- Klik untuk memilih --</option>
              <?php foreach ($customers as $c): ?>
              <option value="<?= $c['id_customer'] ?>" <?= old('id_customer') == $c['id_customer'] ? 'selected' : '' ?>>
                <?= esc($c['nama']) ?> (No. KTP: <?= esc($c['no_ktp']) ?>)
              </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="form-label text-secondary fw-semibold">Pilih Mobil Tersedia <span class="text-danger">*</span></label>
            <select name="id_mobil" id="mobilSel" class="form-select bg-white text-dark" required>
              <option value="">-- Klik untuk memilih --</option>
              <?php foreach ($mobils as $m): ?>
              <option value="<?= $m['id_mobil'] ?>" data-harga="<?= $m['harga_jual'] ?>" <?= old('id_mobil') == $m['id_mobil'] ? 'selected' : '' ?>>
                <?= esc($m['merek']) ?> - <?= esc($m['nama_mobil']) ?> (<?= esc($m['warna']) ?>)
              </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>

      <h6 class="text-primary fw-bold mb-3"><i class="bi bi-calendar-event me-1"></i> 2. Waktu Batas Tempo</h6>
      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <div class="form-group">
            <label class="form-label text-secondary fw-semibold">Tanggal Pemesanan <span class="text-danger">*</span></label>
            <input type="date" name="tgl_pesan" id="tglPesan" class="form-control bg-white text-dark" value="<?= old('tgl_pesan', date('Y-m-d')) ?>" required>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="form-label text-secondary fw-semibold">Batas Waktu Pelunasan / Transaksi (Jatuh Tempo)</label>
            <input type="date" name="tgl_jatuh_tempo" id="tglTempo" class="form-control text-danger fw-bold border-danger border-dashed" readonly style="background-color: #fff5f5;">
            <div class="form-text text-danger mt-1"><i class="bi bi-exclamation-triangle"></i> Sistem menghitung otomatis masa aktif data pesanan selama +7 hari.</div>
          </div>
        </div>
      </div>

      <h6 class="text-primary fw-bold mb-3"><i class="bi bi-wallet2 me-1"></i> 3. Nominal Nilai Kesepakatan</h6>
      <div class="p-3 mb-4 rounded border" style="background-color: #f8f9fa;">
        <div class="row g-3">
          <div class="col-md-4">
            <div class="form-group">
              <label class="form-label text-secondary small fw-semibold">Harga Asli Unit Showroom</label>
              <div class="input-group">
                <span class="input-group-text bg-secondary-subtle">Rp</span>
                <input type="text" id="hargaJual" class="form-control mask-rupiah" readonly style="background-color: #e9ecef;">
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label class="form-label text-dark small fw-semibold">Harga Deal (Hasil Nego) <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text bg-success text-white fw-bold">Rp</span>
                <input type="text" name="harga_jadi" id="hargaJadi" class="form-control border-success mask-rupiah" value="<?= old('harga_jadi') ?>" required>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label class="form-label text-dark small fw-semibold">Nilai Uang Tanda Jadi (Booking Fee) <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text bg-primary text-white fw-bold">Rp</span>
                <input type="text" name="nilai_tanda_jadi" id="nilaiTandaJadi" class="form-control border-primary mask-rupiah" value="<?= old('nilai_tanda_jadi', '500.000') ?>" required>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
        <a href="<?= base_url('pemesanan') ?>" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i> Batal</a>
        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check2-circle"></i> Simpan Pemesanan Baru</button>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobilSel       = document.getElementById('mobilSel');
    const hargaJual      = document.getElementById('hargaJual');
    const hargaJadi      = document.getElementById('hargaJadi');
    const tglPesan       = document.getElementById('tglPesan');
    const tglTempo       = document.getElementById('tglTempo');

    // Deteksi Otomatisasi saat Mobil Dipilih
    if (mobilSel) {
        mobilSel.addEventListener('change', function(){
            const opt = this.options[this.selectedIndex];
            const harga = opt.getAttribute('data-harga') || 0;
            
            if(hargaJual) hargaJual.value = new Intl.NumberFormat('id-ID').format(harga);
            if(hargaJadi) hargaJadi.value = new Intl.NumberFormat('id-ID').format(harga);
        });
    }

    // Hitung Otomatis Tanggal Jatuh Tempo +7 Hari
    function hitungJatuhTempo() {
        if (tglPesan && tglTempo && tglPesan.value) {
            const d = new Date(tglPesan.value);
            if (!isNaN(d.getTime())) {
                d.setDate(d.getDate() + 7);
                tglTempo.value = d.toISOString().split('T')[0];
            }
        }
    }

    if (tglPesan) {
        tglPesan.addEventListener('change', hitungJatuhTempo);
        hitungJatuhTempo(); // Jalankan inisialisasi awal saat halaman termuat
    }
});
</script>

<?= view('templates/footer') ?>