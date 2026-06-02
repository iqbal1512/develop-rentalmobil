<?php $title = 'Dashboard'; ?>
<?= view('templates/header') ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-3" role="alert" style="border-radius: 0.5rem;">
        <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if ($pembayaran_menunggu > 0): ?>
    <div class="alert alert-warning border-0 shadow-sm mb-4 d-flex align-items-center justify-content-between" style="border-radius: 0.5rem;">
        <div>
            <i class="bi bi-exclamation-triangle-fill me-2" style="font-size: 1.1rem;"></i>
            Ada <strong><?= $pembayaran_menunggu ?> pembayaran baru</strong> dari customer yang menunggu konfirmasi/verifikasi Anda.
        </div>
        <a href="<?= base_url('pembayaran') ?>" class="btn btn-warning btn-sm font-weight-bold" style="font-size: 0.75rem; border-radius: 6px;">Periksa</a>
    </div>
<?php endif; ?>

<div class="row g-3" style="margin-bottom:16px">
  <div class="col-6 col-md-3">
    <div class="stat-card accent">
      <div class="stat-icon accent"><i class="bi bi-car-front-fill"></i></div>
      <div class="stat-info">
        <div class="stat-value"><?= $total_mobil ?></div>
        <div class="stat-label">Total Unit Mobil</div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-card success">
      <div class="stat-icon success"><i class="bi bi-building"></i></div>
      <div class="stat-info">
        <div class="stat-value"><?= $total_supplier ?></div>
        <div class="stat-label">Supplier Aktif</div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-card warning">
      <div class="stat-icon warning"><i class="bi bi-people-fill"></i></div>
      <div class="stat-info">
        <div class="stat-value"><?= $total_customer ?></div>
        <div class="stat-label">Total Customer</div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-card info">
      <div class="stat-icon info"><i class="bi bi-calendar-check-fill"></i></div>
      <div class="stat-info">
        <div class="stat-value"><?= $total_pemesanan_aktif ?></div>
        <div class="stat-label">Pemesanan Aktif</div>
      </div>
    </div>
  </div>
</div>

<div class="row g-3" style="margin-bottom:24px">
  <div class="col-6 col-md-3">
    <div class="stat-card success">
      <div class="stat-icon success"><i class="bi bi-receipt-cutoff"></i></div>
      <div class="stat-info">
        <div class="stat-value"><?= $total_penjualan_bulan ?></div>
        <div class="stat-label">Terjual Bulan Ini</div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-card accent">
      <div class="stat-icon accent"><i class="bi bi-cash-stack"></i></div>
      <div class="stat-info">
        <div class="stat-value" style="font-size:16px; font-weight:700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
            Rp<?= number_format($pendapatan_bulan, 0, ',', '.') ?>
        </div>
        <div class="stat-label">Omset Bulan Ini</div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-card warning">
      <div class="stat-icon warning"><i class="bi bi-hourglass-split"></i></div>
      <div class="stat-info">
        <div class="stat-value"><?= $pembayaran_menunggu ?></div>
        <div class="stat-label">Konfirmasi Bayar</div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-card danger">
      <div class="stat-icon danger"><i class="bi bi-car-front"></i></div>
      <div class="stat-info">
        <div class="stat-value"><?= $mobil_tersedia ?></div>
        <div class="stat-label">Mobil Ready Stok</div>
      </div>
    </div>
  </div>
</div>

<div class="row g-4 mb-4">
  <div class="col-lg-8">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0"><i class="bi bi-bar-chart-line-fill text-accent me-2"></i>Tren Penjualan (6 Bulan Terakhir)</h5>
      </div>
      <div class="card-body">
        <div class="chart-container" style="position: relative; height:250px; width:100%;">
          <canvas id="chartPenjualan"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card h-100">
      <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-pie-chart-fill text-accent me-2"></i>Status Inventori</h5>
      </div>
      <div class="card-body d-flex flex-column justify-content-between">
        <div class="chart-container" style="position: relative; height:160px; width:100%;">
          <canvas id="chartStatus"></canvas>
        </div>
        <div style="margin-top:16px">
          <?php foreach ($status_mobil as $s): ?>
          <div class="d-flex justify-content-between align-items-center" style="margin-bottom:6px">
            <span class="fs-12 text-secondary" style="font-size:0.85rem;">
              <i class="bi bi-circle-fill me-1" style="font-size:0.5rem; color: <?= $s['status_jual'] === 'tersedia' ? '#10b981' : ($s['status_jual'] === 'dipesan' ? '#f59e0b' : '#6366f1') ?>;"></i> 
              <?= ucfirst(esc($s['status_jual'])) ?>
            </span>
            <span class="badge rounded-pill <?= $s['status_jual'] === 'tersedia' ? 'bg-success' : ($s['status_jual'] === 'dipesan' ? 'bg-warning text-dark' : 'bg-primary') ?>">
              <?= esc($s['total']) ?> Unit
            </span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row g-4">
  <div class="col-md-6">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0"><i class="bi bi-calendar-check-fill text-warning me-2"></i>Pemesanan Terbaru</h5>
        <a href="<?= base_url('pemesanan') ?>" class="btn btn-sm btn-outline-primary" style="font-size:0.75rem; font-weight:600;">Lihat Semua</a>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
              <thead class="table-light">
                <tr>
                  <th>Customer</th>
                  <th>Mobil</th>
                  <th class="text-end pe-3">Status</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($pemesanan_terbaru)): ?>
                  <?php foreach ($pemesanan_terbaru as $p): ?>
                  <tr>
                    <td class="fw-bold" style="font-weight: 600;"><?= esc($p['nama_customer'] ?? 'Umum') ?></td>
                    <td class="text-secondary"><?= esc($p['nama_mobil'] ?? 'Unit Dihapus') ?></td>
                    <td class="text-end pe-3">
                        <span class="badge <?= ($p['status_pemesanan']=='menunggu') ? 'bg-danger-subtle text-danger' : 'bg-info-subtle text-info' ?> px-2 py-1 text-uppercase" style="font-size:0.7rem;">
                            <?= esc($p['status_pemesanan']) ?>
                        </span>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr><td colspan="3" class="text-center text-muted p-4">Belum ada data pemesanan masuk</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0"><i class="bi bi-credit-card-2-front-fill text-info me-2"></i>Pembayaran Pending</h5>
        <a href="<?= base_url('pembayaran') ?>" class="btn btn-sm btn-outline-primary" style="font-size:0.75rem; font-weight:600;">Lihat Semua</a>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
              <thead class="table-light">
                <tr>
                  <th>Customer</th>
                  <th>Jumlah Transaksi</th>
                  <th class="text-end pe-3">Status</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($pembayaran_pending)): ?>
                  <?php foreach ($pembayaran_pending as $b): ?>
                  <tr>
                    <td class="fw-bold" style="font-weight: 600;"><?= esc($b['nama_customer'] ?? 'Customer Showroom') ?></td>
                    <td class="text-success font-weight-bold" style="font-weight: 600;">Rp<?= number_format($b['jumlah_bayar'], 0, ',', '.') ?></td>
                    <td class="text-end pe-3"><span class="badge bg-warning text-dark px-2 py-1">Menunggu</span></td>
                  </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr><td colspan="3" class="text-center text-muted p-4">Tidak ada pembayaran yang tertunda</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
// PERBAIKAN DATA PASING SINKRONISASI GRAFIK
$labels_array = [];
$data_array   = [];
if (!empty($chart_penjualan)) {
    foreach ($chart_penjualan as $row) {
        $labels_array[] = $row['bulan'] ?? 'Unknown';
        $data_array[]   = $row['total'] ?? 0;
    }
}
$bulanLabels  = json_encode($labels_array);
$bulanData    = json_encode($data_array);
$statusLabels = json_encode(array_column($status_mobil, 'status_jual') ?: []);
$statusData   = json_encode(array_column($status_mobil, 'total') ?: []);
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Chart Batang Penjualan Bulanan
    const ctxP = document.getElementById('chartPenjualan');
    new Chart(ctxP, {
        type: 'bar',
        data: {
            labels: <?= $bulanLabels ?>,
            datasets: [{
                label: 'Unit Mobil Terjual',
                data: <?= $bulanData ?>,
                backgroundColor: 'rgba(99, 102, 241, 0.75)',
                borderColor: '#6366f1',
                borderWidth: 1.5,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { 
                    beginAtZero: true, 
                    grid: { color: '#e2e8f0' }, 
                    ticks: { color: '#64748b', precision: 0 } 
                },
                x: { 
                    grid: { display: false }, 
                    ticks: { color: '#64748b' } 
                }
            },
            plugins: { 
                legend: { display: false } 
            }
        }
    });

    // 2. Chart Donat Status Mobil
    const ctxS = document.getElementById('chartStatus');
    new Chart(ctxS, {
        type: 'doughnut',
        data: {
            labels: <?= $statusLabels ?>,
            datasets: [{
                data: <?= $statusData ?>,
                backgroundColor: ['#10b981', '#f59e0b', '#6366f1'],
                borderColor: '#ffffff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false } 
            },
            cutout: '70%'
        }
    });
});
</script>

<?= view('templates/footer') ?>