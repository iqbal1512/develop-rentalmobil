<?php $title = 'Data Mobil'; ?>
<?= view('templates/header') ?>

<style>
  /* ============================================
     SHOWROOM MOBIL — Professional Card Layout
     Semua ukuran card seragam, foto auto-crop,
     jarak presisi, footer selalu rata bawah.
  ============================================ */

  :root {
    --accent: var(--primary);
    --accent-dark: var(--primary-hover);
    --accent-light: var(--gray-100);
    --radius-card: 0.5rem;
    --radius-btn: 0.375rem;
    --gap-grid: 1.5rem;
    --img-ratio: 62%;
  }

  /* ── HALAMAN ─────────────────────────────── */
  .sm-page {
    background: transparent;
    padding: 0 0 2.5rem;
    min-height: 100vh;
  }

  /* ── HEADER ──────────────────────────────── */
  .sm-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    padding: 24px 24px 10px;
    gap: 12px;
    flex-wrap: wrap;
  }

  .sm-header-left .breadcrumb {
    font-size: 11px;
    margin-bottom: 4px;
    background: none;
    padding: 0;
  }

  .sm-header-left h2 {
    font-size: 22px;
    font-weight: 800;
    color: #1a202c;
    margin: 0;
    line-height: 1.2;
  }

  .sm-header-left p {
    font-size: 12px;
    color: #718096;
    margin: 2px 0 0;
  }

  .sm-btn-add {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 9px 20px;
    border-radius: 10px;
    background: var(--accent);
    color: #fff;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    border: none;
    transition: background 0.15s;
    white-space: nowrap;
  }

  .sm-btn-add:hover {
    background: var(--accent-dark);
    color: #fff;
  }

  /* ── TOOLBAR FILTER ──────────────────────── */
  .sm-toolbar {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 24px;
    background: #ffffff;
    border-top: 1px solid var(--gray-200);
    border-bottom: 1px solid var(--gray-200);
    flex-wrap: wrap;
  }

  .sm-search-wrap {
    position: relative;
    flex: 1;
    min-width: 220px;
  }

  .sm-search-wrap i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #a0aec0;
    font-size: 15px;
    pointer-events: none;
  }

  .sm-search-wrap input {
    width: 100%;
    padding: 9px 12px 9px 38px;
    border: 1px solid #e2e8f0;
    border-radius: 9px;
    background: #f8fafc;
    font-size: 13px;
    color: #1a202c;
    outline: none;
    transition: border-color 0.2s, background 0.2s;
    font-family: inherit;
  }

  .sm-search-wrap input:focus {
    border-color: var(--accent);
    background: #fff;
    outline: none;
    box-shadow: 0 0 0 1px var(--accent);
  }

  .sm-filter-btns {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
  }

  .sm-filter-btn {
    padding: 7px 14px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    background: #fff;
    font-size: 12px;
    font-weight: 600;
    color: #4a5568;
    cursor: pointer;
    transition: all 0.15s;
    font-family: inherit;
    white-space: nowrap;
  }

  .sm-filter-btn:hover,
  .sm-filter-btn.active {
    background: var(--accent);
    color: #fff;
    border-color: var(--accent);
  }

  .sm-filter-btn[data-filter="tersedia"].active { background: #10b981; border-color: #10b981; }
  .sm-filter-btn[data-filter="dipesan"].active  { background: #f59e0b; border-color: #f59e0b; }
  .sm-filter-btn[data-filter="terjual"].active  { background: #ef4444; border-color: #ef4444; }

  .sm-sort {
    padding: 8px 12px;
    border: 1px solid #e2e8f0;
    border-radius: 9px;
    background: #f8fafc;
    font-size: 12px;
    font-weight: 500;
    color: #4a5568;
    cursor: pointer;
    outline: none;
    font-family: inherit;
    min-width: 140px;
  }

  .sm-sort:focus { border-color: var(--accent); }

  /* ── GRID KARTU ──────────────────────────── */
  .sm-grid {
    display: grid;
    /*
      auto-fill = isi sebanyak mungkin kolom
      minmax(240px, 1fr) = min 240px, max sama rata
      Ini memastikan card tidak terlalu lebar/sempit
    */
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: var(--gap-grid);
    padding: 20px 24px 0;
  }

  /* ── SATU KARTU ──────────────────────────── */
  .sm-card {
    background: #fff;
    border: 1px solid #edf2f7;
    border-radius: var(--radius-card);
    overflow: hidden;
    display: flex;
    flex-direction: column; /* ← kunci: flex column */
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s;
  }

  .sm-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    border-color: var(--gray-300);
  }

  /* ── FOTO WRAPPER ────────────────────────── */
  /*
    padding-top: var(--img-ratio) = rasio aspek tetap.
    Semua gambar besar/kecil akan di-crop ke ukuran
    yang SAMA tanpa meluber ke card lain.
  */
  .sm-img-wrap {
    position: relative;
    width: 100%;
    padding-top: var(--img-ratio);
    background: #f0f4f8;
    overflow: hidden;   /* ← crop otomatis */
    flex-shrink: 0;     /* ← jangan pernah menyusut */
  }

  .sm-img-wrap img {
    position: absolute;
    inset: 0;           /* top:0; right:0; bottom:0; left:0 */
    width: 100%;
    height: 100%;
    object-fit: cover;          /* crop tanpa distorsi */
    object-position: center;    /* fokus ke tengah */
    display: block;
    transition: transform 0.45s ease;
  }

  .sm-card:hover .sm-img-wrap img {
    transform: scale(1.05);
  }

  /* Placeholder saat tidak ada foto */
  .sm-img-placeholder {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 6px;
    color: #a0aec0;
  }

  .sm-img-placeholder i { font-size: 32px; }
  .sm-img-placeholder span { font-size: 11px; font-weight: 600; }

  /* Badge jumlah foto (atas kiri) */
  .sm-img-count {
    position: absolute;
    top: 9px;
    left: 9px;
    background: rgba(0, 0, 0, 0.45);
    backdrop-filter: blur(6px);
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    padding: 3px 9px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    gap: 4px;
    z-index: 2;
  }

  /* Badge status (bawah kanan) */
  .sm-status {
    position: absolute;
    bottom: 9px;
    right: 9px;
    font-size: 10px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 7px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    z-index: 2;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
  }

  .sm-status-tersedia { background: #10b981; color: #fff; }
  .sm-status-dipesan  { background: #f59e0b; color: #fff; }
  .sm-status-terjual  { background: #ef4444; color: #fff; }

  /* ── BODY KARTU ──────────────────────────── */
  .sm-body {
    padding: 13px 14px 11px;
    flex: 1;            /* ← body mengisi sisa ruang */
    display: flex;
    flex-direction: column;
    gap: 0;
  }

  /* Harga */
  .sm-price-label {
    font-size: 10px;
    font-weight: 600;
    color: #a0aec0;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    margin-bottom: 1px;
  }

  .sm-price-main {
    font-size: 17px;
    font-weight: 800;
    color: var(--accent);
    line-height: 1.15;
  }

  .sm-price-sub {
    font-size: 10px;
    color: #a0aec0;
    margin-top: 1px;
    margin-bottom: 9px;
  }

  /* Nama & tipe */
  .sm-car-name {
    font-size: 14px;
    font-weight: 700;
    color: #1a202c;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;   /* ← nama panjang tidak merusak layout */
    margin-bottom: 2px;
  }

  .sm-car-type {
    font-size: 11px;
    color: #718096;
    margin-bottom: 10px;
  }

  /* Spesifikasi grid 2 kolom */
  .sm-specs {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 5px 8px;
    margin-top: auto;         /* ← spec selalu menempel ke bawah body */
    padding-top: 10px;
    border-top: 1px solid #f1f5f9;
  }

  .sm-spec {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 11px;
    color: #4a5568;
  }

  .sm-spec i {
    font-size: 13px;
    color: #a0aec0;
    flex-shrink: 0;
    width: 14px;
    text-align: center;
  }

  .sm-spec span {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  /* ── FOOTER TOMBOL ───────────────────────── */
  .sm-foot {
    padding: 9px 14px;
    border-top: 1px solid #f1f5f9;
    background: #f8fafc;
    display: flex;
    gap: 7px;
    flex-shrink: 0;   /* ← footer selalu di bawah, tidak terkompresi */
  }

  .sm-btn {
    flex: 1;
    padding: 8px 6px;
    border-radius: var(--radius-btn);
    font-family: inherit;
    font-size: 11px;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    transition: all 0.15s;
    text-decoration: none;
    border: 1px solid;
  }

  .sm-btn-edit {
    background: #fff;
    border-color: #e2e8f0;
    color: #4a5568;
  }

  .sm-btn-edit:hover {
    background: var(--accent);
    border-color: var(--accent);
    color: #fff;
  }

  .sm-btn-del {
    background: #fff1f2;
    border-color: #fecaca;
    color: #e11d48;
  }

  .sm-btn-del:hover {
    background: #e11d48;
    border-color: #e11d48;
    color: #fff;
  }

  /* ── EMPTY STATE ─────────────────────────── */
  .sm-empty {
    grid-column: 1 / -1;
    text-align: center;
    padding: 4rem 1rem;
    color: #a0aec0;
  }

  .sm-empty i { font-size: 48px; display: block; margin-bottom: 12px; opacity: 0.5; }
  .sm-empty h5 { font-size: 16px; font-weight: 700; color: #4a5568; margin-bottom: 6px; }
  .sm-empty p { font-size: 13px; }

  /* ── COUNT BADGE ─────────────────────────── */
  .sm-count {
    display: inline-block;
    background: var(--accent);
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 20px;
    margin-left: 8px;
    vertical-align: middle;
  }

  /* ── RESPONSIF ───────────────────────────── */
  @media (max-width: 600px) {
    .sm-grid { padding: 12px 12px 0; gap: 12px; }
    .sm-header { padding: 16px 12px 8px; }
    .sm-toolbar { padding: 10px 12px; }
  }
</style>

<div class="sm-page">

  <!-- HEADER -->
  <div class="sm-header">
    <div class="sm-header-left">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="<?= base_url() ?>" class="text-decoration-none text-muted">Home</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">Inventaris Mobil</li>
        </ol>
      </nav>
      <h2>
        Inventaris Mobil
        <span class="sm-count" id="smCount"><?= count($mobils) ?> unit</span>
      </h2>
      <p>Menampilkan unit kendaraan tersedia di showroom kami.</p>
    </div>
    <a href="<?= base_url('mobil/create') ?>" class="sm-btn-add">
      <i class="bi bi-plus-lg"></i> Tambah Unit
    </a>
  </div>

  <!-- TOOLBAR FILTER & SEARCH -->
  <div class="sm-toolbar">

    <!-- Search -->
    <div class="sm-search-wrap">
      <i class="bi bi-search"></i>
      <input
        type="text"
        id="smSearch"
        placeholder="Cari merk, tipe, atau nama mobil..."
        autocomplete="off"
      >
    </div>

    <!-- Filter Status -->
    <div class="sm-filter-btns">
      <button class="sm-filter-btn active" data-filter="all">Semua</button>
      <button class="sm-filter-btn" data-filter="tersedia">Tersedia</button>
      <button class="sm-filter-btn" data-filter="dipesan">Dipesan</button>
      <button class="sm-filter-btn" data-filter="terjual">Terjual</button>
    </div>

    <!-- Sort -->
    <select class="sm-sort" id="smSort">
      <option value="">Urutkan</option>
      <option value="harga_asc">Harga Terendah</option>
      <option value="harga_desc">Harga Tertinggi</option>
      <option value="tahun_desc">Tahun Terbaru</option>
      <option value="nama_asc">Nama A–Z</option>
    </select>

  </div>

  <!-- GRID KARTU -->
  <?php if (empty($mobils)): ?>
    <div class="sm-grid">
      <div class="sm-empty">
        <i class="bi bi-car-front"></i>
        <h5>Belum ada unit tersedia</h5>
        <p>Klik tombol "Tambah Unit" untuk memasukkan data mobil pertama Anda.</p>
      </div>
    </div>
  <?php else: ?>

    <div class="sm-grid" id="smGrid">
      <?php foreach ($mobils as $m):
        // Tentukan class status
        $statusClass = 'sm-status-tersedia';
        $statusText  = 'Tersedia';
        if ($m['status_jual'] === 'dipesan') {
          $statusClass = 'sm-status-dipesan';
          $statusText  = 'Booked';
        } elseif ($m['status_jual'] === 'terjual') {
          $statusClass = 'sm-status-terjual';
          $statusText  = 'Sold';
        }
      ?>

      <div class="sm-card car-item"
           data-status="<?= esc($m['status_jual']) ?>"
           data-nama="<?= strtolower(esc($m['nama_mobil'])) ?>"
           data-vendor="<?= strtolower(esc($m['vendor'])) ?>"
           data-tipe="<?= strtolower(esc($m['tipe'])) ?>"
           data-harga="<?= (int)$m['harga_jual'] ?>"
           data-tahun="<?= (int)($m['tahun'] ?? 0) ?>">

        <!-- FOTO -->
        <div class="sm-img-wrap">

          <!-- Badge jumlah foto -->
          <div class="sm-img-count">
            <i class="bi bi-camera"></i>
            <?= !empty($m['foto']) ? '1' : '0' ?>
          </div>

          <!-- Badge status -->
          <div class="sm-status <?= $statusClass ?>">
            <?= $statusText ?>
          </div>

          <?php if (!empty($m['foto'])): ?>
            <!-- Foto asli — object-fit:cover crop otomatis, tidak tumpang tindih -->
            <img
              src="<?= base_url('uploads/mobil/' . $m['foto']) ?>"
              alt="<?= esc($m['nama_mobil']) ?>"
              loading="lazy"
            >
          <?php else: ?>
            <!-- Placeholder jika tidak ada foto -->
            <div class="sm-img-placeholder">
              <i class="bi bi-car-front"></i>
              <span><?= esc($m['vendor']) ?></span>
            </div>
          <?php endif; ?>

        </div>
        <!-- /FOTO -->

        <!-- BODY -->
        <div class="sm-body">

          <!-- Harga -->
          <div class="sm-price-label">Harga Cash</div>
          <div class="sm-price-main">
            Rp <?= number_format($m['harga_jual'], 0, ',', '.') ?>
          </div>
          <div class="sm-price-sub">
            Beli: Rp <?= number_format($m['harga_beli'], 0, ',', '.') ?>
          </div>

          <!-- Nama & Tipe -->
          <div class="sm-car-name" title="<?= esc($m['nama_mobil']) ?>">
            <?= esc($m['nama_mobil']) ?>
          </div>
          <div class="sm-car-type">
            <?= esc($m['tipe']) ?> &bull; <?= ucfirst(esc($m['status_mobil'])) ?>
          </div>

          <!-- Spesifikasi -->
          <div class="sm-specs">
            <div class="sm-spec">
              <i class="bi bi-tag"></i>
              <span><?= esc($m['vendor']) ?></span>
            </div>
            <div class="sm-spec">
              <i class="bi bi-palette"></i>
              <span><?= esc($m['warna']) ?></span>
            </div>
            <div class="sm-spec">
              <i class="bi bi-calendar4-event"></i>
              <span><?= esc($m['tahun'] ?? '-') ?></span>
            </div>
            <div class="sm-spec">
              <i class="bi bi-hash"></i>
              <span><?= esc($m['no_polisi'] ?? '-') ?></span>
            </div>
          </div>

        </div>
        <!-- /BODY -->

        <!-- FOOTER TOMBOL -->
        <div class="sm-foot">
          <a href="<?= base_url('mobil/edit/' . $m['id_mobil']) ?>" class="sm-btn sm-btn-edit">
            <i class="bi bi-pencil-square"></i> Edit
          </a>
          <button
            onclick="confirmDelete('<?= base_url('mobil/delete/' . $m['id_mobil']) ?>')"
            class="sm-btn sm-btn-del">
            <i class="bi bi-trash"></i> Hapus
          </button>
        </div>
        <!-- /FOOTER -->

      </div>
      <!-- /sm-card -->

      <?php endforeach; ?>
    </div>
    <!-- /sm-grid -->

  <?php endif; ?>

</div><!-- /sm-page -->


<script>
(function () {
  /* ── Referensi elemen ── */
  var searchEl  = document.getElementById('smSearch');
  var sortEl    = document.getElementById('smSort');
  var countEl   = document.getElementById('smCount');
  var grid      = document.getElementById('smGrid');

  if (!grid) return; // halaman kosong, tidak ada grid

  var filterBtns = document.querySelectorAll('.sm-filter-btn');

  /* ── State ── */
  var currentFilter = 'all';
  var currentSearch = '';
  var currentSort   = '';

  /* ── Ambil semua item sekali ── */
  var allItems = Array.from(grid.querySelectorAll('.car-item'));

  /* ── Fungsi utama: filter + sort + render ── */
  function applyAll() {
    // 1. Filter
    var visible = allItems.filter(function (el) {
      var status = el.dataset.status || '';
      var text   = (el.dataset.nama + ' ' + el.dataset.vendor + ' ' + el.dataset.tipe).toLowerCase();

      var matchFilter = (currentFilter === 'all') || (status === currentFilter);
      var matchSearch = !currentSearch || text.indexOf(currentSearch) !== -1;

      return matchFilter && matchSearch;
    });

    // 2. Sort
    if (currentSort) {
      visible.sort(function (a, b) {
        switch (currentSort) {
          case 'harga_asc':
            return parseInt(a.dataset.harga) - parseInt(b.dataset.harga);
          case 'harga_desc':
            return parseInt(b.dataset.harga) - parseInt(a.dataset.harga);
          case 'tahun_desc':
            return parseInt(b.dataset.tahun) - parseInt(a.dataset.tahun);
          case 'nama_asc':
            return a.dataset.nama.localeCompare(b.dataset.nama);
          default:
            return 0;
        }
      });
    }

    // 3. Tampilkan / sembunyikan
    allItems.forEach(function (el) { el.style.display = 'none'; });
    visible.forEach(function (el) { el.style.display = ''; });

    // Reorder DOM sesuai urutan sort
    visible.forEach(function (el) { grid.appendChild(el); });

    // 4. Update count badge
    if (countEl) {
      countEl.textContent = visible.length + ' unit';
    }

    // 5. Empty state
    var existingEmpty = grid.querySelector('.sm-empty-runtime');
    if (visible.length === 0) {
      if (!existingEmpty) {
        var div = document.createElement('div');
        div.className = 'sm-empty sm-empty-runtime';
        div.style.gridColumn = '1 / -1';
        div.innerHTML = '<i class="bi bi-search"></i><h5>Tidak ditemukan</h5><p>Coba kata kunci atau filter yang berbeda.</p>';
        grid.appendChild(div);
      }
    } else {
      if (existingEmpty) existingEmpty.remove();
    }
  }

  /* ── Event: search ── */
  searchEl && searchEl.addEventListener('input', function () {
    currentSearch = this.value.toLowerCase().trim();
    applyAll();
  });

  /* ── Event: filter buttons ── */
  filterBtns.forEach(function (btn) {
    btn.addEventListener('click', function () {
      filterBtns.forEach(function (b) { b.classList.remove('active'); });
      btn.classList.add('active');
      currentFilter = btn.dataset.filter;
      applyAll();
    });
  });

  /* ── Event: sort ── */
  sortEl && sortEl.addEventListener('change', function () {
    currentSort = this.value;
    applyAll();
  });

})();
</script>


<?= view('templates/footer') ?>