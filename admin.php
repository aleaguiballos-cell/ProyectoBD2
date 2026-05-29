<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: Login.html');
    exit();
}
if (!isset($_SESSION['permisos']) || $_SESSION['permisos'] !== 'admin') {
    header('Location: index.php');
    exit();
}
$nombre_admin = $_SESSION['nombre_completo'] ?? 'Administrador';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Admin — Carnes Ideal</title>
  <link rel="icon" type="image/png" href="images/circlecarnesideal.png">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root {
      --rojo:      #df3b2c;
      --rojo-dark: #b02a1e;
      --rojo-soft: #ffeaea;
      --blanco:    #ffffff;
      --gris-bg:   #f5f5f5;
      --gris-card: #ffffff;
      --texto:     #1a1a1a;
      --texto-sub: #666;
      --borde:     #e8e8e8;
      --sidebar-w: 240px;
      --topbar-h:  64px;
      --shadow:    0 2px 12px rgba(0,0,0,0.08);
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Poppins', sans-serif;
      background: var(--gris-bg);
      color: var(--texto);
      min-height: 100vh;
    }

    /* ── SIDEBAR ── */
    .sidebar {
      position: fixed;
      top: 0; left: 0;
      width: var(--sidebar-w);
      height: 100vh;
      background: linear-gradient(175deg, #1a0a08 0%, #3a1210 50%, #df3b2c 100%);
      display: flex;
      flex-direction: column;
      z-index: 100;
      box-shadow: 4px 0 20px rgba(0,0,0,0.15);
    }

    .sidebar-logo {
      padding: 20px 16px 16px;
      display: flex;
      align-items: center;
      gap: 10px;
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    .sidebar-logo img {
      width: 44px; height: 44px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid rgba(255,255,255,0.3);
    }
    .sidebar-logo span {
      color: #fff;
      font-weight: 700;
      font-size: 0.95rem;
      line-height: 1.2;
    }
    .sidebar-logo small {
      color: rgba(255,255,255,0.6);
      font-size: 0.7rem;
      display: block;
    }

    .sidebar-nav {
      flex: 1;
      padding: 16px 0;
      overflow-y: auto;
    }

    .nav-section {
      padding: 6px 16px 2px;
      color: rgba(255,255,255,0.4);
      font-size: 0.65rem;
      font-weight: 600;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      margin-top: 8px;
    }

    .nav-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 16px;
      color: rgba(255,255,255,0.75);
      cursor: pointer;
      transition: all 0.2s;
      border-left: 3px solid transparent;
      font-size: 0.88rem;
    }
    .nav-item:hover {
      background: rgba(255,255,255,0.08);
      color: #fff;
      border-left-color: rgba(255,255,255,0.4);
    }
    .nav-item.active {
      background: rgba(255,255,255,0.12);
      color: #fff;
      border-left-color: #fff;
      font-weight: 600;
    }
    .nav-item i { width: 18px; text-align: center; font-size: 0.9rem; }

    .sidebar-footer {
      padding: 16px;
      border-top: 1px solid rgba(255,255,255,0.1);
    }
    .sidebar-footer .admin-info {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 10px;
    }
    .admin-avatar {
      width: 36px; height: 36px;
      border-radius: 50%;
      background: rgba(255,255,255,0.2);
      display: flex; align-items: center; justify-content: center;
      color: #fff;
      font-weight: 700;
      font-size: 0.9rem;
      flex-shrink: 0;
    }
    .admin-name { color: #fff; font-size: 0.82rem; font-weight: 600; }
    .admin-role { color: rgba(255,255,255,0.5); font-size: 0.7rem; }

    .btn-logout {
      width: 100%;
      padding: 8px;
      background: rgba(255,255,255,0.1);
      border: 1px solid rgba(255,255,255,0.2);
      color: #fff;
      border-radius: 8px;
      cursor: pointer;
      font-size: 0.82rem;
      font-family: 'Poppins', sans-serif;
      transition: background 0.2s;
      display: flex; align-items: center; justify-content: center; gap: 6px;
    }
    .btn-logout:hover { background: rgba(255,255,255,0.2); }

    /* ── MAIN CONTENT ── */
    .main {
      margin-left: var(--sidebar-w);
      min-height: 100vh;
    }

    .topbar {
      height: var(--topbar-h);
      background: #fff;
      border-bottom: 1px solid var(--borde);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 24px;
      position: sticky;
      top: 0;
      z-index: 50;
      box-shadow: var(--shadow);
    }

    .topbar-title { font-size: 1.1rem; font-weight: 600; color: var(--texto); }
    .topbar-title span { color: var(--rojo); }

    .topbar-right { display: flex; align-items: center; gap: 12px; }

    .topbar-date {
      font-size: 0.8rem;
      color: var(--texto-sub);
      background: var(--gris-bg);
      padding: 6px 12px;
      border-radius: 20px;
    }

    .page { display: none; padding: 24px; }
    .page.active { display: block; }

    /* ── HERO BANNER ── */
    .hero-banner {
      border-radius: 16px;
      overflow: hidden;
      position: relative;
      margin-bottom: 24px;
      height: 200px;
      box-shadow: var(--shadow);
    }
    .hero-banner img {
      width: 100%; height: 100%;
      object-fit: cover;
      object-position: center 30%;
    }
    .hero-overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(90deg, rgba(26,10,8,0.85) 0%, rgba(26,10,8,0.3) 60%, transparent 100%);
      display: flex;
      align-items: center;
      padding: 0 32px;
    }
    .hero-text h2 {
      color: #fff;
      font-size: 1.6rem;
      font-weight: 800;
      line-height: 1.2;
      text-shadow: 0 2px 8px rgba(0,0,0,0.3);
    }
    .hero-text p {
      color: rgba(255,255,255,0.8);
      font-size: 0.88rem;
      margin-top: 4px;
    }
    .hero-badge {
      display: inline-block;
      background: var(--rojo);
      color: #fff;
      font-size: 0.7rem;
      font-weight: 600;
      padding: 3px 10px;
      border-radius: 20px;
      margin-bottom: 8px;
      letter-spacing: 1px;
      text-transform: uppercase;
    }

    /* ── STATS GRID ── */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 16px;
      margin-bottom: 24px;
    }

    .stat-card {
      background: #fff;
      border-radius: 12px;
      padding: 20px;
      box-shadow: var(--shadow);
      display: flex;
      align-items: center;
      gap: 14px;
      transition: transform 0.2s;
    }
    .stat-card:hover { transform: translateY(-2px); }

    .stat-icon {
      width: 48px; height: 48px;
      border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.3rem;
      flex-shrink: 0;
    }
    .stat-icon.red    { background: #ffeaea; color: var(--rojo); }
    .stat-icon.orange { background: #fff3e0; color: #f57c00; }
    .stat-icon.green  { background: #e8f5e9; color: #2e7d32; }
    .stat-icon.blue   { background: #e3f2fd; color: #1565c0; }

    .stat-num { font-size: 1.6rem; font-weight: 700; color: var(--texto); line-height: 1; }
    .stat-label { font-size: 0.78rem; color: var(--texto-sub); margin-top: 2px; }

    /* ── CARDS ── */
    .card {
      background: #fff;
      border-radius: 12px;
      box-shadow: var(--shadow);
      overflow: hidden;
      margin-bottom: 20px;
    }
    .card-header {
      padding: 16px 20px;
      border-bottom: 1px solid var(--borde);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .card-header h3 { font-size: 0.95rem; font-weight: 600; display: flex; align-items: center; gap: 8px; }
    .card-header h3 i { color: var(--rojo); }
    .card-body { padding: 20px; }

    /* ── TABLE ── */
    .tabla-wrap { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
    thead tr { background: var(--gris-bg); }
    th {
      padding: 10px 14px;
      text-align: left;
      font-weight: 600;
      color: var(--texto-sub);
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      white-space: nowrap;
    }
    td { padding: 10px 14px; border-bottom: 1px solid var(--borde); vertical-align: middle; }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background: #fafafa; }

    .badge {
      display: inline-block;
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 0.72rem;
      font-weight: 600;
    }
    .badge-admin    { background: #ffeaea; color: var(--rojo); }
    .badge-operativo{ background: #e3f2fd; color: #1565c0; }
    .badge-ok       { background: #e8f5e9; color: #2e7d32; }
    .badge-no       { background: #ffeaea; color: var(--rojo); }

    /* ── BOTONES ── */
    .btn {
      padding: 8px 16px;
      border-radius: 8px;
      border: none;
      cursor: pointer;
      font-family: 'Poppins', sans-serif;
      font-size: 0.82rem;
      font-weight: 600;
      transition: all 0.2s;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }
    .btn-primary { background: var(--rojo); color: #fff; }
    .btn-primary:hover { background: var(--rojo-dark); }
    .btn-outline { background: #fff; color: var(--rojo); border: 1.5px solid var(--rojo); }
    .btn-outline:hover { background: var(--rojo-soft); }
    .btn-danger { background: #ffeaea; color: var(--rojo); border: none; }
    .btn-danger:hover { background: var(--rojo); color: #fff; }
    .btn-sm { padding: 5px 10px; font-size: 0.75rem; }

    /* ── BÚSQUEDA ── */
    .search-box {
      display: flex;
      align-items: center;
      gap: 8px;
      background: var(--gris-bg);
      border: 1px solid var(--borde);
      border-radius: 8px;
      padding: 7px 12px;
      flex: 1;
      max-width: 300px;
    }
    .search-box i { color: var(--texto-sub); font-size: 0.85rem; }
    .search-box input {
      border: none;
      background: none;
      outline: none;
      font-family: 'Poppins', sans-serif;
      font-size: 0.83rem;
      width: 100%;
      color: var(--texto);
    }

    /* ── GRID 2 COL ── */
    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    @media (max-width: 900px) { .grid-2 { grid-template-columns: 1fr; } }

    /* ── LOADING ── */
    .loading {
      text-align: center;
      padding: 40px;
      color: var(--texto-sub);
      font-size: 0.88rem;
    }
    .loading i { font-size: 1.5rem; color: var(--rojo); animation: spin 1s linear infinite; display: block; margin-bottom: 8px; }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* ── EMPTY STATE ── */
    .empty {
      text-align: center;
      padding: 40px;
      color: var(--texto-sub);
    }
    .empty i { font-size: 2.5rem; color: #ddd; display: block; margin-bottom: 10px; }

    /* ── MODAL ── */
    .modal-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.5);
      z-index: 200;
      align-items: center;
      justify-content: center;
    }
    .modal-overlay.open { display: flex; }
    .modal {
      background: #fff;
      border-radius: 16px;
      padding: 28px;
      width: 90%;
      max-width: 480px;
      box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }
    .modal h3 { font-size: 1.1rem; font-weight: 700; margin-bottom: 16px; color: var(--texto); }
    .modal-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px; }
    .form-field { margin-bottom: 14px; }
    .form-field label { display: block; font-size: 0.8rem; font-weight: 600; color: var(--texto-sub); margin-bottom: 5px; }
    .form-field input, .form-field select {
      width: 100%;
      padding: 9px 12px;
      border: 1.5px solid var(--borde);
      border-radius: 8px;
      font-family: 'Poppins', sans-serif;
      font-size: 0.85rem;
      outline: none;
      transition: border 0.2s;
    }
    .form-field input:focus, .form-field select:focus { border-color: var(--rojo); }

    /* ── MINI CHART ── */
    .bar-chart { display: flex; flex-direction: column; gap: 10px; }
    .bar-item label { font-size: 0.78rem; color: var(--texto-sub); display: flex; justify-content: space-between; margin-bottom: 3px; }
    .bar-track { background: var(--gris-bg); border-radius: 4px; height: 8px; overflow: hidden; }
    .bar-fill { height: 100%; background: linear-gradient(90deg, var(--rojo-dark), var(--rojo)); border-radius: 4px; transition: width 1s ease; }

    /* ── TOAST ── */
    .toast {
      position: fixed;
      bottom: 24px;
      right: 24px;
      background: #1a1a1a;
      color: #fff;
      padding: 12px 20px;
      border-radius: 10px;
      font-size: 0.85rem;
      z-index: 999;
      display: none;
      align-items: center;
      gap: 8px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.25);
    }
    .toast.show { display: flex; animation: slideUp 0.3s ease; }
    @keyframes slideUp { from { opacity:0; transform: translateY(10px); } to { opacity:1; transform: translateY(0); } }
    .toast.success i { color: #4caf50; }
    .toast.error i { color: var(--rojo); }
  </style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
  <div class="sidebar-logo">
    <img src="images/circlecarnesideal.png" alt="Logo">
    <div>
      <span>Carnes Ideal<small>Panel Administrativo</small></span>
    </div>
  </div>

  <nav class="sidebar-nav">
    <div class="nav-section">Principal</div>
    <div class="nav-item active" onclick="showPage('dashboard')">
      <i class="fa fa-home"></i> Dashboard
    </div>

    <div class="nav-section">Gestión</div>
    <div class="nav-item" onclick="showPage('recepciones')">
      <i class="fa fa-clipboard-list"></i> Recepciones
    </div>
    <div class="nav-item" onclick="showPage('usuarios')">
      <i class="fa fa-users"></i> Usuarios
    </div>

    <div class="nav-section">Sistema</div>
    <div class="nav-item" onclick="window.location.href='index.php'">
      <i class="fa fa-file-alt"></i> Ir al Formulario
    </div>
  </nav>

  <div class="sidebar-footer">
    <div class="admin-info">
      <div class="admin-avatar"><?php echo strtoupper(substr($nombre_admin, 0, 1)); ?></div>
      <div>
        <div class="admin-name"><?php echo htmlspecialchars($nombre_admin); ?></div>
        <div class="admin-role">Administrador</div>
      </div>
    </div>
    <button class="btn-logout" onclick="cerrarSesion()">
      <i class="fa fa-sign-out-alt"></i> Cerrar sesión
    </button>
  </div>
</aside>

<!-- MAIN -->
<div class="main">
  <div class="topbar">
    <div class="topbar-title">Panel de <span>Administración</span></div>
    <div class="topbar-right">
      <div class="topbar-date" id="fecha-actual"></div>
    </div>
  </div>

  <!-- DASHBOARD -->
  <div class="page active" id="page-dashboard">

    <!-- Hero banner con la imagen -->
    <div class="hero-banner">
      <img src="images/hero-carnes.jpg" alt="Carnes Ideal" onerror="this.style.display='none'; this.nextElementSibling.style.background='linear-gradient(135deg, #1a0a08 0%, #df3b2c 100%)'">
      <div class="hero-overlay">
        <div class="hero-text">
          <span class="hero-badge">Panel Admin</span>
          <h2>Variedad de carne<br>para tus comidas</h2>
          <p>Sistema Integral de Recepción y Administración</p>
        </div>
      </div>
    </div>

    <!-- Stats -->
    <div class="stats-grid" id="stats-grid">
      <div class="stat-card">
        <div class="stat-icon red"><i class="fa fa-clipboard-check"></i></div>
        <div>
          <div class="stat-num" id="stat-total">—</div>
          <div class="stat-label">Recepciones totales</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon orange"><i class="fa fa-calendar-day"></i></div>
        <div>
          <div class="stat-num" id="stat-hoy">—</div>
          <div class="stat-label">Registros hoy</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon green"><i class="fa fa-users"></i></div>
        <div>
          <div class="stat-num" id="stat-usuarios">—</div>
          <div class="stat-label">Usuarios activos</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon blue"><i class="fa fa-truck"></i></div>
        <div>
          <div class="stat-num" id="stat-proveedores">—</div>
          <div class="stat-label">Proveedores distintos</div>
        </div>
      </div>
    </div>

    <div class="grid-2">
      <!-- Últimas recepciones -->
      <div class="card">
        <div class="card-header">
          <h3><i class="fa fa-clock"></i> Últimas recepciones</h3>
          <button class="btn btn-outline btn-sm" onclick="showPage('recepciones')">Ver todo</button>
        </div>
        <div class="card-body" id="ultimas-recepciones">
          <div class="loading"><i class="fa fa-spinner"></i> Cargando...</div>
        </div>
      </div>

      <!-- Top proveedores -->
      <div class="card">
        <div class="card-header">
          <h3><i class="fa fa-chart-bar"></i> Top proveedores</h3>
        </div>
        <div class="card-body" id="top-proveedores">
          <div class="loading"><i class="fa fa-spinner"></i> Cargando...</div>
        </div>
      </div>
    </div>
  </div>

  <!-- RECEPCIONES -->
  <div class="page" id="page-recepciones">
    <div class="card">
      <div class="card-header">
        <h3><i class="fa fa-clipboard-list"></i> Registros de Recepción</h3>
        <div style="display:flex;gap:10px;align-items:center;">
          <div class="search-box">
            <i class="fa fa-search"></i>
            <input type="text" placeholder="Buscar proveedor, producto..." id="buscar-recepcion" oninput="filtrarRecepciones()">
          </div>
          <button class="btn btn-primary btn-sm" onclick="exportarCSV()">
            <i class="fa fa-download"></i> Exportar
          </button>
        </div>
      </div>
      <div class="card-body">
        <div class="tabla-wrap">
          <table id="tabla-recepciones">
            <thead>
              <tr>
                <th>#</th>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Proveedor</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Unidad</th>
                <th>Precio</th>
                <th>Temp °C</th>
                <th>Empaque</th>
                <th>Verificó</th>
              </tr>
            </thead>
            <tbody id="tbody-recepciones">
              <tr><td colspan="11"><div class="loading"><i class="fa fa-spinner"></i> Cargando datos...</div></td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- USUARIOS -->
  <div class="page" id="page-usuarios">
    <div class="card">
      <div class="card-header">
        <h3><i class="fa fa-users"></i> Gestión de Usuarios</h3>
        <div style="display:flex;gap:10px;">
          <div class="search-box">
            <i class="fa fa-search"></i>
            <input type="text" placeholder="Buscar usuario..." id="buscar-usuario" oninput="filtrarUsuarios()">
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="tabla-wrap">
          <table id="tabla-usuarios">
            <thead>
              <tr>
                <th>#</th>
                <th>Nombre completo</th>
                <th>Usuario</th>
                <th>Correo</th>
                <th>Permisos</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody id="tbody-usuarios">
              <tr><td colspan="6"><div class="loading"><i class="fa fa-spinner"></i> Cargando usuarios...</div></td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</div><!-- /main -->

<!-- MODAL eliminar usuario -->
<div class="modal-overlay" id="modal-eliminar">
  <div class="modal">
    <h3><i class="fa fa-exclamation-triangle" style="color:var(--rojo)"></i> ¿Eliminar usuario?</h3>
    <p style="font-size:0.88rem;color:var(--texto-sub);">Esta acción no se puede deshacer. El usuario <strong id="modal-username"></strong> será eliminado permanentemente.</p>
    <div class="modal-actions">
      <button class="btn btn-outline" onclick="cerrarModal()">Cancelar</button>
      <button class="btn btn-primary" id="btn-confirmar-eliminar">Eliminar</button>
    </div>
  </div>
</div>

<!-- TOAST -->
<div class="toast" id="toast"><i class="fa fa-check-circle"></i> <span id="toast-msg"></span></div>

<script>
// ── DATOS ──────────────────────────────────────────
let todosRecepciones = [];
let todosUsuarios    = [];
let usuarioAEliminar = null;

// ── NAVEGACIÓN ─────────────────────────────────────
function showPage(page) {
  document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
  document.getElementById('page-' + page).classList.add('active');
  event.currentTarget && event.currentTarget.classList.add('active');

  // Cargar datos según la página
  if (page === 'dashboard')    cargarDashboard();
  if (page === 'recepciones')  cargarRecepciones();
  if (page === 'usuarios')     cargarUsuarios();
}

// ── FECHA ACTUAL ───────────────────────────────────
function actualizarFecha() {
  const ahora = new Date();
  const opciones = { weekday:'long', year:'numeric', month:'long', day:'numeric' };
  document.getElementById('fecha-actual').textContent = ahora.toLocaleDateString('es-MX', opciones);
}
actualizarFecha();

// ── DASHBOARD ──────────────────────────────────────
function cargarDashboard() {
  fetch('admin_data.php?action=stats')
    .then(r => r.json())
    .then(d => {
      document.getElementById('stat-total').textContent      = d.total      ?? '0';
      document.getElementById('stat-hoy').textContent        = d.hoy        ?? '0';
      document.getElementById('stat-usuarios').textContent   = d.usuarios   ?? '0';
      document.getElementById('stat-proveedores').textContent= d.proveedores?? '0';
    }).catch(() => {});

  fetch('admin_data.php?action=ultimas')
    .then(r => r.json())
    .then(rows => {
      const el = document.getElementById('ultimas-recepciones');
      if (!rows.length) { el.innerHTML = '<div class="empty"><i class="fa fa-inbox"></i> Sin registros aún</div>'; return; }
      el.innerHTML = `<table style="width:100%;font-size:0.8rem;border-collapse:collapse;">
        <thead><tr style="background:var(--gris-bg);">
          <th style="padding:8px;text-align:left;">Fecha</th>
          <th style="padding:8px;text-align:left;">Proveedor</th>
          <th style="padding:8px;text-align:left;">Producto</th>
          <th style="padding:8px;text-align:left;">Usuario</th>
        </tr></thead>
        <tbody>${rows.map(r => `<tr style="border-bottom:1px solid var(--borde);">
          <td style="padding:8px;">${r.fecha}</td>
          <td style="padding:8px;">${r.proveedor}</td>
          <td style="padding:8px;">${r.producto}</td>
          <td style="padding:8px;">${r.usuario}</td>
        </tr>`).join('')}</tbody>
      </table>`;
    }).catch(() => {
      document.getElementById('ultimas-recepciones').innerHTML = '<div class="empty"><i class="fa fa-inbox"></i> Sin datos</div>';
    });

  fetch('admin_data.php?action=top_proveedores')
    .then(r => r.json())
    .then(rows => {
      const el = document.getElementById('top-proveedores');
      if (!rows.length) { el.innerHTML = '<div class="empty"><i class="fa fa-chart-bar"></i> Sin datos</div>'; return; }
      const max = rows[0].total;
      el.innerHTML = `<div class="bar-chart">${rows.map(r => `
        <div class="bar-item">
          <label><span>${r.proveedor}</span><span style="color:var(--rojo);font-weight:700;">${r.total}</span></label>
          <div class="bar-track"><div class="bar-fill" style="width:${(r.total/max*100).toFixed(0)}%"></div></div>
        </div>`).join('')}</div>`;
    }).catch(() => {
      document.getElementById('top-proveedores').innerHTML = '<div class="empty"><i class="fa fa-chart-bar"></i> Sin datos</div>';
    });
}

// ── RECEPCIONES ────────────────────────────────────
function cargarRecepciones() {
  document.getElementById('tbody-recepciones').innerHTML = '<tr><td colspan="11"><div class="loading"><i class="fa fa-spinner"></i> Cargando...</div></td></tr>';
  fetch('admin_data.php?action=recepciones')
    .then(r => r.json())
    .then(rows => {
      todosRecepciones = rows;
      renderRecepciones(rows);
    }).catch(() => {
      document.getElementById('tbody-recepciones').innerHTML = '<tr><td colspan="11"><div class="empty"><i class="fa fa-exclamation-circle"></i> Error al cargar datos</div></td></tr>';
    });
}

function renderRecepciones(rows) {
  const tbody = document.getElementById('tbody-recepciones');
  if (!rows.length) {
    tbody.innerHTML = '<tr><td colspan="11"><div class="empty"><i class="fa fa-inbox"></i> No hay registros</div></td></tr>';
    return;
  }
  tbody.innerHTML = rows.map((r, i) => `
    <tr>
      <td style="color:var(--texto-sub);font-size:0.75rem;">${r.id}</td>
      <td>${r.fecha}</td>
      <td>${r.usuario}</td>
      <td><strong>${r.proveedor}</strong></td>
      <td>${r.producto}</td>
      <td>${r.cantidad}</td>
      <td>${r.unidad}</td>
      <td>$${parseFloat(r.precio_unidad||0).toFixed(2)}</td>
      <td>${r.temp_producto}°C</td>
      <td><span class="badge ${r.empaque_limpio==='Si'?'badge-ok':'badge-no'}">${r.empaque_limpio||'—'}</span></td>
      <td>${r.verifico}</td>
    </tr>`).join('');
}

function filtrarRecepciones() {
  const q = document.getElementById('buscar-recepcion').value.toLowerCase();
  renderRecepciones(todosRecepciones.filter(r =>
    (r.proveedor||'').toLowerCase().includes(q) ||
    (r.producto||'').toLowerCase().includes(q) ||
    (r.usuario||'').toLowerCase().includes(q)
  ));
}

function exportarCSV() {
  if (!todosRecepciones.length) { mostrarToast('No hay datos para exportar', 'error'); return; }
  const cols = ['id','fecha','usuario','proveedor','producto','cantidad','unidad','precio_unidad','temp_producto','empaque_limpio','verifico'];
  const csv  = [cols.join(','), ...todosRecepciones.map(r => cols.map(c => `"${r[c]||''}"`).join(','))].join('\n');
  const a = document.createElement('a');
  a.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv);
  a.download = 'recepciones_' + new Date().toISOString().slice(0,10) + '.csv';
  a.click();
  mostrarToast('Archivo CSV descargado');
}

// ── USUARIOS ───────────────────────────────────────
function cargarUsuarios() {
  document.getElementById('tbody-usuarios').innerHTML = '<tr><td colspan="6"><div class="loading"><i class="fa fa-spinner"></i> Cargando...</div></td></tr>';
  fetch('admin_data.php?action=usuarios')
    .then(r => r.json())
    .then(rows => {
      todosUsuarios = rows;
      renderUsuarios(rows);
    }).catch(() => {
      document.getElementById('tbody-usuarios').innerHTML = '<tr><td colspan="6"><div class="empty"><i class="fa fa-exclamation-circle"></i> Error al cargar</div></td></tr>';
    });
}

function renderUsuarios(rows) {
  const tbody = document.getElementById('tbody-usuarios');
  if (!rows.length) {
    tbody.innerHTML = '<tr><td colspan="6"><div class="empty"><i class="fa fa-users"></i> No hay usuarios</div></td></tr>';
    return;
  }
  tbody.innerHTML = rows.map((r, i) => `
    <tr>
      <td style="color:var(--texto-sub);font-size:0.75rem;">${r.id}</td>
      <td><strong>${r.nombre_completo}</strong></td>
      <td>${r.username}</td>
      <td style="color:var(--texto-sub);">${r.correo_electronico}</td>
      <td><span class="badge ${r.permisos==='admin'?'badge-admin':'badge-operativo'}">${r.permisos}</span></td>
      <td>
        <button class="btn btn-danger btn-sm" onclick="confirmarEliminar(${r.id}, '${r.username}')">
          <i class="fa fa-trash"></i> Eliminar
        </button>
      </td>
    </tr>`).join('');
}

function filtrarUsuarios() {
  const q = document.getElementById('buscar-usuario').value.toLowerCase();
  renderUsuarios(todosUsuarios.filter(u =>
    (u.username||'').toLowerCase().includes(q) ||
    (u.nombre_completo||'').toLowerCase().includes(q)
  ));
}

// ── ELIMINAR USUARIO ───────────────────────────────
function confirmarEliminar(id, username) {
  usuarioAEliminar = id;
  document.getElementById('modal-username').textContent = username;
  document.getElementById('modal-eliminar').classList.add('open');
}

function cerrarModal() {
  document.getElementById('modal-eliminar').classList.remove('open');
  usuarioAEliminar = null;
}

document.getElementById('btn-confirmar-eliminar').addEventListener('click', function() {
  if (!usuarioAEliminar) return;
  fetch('admin_data.php?action=eliminar_usuario&id=' + usuarioAEliminar)
    .then(r => r.json())
    .then(d => {
      cerrarModal();
      if (d.ok) { mostrarToast('Usuario eliminado'); cargarUsuarios(); }
      else mostrarToast('Error al eliminar', 'error');
    });
});

// ── TOAST ──────────────────────────────────────────
function mostrarToast(msg, tipo = 'success') {
  const t = document.getElementById('toast');
  document.getElementById('toast-msg').textContent = msg;
  t.className = 'toast show ' + tipo;
  t.querySelector('i').className = tipo === 'error' ? 'fa fa-times-circle' : 'fa fa-check-circle';
  setTimeout(() => t.classList.remove('show'), 3000);
}

// ── CERRAR SESIÓN ──────────────────────────────────
function cerrarSesion() {
  fetch('logout.php').then(() => window.location.href = 'Login.html');
}

// ── INIT ───────────────────────────────────────────
cargarDashboard();
</script>
</body>
</html>
