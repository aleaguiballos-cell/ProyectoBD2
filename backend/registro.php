<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'sql311.infinityfree.com';
$user = 'if0_41904449';
$pass = 'Escomibm123';
$db   = 'if0_41904449_carniceria';

$conn = new mysqli($host, $user, $pass, $db, 3306);

if ($conn->connect_error) {
    die('ERROR_CONEXION: ' . $conn->connect_error);
}

$conn->set_charset('utf8mb4');

$username           = trim($_POST['username']         ?? '');
$password           = trim($_POST['password']         ?? '');
$confirm            = trim($_POST['confirm_password'] ?? '');
$nombre_completo    = trim($_POST['nombre_completo']  ?? '');
$correo_electronico = trim($_POST['correo']           ?? '');
$permisos           = trim($_POST['permisos']         ?? '');

if (empty($username) || empty($password) || empty($confirm) ||
    empty($nombre_completo) || empty($correo_electronico) || empty($permisos)) {

    $vacios = [];
    if (empty($username))           $vacios[] = 'username';
    if (empty($password))           $vacios[] = 'password';
    if (empty($confirm))            $vacios[] = 'confirm_password';
    if (empty($nombre_completo))    $vacios[] = 'nombre_completo';
    if (empty($correo_electronico)) $vacios[] = 'correo';
    if (empty($permisos))           $vacios[] = 'permisos';

    echo 'EMPTY: ' . implode(', ', $vacios);
    exit;
}

if ($password !== $confirm) {
    echo 'NO_MATCH';
    exit;
}

if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
    echo 'WEAK';
    exit;
}

$check = $conn->prepare('SELECT id FROM usuarios WHERE username = ?');
if (!$check) {
    echo 'ERROR_PREPARE_CHECK: ' . $conn->error;
    exit;
}
$check->bind_param('s', $username);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo 'EXISTS';
    $check->close();
    exit;
}
$check->close();

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare(
    'INSERT INTO usuarios (username, password, nombre_completo, correo_electronico, permisos)
     VALUES (?, ?, ?, ?, ?)'
);

if (!$stmt) {
    echo 'ERROR_PREPARE_INSERT: ' . $conn->error;
    exit;
}

$stmt->bind_param('sssss', $username, $hash, $nombre_completo, $correo_electronico, $permisos);

if ($stmt->execute()) {
    echo 'OK';
} else {
    echo 'ERROR_INSERT: ' . $stmt->error . ' (errno: ' . $stmt->errno . ')';
}

$stmt->close();
$conn->close();
?>
