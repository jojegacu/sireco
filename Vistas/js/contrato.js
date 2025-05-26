$(document).on("click", "#btnBuscarCandidato", function () {
  const id = $("#buscarId").val().trim();

  if (id === "") {
    alert("Ingresa un ID válido.");
    return;
  }

  $.ajax({
    url: "Ajax/contratoA.php",
    method: "POST",
    data: { buscarCandidato: true, idAspirante: id },
    dataType: "json",
    success: function (res) {
      if (res.success) {
        // Mostrar datos aspirante
        $("#aspNombre").text(res.aspirante.nombre);
        $("#aspPaterno").text(res.aspirante.apPaterno);
        $("#aspMaterno").text(res.aspirante.apMaterno);
        $("#aspFecha").text(res.aspirante.fechaNacimiento);
        $("#aspTel").text(res.aspirante.telefonoCel);
        $("#aspDir").text(res.aspirante.direccion);

        // Mostrar datos vacante
        $("#vacClave").text(res.vacante.clave);
        $("#vacId").text(res.vacante.id);
        $("#vacTienda").text(res.vacante.tienda);
        $("#vacResponsable").text(res.vacante.responsable);
        $("#vacDir").text(res.vacante.direccion);
        $("#vacCp").text(res.vacante.codPostal);

        $("#datosAspirante, #datosVacante, #botonesAccion").fadeIn();
      } else {
        alert("❌ No se encontró el candidato.");
      }
    },
    error: function (xhr) {
     
    }
  });
});
