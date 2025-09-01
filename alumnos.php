<?php
// alumnos.php - API JSON
include("conexion.php");
header('Content-Type: application/json; charset=utf-8');

$accion = $_POST['accion'] ?? $_GET['accion'] ?? '';

function esc($conn,$v){ return $conn->real_escape_string(trim($v ?? '')); }

if ($accion === 'registrar') {
    $p_apellido = esc($conn, $_POST['p_apellido']);
    $s_apellido = esc($conn, $_POST['s_apellido']);
    $nombre = esc($conn, $_POST['nombre']);
    $carrera = esc($conn, $_POST['carrera']);
    $semestre = esc($conn, $_POST['semestre'] ?? '');
    $correo = esc($conn, $_POST['correo'] ?? '');
    $tipo_alumno = ($carrera === 'EXTERNOS') ? 'extrei' : 'intnvoalutir';

    $taller = esc($conn, $_POST['taller'] ?? '');
    $grupo = esc($conn, $_POST['grupo'] ?? '');
    $campo = esc($conn, $_POST['campo'] ?? '');

    $stmt = $conn->prepare("INSERT INTO alumnos (p_apellido, s_apellido, nombre, carrera, semestre, correo, tipo_alumno)
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $p_apellido, $s_apellido, $nombre, $carrera, $semestre, $correo, $tipo_alumno);
    if (!$stmt->execute()) {
        http_response_code(500);
        echo json_encode(['ok'=>false, 'error'=>$stmt->error]);
        exit;
    }

    // Obtener matrícula recién generada
    $res = $conn->query("SELECT MAX(matricula) AS mat FROM alumnos");
    $mat = $res->fetch_assoc()['mat'];

    if ($taller !== '' && $grupo !== '') {
        $s2 = $conn->prepare("INSERT INTO talleres (matricula, taller, grupo, campo) VALUES (?,?,?,?)");
        $s2->bind_param("isss", $mat, $taller, $grupo, $campo);
        $s2->execute();
    }

    echo json_encode(['ok'=>true, 'matricula'=>$mat]);
    exit;
}

if ($accion === 'listar') {
    $sql = "SELECT a.matricula, a.p_apellido, a.s_apellido, a.nombre, a.carrera, a.semestre, a.correo, a.tipo_alumno,
            GROUP_CONCAT(CONCAT(t.taller,' [',COALESCE(t.grupo,''),'] (',COALESCE(t.campo,''),')') SEPARATOR ' || ') AS talleres
            FROM alumnos a
            LEFT JOIN talleres t ON a.matricula = t.matricula
            GROUP BY a.matricula
            ORDER BY a.matricula DESC";
    $res = $conn->query($sql);
    $out = [];
    while($r = $res->fetch_assoc()) $out[] = $r;
    echo json_encode(['ok'=>true, 'data'=>$out]);
    exit;
}

if ($accion === 'buscar') {
    $mat = (int)($_GET['matricula'] ?? $_POST['matricula'] ?? 0);
    if ($mat === 0) { echo json_encode(['ok'=>false,'error'=>'matricula vacía']); exit;}
    $res = $conn->query("SELECT * FROM alumnos WHERE matricula = $mat");
    if ($res->num_rows === 0) { echo json_encode(['ok'=>false,'error'=>'No encontrado']); exit; }
    $al = $res->fetch_assoc();
    $t = $conn->query("SELECT taller, grupo, campo FROM talleres WHERE matricula = $mat");
    $tarr = [];
    while($row = $t->fetch_assoc()) $tarr[] = $row;
    echo json_encode(['ok'=>true, 'alumno'=>$al, 'talleres'=>$tarr]);
    exit;
}

if ($accion === 'eliminar') {
    $mat = (int)($_POST['matricula'] ?? 0);
    if ($mat === 0) { echo json_encode(['ok'=>false,'error'=>'matricula vacía']); exit; }
    $conn->query("DELETE FROM talleres WHERE matricula = $mat");
    $conn->query("DELETE FROM alumnos WHERE matricula = $mat");
    echo json_encode(['ok'=>true]);
    exit;
}

if ($accion === 'actualizar') {
    $mat = (int)($_POST['matricula'] ?? 0);
    if ($mat === 0) { echo json_encode(['ok'=>false,'error'=>'matricula vacía']); exit; }
    $p_apellido = esc($conn,$_POST['p_apellido'] ?? '');
    $s_apellido = esc($conn,$_POST['s_apellido'] ?? '');
    $nombre = esc($conn,$_POST['nombre'] ?? '');
    $carrera = esc($conn,$_POST['carrera'] ?? '');
    $semestre = esc($conn,$_POST['semestre'] ?? '');
    $correo = esc($conn,$_POST['correo'] ?? '');
    $stmt = $conn->prepare("UPDATE alumnos SET p_apellido=?, s_apellido=?, nombre=?, carrera=?, semestre=?, correo=? WHERE matricula=?");
    $stmt->bind_param("ssssssi",$p_apellido,$s_apellido,$nombre,$carrera,$semestre,$correo,$mat);
    $stmt->execute();
    echo json_encode(['ok'=>true]);
    exit;
}

echo json_encode(['ok'=>false,'error'=>'acción no válida']);
