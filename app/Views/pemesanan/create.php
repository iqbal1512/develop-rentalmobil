<?php $title = 'Tambah Pemesanan'; ?>
<?= view('templates/header') ?>

<div class="card" style="max-width: 900px; margin: 0 auto;">
  <div class="card-header">
    <h5><i class="bi bi-calendar-plus-fill" style="color: var(--primary);"></i> Tambah Pemesanan Mobil</h5>
    <a href="<?= base_url('pemesanan') ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
  </div>
  <div class="card-body">
    <!-- Info bisnis -->
    <div class="alert alert-info" style="margin-bottom:18px">
      <i class="bi bi-info-circle-fill"></i>
      <strong>Syarat Pemesanan:</strong> Bukti pesanan Rp500.000 + DP 30% dalam 7 hari + Fotocopy KTP. Lewat 7 hari → otomatis batal.
    </div>

    <p style="color: var(--gray-500); font-size: 0.875rem; margin-bottom: 1.5rem;">Lengkapi formulir di bawah ini untuk mencatat pemesanan unit kendaraan.</p>

    <form action="<?= base_url('pemesanan/store') ?>" method="POST">
      <?= csrf_field() ?>
      <div class="row">
        <div class="col-md-6 form-group">
          <label class="form-label">Customer <span class="text-danger">*</span></label>
          <select name="id_customer" class="form-select" required>
            <option value="">-- Pilih Customer --</option>
            <?php foreach ($customers as $c): ?>
            <option value="<?= $c['id_customer'] ?>" <?= old('id_customer') == $c['id_customer'] ? 'selected' : '' ?>>
              <?= esc($c['nama']) ?> (<?= esc($c['no_ktp']) ?>)
            </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-6 form-group">
          <label class="form-label">Mobil <span class="text-danger">*</span></label>
          <select name="id_mobil" id="mobilSel" class="form-select" required>
            <option value="">-- Pilih Mobil Tersedia --</option>
            <?php foreach ($mobils as $m): ?>
            <option value="<?= $m['id_mobil'] ?>" data-harga="<?= $m['harga_jual'] ?>" <?= old('id_mobil') == $m['id_mobil'] ? 'selected' : '' ?>>
              <?= esc($m['nama_mobil']) ?> - <?= esc($m['warna']) ?> (Rp<?= number_format($m['harga_jual'],0,',','.') ?>)
            </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="row mt-3">
        <div class="col-md-4 form-group">
          <label class="form-label">Tanggal Pesan <span class="text-danger">*</span></label>
          <input type="date" name="tgl_pesan" id="tglPesan" class="form-control" value="<?= old('tgl_pesan', date('Y-m-d')) ?>" required>
        </div>
        <div class="col-md-4 form-group">
          <label class="form-label">Jatuh Tempo (otomatis +7 hari)</label>
          <input type="date" name="tgl_jatuh_tempo" id="tglTempo" class="form-control" readonly style="background:var(--bg-primary)">
        </div>
        <div class="col-md-4 form-group">
          <label class="form-label">Biaya Bukti Pesan</label>
          <input type="text" class="form-control" value="Rp500.000" readonly style="background:var(--bg-primary)">
          <input type="hidden" name="biaya_bukti_pesan" value="500000">
        </div>
      </div>

      <div class="row mt-3">
        <div class="col-md-4 form-group">
          <label class="form-label">Harga Jual Mobil</label>
          <input type="number" name="harga_jual" id="hargaJual" class="form-control" value="<?= old('harga_jual', 0) ?>" min="0" readonly style="background:var(--bg-primary)">
        </div>
        <div class="col-md-4 form-group">
          <label class="form-label">Harga Jual Jadi (setelah nego) <span class="text-danger">*</span></label>
          <input type="number" name="harga_jual_jadi" id="hargaJadi" class="form-control" value="<?= old('harga_jual_jadi', 0) ?>" min="0" required>
        </div>
        <div class="col-md-4 form-group">
          <label class="form-label">Nominal DP (30%)</label>
          <input type="number" name="nominal_dp" id="nominalDp" class="form-control" value="<?= old('nominal_dp', 0) ?>" min="0" readonly style="background:var(--bg-primary)">
        </div>
      </div>

      <div class="row mt-3">
        <div class="col-md-6 form-group">
          <label class="form-label">DP Awal Dibayar</label>
          <input type="number" name="dp_awal_dibayar" id="dpAwal" class="form-control" value="<?= old('dp_awal_dibayar', 500000) ?>" min="0">
          <div class="form-text text-secondary">Min: Rp500.000 (bukti pesan)</div>
        </div>
        <div class="col-md-6 form-group">
          <label class="form-label">Sisa DP Internal</label>
          <input type="number" name="sisa_dp_internal" id="sisaDp" class="form-control" value="<?= old('sisa_dp_internal', 0) ?>" readonly style="background:var(--bg-primary)">
        </div>
      </div>

      <div class="row mt-3">
        <div class="col-md-6 form-group">
          <label class="form-label">KTP Sudah Diterima?</label>
          <select name="ktp_diterima" class="form-select">
            <option value="0" <?= old('ktp_diterima') == '0' ? 'selected' : '' ?>>Belum</option>
            <option value="1" <?= old('ktp_diterima') == '1' ? 'selected' : '' ?>>Sudah</option>
          </select>
        </div>
        <div class="col-md-6 form-group">
          <label class="form-label">Catatan</label>
          <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan tambahan..."><?= old('catatan') ?></textarea>
        </div>
      </div>

      <div class="d-flex justify-content-between align-items-center mt-4 pt-4" style="border-top: 1px solid var(--gray-200);">
        <a href="<?= base_url('pemesanan') ?>" class="btn btn-secondary">Batalkan</a>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle"></i> Simpan Pemesanan</button>
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
            if(hargaJual) hargaJual.value = harga;
            if(hargaJadi) hargaJadi.value = harga;
            hitungDp();
        });
    }

    if (hargaJadi) hargaJadi.addEventListener('input', hitungDp);
    if (dpAwal) dpAwal.addEventListener('input', hitungSisa);

    function hitungDp(){
        if (!hargaJadi || !nominalDp) return;
        const jadi = parseFloat(hargaJadi.value) || 0;
        const dp = Math.ceil(jadi * 0.3);
        nominalDp.value = dp;
        hitungSisa();
    }

    function hitungSisa(){
        if (!nominalDp || !dpAwal || !sisaDp) return;
        const dp = parseFloat(nominalDp.value) || 0;
        const bayar = parseFloat(dpAwal.value) || 0;
        sisaDp.value = Math.max(0, dp - bayar);
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
