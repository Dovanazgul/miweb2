<?php
include("conexion.php");

if (!isset($_GET['matricula'])) {
    die("Matrícula no especificada.");
}
$matricula = $_GET['matricula'];

// Borrar talleres asociados
$conn->query("DELETE FROM talleres WHERE matricula='$matricula'");
// Borrar alumno
$conn->query("DELETE FROM alumnos WHERE matricula='$matricula'");

echo "Alumno eliminado correctamente. <a href='index.php'>Volver</a>";
?>
