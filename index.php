<?php include("conexion.php"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Sistema de Inscripciones</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h1>Registro y Gestión de Alumnos</h1>

    <section class="card">
      <h2>Registrar Alumno</h2>
      <div class="form-grid">
        <label>Primer Apellido</label><input id="p_apellido" type="text" />
        <label>Segundo Apellido</label><input id="s_apellido" type="text" />
        <label>Nombre</label><input id="nombre" type="text" />
        <label>Carrera</label>
        <select id="carrera">
          <option value="">-- Selecciona --</option>
          <?php
            $opts = ['ADMINISTRACIÓN','BIOQUÍMICA DIAGNÓSTICA','CCH/PREPARATORIA','CONTADURÍA',
              'DISEÑO Y COMUNICACIÓN VISUAL','EXTERNOS','FARMACIA','INFORMÁTICA',
              'INGENIERÍA AGRÍCOLA','INGENIERÍA EN ALIMENTOS','INGENIERÍA EN TELECOMUNICACIONES, SISTEMAS Y ELECTRONICA',
              'INGENIERÍA INDUSTRIAL','INGENIERÍA MECÁNICA ELECTRICA','INGENIERÍA QUÍMICA','MEDICO VETERINARIO ZOOTECNISTA',
              'OTRA INSTANCIA UNAM','QUÍMICA','QUÍMICA FARMACEÚTICO BIOLÓGICA','QUÍMICA INDUSTRIAL','TECNOLOGÍA',
              'TRABAJADOR ACADEMICO','TRABAJADOR ADMINISTRATIVO'];
            foreach($opts as $o) echo "<option>".htmlspecialchars($o)."</option>";
          ?>
        </select>

        <label>Taller</label>
        <select id="taller_select">
          <option value="">-- Selecciona Taller --</option>
          <?php
            $rs = $conn->query("SELECT DISTINCT taller FROM talleres_grupos ORDER BY taller");
            while($r = $rs->fetch_assoc()){
              echo '<option value="'.htmlspecialchars($r['taller']).'">'.htmlspecialchars($r['taller']).'</option>';
            }
          ?>
        </select>

        <label>Grupo (del taller)</label><select id="grupo_select"><option value="">-- Selecciona taller primero --</option></select>
        <label>Campo</label><input id="campo_input" type="text" readonly />

        <label>Semestre</label><input id="semestre" type="text" />
        <label>Correo</label><input id="correo" type="email" />

        <div class="btn-row">
          <button id="btnRegistrar">Registrar</button>
          <button id="btnLimpiar" type="button">Limpiar</button>
        </div>
      </div>

      <div id="registro_msg" class="msg" style="display:none"></div>
    </section>

    <section class="card">
      <h2>Buscar / Listar</h2>
      <div class="search-row">
        <input id="buscar_matricula" placeholder="Buscar por matrícula..." />
        <button id="btnBuscar">Buscar</button>
        <button id="btnListar">Listar todos</button>
      </div>

      <div id="resultado" class="table-wrap"></div>
    </section>
  </div>

  <script src="script.js"></script>
</body>
</html>
