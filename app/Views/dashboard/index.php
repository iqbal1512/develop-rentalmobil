<?php $title = 'Dashboard'; ?>
<?= view('templates/header') ?>

<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success"><i class="bi bi-check-circle-fill"></i> <?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="row" style="margin-bottom:8px">
  <div class="col-3">
    <div class="stat-card accent">
      <div class="stat-icon accent"><i class="bi bi-car-front-fill"></i></div>
      <div class="stat-info">
        <div class="stat-value"><?= $total_mobil ?></div>
        <div class="stat-label">Total Mobil</div>
      </div>
    </div>
  </div>
  <div class="col-3">
    <div class="stat-card success">
      <div class="stat-icon success"><i class="bi bi-building"></i></div>
      <div class="stat-info">
        <div class="stat-value"><?= $total_supplier ?></div>
        <div class="stat-label">Supplier Aktif</div>
      </div>
    </div>
  </div>
  <div class="col-3">
    <div class="stat-card warning">
      <div class="stat-icon warning"><i class="bi bi-people-fill"></i></div>
      <div class="stat-info">
        <div class="stat-value"><?= $total_customer ?></div>
        <div class="stat-label">Total Customer</div>
      </div>
    </div>
  </div>
  <div class="col-3">
    <div class="stat-card info">
      <div class="stat-icon info"><i class="bi bi-calendar-check-fill"></i></div>
      <div class="stat-info">
        <div class="stat-value"><?= $total_pemesanan_aktif ?></div>
        <div class="stat-label">Pemesanan Aktif</div>
      </div>
    </div>
  </div>
</div>
<div></div> hjkkkllhh
<div class="row" style="margin-bottom:8px">
  <div class="col-3">
    <div class="stat-card success">
      <div class="stat-icon success"><i class="bi bi-receipt-cutoff"></i></div>
      <div class="stat-info">
        <div class="stat-value"><?= $total_penjualan_bulan ?></div>
        <div class="stat-label">Penjualan Bulan Ini</div>
      </div>
    </div>
  </div>
  <div class="col-3">
    <div class="stat-card accent">
      <div class="stat-icon accent"><i class="bi bi-cash-stack"></i></div>
      <div class="stat-info">
        <div class="stat-value" style="font-size:16px"><?= 'Rp' . number_format($pendapatan_bulan, 0, ',', '.') ?></div>
        <div class="stat-label">Pendapatan Bulan Ini</div>
      </div>
    </div>
  </div>
  <div class="col-3">
    <div class="stat-card warning">
      <div class="stat-icon warning"><i class="bi bi-hourglass-split"></i></div>
      <div class="stat-info">
        <div class="stat-value"><?= $pembayaran_menunggu ?></div>
        <div class="stat-label">Pembayaran Ditunggu</div>
      </div>
    </div>
  </div>
  <div class="col-3">
    <div class="stat-card danger">
      <div class="stat-icon danger"><i class="bi bi-car-front"></i></div>
      <div class="stat-info">
        <div class="stat-value"><?= $mobil_tersedia ?></div>
        <div class="stat-label">Mobil Tersedia</div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-8">
    <div class="card">
      <div class="card-header">
        <h5><i class="bi bi-bar-chart-line-fill text-accent"></i> Penjualan 6 Bulan Terakhir</h5>
      </div>
      <div class="card-body">
        <div class="chart-container" style="height:250px">
          <canvas id="chartPenjualan"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="col-4">
    <div class="card">
      <div class="card-header">
        <h5><i class="bi bi-pie-chart-fill text-accent"></i> Status Mobil</h5>
      </div>
      <div class="card-body">
        <div class="chart-container" style="height:200px">
          <canvas id="chartStatus"></canvas>
        </div>
        <div style="margin-top:12px">
          <?php foreach ($status_mobil as $s): ?>
          <div class="d-flex justify-content-between align-items-center" style="margin-bottom:6px">
            <span class="fs-12 text-secondary"><?= ucfirst($s['status_jual']) ?></span>
            <span class="badge <?= $s['status_jual'] === 'tersedia' ? 'badge-success' : ($s['status_jual'] === 'dipesan' ? 'badge-warning' : 'badge-accent') ?>">
              <?= $s['total'] ?>
            </span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row" style="margin-top:4px">
  <div class="col-6">
    <div class="card">
      <div class="card-header">
        <h5><i class="bi bi-calendar-check-fill text-warning"></i> Pemesanan Terbaru</h5>
        <a href="<?= base_url('pemesanan') ?>" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
      </div>
      <div class="card-body" style="padding:0">
        <table class="table table-hover table-hover mb-0">
          <thead>
            <tr>
              <th>Customer</th>
              <th>Mobil</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($pemesanan_terbaru)): ?>
              <?php foreach ($pemesanan_terbaru as $p): ?>
              <tr>
                <td class="fw-600"><?= esc($p['nama_customer']) ?></td>
                <td class="text-secondary"><?= esc($p['nama_mobil']) ?></td>
                <td><span class="badge badge-info"><?= ucfirst($p['status_pemesanan']) ?></span></td>
              </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="3" class="text-center p-3">Belum ada data</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-6">
    <div class="card">
      <div class="card-header">
        <h5><i class="bi bi-credit-card-2-front-fill text-info"></i> Pembayaran Pending</h5>
        <a href="<?= base_url('pembayaran') ?>" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
      </div>
      <div class="card-body" style="padding:0">
        <table class="table table-hover table-hover mb-0">
          <thead>
            <tr>
              <th>Customer</th>
              <th>Jumlah</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($pembayaran_pending)): ?>
              <?php foreach ($pembayaran_pending as $b): ?>
              <tr>
                <td><?= esc($b['nama_customer'] ?? '-') ?></td>
                <td class="text-success">Rp<?= number_format($b['jumlah_bayar'], 0, ',', '.') ?></td>
                <td><span class="badge badge-warning">Menunggu</span></td>
              </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="3" class="text-center p-3">Tidak ada pending</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php
$bulanLabels = json_encode(array_column($chart_penjualan, 'bulan_label') ?: []);
$bulanData   = json_encode(array_column($chart_penjualan, 'total') ?: []);
$statusLabels = json_encode(array_column($status_mobil, 'status_jual') ?: []);
$statusData   = json_encode(array_column($status_mobil, 'total') ?: []);
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart Penjualan
    const ctxP = document.getElementById('chartPenjualan');
    new Chart(ctxP, {
        type: 'bar',
        data: {
            labels: <?= $bulanLabels ?>,
            datasets: [{
                label: 'Unit Terjual',
                data: <?= $bulanData ?>,
                backgroundColor: 'rgba(99,102,241,0.7)',
                borderColor: '#6366f1',
                borderWidth: 2,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, grid: { color: '#2d3348' }, ticks: { color: '#94a3b8' } },
                x: { grid: { color: '#2d3348' }, ticks: { color: '#94a3b8' } }
            },
            plugins: { legend: { display: false } }
        }
    });

    // Chart Status
    const ctxS = document.getElementById('chartStatus');
    new Chart(ctxS, {
        type: 'doughnut',
        data: {
            labels: <?= $statusLabels ?>,
            datasets: [{
                data: <?= $statusData ?>,
                backgroundColor: ['#10b981', '#f59e0b', '#6366f1'],
                borderColor: '#1e2235',
                borderWidth: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            cutout: '70%'
        }
    });
});
</script>

<?= view('templates/footer') ?>