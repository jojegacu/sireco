<?php
require_once "../Controladores/vacanteControlador.php";
require_once "../Modelos/vacanteModelo.php";

// Debug temporal
//file_put_contents("log_vacante.txt", json_encode($_POST));


if (isset($_POST["cargarCombos"])) {
    $regiones = vacanteControlador::ctrObtenerRegiones();
    $puestos  = vacanteControlador::ctrObtenerPuestos();
    echo json_encode([
        "regiones" => $regiones,
        "puestos"  => $puestos
    ]);
}

if (isset($_POST["registrarVacante"])) {
  $datos = array(
    "id"            => $_POST["id"],
    "idRegionFk"    => $_POST["idRegionFk"],
    "tienda"        => $_POST["tienda"],
    "responsable"   => $_POST["responsable"],
    "codPostal"     => $_POST["codPostal"],
    "estado"        => $_POST["estado"] ?? null,
    "ciudadMun"     => $_POST["ciudadMun"] ?? null,
    "colBarrio"     => $_POST["colBarrio"] ?? null,
    "idCatalogoFk"  => $_POST["idCatalogoFk"],
    "fechaCap"      => $_POST["fechaCap"],
    "clave"         => $_POST["clave"] ?? 0
  );

  $respuesta = vacanteControlador::ctrRegistrarVacante($datos);
  echo json_encode(["success" => $respuesta === "ok"]);
  return;
}

if (isset($_POST["verificarClave"])) {
  $existe = vacanteControlador::ctrVerificarClave($_POST["clave"]);
  echo json_encode(["disponible" => !$existe]);
  return;
}

if (isset($_POST["eliminarVacante"])) {
  $respuesta = vacanteControlador::ctrEliminarVacante($_POST["id"]);
  echo $respuesta;
  return;
}

// Obtener datos de una vacante por ID
if (isset($_POST["obtenerVacante"])) {
    if (!isset($_POST["idVacante"])) {
        echo json_encode(["success" => false, "mensaje" => "Falta el ID de vacante"]);
        return;
    }

    $vacante = vacanteControlador::ctrObtenerVacantePorId($_POST["idVacante"]);

    if ($vacante) {
        echo json_encode($vacante);
    } else {
        echo json_encode(["success" => false, "mensaje" => "Vacante no encontrada"]);
    }
    return;
}


// Actualizar vacante
if (isset($_POST["actualizarVacante"])) {
  $datos = array(
    "idVacante" => $_POST["idVacante"],
    "id"            => $_POST["id"], // ✅ ahora sí está definido correctamente
    "clave" => $_POST["clave"],
    "idRegionFk" => $_POST["idRegionFk"],
    "tienda" => $_POST["tienda"],
    "responsable" => $_POST["responsable"],
    "codPostal" => $_POST["codPostal"],
    "estado" => $_POST["estado"],
    "ciudadMun" => $_POST["ciudadMun"],
    "colBarrio" => $_POST["colBarrio"],
    "idCatalogoFk" => $_POST["idCatalogoFk"],
    "fechaCap" => $_POST["fechaCap"]
  );

  $respuesta = vacanteControlador::ctrActualizarVacante($datos);
  echo json_encode(["success" => $respuesta === "ok"]);
  return;
}

// Ruta para procesar archivo Excel
if (isset($_FILES["archivoExcel"])) {
    $respuesta = vacanteControlador::ctrProcesarExcel($_FILES["archivoExcel"]);
    echo json_encode($respuesta);
    return;
}

if (isset($_POST["eliminarPorCategoria"])) {
  $respuesta = vacanteControlador::ctrEliminarPorCategoria($_POST["categoria"]);
  echo $respuesta;
  return;
}


if (isset($_POST["accion"])) {
  switch ($_POST["accion"]) {
    
    case "listarVacantes":
      $vacantes = vacanteControlador::obtenerVacantesControlador();
      echo json_encode($vacantes);
      break;

    case "generarClave":
      if (!empty($_POST["idVacante"])) {
        $clave = vacanteControlador::generarClaveVacanteControlador($_POST["idVacante"]);
        echo json_encode(["clave" => $clave]);
      }
      break;

    default:
      echo json_encode(["error" => "Acción no reconocida"]);
  }
}
