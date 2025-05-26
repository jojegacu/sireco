

$(document).ready(function () {

  // Cargar combos al iniciar
  cargarCombos();

  function cargarCombos() {
    $.ajax({
      url: baseUrl + "/Ajax/vacanteA.php",
      method: "POST",
      data: { cargarCombos: true },
      dataType: "json",
      success: function (respuesta) {
         
        // Combo de regiones
        let opcionesRegion = '<option value="">Selecciona una región</option>';
        respuesta.regiones.forEach(function (region) {
          opcionesRegion += `<option value="${region.idRegion}">${region.region}</option>`;
        });
        $("#comboRegion").html(opcionesRegion);

        // Combo de puestos (vacantes)
        let opcionesPuesto = '<option value="">Selecciona un puesto</option>';
        respuesta.puestos.forEach(function (puesto) {
          opcionesPuesto += `<option value="${puesto.idCatalogo}">${puesto.valor}</option>`;
        });
        $("#comboPuesto").html(opcionesPuesto);
      },
      error: function (xhr, status, error) {
        
      }
    });
  }

});


  $("#formVacante").on("submit", function (e) {
  e.preventDefault();

  // Generar nueva clave antes de enviar
  verificarClaveUnica(() => {
    const datos = new FormData($("#formVacante")[0]);
    datos.append("registrarVacante", true);

    $.ajax({
      url: baseUrl + "/Ajax/vacanteA.php",
      method: "POST",
      data: datos,
      contentType: false,
      processData: false,
      dataType: "json",
      success: function (respuesta) {
        if (respuesta.success) {
          alert("✅ Vacante registrada correctamente.");
          $("#formVacante")[0].reset();
          verificarClaveUnica(); // Generar nueva clave para el siguiente uso
        } else {
          alert("❌ Ocurrió un error al registrar la vacante.");
        }
      },
      error: function (xhr, status, error) {
       
        alert("❌ Error al conectar con el servidor.");
      }
    });
  });
});


function generarClaveAleatoria() {
  const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
  let clave = "";
  for (let i = 0; i < 6; i++) {
    clave += chars.charAt(Math.floor(Math.random() * chars.length));
  }
  return clave;
}

function verificarClaveUnica(callback) {
  const nuevaClave = generarClaveAleatoria();

  $.ajax({
    url: baseUrl + "/Ajax/vacanteA.php",
    method: "POST",
    data: {
      verificarClave: true,
      clave: nuevaClave
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta.disponible) {
        $("#clave").val(nuevaClave);
        
        if (callback) callback();
      } else {
        verificarClaveUnica(callback); // intentar otra clave
      }
    },
    error: function () {
      alert("❌ Error al verificar clave única.");
    }
  });
}

// Ejecutar al cargar
verificarClaveUnica();

$(document).on("click", ".btnEliminarVacante", function () {
  const id = $(this).data("id");

  Swal.fire({
    title: "¿Estás seguro?",
    text: "Esta acción eliminará la vacante completamente.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar"
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: baseUrl + "/Ajax/vacanteA.php",
        method: "POST",
        data: { eliminarVacante: true, id: id },
        success: function (respuesta) {
          if (respuesta === "ok") {
            Swal.fire("Eliminado", "La vacante ha sido eliminada.", "success").then(() => {
              location.reload();
            });
          } else {
            Swal.fire("Error", "No se pudo eliminar la vacante.", "error");
          }
        },
        error: function () {
          Swal.fire("Error", "Fallo de comunicación con el servidor.", "error");
        }
      });
    }
  });
});


// Función para abrir modal y cargar datos
$(document).on("click", ".btnEditarVacante", function () {
  const id = $(this).data("id");

  $.ajax({
    url: baseUrl + "/Ajax/vacanteA.php",
    method: "POST",
    data: { obtenerVacante: true, id: id },
    dataType: "json",
    success: function (vacante) {
     
      // Rellenar combos primero
      cargarCombosEditar(() => {
        $("#edit_id").val(vacante.id);
        $("#edit_clave").val(vacante.clave);
        $("#edit_comboRegion").val(vacante.idRegionFk);
        $("#edit_tienda").val(vacante.tienda);
        $("#edit_responsable").val(vacante.responsable);
        $("#codPostalGenerales").val(vacante.cp);
        $("#estadoGenerales").val(vacante.edo);
        $("#ciudadGenerales").val(vacante.mun);
        $("#coloniaGenerales").val(vacante.col);
        $("#edit_fechaCap").val(vacante.fechaAlta);
        $("#edit_comboPuesto").val(vacante.idCatalogoFK);        
        $("#modalEditarVacante").modal("show");
      });
    },
    error: function () {
      alert("❌ Error al obtener los datos de la vacante.");
    }
  });
});

function cargarCombosEditar(callback) {
  $.ajax({
    url: baseUrl + "/Ajax/vacanteA.php",
    method: "POST",
    data: { cargarCombos: true },
    dataType: "json",
    success: function (res) {
      let opcionesRegion = '';
      res.regiones.forEach(r => {
        opcionesRegion += `<option value="${r.idRegion}">${r.region}</option>`;
      });
      $("#edit_comboRegion").html(opcionesRegion);

      let opcionesPuestos = '';
      res.puestos.forEach(p => {
        opcionesPuestos += `<option value="${p.idCatalogo}">${p.valor}</option>`;
      });
      $("#edit_comboPuesto").html(opcionesPuestos);

      if (callback) callback();
    },
    error: function () {
      alert("❌ Error al cargar combos en edición.");
    }
  });
}

// Actualizar vacante
$("#formEditarVacante").on("submit", function (e) {
  e.preventDefault();

  const datos = new FormData(this);
  datos.append("actualizarVacante", true);

  $.ajax({
    url: baseUrl + "/Ajax/vacanteA.php",
    method: "POST",
    data: datos,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (resp) {
      if (resp.success) {
        alert("✅ Vacante actualizada correctamente.");
        $("#modalEditarVacante").modal("hide");
        location.reload();
      } else {
        alert("❌ Error al actualizar vacante.");
      }
    },
    error: function () {
      alert("❌ Error de conexión.");
    }
  });
});

// Capturar envío del formulario Excel
$(document).on("submit", "#formCargaExcel", function (e) {
  e.preventDefault();

  const formData = new FormData(this);

  // Mostrar barra de progreso
  const barra = $(".progress");
  const barraInterna = $(".progress-bar");
  barra.show();
  barraInterna.css("width", "10%").text("Iniciando...");

  $.ajax({
    url: baseUrl + "Ajax/vacanteA.php",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    xhr: function () {
      const xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          const porcentaje = Math.round((evt.loaded / evt.total) * 100);
          barraInterna.css("width", porcentaje + "%").text(porcentaje + "%");
        }
      }, false);
      return xhr;
    },
    success: function (respuesta) {
      try {
        const res = JSON.parse(respuesta);
        if (res.success) {
          barraInterna
            .removeClass("bg-danger")
            .addClass("bg-success")
            .css("width", "100%")
            .text("Completado");
          $("#btnFinalizado").removeClass("d-none");
        } else {
          barraInterna
            .addClass("bg-danger")
            .css("width", "100%")
            .text("Error: " + res.mensaje);
        }
      } catch (error) {
        barraInterna
          .addClass("bg-danger")
          .css("width", "100%")
          .text("❌ Error interno");
        
      }
    },
    error: function () {
      barraInterna
        .addClass("bg-danger")
        .css("width", "100%")
        .text("❌ Error de conexión");
    }
  });
});

$(document).on("click", "#btnFinalizado", function () {
  window.location.href = baseUrl + "masiva";
});

$(document).on("submit", "#formEliminarCategoria", function (e) {
  e.preventDefault();
  const categoria = $(this).find("input[name='categoriaEliminar']").val();

  Swal.fire({
    title: "¿Estás seguro?",
    text: `Esto eliminará todas las vacantes con la categoría "${categoria}". Esta acción no se puede deshacer.`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar"
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: baseUrl + "/Ajax/vacanteA.php",
        method: "POST",
        data: {
          eliminarPorCategoria: true,
          categoria: categoria
        },
        success: function (respuesta) {
          if (respuesta === "ok") {
            Swal.fire("✅ Eliminado", "Se eliminaron las vacantes.", "success").then(() => {
              location.reload();
            });
          } else {
            Swal.fire("❌ Error", "No se pudieron eliminar los datos.", "error");
          }
        },
        error: function () {
          Swal.fire("❌ Error", "Error de conexión con el servidor.", "error");
        }
      });
    }
  });
});


let vacantes = [];
let vacantesPorPagina = 10;
let paginaActual = 1;

function renderizarTablaVacantes() {
  const inicio = (paginaActual - 1) * vacantesPorPagina;
  const fin = inicio + vacantesPorPagina;
  const vacantesPagina = vacantes.slice(inicio, fin);

  const tbody = $("#tablaVacantes tbody");
  tbody.empty();

  vacantesPagina.forEach(function (vacante) {
    let fila = `
      <tr data-id="${vacante.idVacante}" data-clave="${vacante.clave}" data-categoria="${vacante.categoria}">
        <td>${vacante.idVacante}</td>
        <td>${vacante.clave || ''}</td>
        <td>${vacante.categoria}</td>
        <td>${vacante.cp}</td>
        <td>${vacante.estado}</td>
        <td>${vacante.municipio}</td>
        <td>${vacante.colonia}</td>
        <td>${vacante.tienda}</td>
      </tr>`;
    tbody.append(fila);
  });

  generarPaginadorVacantes();
}

function generarPaginadorVacantes() {
  const totalPaginas = Math.ceil(vacantes.length / vacantesPorPagina);
  const paginador = $("#paginadorVacantes");
  paginador.empty();

  for (let i = 1; i <= totalPaginas; i++) {
    const btn = $("<button>")
      .addClass("btn btn-sm btn-default mx-1")
      .text(i)
      .on("click", function () {
        paginaActual = i;
        renderizarTablaVacantes();
      });

    if (i === paginaActual) btn.addClass("btn-primary");
    paginador.append(btn);
  }
}

$(document).on("click", "#btnBuscarPuesto", function () {
  $.ajax({
    url: baseUrl + "Ajax/vacanteA.php",
    method: "POST",
    data: { accion: "listarVacantes" },
    dataType: "json",
    success: function (respuesta) {
      vacantes = respuesta;
      paginaActual = 1;
      renderizarTablaVacantes();
    }
  });
});



$(document).on("keyup", "#filtroVacantes", function () {
  let filtro = $(this).val().toLowerCase();
  $("#tablaVacantes tbody tr").each(function () {
    let texto = $(this).text().toLowerCase();
    $(this).toggle(texto.includes(filtro));
  });
});


$(document).on("click", "#tablaVacantes tbody tr", function () {
  let fila = $(this);
  let idVacante = fila.data("id");
  let categoria = fila.data("categoria");
  let clave = fila.data("clave");

  // Si no tiene clave, generarla en el backend
  if (!clave) {
    $.ajax({
      url: baseUrl + "Ajax/vacanteA.php",
      method: "POST",
      data: {
        accion: "generarClave",
        idVacante: idVacante
      },
      dataType: "json",
      success: function (respuesta) {
        clave = respuesta.clave;
        mostrarAlertaSeleccion(idVacante, categoria, clave);
      }
    });
  } else {
    mostrarAlertaSeleccion(idVacante, categoria, clave);
  }
});

function mostrarAlertaSeleccion(id, categoria, clave) {
  Swal.fire({
    title: "Vacante seleccionada",
    html: `
      <p><strong>ID:</strong> ${id}</p>
      <p><strong>Categoría:</strong> ${categoria}</p>
      <p><strong>Clave:</strong> ${clave}</p>
    `,
    icon: "info",
    showCancelButton: true,
    confirmButtonText: "Aceptar",
    cancelButtonText: "Cancelar"
  }).then((result) => {
    if (result.isConfirmed) {
      $("#buscarClaveVacante").val(clave);
      $("#puestoGenerales").val(id);
      $("#modalVacantes").modal("hide");
    }
  });
}



