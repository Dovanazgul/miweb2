<?php include("conexion.php"); ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Alumno</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h2>Registro de Alumno</h2>
<form action="" method="post">
    Matrícula: <input type="text" name="matricula" required><br>
    P. Apellido: <input type="text" name="p_apellido"><br>
    S. Apellido: <input type="text" name="s_apellido"><br>
    Nombre: <input type="text" name="nombre"><br>
    Grupo: <input type="text" name="grupo"><br>
    Taller: <input type="text" name="taller"><br>
    Campo: <input type="text" name="campo"><br>
    Semestre: <input type="number" name="semestre"><br>
    Correo: <input type="email" name="correo"><br>
    <button type="submit" name="guardar">Guardar</button>
</form>
<a href="index.php">Volver</a>
</body>
</html>

<?php
if (isset($_POST['guardar'])) {
    $matricula = $_POST['matricula'];
    $p_apellido = $_POST['p_apellido'];
    $s_apellido = $_POST['s_apellido'];
    $nombre = $_POST['nombre'];
    $grupo = $_POST['grupo'];
    $taller = $_POST['taller'];
    $campo = $_POST['campo'];
    $semestre = $_POST['semestre'];
    $correo = $_POST['correo'];

    // Guardar en alumnos
    $sql1 = "INSERT INTO alumnos (matricula, p_apellido, s_apellido, nombre, grupo, semestre, correo) 
             VALUES ('$matricula','$p_apellido','$s_apellido','$nombre','$grupo','$semestre','$correo')
             ON DUPLICATE KEY UPDATE nombre='$nombre', grupo='$grupo', semestre='$semestre', correo='$correo'";
    $conn->query($sql1);

    // Guardar en talleres
    $sql2 = "INSERT INTO talleres (matricula, taller, campo) VALUES ('$matricula','$taller','$campo')";
    $conn->query($sql2);

    echo "Alumno registrado correctamente.";
}
?>
