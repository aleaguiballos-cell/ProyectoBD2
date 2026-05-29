<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario']) || $_SESSION['permisos'] !== 'admin') {
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

$host = 'sql311.infinityfree.com';
$user = 'if0_41904449';
$pass = 'Escomibm123';
$db   = 'if0_41904449_carniceria';

$conn = new mysqli($host, $user, $pass, $db, 3306);
if ($conn->connect_error) { echo json_encode(['error' => 'DB error']); exit(); }
$conn->set_charset('utf8mb4');

$action = $_GET['action'] ?? '';

switch ($action) {

  case 'stats':
    $hoy = date('Y-m-d');
    $total      = $conn->query("SELECT COUNT(*) c FROM recepciones")->fetch_assoc()['c'];
    $hoyCount   = $conn->query("SELECT COUNT(*) c FROM recepciones WHERE DATE(fecha_registro) = '$hoy'")->fetch_assoc()['c'];
    $usuarios   = $conn->query("SELECT COUNT(*) c FROM usuarios")->fetch_assoc()['c'];
    $proveedores= $conn->query("SELECT COUNT(DISTINCT proveedor) c FROM recepciones")->fetch_assoc()['c'];
    echo json_encode(['total'=>$total,'hoy'=>$hoyCount,'usuarios'=>$usuarios,'proveedores'=>$proveedores]);
    break;

  case 'ultimas':
    $res = $conn->query("SELECT fecha, proveedor, producto, usuario FROM recepciones ORDER BY id DESC LIMIT 5");
    $rows = [];
    while ($r = $res->fetch_assoc()) $rows[] = $r;
    echo json_encode($rows);
    break;

  case 'top_proveedores':
    $res = $conn->query("SELECT proveedor, COUNT(*) total FROM recepciones GROUP BY proveedor ORDER BY total DESC LIMIT 6");
    $rows = [];
    while ($r = $res->fetch_assoc()) $rows[] = $r;
    echo json_encode($rows);
    break;

  case 'recepciones':
    $res = $conn->query("SELECT * FROM recepciones ORDER BY id DESC");
    $rows = [];
    while ($r = $res->fetch_assoc()) $rows[] = $r;
    echo json_encode($rows);
    break;

  case 'usuarios':
    $res = $conn->query("SELECT id, username, nombre_completo, correo_electronico, permisos FROM usuarios ORDER BY id DESC");
    $rows = [];
    while ($r = $res->fetch_assoc()) $rows[] = $r;
    echo json_encode($rows);
    break;

  case 'eliminar_usuario':
    $id = (int)($_GET['id'] ?? 0);
    if ($id > 0) {
      $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
      $stmt->bind_param('i', $id);
      echo json_encode(['ok' => $stmt->execute()]);
    } else {
      echo json_encode(['ok' => false]);
    }
    break;

  default:
    echo json_encode(['error' => 'Acción no válida']);
}

$conn->close();
?>
