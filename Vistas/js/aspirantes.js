

// ================== CÓDIGO POSTAL (autocompletado) ==================
document.addEventListener("DOMContentLoaded", function () {
  let inputCP = document.getElementById("codPostal");
  if (!inputCP) return;

  let listaSugerencias = document.createElement("ul");
  listaSugerencias.setAttribute("id", "lista-sugerencias");
  listaSugerencias.style.position = "absolute";
  listaSugerencias.style.border = "1px solid #ccc";
  listaSugerencias.style.backgroundColor = "#fff";
  listaSugerencias.style.width = inputCP.offsetWidth + "px";
  listaSugerencias.style.listStyle = "none";
  listaSugerencias.style.padding = "0";
  listaSugerencias.style.zIndex = "1000";
  listaSugerencias.style.display = "none";

  inputCP.parentNode.appendChild(listaSugerencias);

  inputCP.addEventListener("input", function () {
    let codigoPostal = inputCP.value.trim();
    if (codigoPostal.length < 5) {
      listaSugerencias.style.display = "none";
      return;
    }

    let xhr = new XMLHttpRequest();
    xhr.open("POST", baseUrl + "Ajax/aspirantesA.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
      if (xhr.status === 200) {
        let respuesta = JSON.parse(xhr.responseText);
        if (respuesta.success) {
          listaSugerencias.innerHTML = "";
          if (respuesta.data.length > 0) {
            respuesta.data.forEach(function (item) {
              let li = document.createElement("li");
              li.textContent = item.colBarrio;
              li.style.padding = "5px";
              li.style.cursor = "pointer";
              li.style.borderBottom = "1px solid #ddd";

              li.addEventListener("click", function () {
                document.getElementById("codPostal").value = item.codPostal;
                document.getElementById("estado").value = item.estado;
                document.getElementById("ciudadMun").value = item.ciudadMun;
                document.getElementById("colBarrio").value = item.colBarrio;
                listaSugerencias.style.display = "none";
              });

              listaSugerencias.appendChild(li);
            });
            listaSugerencias.style.display = "block";
          } else {
            listaSugerencias.style.display = "none";
          }
        }
      }
    };

    xhr.send("codPostal=" + codigoPostal);
  });

  document.addEventListener("click", function (e) {
    if (!inputCP.contains(e.target) && !listaSugerencias.contains(e.target)) {
      listaSugerencias.style.display = "none";
    }
  });
});


// ========================= FUNCIONES GENERALES =========================

// Ver CV
$(document).on("click", ".btnVerCv", function () {
  var idAspirante = $(this).data("id");
  $("#idActualizarCv").val(idAspirante);

  $.ajax({
    url: baseUrl + "Ajax/aspirantesA.php",
    method: "POST",
    data: { idCv: idAspirante },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta && respuesta.ruta) {
        $('#contenidoCV').html(`
          <embed src="${respuesta.ruta}?v=${new Date().getTime()}" type="application/pdf" width="100%" height="500px" />
        `);
        $('#modalCV').modal('show');
      } else {
        $('#contenidoCV').html("<p class='text-danger'>No se pudo cargar el CV.</p>");
        $('#modalCV').modal('show');
      }
    },
    error: function (xhr, status, error) {      
    }
  });
});

// Subir nuevo CV
$(document).on("submit", "#formActualizarCV", function (e) {
  e.preventDefault();
  var formData = new FormData(this);

  $('#contenidoCV').html(`
    <div class="text-center my-4">
      <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
      <p class="mt-2">Subiendo nuevo CV, por favor espera...</p>
    </div>
  `);

  $.ajax({
    url: baseUrl + "Ajax/aspirantesA.php",
    method: "POST",
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      if (respuesta && respuesta.ruta) {
        $('#contenidoCV').html(`
          <div class="alert alert-success" role="alert">✅ CV actualizado correctamente.</div>
          <embed src="${respuesta.ruta}?v=${new Date().getTime()}" type="application/pdf" width="100%" height="500px" />
        `);
        $("#nuevoCv").val("");
        setTimeout(() => $(".alert-success").fadeOut("slow", function () { $(this).remove(); }), 3000);
      } else {
        $('#contenidoCV').html(`<p class='text-danger'>❌ ${respuesta.error || "Respuesta inesperada del servidor."}</p>`);
      }
    },
    error: function (xhr, status, error) {
      $('#contenidoCV').html("<p class='text-danger'>❌ Error AJAX: " + error + "</p>");
    }
  });
});

// ================== BOTÓN GENERALES ==================
$(document).on("click", ".btnGenerales", function () {
  const id = $(this).data("id");

  $.ajax({
    url: baseUrl + "Ajax/aspirantesA.php",
    method: "POST",
    dataType: "json",
    data: {
      obtenerGenerales: true,
      idAspirante: id
    },
    success: function (respuesta) {
      if (!respuesta || respuesta.error) {
        alert("❌ No se pudo obtener la información del candidato.");
        return;
      }

      // Llenar campos existentes
      $("#idGenerales").val(respuesta.idAspirante);
      $("#nombreGenerales").val(respuesta.nombre);
      $("#apPaternoGenerales").val(respuesta.apPaterno);
      $("#apMaternoGenerales").val(respuesta.apMaterno);
      $("#fechaNacimientoGenerales").val(respuesta.fechaNacimiento);
      $("#codPostalGenerales").val(respuesta.codPostal);
      $("#estadoGenerales").val(respuesta.estado);
      $("#ciudadGenerales").val(respuesta.ciudadMun);
      $("#coloniaGenerales").val(respuesta.colBarrio);
      $("#calleNoGenerales").val(respuesta.calleNo);
      $("#telefonoCelGenerales").val(respuesta.telefonoCel);
      $("#correoGenerales").val(respuesta.correo);
      $("#fechaCapturaGenerales").val(respuesta.fechaCaptura);

      // ✅ Nuevos campos ocultos
$("#puestoGenerales").val(respuesta.puesto || "");
$("#nuevoGenerales").val(respuesta.nuevo || 0);

// ✅ Si nuevo == 1, deshabilita todos los campos del formulario
if (parseInt(respuesta.nuevo) === 1) {
  $("#formGenerales input").prop("readonly", true); // todos readonly
} else {
  // Restaurar comportamiento normal: solo estado, ciudad y colonia readonly
  $("#formGenerales input").prop("readonly", false);
  $("#estadoGenerales, #ciudadGenerales, #coloniaGenerales").prop("readonly", true);
}

// Mostrar modal
$("#modalGenerales").modal("show");

    },
    error: function () {
      alert("❌ Error en la petición AJAX al cargar datos del candidato.");
    }
  });
});


// ================== AUTOCOMPLETADO DEL CÓDIGO POSTAL (modal Generales) ==================
document.addEventListener("DOMContentLoaded", function () {
  const inputCP = document.getElementById("codPostalGenerales");
  if (!inputCP) return;

  const listaSugerencias = document.createElement("ul");
  listaSugerencias.setAttribute("id", "lista-sugerencias-cp");
  listaSugerencias.classList.add("list-group");
  listaSugerencias.style.position = "fixed";
  listaSugerencias.style.zIndex = "9999";
  listaSugerencias.style.width = "auto";
  listaSugerencias.style.minWidth = "250px";
  listaSugerencias.style.maxHeight = "300px";
  listaSugerencias.style.overflowY = "auto";
  listaSugerencias.style.display = "none";
  listaSugerencias.style.boxShadow = "0 4px 8px rgba(0, 0, 0, 0.1)";

  // Añadir al body en lugar de al contenedor del input para evitar recortes
  document.body.appendChild(listaSugerencias);

  // Función para posicionar la lista de sugerencias
  function posicionarListaSugerencias() {
    const rect = inputCP.getBoundingClientRect();
    listaSugerencias.style.top = rect.bottom + window.scrollY + "px";
    listaSugerencias.style.left = rect.left + window.scrollX + "px";
    listaSugerencias.style.width = rect.width + "px";
  }

  // Evento para posicionar la lista cuando se interactúa con el input
  inputCP.addEventListener("input", function () {
    const codigoPostal = inputCP.value.trim();
    if (codigoPostal.length < 5) {
      listaSugerencias.style.display = "none";
      return;
    }
    posicionarListaSugerencias();

    const xhr = new XMLHttpRequest();
    xhr.open("POST", baseUrl + "Ajax/aspirantesA.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
      if (xhr.status === 200) {
        
        try {
          const resultados = JSON.parse(xhr.responseText);
          listaSugerencias.innerHTML = "";

          if (resultados.success && resultados.data.length > 0) {
            resultados.data.forEach(function (r) {
              const item = document.createElement("li");
              item.classList.add("list-group-item", "list-group-item-action");
              item.textContent = `${r.colBarrio}, ${r.ciudadMun}, ${r.estado}`;
              item.style.cursor = "pointer";

              item.addEventListener("click", function () {
                document.getElementById("estadoGenerales").value = r.estado;
                document.getElementById("ciudadGenerales").value = r.ciudadMun;
                document.getElementById("coloniaGenerales").value = r.colBarrio;
                listaSugerencias.style.display = "none";
              });

              listaSugerencias.appendChild(item);
            });
            listaSugerencias.style.display = "block";
            posicionarListaSugerencias(); // Asegurar posicionamiento correcto fuera del modal
          } else {
            listaSugerencias.style.display = "none";
          }
        } catch (e) {
          console.error("❌ Error al convertir respuesta en JSON:", xhr.responseText);
        }
      }
    };

    xhr.send("codPostal=" + encodeURIComponent(codigoPostal));
  });

  // Ocultar sugerencias si se hace clic fuera
  document.addEventListener("click", function (e) {
    if (!listaSugerencias.contains(e.target) && e.target !== inputCP) {
      listaSugerencias.style.display = "none";
    }
  });
});


// ================== GUARDAR CAMBIOS DESDE MODAL GENERALES ==================
$("#formGenerales").on("submit", function (e) {
  e.preventDefault(); 

  const datos = {
    actualizarGenerales: true,
    id: $("#idGenerales").val(),
    nombre: $("#nombreGenerales").val(),
    apPaterno: $("#apPaternoGenerales").val(),
    apMaterno: $("#apMaternoGenerales").val(),
    fechaNacimiento: $("#fechaNacimientoGenerales").val(),
    codPostal: $("#codPostalGenerales").val(),
    estado: $("#estadoGenerales").val(),
    ciudadMun: $("#ciudadGenerales").val(),
    colBarrio: $("#coloniaGenerales").val(),
    calleNo: $("#calleNoGenerales").val(),
    telefonoCel: $("#telefonoCelGenerales").val(),
    correo: $("#correoGenerales").val(),
    fechaCaptura: $("#fechaCapturaGenerales").val(),

    // ✅ Nuevos campos ocultos
    puesto: $("#puestoGenerales").val(),
    nuevo: $("#nuevoGenerales").val()
  };
  
  $.ajax({
    url: baseUrl + "Ajax/aspirantesA.php",
    method: "POST",
    data: datos,
    success: function (respuesta) {    
      
      if (respuesta === "ok" || respuesta === '"ok"') {
        alert("✅ Información actualizada correctamente.");
        $("#modalGenerales").modal("hide");
        location.reload();
      } else {
        alert("❌ Error al actualizar la información.");        
      }
    },
    error: function () {
      alert("❌ Error en la petición AJAX.");
    }
  });
});

//=================== BOTON ELIMINAR ===============================
$(document).on("click", ".btnEliminarAspirante", function () {
  const id = $(this).data("id");
  if (confirm("¿Estás seguro de que deseas eliminar este aspirante?")) {
    window.location.href = "nuevos/" + id;
  }
});


// WhatsApp
$(document).on("click", ".btnChatWhatsapp", function () {
  const tel = String($(this).data("tel") || "").trim();
  const numero = tel.replace(/\D/g, '');

  $("#nombreAspiranteWA").text(`Número: ${tel}`);
  $("#mensajeWA").val("Hola, soy del área de reclutamiento de Grupo ASE, ¿podemos hablar?");

  $("#linkWhatsapp").off("click").on("click", function (e) {
    e.preventDefault();
    const mensaje = encodeURIComponent($("#mensajeWA").val().trim());
    const url = `https://wa.me/52${numero}?text=${mensaje}`;
    window.open(url, "_blank", `width=600,height=700,top=${(screen.height/2)-350},left=${(screen.width/2)-300}`);
    $("#modalWhatsapp").modal("hide");
  });

  $("#modalWhatsapp").modal("show");
});




// ========================= AUTORIZACIÓN =========================

// Declaración segura de variable global
if (typeof candidatoSeleccionado === "undefined") {
  var candidatoSeleccionado = null;
}

// Abrir el modal al hacer clic en el botón "Autorizar"
$(document).on("click", ".btnAutorizar", function () {
  const id = $(this).data("id");
  candidatoSeleccionado = id;

  // Solicita los datos del candidato y sus comentarios
  $.ajax({
    url: baseUrl + "Ajax/aspirantesA.php",
    method: "POST",
    data: {
      obtenerInfoCandidato: true,
      idAspirante: id
    },
    dataType: "json",
    success: function (respuesta) {      
      if (respuesta.success) {
        const nombre = `${respuesta.data.nombre} ${respuesta.data.apPaterno} ${respuesta.data.apMaterno}`;
        const puesto = respuesta.data.puesto || "Sin puesto asignado";
        $("#infoCandidato").html(`<strong>${nombre}</strong><br>${puesto}`);
        $("#comentarioNuevo").val("");
        $("#listaComentarios").empty();

        const estado = parseInt(respuesta.data.nuevo); // ← usamos el campo "nuevo"

        if (estado === 0) {
          $("#btnAutorizarCandidato").show().text("Autorizar");
          $("#btnRegresarCandidato").hide();
        } else if (estado === 1) {
          $("#btnAutorizarCandidato").show().text("Finalizar");
          $("#btnRegresarCandidato").show();
        } else {
          $("#btnAutorizarCandidato").hide();
          $("#btnRegresarCandidato").hide();
        }


        if (respuesta.comentarios.length > 0) {
          respuesta.comentarios.forEach(c => {
            $("#listaComentarios").append(`
              <li class="list-group-item">
                <strong>${c.nombre} ${c.apellidos}</strong><br>
                <small class="text-muted">${c.fechaCaptura}</small><br>
                ${c.comentario}
              </li>
            `);
          });
        } else {
          $("#listaComentarios").append(`<li class="list-group-item text-muted">Sin observaciones previas.</li>`);
        }

        $("#modalAutorizar").modal("show");
      } else {
        alert("❌ No se pudo obtener la información del candidato.");
      }
    },
    error: function () {
      alert("❌ Error al obtener datos del candidato.");
    }
  });
});

// Guardar nuevo comentario
$("#btnAgregarComentario").off("click").on("click", function () {
  const comentario = $("#comentarioNuevo").val().trim();

  if (!comentario) {
    alert("⚠️ Ingresa un comentario antes de guardar.");
    return;
  }

  $.ajax({
    url: baseUrl + "Ajax/aspirantesA.php",
    method: "POST",
    data: {
      agregarComentario: true,
      idAspirante: candidatoSeleccionado,
      comentario: comentario
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta.success) {
        $("#comentarioNuevo").val("");
        $("#listaComentarios").prepend(`
          <li class="list-group-item">
            <strong>Tú</strong><br>
            <small class="text-muted">${respuesta.fecha}</small><br>
            ${comentario}
          </li>
        `);
      } else {
        alert("❌ No se pudo guardar el comentario.");
      }
    },
    error: function () {
      alert("❌ Error al guardar comentario.");
    }
  });
});


// Autorizar Candidato
$("#btnAutorizarCandidato").off("click").on("click", function () {
  let nuevoEstado = 1; // por defecto: autorizar inicial

  if ($(this).text().toLowerCase().includes("finalizar")) {
    nuevoEstado = 2; // si el botón dice "Finalizar", pasa a estado 2
  }

  $.ajax({
    url: baseUrl + "Ajax/aspirantesA.php",
    method: "POST",
    data: {
      actualizarEstadoCandidato: true,
      idAspirante: candidatoSeleccionado,
      nuevo: nuevoEstado
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta.success) {
        alert("✅ Estado actualizado correctamente.");
        $("#modalAutorizar").modal("hide");
        $(`.btnAutorizar[data-id='${candidatoSeleccionado}']`).closest("tr").fadeOut();
        // ✅ NUEVO: Actualiza notificado
        actualizarNotificado(candidatoSeleccionado);
      } else {
        alert("❌ No se pudo actualizar el estado.");
      }
    },
    error: function () {
      alert("❌ Error al autorizar candidato.");
    }
  });
});

$("#btnRegresarCandidato").off("click").on("click", function () {
  $.ajax({
    url: baseUrl + "Ajax/aspirantesA.php",
    method: "POST",
    data: {
      actualizarEstadoCandidato: true,
      idAspirante: candidatoSeleccionado,
      nuevo: 0
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta.success) {
        alert("🔄 Candidato regresado a nuevo.");
        $("#modalAutorizar").modal("hide");
        $(`.btnAutorizar[data-id='${candidatoSeleccionado}']`).closest("tr").fadeOut();
        // ✅ NUEVO: Actualiza notificado
         actualizarNotificado(candidatoSeleccionado);
      } else {
        alert("❌ No se pudo regresar el estado.");
      }
    },
    error: function () {
      alert("❌ Error al intentar regresar al estado 0.");
    }
  });
});

// Al dar clic en el botón de eliminar
$(document).on("click", ".btnEliminarCandidato", function () {
  aspiranteEliminarID = $(this).data("id"); 
  const nuevo = $(this).data("nuevo");

  $("#idAspiranteEliminar").val(aspiranteEliminarID);
  $("#comentarioEliminar").val("");

  let claveStatusFiltro = (nuevo == 2) ? 2 : 1;

  $.ajax({
    url: baseUrl + "Ajax/aspirantesA.php",
    method: "POST",
    data: { obtenerEstatus: true, claveStatus: claveStatusFiltro },
    dataType: "json",
    success: function (estatus) {
      const $select = $("#estatusEliminar");
      $select.empty().append('<option value="">Seleccione</option>');
      estatus.forEach(function (item) {
        $select.append(`<option value="${item.idestatus}">${item.estatus}</option>`);
      });

      $("#modalEliminarCandidato").modal("show");
    },
    error: function (xhr) {
      console.error("❌ Error cargando estatus:", xhr.responseText);
      alert("Error al cargar estatus.");
    }
  });
});



// Al dar clic en "Aceptar" dentro de la modal
// Al dar clic en "Aceptar" dentro de la modal
$("#confirmarEliminar").on("click", function () {
  const comentario = $("#comentarioEliminar").val().trim();
  const idEstatus = $("#estatusEliminar").val();

  if (!comentario || !idEstatus) {
    alert("⚠️ Por favor llena comentario y estatus.");
    return;
  }

  $.ajax({
    url: baseUrl + "Ajax/aspirantesA.php",
    method: "POST",
    data: {
      eliminarCandidato: true,
      idAspirante: aspiranteEliminarID,
      comentario: comentario,
      idEstatus: idEstatus
    },
    dataType: "text",
    success: function (respuesta) {
      if (respuesta.trim() === "ok") {
        $("#modalEliminarCandidato").modal("hide");
        alert("✅ Candidato eliminado correctamente.");

        // ✅ NUEVO: Actualiza notificado a 0
        actualizarNotificado(aspiranteEliminarID);

        // ⚠️ Retardamos el reload para asegurar que el AJAX anterior se ejecute
        setTimeout(() => {
          location.reload();
        }, 500);
      } else {
        alert("❌ Error al eliminar: " + respuesta);
      }
    },
    error: function (xhr, status, error) {
      console.error("❌ Error AJAX:", error);
      alert("Error al conectar con el servidor.");
    }
  });
});


// ================== AUTOCOMPLETAR POR CLAVE DE VACANTE ==================
document.addEventListener("DOMContentLoaded", function () {
  const inputClave = document.getElementById("buscarClaveVacante");
  const inputIdVacante = document.getElementById("puestoGenerales");

  if (!inputClave || !inputIdVacante) return;

  const listaClaves = document.createElement("ul");
  listaClaves.classList.add("list-group");
  listaClaves.style.position = "absolute";
  listaClaves.style.zIndex = "1000";
  listaClaves.style.width = "100%";
  listaClaves.style.display = "none";
  inputClave.parentNode.appendChild(listaClaves);

  inputClave.addEventListener("input", function () {
    const clave = this.value.trim();
    if (clave.length < 2) {
      listaClaves.style.display = "none";
      return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open("POST", baseUrl + "Ajax/aspirantesA.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
      if (xhr.status === 200) {
        try {
          const res = JSON.parse(xhr.responseText);
          listaClaves.innerHTML = "";

          if (res.success && res.data.length > 0) {
            res.data.forEach(item => {
              const li = document.createElement("li");
              li.className = "list-group-item list-group-item-action";
              li.textContent = item.clave;
              li.style.cursor = "pointer";
              li.addEventListener("click", function () {
                inputClave.value = item.clave;
                inputIdVacante.value = item.idVacante;
                listaClaves.style.display = "none";
              });
              listaClaves.appendChild(li);
            });
            listaClaves.style.display = "block";
          } else {
            listaClaves.style.display = "none";
          }
        } catch (e) {
          console.error("❌ Error parseando respuesta:", xhr.responseText);
        }
      }
    };
    xhr.send("buscarClaveVacante=" + encodeURIComponent(clave));
  });

  document.addEventListener("click", function (e) {
    if (!listaClaves.contains(e.target) && e.target !== inputClave) {
      listaClaves.style.display = "none";
    }
  });
});


$(document).on("click", ".btnReasignarVacante", function () {
  const idAspirante = $(this).data("id");
  $("#idAspiranteReasignar").val(idAspirante);

  $.ajax({
    url: baseUrl + "Ajax/aspirantesA.php",
    method: "POST",
    dataType: "json",
    data: {
      obtenerDatosReasignar: true,
      idAspirante: idAspirante
    },
    success: function (resp) {
  if (resp.success) {
    const a = resp.aspirante;
    const v = resp.vacante;

    // Candidato
    $("#infoNombre").text("Nombre: " + a.nombre);
    $("#infoApPaterno").text("Paterno: " + a.apPaterno);
    $("#infoApMaterno").text("Materno: " + a.apMaterno);
    $("#infoCP").text("CP: " + a.codPostal);
    $("#infoEstado").text("Estado: " + a.estado);
    $("#infoMunicipio").text("Municipio: " + a.ciudadMun);

    // Vacante (con validación)
    if (v) {
      $("#infoVacID").text("ID: " + v.id);
      $("#infoClave").text("Clave: " + v.clave);
      $("#infoTienda").text("Tienda: " + v.tienda);
      $("#infoVacCP").text("CP: " + v.cp);
      $("#infoVacEstado").text("Estado: " + v.edo);
      $("#infoVacMun").text("Municipio: " + v.mun);
    } else {
      $("#infoVacID").text("ID: SIN ASIGNAR");
      $("#infoClave").text("Clave: SIN ASIGNAR");
      $("#infoTienda").text("Tienda: SIN ASIGNAR");
      $("#infoVacCP").text("CP: -");
      $("#infoVacEstado").text("Estado: -");
      $("#infoVacMun").text("Municipio: -");
    }

    $("#modalReasignarVacante").modal("show");
  }
},
    error: function (xhr) {
      alert("❌ Error al obtener datos del candidato.");
      console.error(xhr.responseText);
    }
  });
});

// ================= BUSCADOR DE VACANTES =================
$("#filtroVacante").on("input", function () {
  const filtro = $(this).val().trim();
  $.ajax({
    url: baseUrl + "Ajax/aspirantesA.php",
    method: "POST",
    dataType: "json",
    data: { buscarVacantesDisponibles: true, filtro: filtro },
    success: function (resp) {
      const tbody = $("#tablaVacantes tbody");
      tbody.empty();

      if (resp.success) {
        resp.data.forEach(v => {
          const fila = `<tr data-id="${v.idVacante}">
            <td>${v.clave}</td>
            <td>${v.tienda}</td>
            <td>${v.cp}</td>
            <td>${v.edo}</td>
          </tr>`;
          tbody.append(fila);
        });
      }
    }
  });
});

// ================= SELECCIONAR VACANTE =================
$(document).on("click", "#tablaVacantes tbody tr", function () {
  const idVacante = $(this).data("id");
  const clave = $(this).children("td").eq(0).text();

  // ✅ Guardamos el ID de la vacante
  $("#idVacanteSeleccionada").val(idVacante);

  // ✅ Colocamos la clave en el input del filtro
  $("#filtroVacante").val(clave);

  // ✅ Destacamos la fila seleccionada
  $("#tablaVacantes tbody tr").removeClass("table-primary");
  $(this).addClass("table-primary");
});


// ================= ACTUALIZAR VACANTE =================
$("#btnActualizarVacante").on("click", function () {
  const idAspirante = $("#idAspiranteReasignar").val();
  const idVacante = $("#idVacanteSeleccionada").val();
  if (!idVacante) return alert("⚠️ Selecciona una vacante antes de continuar.");

  $.ajax({
    url: baseUrl + "Ajax/aspirantesA.php",
    method: "POST",
    dataType: "json",
    data: {
      actualizarVacanteCandidato: true,
      idAspirante: idAspirante,
      idVacante: idVacante
    },
    success: function (resp) {
      if (resp.success) {
        alert("✅ Vacante actualizada correctamente.");
        $("#modalReasignarVacante").modal("hide");
        location.reload();
      } else {
        alert("❌ No se pudo actualizar la vacante.");
      }
    },
    error: function () {
      alert("❌ Error en la petición AJAX.");
    }
  });
});

function actualizarNotificado(idAspirante) {
  $.ajax({
    url: baseUrl + "Ajax/aspirantesA.php",
    method: "POST",
    data: {
      actualizarNotificado: true,
      idAspirante: idAspirante
    },
    success: function (resp) {
      
    },
    error: function () {
      console.warn("⚠️ Error al actualizar notificado");
    }
  });
}

function cargarProcedencias() {
  $.ajax({
    url: "Ajax/aspirantesA.php",
    method: "POST",
    data: { cargarProcedencias: true },
    dataType: "json",
    success: function (respuesta) {
      const $combo = $("#idProcedenciaFk");
      $combo.empty().append('<option value="">Seleccione una opción</option>');

      const yaAgregados = new Set();

      respuesta.forEach(function (item) {
        if (!yaAgregados.has(item.idCatalogo)) {
          $combo.append(`<option value="${item.idCatalogo}">${item.valor}</option>`);
          yaAgregados.add(item.idCatalogo);
        }
      });
    }
  });
}

// Cargar al iniciar
$(document).ready(function () {
  cargarProcedencias();
});
