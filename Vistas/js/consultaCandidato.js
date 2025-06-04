

$(document).ready(function () {

  // Variable global para guardar el ID seleccionado
  let idConsultaSeleccionado = null;

  // Al hacer clic en el botón de consultar candidato
  $(document).on("click", ".btnConsultaCandidato", function () {
    idConsultaSeleccionado = $(this).data("id");

    // Llamada AJAX para obtener toda la información
    $.ajax({
      url: baseUrl + "Ajax/consultaCandidatoA.php",
      method: "POST",
      dataType: "json",
      data: {
        consultaCandidato: true,
        idAspirante: idConsultaSeleccionado
      },
      success: function (respuesta) {
         
        if (respuesta.success) {
          // 🔵 Bloque 1 - Aspirante
          $("#consulta_idAspirante").val(respuesta.aspirante.idAspirante);
          $("#consulta_nombre").val(respuesta.aspirante.nombre);
          $("#consulta_apPaterno").val(respuesta.aspirante.apPaterno);
          $("#consulta_apMaterno").val(respuesta.aspirante.apMaterno);
          $("#consulta_telefono").val(respuesta.aspirante.telefonoCel);
          $("#consulta_calleNo").val(respuesta.aspirante.calleNo);
          $("#consulta_colonia").val(respuesta.aspirante.colBarrio);
          $("#consulta_municipio").val(respuesta.aspirante.ciudadMun);
          $("#consulta_estado").val(respuesta.aspirante.estado);
          $("#consulta_cp").val(respuesta.aspirante.codPostal);
          $("#consulta_fechaNacimiento").val(respuesta.aspirante.fechaNacimiento);
          $("#consulta_puesto").val(respuesta.aspirante.puesto);

          // CV Checkbox
          if (respuesta.aspirante.cv) {
            $("#consulta_cv").prop("checked", true);
          } else {
            $("#consulta_cv").prop("checked", false);
          }

          // Concepto y Estatus
          $("#consulta_concepto").val(respuesta.estatus.concepto || "");
          $("#consulta_estatus").val(respuesta.estatus.estatus || "");

          // 🔵 Bloque 2 - Contratación
          if (respuesta.contratacion) {
            $("#consulta_genero").val(respuesta.contratacion.genero_desc || "");
            $("#consulta_curp").val(respuesta.contratacion.curpAsp || "");
            $("#consulta_rfc").val(respuesta.contratacion.rfcAsp || "");
            $("#consulta_nss").val(respuesta.contratacion.nss || "");
            $("#consulta_estadoCivil").val(respuesta.contratacion.edoCivil_desc || "");
            $("#consulta_telEmergencia").val(respuesta.contratacion.numEmergencia || "");
            $("#consulta_contactoEmergencia").val(respuesta.contratacion.contEmergencia || "");

            // Checkboxes de documentos
            marcarCheckbox("#consulta_actaNacimiento", respuesta.contratacion.actaNac);
            marcarCheckbox("#consulta_comprobanteDomicilio", respuesta.contratacion.compDom);
            marcarCheckbox("#consulta_situacionFiscal", respuesta.contratacion.sitFiscal);
            marcarCheckbox("#consulta_ine", respuesta.contratacion.ine);
            marcarCheckbox("#consulta_cuentaBanco", respuesta.contratacion.cuentaBanco);
            marcarCheckbox("#consulta_docNss", respuesta.contratacion.docNss);
            marcarCheckbox("#consulta_docCurp", respuesta.contratacion.docCurp);
            marcarCheckbox("#consulta_cartaInfonavit", respuesta.contratacion.cartaInfonavit);
            marcarCheckbox("#consulta_foto", respuesta.contratacion.foto);
          } else {
            limpiarBloqueContratacion();
          }


          // Mostrar modal
          $("#modalConsultaCandidato").modal("show");
        } else {
          alert("❌ No se pudo obtener la información del candidato.");
        }
      },
      error: function (xhr, status, error) {
        
        alert("Error en la consulta del candidato.");
      }
    });
  });

  // Función para marcar o desmarcar checkbox
  function marcarCheckbox(idInput, valor) {
    if (valor && valor.trim() !== "") {
      $(idInput).prop("checked", true);
    } else {
      $(idInput).prop("checked", false);
    }
  }

  // Función para limpiar campos de contratación si no hay info
  function limpiarBloqueContratacion() {
    $("#consulta_genero").val("");
    $("#consulta_curp").val("");
    $("#consulta_rfc").val("");
    $("#consulta_nss").val("");
    $("#consulta_estadoCivil").val("");
    $("#consulta_telEmergencia").val("");
    $("#consulta_contactoEmergencia").val("");

    const checks = [
      "#consulta_actaNacimiento",
      "#consulta_comprobanteDomicilio",
      "#consulta_situacionFiscal",
      "#consulta_ine",
      "#consulta_cuentaBanco",
      "#consulta_docNss",
      "#consulta_docCurp",
      "#consulta_cartaInfonavit",
      "#consulta_foto"
    ];
    checks.forEach(id => $(id).prop("checked", false));
  }

});


// Al hacer clic en el botón de reincorporar candidato
$(document).on("click", ".btnReincorporarCandidato", function () {
  const id = $(this).data("id");

  // Mostrar modal de confirmación
  $("#idReincorporarCandidato").val(id);
  $("#modalReincorporarCandidato").modal("show");
});

// Confirmar reincorporación
$("#btnConfirmarReincorporar").click(function () {
  const id = $("#idReincorporarCandidato").val();

  $.ajax({
    url: baseUrl + "Ajax/consultaCandidatoA.php",
    method: "POST",
    dataType: "json", // Asegura que jQuery espere un JSON
    data: {
      reincorporarCandidato: true,
      idAspirante: id
    },
    success: function (respuesta) {
      if (respuesta.success) {
         // ✅ NUEVO: Actualiza notificado a 0
        actualizarNotificado(id);
        $("#modalReincorporarCandidato").modal("hide");
        alert("✅ Candidato reincorporado con éxito.");
        // Ocultar o eliminar la fila correspondiente
        $('button[data-id="' + id + '"]').closest("tr").remove();
      } else {
        alert("❌ No se pudo reincorporar al candidato.");
      }
    },
    error: function () {
      alert("❌ Error AJAX.");
    }
  });
});

// Función para validar si todos los checkboxes están marcados
function validarCheckDocumentos() {
  const totalChecks = $(".check-doc").length;
  const marcados = $(".check-doc:checked").length;
  if (totalChecks > 0 && totalChecks === marcados) {
    $("#btnFinalizarContrato").show();
  } else {
    $("#btnFinalizarContrato").hide();
  }
}


// Ejecutar la validación inicial después de cargar los documentos
validarCheckDocumentos();

// Cada vez que se cambia un checkbox, volvemos a validar
$(document).on("change", ".check-doc", function () {
  validarCheckDocumentos();
});

$(document).on("click", "#btnFinalizarContrato", function () {
  if (!idConsultaSeleccionado) {
    alert("❌ No se ha seleccionado ningún aspirante.");
    return;
  }

  if (!confirm("¿Estás seguro de que deseas finalizar el contrato? Esto no se puede deshacer.")) return;

  $.ajax({
    url: baseUrl + "Ajax/consultaCandidatoA.php",
    method: "POST",
    data: {
      finalizarContrato: true,
      idAspirante: idConsultaSeleccionado
    },
    success: function (respuesta) {
      if (respuesta.success) {
        // ✅ NUEVO: Actualiza notificado a 0
        actualizarNotificado(idConsultaSeleccionado);
        alert("✅ Contrato finalizado correctamente.");
        $("#modalConsultaContratacion").modal("hide");
        location.reload();
      } else {
        alert("❌ Error al finalizar el contrato. Intenta nuevamente.");
        
      }
    },
    error: function (xhr, status, error) {
      
      alert("❌ Error al finalizar contrato.");
    }
  });
});


// Abrir modal al hacer clic en "Eliminar"
$(document).on("click", ".btnEliminarCandidato", function () {
  const id = $(this).data("id");
  $("#idEliminarCandidato").val(id);
  $("#modalEliminarCandidato").modal("show");
});

// Confirmar eliminación
$("#btnConfirmarEliminar").click(function () {
  const id = $("#idEliminarCandidato").val();

  $.ajax({
    url: baseUrl + "Ajax/consultaCandidatoA.php",
    method: "POST",
    data: {
      eliminarCandidatoCompleto: true,
      idAspirante: id
    },
    success: function (respuesta) {
      if (respuesta.success) {
        alert("✅ Candidato eliminado correctamente.");
        $("#modalEliminarCandidato").modal("hide");
        $('button[data-id="' + id + '"]').closest("tr").remove();
      } else {
        alert("❌ No se pudo eliminar. " + (respuesta.error || ""));
      }
    },
    error: function () {
      alert("❌ Error en la solicitud AJAX.");
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
      
    }
  });
}

$(document).ready(function () {
  $("#modalValidarCurp").modal("show");

  $("#btnValidarCurp").click(function () {
    const curp = $("#inputCurp").val().trim().toUpperCase();

    if (curp.length !== 18) {
      alert("⚠️ La CURP debe tener exactamente 18 caracteres.");
      return;
    }

    $.ajax({
      url: baseUrl + "Ajax/consultaCandidatoA.php",
      method: "POST",
      dataType: "json",
      data: {
        validarCurpDuplicada: true,
        curp: curp
      },
      success: function (resp) {
        if (resp.duplicado) {
          alert("⚠️ Tu CURP ya está registrada. Comunícate con el personal de Grupo ASE.");
          window.location.href = "ingresar";
        } else {
          $("#modalValidarCurp").modal("hide");
        }
      },
      error: function () {
        alert("❌ Error al validar CURP.");
      }
    });
  });
});

$(document).on("input", 'input[name="curpAsp"]', function () {
  const curp = $(this).val().trim().toUpperCase();

  if (curp.length === 18) {
    $.ajax({
      url: baseUrl + "Ajax/consultaCandidatoA.php",
      method: "POST",
      data: {
        accion: "verificarCURP",
        curp: curp
      },
      dataType: "json",
      success: function (respuesta) {
        if (respuesta.existe) {
          Swal.fire({
            icon: "warning",
            title: "CURP ya registrada",
            text: "Esta CURP ya está registrada en el sistema. Comunícate con el personal de Grupo ASE.",
            confirmButtonText: "Aceptar"
          }).then(() => {
            const idAspirante = $("#idAspirante").val();

            $.ajax({
              url: baseUrl + "Ajax/consultaCandidatoA.php",
              method: "POST",
              data: {
                accion: "actualizarAspirantePorCURP",
                curp: curp,
                idAspirante: idAspirante
              },
              dataType: "json",
              success: function (respuesta) {
                $("#modalContratacion").modal("hide");

                if (respuesta.sesion) {
                  window.location.href = "contratacion"; // Sesión activa
                } else {
                  window.location.href = "contratos"; // Pública
                }
              }
            });

            // Limpiar el campo CURP para evitar doble envío
            $('input[name="curpAsp"]').val("");
          });
        }
      }
    });
  }
});
