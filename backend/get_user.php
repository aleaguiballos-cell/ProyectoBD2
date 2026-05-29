<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['nombre_completo'])) {
    echo json_encode(['nombre_completo' => $_SESSION['nombre_completo']]);
} else {
    echo json_encode(['nombre_completo' => null]);
}
