<?php include("conexion.php");

if (!isset($_GET['matricula'])) {
    die("Matrícula no especificada.");
}
$matricula = $_GET['matricula'];

if (isset($_POST['actualizar'])) {
    $p_apellido = $_POST['p_apellido'];
    $s_apellido = $_POST['s_apellido'];
    $nombre = $_POST['nombre'];
    $grupo = $_POST['grupo'];
    $semestre = $_POST['semestre'];
    $correo = $_POST['correo'];

    $sql = "UPDATE alumnos SET 
                p_apellido='$p_apellido', s_apellido='$s_apellido', 
                nombre='$nombre', grupo='$grupo', 
                semestre='$semestre', correo='$correo'
            WHERE matricula='$matricula'";
    $conn->query($sql);
    echo "Alumno actualizado correctamente.";
}

$sql = "SELECT * FROM alumnos WHERE matricula='$matricula'";
$result = $conn->query($sql);
$alumno = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Alumno</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h2>Editar Alumno</h2>
<form method="post">
    P. Apellido: <input type="text" name="p_apellido" value="<?php echo $alumno['p_apellido']; ?>"><br>
    S. Apellido: <input type="text" name="s_apellido" value="<?php echo $alumno['s_apellido']; ?>"><br>
    Nombre: <input type="text" name="nombre" value="<?php echo $alumno['nombre']; ?>"><br>
    Grupo: <input type="text" name="grupo" value="<?php echo $alumno['grupo']; ?>"><br>
    Semestre: <input type="number" name="semestre" value="<?php echo $alumno['semestre']; ?>"><br>
    Correo: <input type="email" name="correo" value="<?php echo $alumno['correo']; ?>"><br>
    <button type="submit" name="actualizar">Actualizar</button>
</form>
<a href="index.php">Volver</a>
</body>
</html>
