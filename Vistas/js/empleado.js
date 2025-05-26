$(document).on("click", ".btnEmpleado", function () {
  var idPersona = $(this).data("id");
  

  $.ajax({
    url: "Ajax/empleadoA.php",
    method: "POST",
    data: { validarEmpleado: true, idPersona: idPersona },
    dataType: "json",
    beforeSend: function () {
      
    },
    success: function (respuesta) {
      
      if (respuesta.existe) {
        window.location = "editarUsrEmp/" + idPersona;
      } else {
        Swal.fire({
          title: "Nuevo empleado",
          text: "Este empleado no existe. ¿Deseas crear uno?",
          icon: "info",
          showCancelButton: true,
          confirmButtonText: "Sí, registrar",
          cancelButtonText: "No"
        }).then((result) => {
          if (result.isConfirmed) {
            $("#modalNuevoEmpleado").modal("show");
            $("#idPersonaEmpleado").val(idPersona);
          }
        });
      }
    },
    error: function () {
      Swal.fire("Error", "No se pudo validar el empleado.", "error");
    }
  });
});


$("#formNuevoEmpleado").submit(function (e) {
  e.preventDefault();

  var datos = $(this).serialize();

  $.ajax({
    url: "Ajax/empleadoA.php",
    method: "POST",
    data: datos + "&crearEmpleado=true",
    success: function (respuesta) {
      Swal.fire({
        title: "Empleado registrado",
        text: "Se registró el empleado correctamente.",
        icon: "success"
      }).then(() => {
        $("#modalNuevoEmpleado").modal("hide");
      });
    },
    error: function () {
      Swal.fire("Error", "No se pudo registrar el empleado", "error");
    }
  });
});
