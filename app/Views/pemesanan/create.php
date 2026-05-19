<?php $title = 'Tambah Pemesanan'; ?>
<?= view('templates/header') ?>

<div class="card bg-white border-light shadow-sm text-dark" style="max-width:850px;margin:0 auto">
  <div class="card-header bg-white text-dark border-light">
    <h5><i class="bi bi-calendar-plus-fill text-accent"></i> Tambah Pemesanan Mobil</h5>
    <a href="<?= base_url('pemesanan') ?>" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
  </div>
  <div class="card-body">
    <!-- Info bisnis -->
    <div class="alert alert-info" style="margin-bottom:18px">
      <i class="bi bi-info-circle-fill"></i>
      <strong>Syarat Pemesanan:</strong> Bukti pesanan Rp500.000 + DP 30% dalam 7 hari + Fotocopy KTP. Lewat 7 hari → otomatis batal.
    </div>

    <form action="<?= base_url('pemesanan/store') ?>" method="POST">
      <?= csrf_field() ?>
      <div class="row">
        <div class="col-6"><div class="form-group">
          <label class="form-label text-dark">Customer <span style="color:var(--danger)">*</span></label>
          <select name="id_customer" class="form-select bg-white text-dark border-light " required>
            <option value="">-- Pilih Customer --</option>
            <?php foreach ($customers as $c): ?>
            <option value="<?= $c['id_customer'] ?>" <?= old('id_customer') == $c['id_customer'] ? 'selected' : '' ?>>
              <?= esc($c['nama']) ?> (<?= esc($c['no_ktp']) ?>)
            </option>
            <?php endforeach; ?>
          </select>
        </div></div>
        <div class="col-6"><div class="form-group">
          <label class="form-label text-dark">Mobil <span style="color:var(--danger)">*</span></label>
          <select name="id_mobil" id="mobilSel" class="form-select bg-white text-dark border-light " required>
            <option value="">-- Pilih Mobil Tersedia --</option>
            <?php foreach ($mobils as $m): ?>
            <option value="<?= $m['id_mobil'] ?>" data-harga="<?= $m['harga_jual'] ?>" <?= old('id_mobil') == $m['id_mobil'] ? 'selected' : '' ?>>
              <?= esc($m['nama_mobil']) ?> - <?= esc($m['warna']) ?> (Rp<?= number_format($m['harga_jual'],0,',','.') ?>)
            </option>
            <?php endforeach; ?>
          </select>
        </div></div>
        <div class="col-4"><div class="form-group">
          <label class="form-label text-dark">Tanggal Pesan <span style="color:var(--danger)">*</span></label>
          <input type="date" name="tgl_pesan" id="tglPesan" class="form-control bg-white text-dark border-light " value="<?= old('tgl_pesan', date('Y-m-d')) ?>" required>
        </div></div>
        <div class="col-4"><div class="form-group">
          <label class="form-label text-dark">Jatuh Tempo (otomatis +7 hari)</label>
          <input type="date" name="tgl_jatuh_tempo" id="tglTempo" class="form-control bg-white text-dark border-light " readonly style="background:var(--bg-primary)">
        </div></div>
        <div class="col-4"><div class="form-group">
          <label class="form-label text-dark">Biaya Bukti Pesan</label>
          <input type="text" class="form-control bg-white text-dark border-light " value="Rp500.000" readonly style="background:var(--bg-primary)">
          <input type="hidden" name="biaya_bukti_pesan" value="500000">
        </div></div>
        <div class="col-4"><div class="form-group">
          <label class="form-label text-dark">Harga Jual Mobil</label>
          <input type="number" name="harga_jual" id="hargaJual" class="form-control bg-white text-dark border-light " value="<?= old('harga_jual', 0) ?>" min="0" readonly style="background:var(--bg-primary)">
        </div></div>
        <div class="col-4"><div class="form-group">
          <label class="form-label text-dark">Harga Jual Jadi (setelah nego) <span style="color:var(--danger)">*</span></label>
          <input type="number" name="harga_jual_jadi" id="hargaJadi" class="form-control bg-white text-dark border-light " value="<?= old('harga_jual_jadi', 0) ?>" min="0" required>
        </div></div>
        <div class="col-4"><div class="form-group">
          <label class="form-label text-dark">Nominal DP (30%)</label>
          <input type="number" name="nominal_dp" id="nominalDp" class="form-control bg-white text-dark border-light " value="<?= old('nominal_dp', 0) ?>" min="0" readonly style="background:var(--bg-primary)">
        </div></div>
        <div class="col-6"><div class="form-group">
          <label class="form-label text-dark">DP Awal Dibayar</label>
          <input type="number" name="dp_awal_dibayar" id="dpAwal" class="form-control bg-white text-dark border-light " value="<?= old('dp_awal_dibayar', 500000) ?>" min="0">
          <div class="form-text text-secondary">Min: Rp500.000 (bukti pesan)</div>
        </div></div>
        <div class="col-6"><div class="form-group">
          <label class="form-label text-dark">Sisa DP Internal</label>
          <input type="number" name="sisa_dp_internal" id="sisaDp" class="form-control bg-white text-dark border-light " value="<?= old('sisa_dp_internal', 0) ?>" readonly style="background:var(--bg-primary)">
        </div></div>
        <div class="col-6"><div class="form-group">
          <label class="form-label text-dark">KTP Sudah Diterima?</label>
          <select name="ktp_diterima" class="form-select bg-white text-dark border-light ">
            <option value="0" <?= old('ktp_diterima') == '0' ? 'selected' : '' ?>>Belum</option>
            <option value="1" <?= old('ktp_diterima') == '1' ? 'selected' : '' ?>>Sudah</option>
          </select>
        </div></div>
        <div class="col-12"><div class="form-group">
          <label class="form-label text-dark">Catatan</label>
          <textarea name="catatan" class="form-control bg-white text-dark border-light " rows="2" placeholder="Catatan tambahan..."><?= old('catatan') ?></textarea>
        </div></div>
      </div>
      <div class="d-flex justify-content-end gap-2" style="margin-top:8px">
        <a href="<?= base_url('pemesanan') ?>" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-primary"><i class="bi bi-save2"></i> Simpan Pemesanan</button>
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
