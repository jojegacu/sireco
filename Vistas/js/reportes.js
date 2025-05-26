document.addEventListener('DOMContentLoaded', () => {
  const combo = document.querySelector('select[name="estatus"]');
  const encabezado = document.getElementById('encabezadoTabla');
  const cuerpo = document.getElementById('cuerpoTabla');
  const btnLimpiar = document.getElementById('btnLimpiar');
  const btnExportarCSV = document.getElementById('btnExportarCSV');

  if (btnLimpiar) {
    btnLimpiar.addEventListener('click', () => {
      location.reload(); // Recarga la página
    });
  }
  
  if (btnExportarCSV) {
    btnExportarCSV.addEventListener('click', () => {
      const estatus = combo.value;
      if (estatus === "") {
        alert("Por favor, seleccione un tipo de reporte antes de exportar");
        return;
      }
      
      // Obtener los valores de fecha solo si se han seleccionado
      const fechaInicioEl = document.querySelector('input[name="fechaInicio"]');
      const fechaFinEl = document.querySelector('input[name="fechaFin"]');
      const fechaInicio = fechaInicioEl && fechaInicioEl.value ? fechaInicioEl.value : '';
      const fechaFin = fechaFinEl && fechaFinEl.value ? fechaFinEl.value : '';
      
      // Obtener las columnas visibles si existe la tabla DataTable
      let columnasVisibles = [];
      if ($.fn.DataTable.isDataTable('#tablaReporte')) {
        const tabla = $('#tablaReporte').DataTable();
        const columnas = tabla.columns();
        for (let i = 0; i < columnas[0].length; i++) {
          const columna = tabla.column(i);
          if (columna.visible()) {
            // Obtener el texto del encabezado
            const nombreColumna = $(columna.header()).text();
            columnasVisibles.push(nombreColumna);
          }
        }
      }
      
      // Crear un formulario temporal para enviar los datos
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = baseUrl + "Ajax/reportesA.php";
      form.target = '_blank';
      
      // Añadir campos
      const accionInput = document.createElement('input');
      accionInput.type = 'hidden';
      accionInput.name = 'accion';
      accionInput.value = 'exportarCSV';
      form.appendChild(accionInput);
      
      const estatusInput = document.createElement('input');
      estatusInput.type = 'hidden';
      estatusInput.name = 'estatus';
      estatusInput.value = estatus;
      form.appendChild(estatusInput);
      
      // Solo agregar fechas si se han proporcionado
      if (fechaInicio) {
        const fechaInicioInput = document.createElement('input');
        fechaInicioInput.type = 'hidden';
        fechaInicioInput.name = 'fechaInicio';
        fechaInicioInput.value = fechaInicio;
        form.appendChild(fechaInicioInput);
      }
      
      if (fechaFin) {
        const fechaFinInput = document.createElement('input');
        fechaFinInput.type = 'hidden';
        fechaFinInput.name = 'fechaFin';
        fechaFinInput.value = fechaFin;
        form.appendChild(fechaFinInput);
      }
      
      // Enviar las columnas visibles
      if (columnasVisibles.length > 0) {
        const columnasInput = document.createElement('input');
        columnasInput.type = 'hidden';
        columnasInput.name = 'columnas';
        columnasInput.value = JSON.stringify(columnasVisibles);
        form.appendChild(columnasInput);
      }
      
      // Añadir al body y enviar
      document.body.appendChild(form);
      form.submit();
      document.body.removeChild(form);
    });
  }

  if (!combo || !encabezado || !cuerpo) return;

  combo.addEventListener('change', () => {
    const estatus = combo.value;
    if (estatus === "") return;

    const formData = new FormData();
    formData.append("accion", "generarPorEstatus");
    formData.append("estatus", estatus);

    // 🟨 NUEVO: agrega valores de fecha
    const fechaInicio = document.querySelector('input[name="fechaInicio"]').value;
    const fechaFin = document.querySelector('input[name="fechaFin"]').value;
    formData.append("fechaInicio", fechaInicio);
    formData.append("fechaFin", fechaFin);

    fetch(baseUrl + "Ajax/reportesA.php", {
      method: "POST",
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if ($.fn.DataTable.isDataTable('#tablaReporte')) {
        $('#tablaReporte').DataTable().clear().destroy();
      }

      encabezado.innerHTML = "";
      cuerpo.innerHTML = "";

      if (!Array.isArray(data) || data.length === 0) {
        encabezado.innerHTML = "<th>No hay datos</th>";
        return;
      }
      
      // Verificar si es estatus 4 (Contratado) para agregar columna EXPORTAR LAYOUT
      const esContratado = estatus === "4";
      const columnas = Object.keys(data[0]);
      
      // Si es estatus Contratado, añadir columna EXPORTAR LAYOUT al principio
      if (esContratado) {
        const thExportar = document.createElement("th");
        thExportar.textContent = "EXPORTAR LAYOUT";
        encabezado.appendChild(thExportar);
      }
      
      // Agregar las columnas normales
      columnas.forEach(col => {
        const th = document.createElement("th");
        th.textContent = col;
        encabezado.appendChild(th);
      });
      
      // Agregar filas
      data.forEach(row => {
        const tr = document.createElement("tr");
        
        // Si es estatus Contratado, añadir celda con botón de exportar layout
        if (esContratado) {
          const tdExportar = document.createElement("td");
          const btnExportar = document.createElement("button");
          btnExportar.className = "btn btn-xs btn-success exportar-layout";
          btnExportar.innerHTML = "<i class='fa fa-file-text-o'></i> Layout";
          btnExportar.setAttribute("data-id", row.idAspirante || "");
          btnExportar.onclick = function() {
            exportarLayoutAspirante(this.getAttribute("data-id"));
          };
          tdExportar.appendChild(btnExportar);
          tr.appendChild(tdExportar);
        }
        
        // Agregar las celdas normales
        columnas.forEach(col => {
          const td = document.createElement("td");
          td.textContent = row[col];
          tr.appendChild(td);
        });
        
        cuerpo.appendChild(tr);
      });

      const tabla = $('#tablaReporte').DataTable({
        destroy: true,
        pageLength: 15,
        scrollX: true,
        language: {
          lengthMenu: "Mostrar _MENU_ registros",
          zeroRecords: "No se encontraron resultados",
          info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
          infoEmpty: "Mostrando 0 de 0 registros",
          infoFiltered: "(filtrado de _MAX_ registros totales)",
          search: "Buscar:",
          paginate: {
            first: "Primero",
            last: "Último",
            next: "Siguiente",
            previous: "Anterior"
          }
        }
      });

      crearControlesDeColumnas(columnas, tabla);
    });
  });
});

// Función para exportar layout de un aspirante específico
function exportarLayoutAspirante(idAspirante) {
  if (!idAspirante) {
    alert('Error: No se pudo identificar al aspirante');
    return;
  }
  
  // Convertir a número para asegurar que es un ID válido
  const idNumerico = parseInt(idAspirante, 10);
  
  if (isNaN(idNumerico) || idNumerico <= 0) {
    alert('Error: ID de aspirante inválido: ' + idAspirante);
    return;
  }
  
  console.log('Exportando layout para aspirante ID:', idNumerico);
  
  // Crear un formulario temporal para enviar la solicitud
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = baseUrl + "Ajax/reportesA.php";
  form.target = '_blank';
  
  // Añadir campos necesarios
  const accionInput = document.createElement('input');
  accionInput.type = 'hidden';
  accionInput.name = 'accion';
  accionInput.value = 'exportarLayoutAspirante';
  form.appendChild(accionInput);
  
  const idInput = document.createElement('input');
  idInput.type = 'hidden';
  idInput.name = 'idAspirante';
  idInput.value = idNumerico.toString(); // Asegurar que enviamos un valor limpio
  form.appendChild(idInput);
  
  // Añadir al body y enviar
  document.body.appendChild(form);
  form.submit();
  document.body.removeChild(form);
}

function crearControlesDeColumnas(columnas, tabla) {
  $('#colToggleContainer').remove();
  $('.colToggleMenu').remove();

  const contenedor = $('<div id="colToggleContainer" style="display:inline-block; margin-left:10px;"></div>');
  const toggleBtn = $('<button type="button" class="btn btn-default btn-block" id="colToggleBtn">🛠️ Mostrar/ocultar columnas</button>');
  const menu = $('<div class="colToggleMenu" style="position:absolute; display:none; background:#fff; border:1px solid #ccc; padding:10px; z-index:9999;"></div>');

  columnas.forEach((col, i) => {
    const item = $(`<div><label><input type="checkbox" class="colToggle" data-index="${i}" checked> ${col}</label></div>`);
    menu.append(item);
  });

  contenedor.append(toggleBtn);
  $('#botoneraReportes .col-toggle-btn-container').remove();
  $('#botoneraReportes .row').append(
    $('<div class="col-xs-12 col-toggle-btn-container" style="margin-bottom:5px;">').append(toggleBtn)
  );

  $('body').append(menu);

  toggleBtn.on('click', function () {
    const offset = $(this).offset();
    menu.css({
      top: offset.top + $(this).outerHeight() + 5,
      left: offset.left
    }).toggle();
  });

  $(document).on('click', function (e) {
    if (!$(e.target).closest('#colToggleBtn, .colToggleMenu').length) {
      menu.hide();
    }
  });

  menu.on('change', '.colToggle', function () {
    const column = tabla.column($(this).data('index'));
    column.visible(!column.visible());
  });
}
