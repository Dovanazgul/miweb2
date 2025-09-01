<?php
include("conexion.php");

$matricula = $_GET['matricula'];
$sql = "SELECT * FROM alumnos WHERE matricula='$matricula'";
$res = $conn->query($sql);

if($res->num_rows > 0){
    $row = $res->fetch_assoc();
    echo "<h2>Resultado</h2>";
    echo "Matrícula: ".$row['matricula']."<br>";
    echo "Nombre: ".$row['nombre']." ".$row['p_apellido']." ".$row['s_apellido']."<br>";
    echo "Carrera: ".$row['carrera']."<br>";
    echo "Taller: ".$row['taller']."<br>";
    echo "Grupo: ".$row['grupo']."<br>";
    echo "<a href='index.php'>Volver</a>";
} else {
    echo "No se encontró alumno. <a href='index.php'>Volver</a>";
}
?>
