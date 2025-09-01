<?php
// talleres_ajax.php
include("conexion.php");

header('Content-Type: application/json; charset=utf-8');

if (!isset($_GET['taller'])) {
    echo json_encode([]);
    exit;
}
$taller = $conn->real_escape_string($_GET['taller']);

$stmt = $conn->prepare("SELECT grupo, campo FROM talleres_grupos WHERE taller = ? ORDER BY grupo");
$stmt->bind_param("s", $taller);
$stmt->execute();
$res = $stmt->get_result();
$out = [];
while ($row = $res->fetch_assoc()) $out[] = $row;

echo json_encode($out);
