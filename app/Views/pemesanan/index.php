<?php $title = 'Pemesanan Mobil'; ?>
<?= view('templates/header') ?>

<div class="card shadow-sm">
  <div class="card-header d-flex justify-content-between align-items-center bg-white text-dark">
    <h5 class="mb-0"><i class="bi bi-calendar-check-fill text-info me-2"></i> Daftar Pemesanan Mobil</h5>
    <div class="d-flex gap-2">
      <a href="<?= base_url('pemesanan/create') ?>" class="btn btn-primary btn-sm">Tambah Pesanan</a>
    </div>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
        <table class="table table-hover table-hover mb-0 datatable" id="tblPemesananLancar">
          <thead class="table-hover">
            <tr>
              <th class="text-center">#</th> <th>Customer</th> <th>Mobil</th> <th>Tgl Pesan</th> <th>Jatuh Tempo</th> <th>Harga Jadi</th> <th>DP</th> <th class="text-center">KTP</th> <th class="text-center">Status</th> <th class="text-center">Aksi</th> </tr>
          </thead>
          <tbody>
            <?php if (!empty($pemesanans)): ?>
                <?php foreach ($pemesanans as $i => $p): ?>
                <tr>
                  <td class="text-center"><?= $i + 1 ?></td>
                  <td><?= esc($p['nama_customer'] ?? '-') ?></td>
                  <td><?= esc($p['nama_mobil'] ?? '-') ?></td>
                  <td><?= date('d/m/Y', strtotime($p['tgl_pesan'] ?? 'now')) ?></td>
                  <td><?= date('d/m/Y', strtotime($p['tgl_jatuh_tempo'] ?? 'now')) ?></td>
                  <td>Rp<?= number_format($p['harga_jual_jadi'] ?? 0, 0, ',', '.') ?></td>
                  <td>Rp<?= number_format($p['nominal_dp'] ?? 0, 0, ',', '.') ?></td>
                  <td class="text-center"><?= ($p['ktp_diterima'] ?? 0) ? 'Diterima' : 'Belum' ?></td>
                  <td class="text-center"><?= strtoupper($p['status_pemesanan'] ?? 'PROSES') ?></td>
                  <td class="text-center">
                    <a href="<?= base_url('pemesanan/detail/' . $p['id_pemesanan']) ?>" class="btn btn-sm btn-info">Detail</a>
                  </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="10" class="text-center p-5">Data Kosong</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
    </div>
  </div>
</div>

<?= view('templates/footer') ?>

<script>
$(document).ready(function() {
    // Menghancurkan instansi lama jika ada
    if ($.fn.DataTable.isDataTable('#tblPemesananLancar')) {
        $('#tblPemesananLancar').DataTable().destroy();
    }

    // Menjalankan DataTables baru dengan hitungan kolom yang segar
    $('#tblPemesananLancar').DataTable({
        "responsive": true,
        "autoWidth": false,
        "destroy": true,
        "columnDefs": [
            { "targets": [0, 9], "orderable": false }
        ]
    });
});
</script>