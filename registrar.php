<?php
include("conexion.php");

$nombre = $_POST['nombre'];
$p_apellido = $_POST['p_apellido'];
$s_apellido = $_POST['s_apellido'];
$carrera = $_POST['carrera'];
$taller = $_POST['taller'];
$grupo = $_POST['grupo'];
$correo = $_POST['correo'];

$sql = "INSERT INTO alumnos (nombre, p_apellido, s_apellido, carrera, taller, grupo, correo) 
VALUES ('$nombre', '$p_apellido', '$s_apellido', '$carrera', '$taller', '$grupo', '$correo')";

if($conn->query($sql)){
    echo "Alumno registrado correctamente <a href='index.php'>Volver</a>";
} else {
    echo "Error: " . $conn->error;
}
?>
