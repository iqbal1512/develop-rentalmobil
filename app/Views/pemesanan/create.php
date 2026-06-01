<?php $title = 'Tambah Pemesanan'; ?>
<?= view('templates/header') ?>

<div class="card" style="max-width: 900px; margin: 0 auto;">
  <div class="card-header">
    <h5><i class="bi bi-calendar-plus-fill" style="color: var(--primary);"></i> Tambah Pemesanan Mobil</h5>
    <a href="<?= base_url('pemesanan') ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
  </div>
  <div class="card-body">
    <!-- Info bisnis -->
    <div class="alert alert-info" style="margin-bottom:18px; border-left: 5px solid var(--info)">
      <i class="bi bi-info-circle-fill me-2"></i>
      <strong>Alur Pemesanan:</strong> Pilih Customer & Mobil → Tentukan Harga Deal → Pembayaran Booking Fee (Min. Rp500.000) → Sisa DP & KTP dilunasi dalam 7 hari.
    </div>

    <form action="<?= base_url('pemesanan/store') ?>" method="POST">
      <?= csrf_field() ?>
      <input type="hidden" name="biaya_bukti_pesan" value="500000">

      <!-- BAGIAN 1: Customer & Mobil -->
      <h6 class="text-primary fw-bold mb-3"><i class="bi bi-person-badge"></i> 1. Data Customer & Mobil</h6>
      <div class="row mb-4">
        <div class="col-md-6 form-group">
          <label class="form-label text-secondary">Pilih Customer <span class="text-danger">*</span></label>
          <select name="id_customer" class="form-select" required>
            <option value="">-- Klik untuk memilih --</option>
            <?php foreach ($customers as $c): ?>
            <option value="<?= $c['id_customer'] ?>" <?= old('id_customer') == $c['id_customer'] ? 'selected' : '' ?>>
              <?= esc($c['nama']) ?> (<?= esc($c['no_ktp']) ?>)
            </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-6 form-group">
          <label class="form-label text-secondary">Pilih Mobil Tersedia <span class="text-danger">*</span></label>
          <select name="id_mobil" id="mobilSel" class="form-select" required>
            <option value="">-- Klik untuk memilih --</option>
            <?php foreach ($mobils as $m): ?>
            <option value="<?= $m['id_mobil'] ?>" data-harga="<?= $m['harga_jual'] ?>" <?= old('id_mobil') == $m['id_mobil'] ? 'selected' : '' ?>>
              <?= esc($m['nama_mobil']) ?> - <?= esc($m['warna']) ?> (Rp <?= number_format($m['harga_jual'],0,',','.') ?>)
            </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <!-- BAGIAN 2: Waktu -->
      <h6 class="text-primary fw-bold mb-3"><i class="bi bi-calendar-event"></i> 2. Waktu Pemesanan</h6>
      <div class="row mb-4">
        <div class="col-md-6 form-group">
          <label class="form-label text-secondary">Tanggal Pemesanan <span class="text-danger">*</span></label>
          <input type="date" name="tgl_pesan" id="tglPesan" class="form-control" value="<?= old('tgl_pesan', date('Y-m-d')) ?>" required>
        </div>
        <div class="col-md-6 form-group">
          <label class="form-label text-secondary">Batas Waktu Pelunasan DP (Jatuh Tempo)</label>
          <input type="date" name="tgl_jatuh_tempo" id="tglTempo" class="form-control text-danger fw-bold" readonly style="background:var(--bg-primary); border: 1px dashed var(--danger)">
          <div class="form-text text-danger"><i class="bi bi-exclamation-triangle"></i> Otomatis +7 hari dari tanggal pesan</div>
        </div>
      </div>

      <!-- BAGIAN 3: Harga & DP -->
      <h6 class="text-primary fw-bold mb-3"><i class="bi bi-wallet2"></i> 3. Kesepakatan Harga & Pembayaran</h6>
      <div class="p-3 mb-4 rounded" style="background-color: #f8f9fa; border: 1px solid #e9ecef;">
        <div class="row">
          <div class="col-md-6 form-group">
            <label class="form-label text-secondary">Harga Asli Mobil</label>
            <div class="input-group">
              <span class="input-group-text">Rp</span>
              <input type="text" name="harga_jual" id="hargaJual" class="form-control mask-rupiah" value="<?= old('harga_jual', 0) ? number_format(old('harga_jual', 0), 0, '', '.') : '' ?>" readonly style="background:#e9ecef;">
            </div>
          </div>
          <div class="col-md-6 form-group">
            <label class="form-label text-secondary">Harga Deal (Setelah Nego) <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text text-success fw-bold">Rp</span>
              <input type="text" name="harga_jual_jadi" id="hargaJadi" class="form-control border-success mask-rupiah" value="<?= old('harga_jual_jadi', 0) ? number_format(old('harga_jual_jadi', 0), 0, '', '.') : '' ?>" required>
            </div>
            <div class="form-text">Harga final yang disepakati dengan pembeli.</div>
          </div>
        </div>

        <hr style="border-color: #dee2e6;">

        <div class="row">
          <div class="col-md-4 form-group">
            <label class="form-label text-secondary">Total DP yang harus dibayar</label>
            <div class="input-group">
              <span class="input-group-text">Rp</span>
              <input type="text" name="nominal_dp" id="nominalDp" class="form-control mask-rupiah" value="<?= old('nominal_dp', 0) ? number_format(old('nominal_dp', 0), 0, '', '.') : '' ?>">
            </div>
            <div class="form-text text-info">Default 30% dari Harga Deal. Bisa diedit.</div>
          </div>
          <div class="col-md-4 form-group">
            <label class="form-label text-secondary">Booking Fee (Dibayar Sekarang)</label>
            <div class="input-group">
              <span class="input-group-text">Rp</span>
              <input type="text" name="dp_awal_dibayar" id="dpAwal" class="form-control mask-rupiah" value="<?= old('dp_awal_dibayar', 500000) ? number_format(old('dp_awal_dibayar', 500000), 0, '', '.') : '' ?>">
            </div>
            <div class="form-text text-warning">Minimal Rp 500.000</div>
          </div>
          <div class="col-md-4 form-group">
            <label class="form-label text-secondary">Kekurangan DP (Sisa DP)</label>
            <div class="input-group">
              <span class="input-group-text text-danger fw-bold">Rp</span>
              <input type="text" name="sisa_dp_internal" id="sisaDp" class="form-control text-danger fw-bold mask-rupiah" value="<?= old('sisa_dp_internal', 0) ? number_format(old('sisa_dp_internal', 0), 0, '', '.') : '' ?>" readonly style="background:#ffebee; border-color: #ffcdd2;">
            </div>
            <div class="form-text text-danger">Harus dilunasi sebelum jatuh tempo.</div>
          </div>
        </div>
      </div>

      <!-- BAGIAN 4: Dokumen -->
      <h6 class="text-primary fw-bold mb-3"><i class="bi bi-file-earmark-person"></i> 4. Dokumen & Catatan Tambahan</h6>
      <div class="row">
        <div class="col-md-6 form-group">
          <label class="form-label text-secondary">Fotocopy KTP Pembeli?</label>
          <div class="d-flex gap-3 mt-2">
            <div class="form-check">
              <input class="form-check-input" type="radio" name="ktp_diterima" id="ktpBelum" value="0" <?= old('ktp_diterima') == '0' ? 'checked' : '' ?> checked>
              <label class="form-check-label text-danger" for="ktpBelum">Belum Diserahkan</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="ktp_diterima" id="ktpSudah" value="1" <?= old('ktp_diterima') == '1' ? 'checked' : '' ?>>
              <label class="form-check-label text-success" for="ktpSudah">Sudah Diserahkan</label>
            </div>
          </div>
        </div>
        <div class="col-md-6 form-group">
          <label class="form-label text-secondary">Catatan (Opsional)</label>
          <textarea name="catatan" class="form-control" rows="2" placeholder="Contoh: Pembeli minta ganti oli sebelum pelunasan..."><?= old('catatan') ?></textarea>
        </div>
      </div>

      <div class="d-flex justify-content-between align-items-center mt-5 pt-3" style="border-top: 1px solid var(--gray-200);">
        <a href="<?= base_url('pemesanan') ?>" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i> Batal</a>
        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check2-circle"></i> Simpan Pemesanan Baru</button>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobilSel  = document.getElementById('mobilSel');
    const hargaJual = document.getElementById('hargaJual');
    const hargaJadi = document.getElementById('hargaJadi');
    const nominalDp = document.getElementById('nominalDp');
    const dpAwal    = document.getElementById('dpAwal');
    const sisaDp    = document.getElementById('sisaDp');
    const tglPesan  = document.getElementById('tglPesan');
    const tglTempo  = document.getElementById('tglTempo');

    if (mobilSel) {
        mobilSel.addEventListener('change', function(){
            const opt = this.options[this.selectedIndex];
            const harga = opt.getAttribute('data-harga') || 0;
            if(hargaJual) hargaJual.value = new Intl.NumberFormat('id-ID').format(harga);
            if(hargaJadi) hargaJadi.value = new Intl.NumberFormat('id-ID').format(harga);
            hitungDp();
        });
    }

    if (hargaJadi) hargaJadi.addEventListener('input', hitungDp);
    if (nominalDp) nominalDp.addEventListener('input', hitungSisa);
    if (dpAwal) dpAwal.addEventListener('input', hitungSisa);

    function hitungDp(){
        if (!hargaJadi || !nominalDp) return;
        const jadi = parseFloat(hargaJadi.value.replace(/[^0-9]/g, '')) || 0;
        const dp = Math.ceil(jadi * 0.3);
        nominalDp.value = new Intl.NumberFormat('id-ID').format(dp);
        hitungSisa();
    }

    function hitungSisa(){
        if (!nominalDp || !dpAwal || !sisaDp) return;
        const dp = parseFloat(nominalDp.value.replace(/[^0-9]/g, '')) || 0;
        const bayar = parseFloat(dpAwal.value.replace(/[^0-9]/g, '')) || 0;
        sisaDp.value = new Intl.NumberFormat('id-ID').format(Math.max(0, dp - bayar));
    }

    if (tglPesan && tglTempo) {
        tglPesan.addEventListener('change', function(){
            const d = new Date(this.value);
            if (!isNaN(d.getTime())) {
                d.setDate(d.getDate() + 7);
                tglTempo.value = d.toISOString().split('T')[0];
            }
        });

        // Init
        if (tglPesan.value) {
            const d = new Date(tglPesan.value);
            if (!isNaN(d.getTime())) {
                d.setDate(d.getDate() + 7);
                tglTempo.value = d.toISOString().split('T')[0];
            }
        }
    }
});
</script>

<?= view('templates/footer') ?>
