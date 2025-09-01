<?php
// conexion.php
$host = '127.0.0.1';
$user = 'root';
$pass = '';   // pon tu contraseña si aplica
$db   = 'db_alumnos';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>
