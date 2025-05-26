$(document).on("click", ".btnGenerarContrato", function () {
  const idAspirante = $(this).data("id");

  $.ajax({
    url: "Ajax/contratacionA.php",
    method: "POST",
    data: { idAspirante: idAspirante },
    dataType: "json",
    success: function (respuesta) {
      

      cargarComboCatalogo(2, 'select[name="genero"]');
      cargarComboCatalogo(1, 'select[name="edoCivil"]');

      // Llenar el bloque superior con datos del aspirante
      const html = `
        <div class="row">
          <div class="col-md-4"><strong>Nombre:</strong> ${respuesta.nombre} ${respuesta.apPaterno} ${respuesta.apMaterno}</div>
          <div class="col-md-4"><strong>Edad:</strong> ${respuesta.edad} años</div>
          <div class="col-md-4"><strong>Teléfono:</strong> ${respuesta.telefonoCel}</div>
        </div>
        <div class="row mt-2">
          <div class="col-md-4"><strong>Código Postal:</strong> ${respuesta.codPostal}</div>
          <div class="col-md-4"><strong>Estado:</strong> ${respuesta.estado}</div>
          <div class="col-md-4"><strong>Municipio:</strong> ${respuesta.ciudadMun}</div>
        </div>
        <div class="row mt-2">
          <div class="col-md-4"><strong>Colonia:</strong> ${respuesta.colBarrio}</div>
          <div class="col-md-4"><strong>Calle y Número:</strong> ${respuesta.calleNo}</div>
          <div class="col-md-4"><strong>Puesto Solicitado:</strong> ${respuesta.puesto}</div>
        </div>

         <hr>
  <h5><strong>Vacante Asignada</strong></h5>
  <div class="row">
    <div class="col-md-4"><strong>Clave:</strong> ${respuesta.clave}</div>
    <div class="col-md-4"><strong>Tienda:</strong> ${respuesta.tienda}</div>
    <div class="col-md-4"><strong>CP:</strong> ${respuesta.vacCP}</div>
  </div>
  <div class="row mt-2">
    <div class="col-md-4"><strong>Estado:</strong> ${respuesta.edo}</div>
    <div class="col-md-4"><strong>Municipio:</strong> ${respuesta.mun}</div>
    <div class="col-md-4"><strong>Colonia:</strong> ${respuesta.col}</div>
  </div>
  <div class="row mt-2">
    <div class="col-md-4"><strong>Responsable:</strong> ${respuesta.responsable}</div>
  </div>       
      `;

      $("#infoAspirante").html(html);
      $("#idAspirante").val(idAspirante);

      // 🟰 NUEVO: consultar si ya hay contratación
      $.ajax({
        url: "Ajax/contratacionA.php",
        method: "POST",
        data: { consultarContratacion: true, idAspirante: idAspirante },
        dataType: "json",
        success: function (datos) {
          if (datos) {
            
            // Llenar los inputs con datos existentes
            $('select[name="genero"]').val(datos.genero);
            $('input[name="curpAsp"]').val(datos.curpAsp);
            $('input[name="rfcAsp"]').val(datos.rfcAsp);
            $('input[name="nss"]').val(datos.nss);
            $('select[name="edoCivil"]').val(datos.edoCivil);
            $('input[name="contEmergencia"]').val(datos.contEmergencia);
            $('input[name="numEmergencia"]').val(datos.numEmergencia);
          } else {
            
            $("#formContratacion")[0].reset(); // Limpia el formulario
          }

          // Finalmente abre el modal ya con datos listos
          $("#modalContratacion").modal("show");
        },
        error: function (xhr) {
         
        }
      });

    },
    error: function (xhr, status, error) {
     
    }
  });
});



function cargarComboCatalogo(grupo, selector) {
  $.ajax({
    url: "Ajax/contratacionA.php",
    method: "POST",
    data: { grupo: grupo },
    dataType: "json",
    success: function (datos) {
      const $combo = $(selector);
      $combo.empty().append('<option value="">Seleccione</option>');
      datos.forEach(function (item) {
        $combo.append(`<option value="${item.idCatalogo}">${item.valor}</option>`);
      });
    },
    error: function (xhr) {
      
    }
  });
}


$(document).on("submit", "#formContratacion", function (e) {
  e.preventDefault();

  const idAspirante = $("#idAspirante").val();
  const genero = $('select[name="genero"]').val();
  const curpAsp = $('input[name="curpAsp"]').val();
  const rfcAsp = $('input[name="rfcAsp"]').val();
  const nss = $('input[name="nss"]').val();
  const edoCivil = $('select[name="edoCivil"]').val(); 
  const contEmergencia = $('input[name="contEmergencia"]').val();
  const numEmergencia = $('input[name="numEmergencia"]').val(); // O el tipo de input que uses para puesto

  $.ajax({
    url: "Ajax/contratacionA.php",
    method: "POST",
    data: {
      guardarActualizarContratacion: true,
      idAspirante: idAspirante,
      genero: genero,
      curpAsp: curpAsp,
      rfcAsp: rfcAsp,
      nss: nss,
      edoCivil: edoCivil,
      contEmergencia: contEmergencia,
      numEmergencia: numEmergencia
     
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta == "ok") {
        alert("✅ Información de contratación guardada correctamente.");
        $("#modalContratacion").modal("hide");
        location.reload();
      } else {
        alert("❌ Error al guardar contratación.");
      }
    },
    error: function (xhr) {
  
  alert("❌ Error de conexión.");
}
  });
});



$(document).on("click", ".btnSuprimirCandidato", function () {
  const id = $(this).data("id");

  Swal.fire({
    title: "¿Eliminar Aspirante?",
    text: "Esta acción eliminará toda la información relacionada.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Sí, eliminar"
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "Ajax/contratacionA.php",
        method: "POST",
        data: { eliminarAspirante: true, idAspirante: id },
        success: function (respuesta) {
          
          if (respuesta.trim() === "ok") {
            Swal.fire("✅ Eliminado", "El aspirante ha sido eliminado.", "success").then(() => {
              location.reload(); // o podrías recargar la tabla si quieres
            });
          } else {
            Swal.fire("❌ Error", respuesta, "error");
          }
        },
        error: function (xhr) {
          
          Swal.fire("❌ Error AJAX", `<pre>${xhr.responseText}</pre>`, "error");
        }
      });
    }
  });
});

$(document).on("click", ".btnCargarDocs", function () {
  const id = $(this).data("id");
  cargarModalArchivos(id);
});

function cargarModalArchivos(id) {
  idConsultaSeleccionado = id;
  document.getElementById("formularioArchivos").reset();
  $("#idAspiranteArchivos").val(id);

  $.ajax({
    url: "Ajax/contratacionA.php",
    method: "POST",
    data: { consultarDocumentos: true, idAspirante: id },
    dataType: "json",
    success: function (respuesta) {
      

      if (!respuesta) {
  alert("⚠️ No se pueden cargar documentos si no se realiza el registro de contratación.");
  return;
}


      const mapeo = {
        "ac": "actaNac",
        "cp": "compDom",
        "sf": "sitFiscal",
        "in": "ine",
        "cb": "cuentaBanco",
        "dn": "docNss",
        "dc": "docCurp",
        "ci": "cartaInfonavit",
        "ft": "foto"
      };

      for (const input in mapeo) {
        const campoBD = mapeo[input];

        const chk = document.getElementById("chk_" + input);
        const link = document.getElementById("link_" + input);

        if (respuesta[campoBD] && respuesta[campoBD] !== "") {
          chk.checked = true;
          link.href = respuesta[campoBD];
          link.style.display = "inline";
        } else {
          chk.checked = false;
          link.href = "#";
          link.style.display = "none";
        }
      }

      // 🔍 Verificación para ocultar botones si ya está todo completo y estás en /contratos
        const totalEsperados = Object.keys(mapeo).length;
        let totalCargados = 0;

        for (const input in mapeo) {
          const campoBD = mapeo[input];
          if (respuesta[campoBD] && respuesta[campoBD] !== "") {
            totalCargados++;
          }
        }

        if (totalCargados === totalEsperados && window.location.pathname.includes("/contratos")) {
          // 1. Ocultar botones de acción
          document.getElementById("botonesAccion").style.display = "none";

          // 2. Mostrar aviso
          const aviso = document.createElement("div");
          aviso.className = "alert alert-success";
          aviso.style.marginTop = "30px";
          aviso.style.textAlign = "center";
          aviso.innerHTML = aviso.innerHTML = `
              ✅ Tu información ya está completa. Comunícate con el área correspondiente para continuar con el proceso.<br><br>
              <a href="ingresar" class="btn btn-danger btn-lg">
                Cerrar
              </a>
            `;


          const contenedor = document.querySelector(".box-body");
          if (!document.getElementById("mensajeCompleto")) {
            aviso.id = "mensajeCompleto";
            contenedor.appendChild(aviso);
          }

          // 3. Ocultar botón Finalizar Contrato en el modal
        setTimeout(() => {
          document.getElementById("btnFinalizarContrato").style.display = "none";
        }, 100);

        } else {
          // Asegurarse que el botón esté visible si NO estamos en contratos
          document.getElementById("btnFinalizarContrato").style.display = "inline-block";
        }


      $('#modalArchivos').modal('hide'); // por si estaba mal cerrado
      $("#modalArchivos").modal('show');
      validarCheckDocumentos(); // 🔁 Forzar validación al mostrar modal
    },
    error: function (xhr, status, error) {
      
      alert("Error al cargar los documentos.");
    }
  });
}


function guardarArchivos() {
  const formData = new FormData(document.getElementById("formularioArchivos"));
  formData.append("subirDocumentos", true); // Agregamos esta marca para que el backend lo distinga

  $.ajax({
    url: "Ajax/contratacionA.php",
    method: "POST",
    data: formData,
    contentType: false,
    processData: false,
    dataType: "json", // 🔥 MUY IMPORTANTE: indicar que esperamos JSON
    success: function (respuesta) {
      

      if (respuesta.trim() === "ok") {
        Swal.fire("✅ Documentos actualizados", "Los documentos fueron cargados correctamente.", "success");
        $("#modalArchivos").modal('hide'); // Cerrar modal
      } else {
        Swal.fire("❌ Error", `<pre>${respuesta}</pre>`, "error");
      }
    },
    error: function (xhr, status, error) {
      
      Swal.fire("❌ Error en la carga", `<pre>${xhr.responseText}</pre>`, "error");
    }
  });
}

$(document).on("click", ".btnDescargarDocs", function () {
  const idAspirante = $(this).data("id");
  window.location.href = "Ajax/contratacionA.php?accion=descargarDocumentos&idAspirante=" + idAspirante;
});
