$(document).on("click", ".btnUsuario", function () {
  var idPersona = $(this).data("id");
  

  $.ajax({
    url: "Ajax/usuariosA.php",
    method: "POST",
    data: { validarUsuario: true, idPersona: idPersona },
    dataType: "json",
    beforeSend: function () {
     
    },
    success: function (respuesta) {
     
      if (respuesta.existe) {
        window.location = "editarUsrUs/" + idPersona;
      } else {
        Swal.fire({
          title: "Nuevo usuario",
          text: "Este usuario no existe. ¿Deseas crear uno?",
          icon: "info",
          showCancelButton: true,
          confirmButtonText: "Sí, registrar",
          cancelButtonText: "No"
        }).then((result) => {
          if (result.isConfirmed) {
            $("#modalNuevoUsuario").modal("show");
            $("#idPersonaNueva").val(idPersona);
          }
        });
      }
    },
    error: function (xhr, status, error) {
     
    }
  });
});


$("#formNuevoUsuario").submit(function (e) {
  e.preventDefault();

  var datos = $(this).serialize();

  $.ajax({
    url: "Ajax/usuariosA.php",
    method: "POST",
    data: datos + "&crearUsuario=true",
    success: function (respuesta) {
      Swal.fire({
        title: "Usuario registrado",
        text: "Se registró el usuario correctamente.",
        icon: "success"
      }).then(() => {
        $("#modalNuevoUsuario").modal("hide");
      });
    },
    error: function () {
      Swal.fire("Error", "No se pudo registrar el usuario", "error");
    }
  });
});
