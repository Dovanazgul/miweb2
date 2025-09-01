$(function(){

  function listar(buscar=""){
    $.post("alumnos.php", {accion:"listar", buscar}, function(raw){
      let alumnos = [];
      try { alumnos = JSON.parse(raw); } catch(e){ console.error(e, raw); }
      let html = "";
      alumnos.forEach(a => {
        html += `<tr>
          <td>${a.matricula || ""}</td>
          <td>${(a.nombre||"")} ${(a.p_apellido||"")} ${(a.s_apellido||"")}</td>
          <td>${a.carrera || ""}</td>
          <td>${a.semestre || ""}</td>
          <td>${a.correo || ""}</td>
          <td>${a.talleres_lista || ""}</td>
          <td>${a.tipo_alumno || ""}</td>
          <td>
            <button class="btn btn-danger btn-sm eliminar" data-id="${a.matricula}">Eliminar</button>
          </td>
        </tr>`;
      });
      $("#tablaAlumnos").html(html);
    });
  }
  listar();

  $("#buscar").on("keyup", function(){ listar($(this).val()); });

  $("#formAlumno").on("submit", function(e){
    e.preventDefault();
    const datos = {
      accion: "insert",
      matricula: $("#matricula").val(),
      nombre: $("#nombre").val(),
      p_apellido: $("#p_apellido").val(),
      s_apellido: $("#s_apellido").val(),
      grupo_alu: $("#grupo_alu").val(),
      semestre: $("#semestre").val(),
      correo: $("#correo").val(),
      carrera: $("#carrera").val(),
      taller: $("#taller").val(),
      grupo_taller: $("#grupo_taller").val(),
      campo: $("#campo").val()
    };
    $.post("alumnos.php", datos, function(res){
      if(res === "ok"){
        listar();
        $("#formAlumno")[0].reset();
        $("#grupo_taller").html("<option value=''>-- Grupo Taller --</option>");
        $("#campo").val("");
      } else {
        alert(res);
      }
    });
  });

  $(document).on("click", ".eliminar", function(){
    if(!confirm("¿Eliminar este alumno y sus talleres?")) return;
    $.post("alumnos.php", {accion:"delete", matricula: $(this).data("id")}, function(res){
      if(res==="ok"){ listar(); } else { alert(res); }
    });
  });

  // Cargar grupos por taller
  $("#taller").on("change", function(){
    const taller = $(this).val();
    if(!taller){
      $("#grupo_taller").html("<option value=''>-- Grupo Taller --</option>");
      $("#campo").val("");
      return;
    }
    $.post("alumnos.php", {accion:"gruposPorTaller", taller}, function(raw){
      let grupos = [];
      try { grupos = JSON.parse(raw); } catch(e){ console.error(e, raw); }
      let html = "<option value=''>-- Grupo Taller --</option>";
      grupos.forEach(g => { html += `<option value="${g.grupo}" data-campo="${g.campo}">${g.grupo}</option>`; });
      $("#grupo_taller").html(html);
      $("#campo").val("");
    });
  });

  // Mostrar campo al seleccionar grupo
  $("#grupo_taller").on("change", function(){
    const campo = $(this).find(":selected").data("campo") || "";
    $("#campo").val(campo);
  });

});
