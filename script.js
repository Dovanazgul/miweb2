// script.js
document.addEventListener("DOMContentLoaded", () => {
  const tallerSelect = document.getElementById("taller_select");
  const grupoSelect = document.getElementById("grupo_select");
  const campoInput = document.getElementById("campo_input");
  const msg = document.getElementById("registro_msg");
  const resultado = document.getElementById("resultado");
  const buscarInput = document.getElementById("buscar_matricula");

  function showMsg(txt, ok=true){
    msg.style.display = 'block';
    msg.className = ok ? 'msg success' : 'msg error';
    msg.textContent = txt;
    setTimeout(()=> msg.style.display='none', 6000);
  }

  tallerSelect.addEventListener("change", () => {
    const taller = tallerSelect.value;
    grupoSelect.innerHTML = '<option value="">Cargando...</option>';
    campoInput.value = '';
    fetch("talleres_ajax.php?taller=" + encodeURIComponent(taller))
      .then(res => {
        if (!res.ok) throw new Error("HTTP " + res.status);
        return res.json();
      })
      .then(data => {
        if (!Array.isArray(data)) { console.error("respuesta no es array:", data); grupoSelect.innerHTML = '<option value="">-- Error --</option>'; return; }
        let html = '<option value="">-- Selecciona grupo --</option>';
        data.forEach(it => html += `<option value="${it.grupo}" data-campo="${it.campo}">${it.grupo}</option>`);
        grupoSelect.innerHTML = html;
      })
      .catch(err => {
        console.error("Error al cargar grupos:", err);
        grupoSelect.innerHTML = '<option value="">-- No se pudieron cargar grupos --</option>';
        showMsg('No fue posible cargar grupos. Ver consola (F12).', false);
      });
  });

  grupoSelect.addEventListener("change", () => {
    const opt = grupoSelect.options[grupoSelect.selectedIndex];
    campoInput.value = opt ? (opt.dataset.campo || '') : '';
  });

  document.getElementById("btnRegistrar").addEventListener("click", (e) => {
    e.preventDefault();
    const p_apellido = document.getElementById("p_apellido").value.trim();
    const nombre = document.getElementById("nombre").value.trim();
    const carrera = document.getElementById("carrera").value;
    const taller = tallerSelect.value;
    const grupo = grupoSelect.value;

    if (!p_apellido || !nombre || !carrera || !taller || !grupo) {
      showMsg('Completa los campos obligatorios (apellido, nombre, carrera, taller, grupo).', false);
      return;
    }

    const data = new FormData();
    data.append('accion','registrar');
    data.append('p_apellido', p_apellido);
    data.append('s_apellido', document.getElementById("s_apellido").value.trim());
    data.append('nombre', nombre);
    data.append('carrera', carrera);
    data.append('taller', taller);
    data.append('grupo', grupo);
    data.append('campo', campoInput.value || '');
    data.append('semestre', document.getElementById("semestre").value.trim());
    data.append('correo', document.getElementById("correo").value.trim());

    fetch('alumnos.php', { method: 'POST', body: data })
      .then(res => res.json())
      .then(res => {
        if (res.ok) {
          showMsg('Registrado. Matrícula: ' + res.matricula, true);
          listar();
          limpiar();
        } else {
          showMsg('Error: ' + (res.error || 'desconocido'), false);
        }
      }).catch(err => {
        console.error(err);
        showMsg('Error de red al registrar (ver consola).', false);
      });
  });

  function limpiar(){
    document.getElementById("p_apellido").value = '';
    document.getElementById("s_apellido").value = '';
    document.getElementById("nombre").value = '';
    document.getElementById("carrera").value = '';
    tallerSelect.value = '';
    grupoSelect.innerHTML = '<option value="">-- Selecciona taller primero --</option>';
    campoInput.value = '';
    document.getElementById("semestre").value = '';
    document.getElementById("correo").value = '';
  }
  document.getElementById("btnLimpiar").addEventListener("click", limpiar);

  // Listar todos
  function listar(){
    fetch('alumnos.php?accion=listar')
      .then(r => r.json())
      .then(res => {
        if (!res.ok) { resultado.innerHTML = '<p>Error al listar</p>'; return; }
        const data = res.data || [];
        if (data.length === 0) { resultado.innerHTML = '<p>No hay registros.</p>'; return; }
        let html = '<table class="list"><thead><tr><th>Matrícula</th><th>Nombre</th><th>Carrera</th><th>Semestre</th><th>Correo</th><th>Talleres</th><th>Tipo</th><th>Acciones</th></tr></thead><tbody>';
        data.forEach(r => {
          html += `<tr>
            <td>${r.matricula}</td>
            <td>${r.nombre} ${r.p_apellido} ${r.s_apellido}</td>
            <td>${r.carrera||''}</td>
            <td>${r.semestre||''}</td>
            <td>${r.correo||''}</td>
            <td>${r.talleres||''}</td>
            <td>${r.tipo_alumno||''}</td>
            <td>
              <button data-action="ver" data-mat="${r.matricula}">Ver</button>
              <button data-action="elim" data-mat="${r.matricula}" class="danger">Eliminar</button>
            </td>
          </tr>`;
        });
        html += '</tbody></table>';
        resultado.innerHTML = html;
      }).catch(err => {
        console.error('Error listar:', err);
        resultado.innerHTML = '<p>Error al listar (ver consola)</p>';
      });
  }
  listar();
  document.getElementById("btnListar").addEventListener("click", listar);

  // Buscar
  document.getElementById("btnBuscar").addEventListener("click", () => {
    const mat = buscarInput.value.trim();
    if (!mat) return;
    fetch('alumnos.php?accion=buscar&matricula=' + encodeURIComponent(mat))
      .then(r => r.json())
      .then(res => {
        if (!res.ok) { resultado.innerHTML = '<p>No encontrado</p>'; return; }
        const a = res.alumno;
        let html = `<h3>Alumno ${a.matricula}</h3><p><strong>${a.nombre} ${a.p_apellido} ${a.s_apellido}</strong></p>
                    <p>Carrera: ${a.carrera||''} | Semestre: ${a.semestre||''} | Correo: ${a.correo||''} | Tipo: ${a.tipo_alumno||''}</p><h4>Talleres</h4>`;
        if (res.talleres && res.talleres.length) {
          html += '<ul>' + res.talleres.map(t => `<li>${t.taller} — ${t.grupo} — ${t.campo}</li>`).join('') + '</ul>';
        } else html += '<p>No tiene talleres</p>';
        resultado.innerHTML = html;
      }).catch(err => { console.error(err); resultado.innerHTML = '<p>Error al buscar</p>'; });
  });

  // Delegación acciones (Ver / Eliminar)
  resultado.addEventListener('click', (e) => {
    const btn = e.target.closest('button');
    if (!btn) return;
    const action = btn.dataset.action;
    const mat = btn.dataset.mat;
    if (action === 'elim') {
      if (!confirm('Eliminar alumno ' + mat + '?')) return;
      const fd = new FormData();
      fd.append('accion','eliminar');
      fd.append('matricula', mat);
      fetch('alumnos.php', { method: 'POST', body: fd })
        .then(r => r.json()).then(res => {
          if (res.ok) { showMsg('Eliminado', true); listar(); } else showMsg('Error al eliminar', false);
        }).catch(err => { console.error(err); showMsg('Error de red', false); });
    }
    if (action === 'ver') {
      buscarInput.value = mat;
      document.getElementById("btnBuscar").click();
    }
  });

});
