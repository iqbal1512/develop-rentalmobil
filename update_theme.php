<?php
$file = 'c:\xampp8.2\htdocs\codeigniter4-develop\public\assets\css\style.css';
$content = file_get_contents($file);
$parts = explode('/* =================== LOGIN PAGE =================== */', $content);

$new_css = <<<CSS
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

:root {
  --gray-50: #f9fafb;
  --gray-100: #f3f4f6;
  --gray-200: #e5e7eb;
  --gray-300: #d1d5db;
  --gray-400: #9ca3af;
  --gray-500: #6b7280;
  --gray-600: #4b5563;
  --gray-700: #374151;
  --gray-800: #1f2937;
  --gray-900: #111827;
  
  --primary: #111827;
  --primary-hover: #374151;
  
  --danger: #ef4444;
  --danger-hover: #dc2626;
  --success: #10b981;
  --warning: #f59e0b;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body {
  font-family: 'Inter', sans-serif;
  background-color: var(--gray-100);
  color: var(--gray-900);
  font-size: 0.875rem;
  line-height: 1.5;
  -webkit-font-smoothing: antialiased;
}

::-webkit-scrollbar { width: 6px; height: 6px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: var(--gray-300); border-radius: 4px; }
::-webkit-scrollbar-thumb:hover { background: var(--gray-400); }

/* Sidebar */
.sidebar {
  position: fixed; top: 0; left: 0; bottom: 0;
  width: 260px;
  background: #ffffff;
  border-right: 1px solid var(--gray-200);
  display: flex; flex-direction: column;
  z-index: 1000;
}
.sidebar-brand {
  padding: 1.5rem;
  display: flex; align-items: center; gap: 0.75rem;
  border-bottom: 1px solid var(--gray-100);
}
.sidebar-brand .brand-custom-logo { width: 40px; }
.sidebar-brand .brand-text h6 { font-size: 1.125rem; font-weight: 700; color: var(--gray-900); }
.sidebar-brand .brand-text small { font-size: 0.75rem; color: var(--gray-500); font-weight: 500; }

.sidebar-nav { flex: 1; padding: 1.5rem 0.75rem; overflow-y: auto; }
.sidebar-label { font-size: 0.75rem; font-weight: 600; color: var(--gray-400); text-transform: uppercase; padding: 0 0.75rem 0.5rem; letter-spacing: 0.05em; }
.nav-item { margin-bottom: 0.25rem; }
.nav-link {
  display: flex; align-items: center; gap: 0.75rem;
  padding: 0.625rem 0.75rem;
  border-radius: 0.375rem;
  color: var(--gray-600);
  text-decoration: none;
  font-weight: 500;
  transition: all 0.15s ease-in-out;
}
.nav-link:hover { background: var(--gray-50); color: var(--gray-900); }
.nav-link.active { background: var(--gray-100); color: var(--primary); font-weight: 600; }
.nav-link i { font-size: 1.25rem; }

.sidebar-footer { padding: 1.25rem; border-top: 1px solid var(--gray-100); }
.user-info { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem; }
.user-avatar {
  width: 36px; height: 36px; border-radius: 9999px;
  background: var(--gray-100); color: var(--primary);
  display: flex; align-items: center; justify-content: center;
  font-weight: 600; border: 1px solid var(--gray-200);
}
.user-info .user-name { font-size: 0.875rem; font-weight: 600; color: var(--gray-900); }
.user-info .user-role { font-size: 0.75rem; color: var(--gray-500); }

.btn-logout {
  display: flex; align-items: center; gap: 0.5rem; width: 100%;
  padding: 0.5rem 0.75rem; border-radius: 0.375rem;
  background: #fff; color: var(--danger);
  border: 1px solid var(--gray-200); font-size: 0.875rem; font-weight: 500; text-decoration: none;
  transition: all 0.15s;
}
.btn-logout:hover { background: #fef2f2; border-color: #fca5a5; }

/* Main */
.main-content { margin-left: 260px; min-height: 100vh; display: flex; flex-direction: column; }

/* Topbar */
.topbar {
  position: sticky; top: 0; z-index: 900;
  background: #ffffff;
  border-bottom: 1px solid var(--gray-200);
  height: 64px;
  padding: 0 2rem;
  display: flex; align-items: center; justify-content: space-between;
}
.topbar-title h4 { font-size: 1.25rem; font-weight: 600; color: var(--gray-900); margin-bottom: 2px; }
.topbar-title .breadcrumb { font-size: 0.8125rem; color: var(--gray-500); display: flex; align-items: center; gap: 4px; }
.topbar-title .breadcrumb a { color: var(--primary); text-decoration: none; font-weight: 500; }
.topbar-title .breadcrumb a:hover { text-decoration: underline; }
.topbar-right { display: flex; align-items: center; gap: 1rem; }
.topbar-badge { padding: 0.25rem 0.75rem; background: var(--gray-100); border-radius: 9999px; font-size: 0.75rem; font-weight: 600; color: var(--gray-700); }

/* Page Content */
.page-content { padding: 2rem; flex: 1; }

/* Cards */
.card {
  background: #ffffff;
  border-radius: 0.5rem;
  box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
  border: none;
  overflow: hidden;
  margin-bottom: 1.5rem;
}
.card-header {
  padding: 1.25rem 1.5rem;
  border-bottom: 1px solid var(--gray-200);
  background: #ffffff;
  display: flex; align-items: center; justify-content: space-between;
}
.card-header h5 { font-size: 1.125rem; font-weight: 600; color: var(--gray-900); display: flex; align-items: center; gap: 0.5rem; }
.card-body { padding: 1.5rem; }

/* Stats Cards */
.stat-card {
  background: #ffffff;
  border-radius: 0.5rem;
  box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
  padding: 1.5rem;
  display: flex; align-items: center; gap: 1.25rem;
  transition: all 0.2s;
  border-left: 4px solid var(--primary);
}
.stat-card:hover { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); transform: translateY(-2px); }
.stat-icon {
  width: 52px; height: 52px; border-radius: 0.5rem;
  background: var(--gray-50); color: var(--gray-600);
  display: flex; align-items: center; justify-content: center; font-size: 1.5rem;
  border: 1px solid var(--gray-100);
}
.stat-info .stat-value { font-size: 1.75rem; font-weight: 700; color: var(--gray-900); line-height: 1.2; }
.stat-info .stat-label { font-size: 0.875rem; font-weight: 500; color: var(--gray-500); }

/* Tables */
.table { width: 100%; border-collapse: collapse; }
.table thead th {
  background: var(--gray-50);
  padding: 0.75rem 1.5rem;
  text-align: left;
  font-size: 0.75rem;
  font-weight: 600;
  color: var(--gray-500);
  text-transform: uppercase;
  letter-spacing: 0.05em;
  border-bottom: 1px solid var(--gray-200);
}
.table tbody td {
  padding: 1rem 1.5rem;
  white-space: nowrap;
  border-bottom: 1px solid var(--gray-200);
  color: var(--gray-700);
  font-size: 0.875rem;
}
.table tbody tr:hover { background: var(--gray-50); }

/* Badges */
.badge {
  display: inline-flex; align-items: center; padding: 0.125rem 0.625rem;
  border-radius: 9999px; font-size: 0.75rem; font-weight: 500;
}
.badge-success { background: #d1fae5; color: #065f46; }
.badge-danger { background: #fee2e2; color: #991b1b; }
.badge-warning { background: #fef3c7; color: #92400e; }
.badge-info { background: #e0f2fe; color: #075985; }
.badge-accent { background: var(--gray-100); color: var(--gray-800); }

/* Buttons */
.btn {
  display: inline-flex; align-items: center; gap: 0.375rem;
  padding: 0.5rem 1rem; border-radius: 0.375rem;
  font-size: 0.875rem; font-weight: 500; cursor: pointer;
  border: 1px solid transparent;
  transition: all 0.15s ease-in-out;
  text-decoration: none;
  box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);
}
.btn-primary { background: var(--primary); color: #ffffff; }
.btn-primary:hover { background: var(--primary-hover); }
.btn-secondary { background: #ffffff; border-color: var(--gray-300); color: var(--gray-700); }
.btn-secondary:hover { background: var(--gray-50); }
.btn-danger { background: var(--danger); color: #ffffff; }
.btn-danger:hover { background: var(--danger-hover); }
.btn-success { background: var(--success); color: #ffffff; }
.btn-sm { padding: 0.375rem 0.75rem; font-size: 0.75rem; }
.btn-icon { padding: 0.5rem; justify-content: center; }
.btn:focus { outline: 2px solid transparent; outline-offset: 2px; box-shadow: 0 0 0 2px #fff, 0 0 0 4px var(--primary); }

/* Forms */
.form-label { display: block; font-size: 0.875rem; font-weight: 500; color: var(--gray-700); margin-bottom: 0.375rem; }
.form-control, .form-select {
  display: block; width: 100%;
  padding: 0.5rem 0.75rem;
  border: 1px solid var(--gray-300);
  border-radius: 0.375rem;
  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
  font-family: inherit; font-size: 0.875rem;
  transition: all 0.15s ease-in-out;
  background: #ffffff;
  color: var(--gray-900);
}
.form-control:focus, .form-select:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 1px var(--primary);
}
.form-control::placeholder { color: var(--gray-400); }
.form-control.is-invalid { border-color: var(--danger); }
.invalid-feedback { font-size: 0.75rem; color: var(--danger); margin-top: 0.375rem; }
.form-group { margin-bottom: 1.25rem; }

/* DataTables Overrides */
.dataTables_wrapper .dataTables_filter input,
.dataTables_wrapper .dataTables_length select {
  border: 1px solid var(--gray-300);
  border-radius: 0.375rem;
  padding: 0.25rem 0.5rem;
  font-size: 0.875rem;
  margin-left: 0.5rem;
  outline: none;
}
.dataTables_wrapper .dataTables_filter input:focus { border-color: var(--primary); box-shadow: 0 0 0 1px var(--primary); }
.dataTables_wrapper .dataTables_paginate { margin-top: 1rem; }
.dataTables_wrapper .dataTables_paginate .paginate_button {
  padding: 0.375rem 0.75rem; margin: 0 2px;
  border-radius: 0.375rem;
  border: 1px solid var(--gray-200) !important;
  background: #ffffff !important;
  color: var(--gray-700) !important;
  font-size: 0.875rem;
}
.dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: var(--gray-50) !important; color: var(--gray-900) !important; }
.dataTables_wrapper .dataTables_paginate .paginate_button.current, 
.dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
  background: var(--primary) !important; color: #ffffff !important;
  border-color: var(--primary) !important;
}

/* Modals */
.modal-overlay {
  position: fixed; inset: 0; background: rgba(17, 24, 39, 0.75);
  backdrop-filter: blur(4px); z-index: 2000;
  display: flex; align-items: center; justify-content: center;
  opacity: 0; pointer-events: none; transition: opacity 0.3s;
}
.modal-overlay.show { opacity: 1; pointer-events: all; }
.modal-box {
  background: #ffffff; border-radius: 0.5rem;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
  max-width: 32rem; width: 100%; padding: 2rem;
  transform: translateY(1rem) scale(0.95); transition: all 0.3s;
}
.modal-overlay.show .modal-box { transform: translateY(0) scale(1); }

/* Alerts */
.alert { padding: 1rem; border-radius: 0.375rem; margin-bottom: 1.5rem; font-size: 0.875rem; font-weight: 500; display: flex; align-items: center; gap: 0.5rem; }
.alert-success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
.alert-danger { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
.alert-warning { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
.alert-info { background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }

/* Grid & Layout Helpers */
.row { display: flex; flex-wrap: wrap; margin-left: -0.75rem; margin-right: -0.75rem; }
.col-md-3, .col-md-4, .col-md-6, .col-md-12 { padding-left: 0.75rem; padding-right: 0.75rem; }
.col-md-3 { width: 25%; }
.col-md-4 { width: 33.333%; }
.col-md-6 { width: 50%; }
.col-md-12 { width: 100%; }
@media (max-width: 768px) {
  .col-md-3, .col-md-4, .col-md-6 { width: 100%; }
}

/* Utilities */
.d-flex { display: flex; }
.gap-1 { gap: 0.25rem; } .gap-2 { gap: 0.5rem; } .gap-3 { gap: 0.75rem; } .gap-4 { gap: 1rem; }
.justify-content-between { justify-content: space-between; }
.align-items-center { align-items: center; }
.text-danger { color: var(--danger) !important; }
.text-success { color: var(--success) !important; }
.text-primary { color: var(--primary) !important; }
.mt-2 { margin-top: 0.5rem; } .mt-3 { margin-top: 1rem; } .mt-4 { margin-top: 1.5rem; }
.mb-2 { margin-bottom: 0.5rem; } .mb-3 { margin-bottom: 1rem; } .mb-4 { margin-bottom: 1.5rem; }

CSS;

file_put_contents($file, $new_css . "\n/* =================== LOGIN PAGE =================== */\n" . $parts[1]);
echo "Theme Laravel Breeze applied successfully.";
?>
