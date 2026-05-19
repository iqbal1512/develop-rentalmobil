<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $title ?? 'Showroom Mobil' ?> — AutoPrime Showroom</title>
  <meta name="description" content="Sistem Informasi Penjualan & Pembelian Mobil — AutoPrime Showroom">

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">

  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>

<?php
$uri    = service('uri');
$seg1   = $uri->getSegment(1);
$seg2   = $uri->getSegment(2);

function isActive(string $path): string {
    $seg = service('uri')->getSegment(1);
    return $seg === $path ? 'active' : '';
}
?>

<!-- ============ SIDEBAR ============ -->
<aside class="sidebar" id="sidebar">

  <!-- Brand -->
  <div class="sidebar-brand">
    <div class="brand-custom-logo">
      <svg class="custom-car-logo" viewBox="0 0 120 40" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M10 25 C 30 15, 60 10, 100 15 L 115 22 L 95 28 C 65 22, 35 25, 5 32 Z" fill="var(--primary)"/>
        <circle cx="85" cy="20" r="5" fill="var(--gray-100)"/>
        <circle cx="85" cy="20" r="2" fill="var(--primary)"/>
        <path d="M15 18 C 40 10, 70 8, 90 12" stroke="var(--gray-400)" stroke-width="1.5" stroke-linecap="round"/>
      </svg>
    </div>
    <div class="brand-text">
      <h6>Showroom</h6>
      <small>Mobil Bekas</small>
    </div>
  </div>

  <!-- Navigation -->
  <nav class="sidebar-nav">

    <span class="sidebar-label">Menu Utama</span>

    <div class="nav-item">
      <a href="<?= base_url('dashboard') ?>" class="nav-link <?= isActive('dashboard') ?>">
        <i class="bi bi-speedometer2"></i> Dashboard
      </a>
    </div>

    <?php if (session()->get('role') === 'admin') : ?>

    <span class="sidebar-label">Data Master</span>

    <div class="nav-item">
      <a href="<?= base_url('supplier') ?>" class="nav-link <?= isActive('supplier') ?>">
        <i class="bi bi-building"></i> Supplier
      </a>
    </div>

    <div class="nav-item">
      <a href="<?= base_url('mobil') ?>" class="nav-link <?= isActive('mobil') ?>">
        <i class="bi bi-car-front-fill"></i> Data Mobil
      </a>
    </div>

    <div class="nav-item">
      <a href="<?= base_url('customer') ?>" class="nav-link <?= isActive('customer') ?>">
        <i class="bi bi-people-fill"></i> Customer
      </a>
    </div>

    <span class="sidebar-label">Transaksi</span>

    <div class="nav-item">
      <a href="<?= base_url('pembelian') ?>" class="nav-link <?= isActive('pembelian') ?>">
        <i class="bi bi-cart-plus-fill"></i> Pembelian
      </a>
    </div>

    <div class="nav-item">
      <a href="<?= base_url('pemesanan') ?>" class="nav-link <?= isActive('pemesanan') ?>">
        <i class="bi bi-calendar-check-fill"></i> Pemesanan
      </a>
    </div>

    <div class="nav-item">
      <a href="<?= base_url('penjualan') ?>" class="nav-link <?= isActive('penjualan') ?>">
        <i class="bi bi-receipt-cutoff"></i> Penjualan
      </a>
    </div>

    <div class="nav-item">
      <a href="<?= base_url('pembayaran') ?>" class="nav-link <?= isActive('pembayaran') ?>">
        <i class="bi bi-credit-card-2-front-fill"></i> Pembayaran
      </a>
    </div>

    <div class="nav-item">
      <a href="<?= base_url('penyerahan') ?>" class="nav-link <?= isActive('penyerahan') ?>">
        <i class="bi bi-box-seam-fill"></i> Penyerahan Mobil
      </a>
    </div>

    <?php endif; ?>

    <span class="sidebar-label">Laporan</span>

    <div class="nav-item">
      <a href="<?= base_url('laporan') ?>" class="nav-link <?= isActive('laporan') ?>">
        <i class="bi bi-bar-chart-line-fill"></i> Laporan
      </a>
    </div>

  </nav>

  <!-- Sidebar Footer / User Info -->
  <div class="sidebar-footer">
    <div class="user-info">
      <div class="user-avatar"><?= strtoupper(substr(session()->get('nama') ?? 'A', 0, 1)) ?></div>
      <div>
        <div class="user-name"><?= esc(session()->get('nama') ?? 'User') ?></div>
        <div class="user-role"><?= ucfirst(session()->get('role') ?? 'admin') ?></div>
      </div>
    </div>
    <a href="<?= base_url('logout') ?>" class="btn-logout">
      <i class="bi bi-box-arrow-right"></i> Keluar
    </a>
  </div>

</aside>

<!-- ============ MAIN CONTENT ============ -->
<div class="main-content">

  <!-- TOPBAR -->
  <header class="topbar">
    <div class="topbar-title">
      <h4><?= $title ?? 'Dashboard' ?></h4>
      <div class="breadcrumb">
        <a href="<?= base_url('dashboard') ?>">Home</a>
        <i class="bi bi-chevron-right" style="font-size:10px"></i>
        <span><?= $title ?? 'Dashboard' ?></span>
      </div>
    </div>
    <div class="topbar-right">
      <div class="topbar-badge">
        <i class="bi bi-clock"></i>
        <?= date('d M Y') ?>
      </div>
      <div class="topbar-badge" style="background:var(--success-light);color:var(--success)">
        <i class="bi bi-circle-fill" style="font-size:7px"></i>
        <?= ucfirst(session()->get('role') ?? 'admin') ?>
      </div>
    </div>
  </header>

  <!-- PAGE CONTENT START -->
  <div class="page-content">
