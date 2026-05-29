<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['usuario'])) {
    http_response_code(403);
    echo "❌ Acceso denegado.";
    exit();
}

$host = 'sql311.infinityfree.com';
$user = 'if0_41904449';
$pass = 'Escomibm123';
$db   = 'if0_41904449_carniceria';

$conn = new mysqli($host, $user, $pass, $db, 3306);
if ($conn->connect_error) {
    echo "❌ Error de conexión: " . $conn->connect_error;
    exit();
}
$conn->set_charset('utf8mb4');

// Leer productos desde JSON
$productos_json = $_POST['productos_json'] ?? '[]';
$productos = json_decode($productos_json, true);

// Datos generales del formulario
$usuario           = $_SESSION['usuario'];
$fecha             = $_POST['fecha']              ?? '';
$proveedor         = $_POST['proveedor']          ?? '';
$sensorial_olor    = $_POST['sensorial_olor']     ?? '';
$obs_olor          = $_POST['obs_olor']           ?? '';
$sensorial_color   = $_POST['sensorial_color']    ?? '';
$obs_color         = $_POST['obs_color']          ?? '';
$sensorial_textura = $_POST['sensorial_textura']  ?? '';
$obs_textura       = $_POST['obs_textura']        ?? '';
$temp_producto     = $_POST['temp_producto']      ?? '0';
$empaque_limpio    = $_POST['empaque_limpio']     ?? '';
$num_remision      = $_POST['num_remision']       ?? '';
$verifico          = $_POST['verifico']           ?? '';

// Si no hay productos, insertar un registro sin producto
if (empty($productos)) {
    $productos = [['producto'=>'','cantidad'=>'0','unidad'=>'','precio'=>'0']];
}

$stmt = $conn->prepare("
    INSERT INTO recepciones (
        usuario, fecha, proveedor,
        producto, cantidad, unidad, precio_unidad,
        sensorial_olor, obs_olor,
        sensorial_color, obs_color,
        sensorial_textura, obs_textura,
        temp_producto, empaque_limpio,
        num_remision, verifico
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

if (!$stmt) {
    echo "❌ Error al preparar: " . $conn->error;
    exit();
}

// Insertar un registro por cada producto
$errores = 0;
foreach ($productos as $p) {
    $producto      = $p['producto'] ?? '';
    $cantidad      = $p['cantidad'] ?? '0';
    $unidad        = $p['unidad']   ?? '';
    $precio_unidad = $p['precio']   ?? '0';

    $stmt->bind_param(
        'sssssssssssssssss',
        $usuario,
        $fecha,
        $proveedor,
        $producto,
        $cantidad,
        $unidad,
        $precio_unidad,
        $sensorial_olor,
        $obs_olor,
        $sensorial_color,
        $obs_color,
        $sensorial_textura,
        $obs_textura,
        $temp_producto,
        $empaque_limpio,
        $num_remision,
        $verifico
    );

    if (!$stmt->execute()) {
        $errores++;
    }
}

if ($errores === 0) {
    echo "✅ Registro guardado correctamente.";
} else {
    echo "❌ Error al guardar: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
