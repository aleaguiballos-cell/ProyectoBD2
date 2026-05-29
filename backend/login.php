<?php
session_start();
header('Content-Type: application/json');

$host = 'sql311.infinityfree.com';
$user = 'if0_41904449';
$pass = 'Escomibm123';
$db   = 'if0_41904449_carniceria';

$conn = new mysqli($host, $user, $pass, $db, 3306);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión: ' . $conn->connect_error]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username && $password) {
        $stmt = $conn->prepare('SELECT password, nombre_completo, permisos FROM usuarios WHERE username = ?');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if (password_verify($password, $row['password'])) {
                $_SESSION['usuario'] = $username;
                $_SESSION['nombre_completo'] = $row['nombre_completo'];
                $_SESSION['permisos'] = $row['permisos'];

                $permisos = $row['permisos'];
                $redirectUrl = ($permisos === 'admin') ? 'admin.php' : 'index.php';

                echo json_encode([
                    'success' => true,
                    'nombre_completo' => $row['nombre_completo'],
                    'message' => '¡Bienvenido ' . $row['nombre_completo'] . '!',
                    'redirectUrl' => $redirectUrl
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => '❌ Usuario o contraseña incorrectos']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => '❌ Usuario o contraseña incorrectos']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Por favor llena todos los campos']);
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_SESSION['nombre_completo'])) {
        echo json_encode(['nombre_completo' => $_SESSION['nombre_completo']]);
    } else {
        echo json_encode(['nombre_completo' => null]);
    }
}

$conn->close();
exit();
?>
