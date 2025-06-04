<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Encabezado para que el navegador sepa que la respuesta es JSON
header('Content-Type: application/json');

// Cargar los controladores y modelos necesarios
require_once "../Controladores/aspiranteControlador.php";
require_once "../Modelos/aspiranteModelo.php";

try {

    if (isset($_POST["actualizarNotificado"])) {
        require_once "../Controladores/aspiranteControlador.php";
        $respuesta = aspiranteControlador::actualizarNotificado($_POST["idAspirante"]);
        echo json_encode(["success" => $respuesta === "ok"]);
        return;
    }

    // ✅ SUBIDA DE CV NUEVO
    if (isset($_POST["idActualizarCv"]) && isset($_FILES["nuevoCv"])) {
        $id = $_POST["idActualizarCv"];
        $archivo = $_FILES["nuevoCv"];

        if (!is_uploaded_file($archivo["tmp_name"])) {
            echo json_encode(["error" => "No se recibió un archivo válido."]);
            exit;
        }

        $nombreArchivo = "cv_" . date("Ymd_His") . "_" . rand(100, 999) . ".pdf";
        $rutaDestino = "../publico/cv/" . $nombreArchivo;

        if (move_uploaded_file($archivo["tmp_name"], $rutaDestino)) {
            $actualizar = aspiranteControlador::actualizarCvControlador($id, $nombreArchivo);
            if ($actualizar) {
                echo json_encode(["ruta" => "publico/cv/" . $nombreArchivo]);
            } else {
                echo json_encode(["error" => "No se pudo actualizar en base de datos."]);
            }
        } else {
            echo json_encode(["error" => "Error al mover el archivo."]);
        }

        exit;
    }

    // ✅ BÚSQUEDA POR CÓDIGO POSTAL
    if (isset($_POST["codPostal"]) && !isset($_POST["actualizarGenerales"])) {
        $codigoPostal = $_POST["codPostal"];
        if (empty($codigoPostal)) {
            throw new Exception("Código postal vacío.");
        }

        $resultado = aspiranteControlador::buscarCPControlador($codigoPostal);
        echo json_encode(["success" => true, "data" => $resultado]);
        exit;
    }

    // ✅ OBTENER CV
    if (isset($_POST["idCv"])) {
        $respuesta = aspiranteControlador::obtenerCvControlador($_POST["idCv"]);
        echo json_encode($respuesta);
        exit;
    }

    // ✅ OBTENER DATOS GENERALES
    if (isset($_POST["obtenerGenerales"])) {
    $id = $_POST["idAspirante"];
    $respuesta = aspiranteControlador::verAspiranteControlador("idAspirante", $id);
    echo json_encode($respuesta);
    return;
}

if (isset($_POST["actualizarGenerales"])) {    
    $datos = array(
        "id"            => $_POST["id"],
        "nombre"        => $_POST["nombre"],
        "apPaterno"     => $_POST["apPaterno"],
        "apMaterno"     => $_POST["apMaterno"],
        "fechaNacimiento"=> $_POST["fechaNacimiento"],
        "codPostal"     => $_POST["codPostal"],
        "estado"        => $_POST["estado"],
        "ciudadMun"     => $_POST["ciudadMun"],
        "colBarrio"     => $_POST["colBarrio"],
        "calleNo"       => $_POST["calleNo"],
        "telefonoCel"   => $_POST["telefonoCel"],
        "correo"        => $_POST["correo"],
        "fechaCaptura"  => $_POST["fechaCaptura"],
        "puesto"        => $_POST["puesto"],
        "nuevo"         => $_POST["nuevo"]
    );
  
    $respuesta = aspiranteControlador::actualizarGeneralesControlador($datos);
    echo ($respuesta === "ok") ? json_encode("ok") : json_encode(["success" => false, "error" => $respuesta]);

    return;
}



   // OBTENER INFO DEL CANDIDATO Y COMENTARIOS
if (isset($_POST["obtenerInfoCandidato"]) && isset($_POST["idAspirante"])) {
    $id = $_POST["idAspirante"];
    $datos = aspiranteControlador::obtenerDatosCandidatoControlador($id);
    $comentarios = aspiranteControlador::obtenerComentariosControlador($id);
    echo json_encode([
        "success" => true,
        "data" => $datos,
        "comentarios" => $comentarios
    ]);
    exit;
}

// AGREGAR COMENTARIO
if (isset($_POST["agregarComentario"]) && isset($_POST["idAspirante"]) && isset($_POST["comentario"])) {
    session_start();
    $idAspirante = $_POST["idAspirante"];
    $comentario = $_POST["comentario"];
    $idPersona = $_SESSION["id"] ?? null;

    if (!$idPersona) {
        echo json_encode(["success" => false, "error" => "Usuario no autenticado."]);
        exit;
    }

    $exito = aspiranteControlador::agregarComentarioControlador($idAspirante, $comentario, $idPersona);
    echo json_encode([
        "success" => $exito,
        "fecha" => date("Y-m-d H:i:s")
    ]);
    exit;
}

// AUTORIZAR CANDIDATO
if (isset($_POST["autorizarCandidato"]) && isset($_POST["idAspirante"])) {
    $id = $_POST["idAspirante"];
    $respuesta = aspiranteControlador::autorizarCandidatoControlador($id);
    echo json_encode(["success" => $respuesta]);
    exit;
}

// ✅ ACTUALIZAR ESTADO (nuevo = 0, 1, 2)
if (isset($_POST["actualizarEstadoCandidato"]) && isset($_POST["idAspirante"]) && isset($_POST["nuevo"])) {
    $id = $_POST["idAspirante"];
    $nuevo = $_POST["nuevo"];

    $respuesta = aspiranteControlador::actualizarEstadoCandidatoControlador($id, $nuevo);

    echo json_encode(["success" => $respuesta]);
    exit;
}

if (isset($_POST["obtenerEstatus"])) {
    $claveStatus = $_POST["claveStatus"];
    $respuesta = aspiranteControlador::obtenerEstatusClave($claveStatus);
    echo json_encode($respuesta);
    return;
}

if (isset($_POST["eliminarCandidato"])) {
    $idAspirante = $_POST["idAspirante"];
    $comentario = $_POST["comentario"];
    $idEstatus = $_POST["idEstatus"];
   
    $guardado = aspiranteControlador::guardarStandbyYActualizarAspirante($idAspirante, $comentario, $idEstatus);

    if ($guardado === "ok") {
        echo "ok";
    } else {
        echo "error";
    }
    exit;
}

if (isset($_POST["buscarClaveVacante"])) {
  $respuesta = aspiranteControlador::ctrBuscarClaveVacante($_POST["buscarClaveVacante"]);
  echo json_encode(["success" => true, "data" => $respuesta]);
  return;
}

// 🔹 Obtener datos de aspirante y su vacante actual
if (isset($_POST["obtenerDatosReasignar"])) {
    $id = $_POST["idAspirante"];
    $aspirante = aspiranteControlador::verAspiranteControlador("idAspirante", $id);

    if ($aspirante && !empty($aspirante["puesto"])) {
        $vacante = aspiranteControlador::obtenerVacanteControlador($aspirante["puesto"]);
    } else {
        $vacante = null;
    }

    echo json_encode([
        "success" => true,
        "aspirante" => $aspirante,
        "vacante" => $vacante
    ]);
    exit;
}

// 🔹 Buscar vacantes disponibles (por clave o cp) 
if (isset($_POST["buscarVacantesDisponibles"])) {
    $filtro = $_POST["filtro"];
    $vacantes = aspiranteControlador::filtrarVacantesControlador($filtro);
    echo json_encode(["success" => true, "data" => $vacantes]);
    exit;
}

// 🔹 Actualizar vacante del candidato
if (isset($_POST["actualizarVacanteCandidato"])) {
    $idAspirante = $_POST["idAspirante"];
    $idVacante = $_POST["idVacante"];
    $resultado = aspiranteControlador::actualizarVacanteControlador($idAspirante, $idVacante);
    echo json_encode(["success" => $resultado]);
    exit;
}

if (isset($_POST["cargarProcedencias"])) {
  require_once "../Controladores/aspiranteControlador.php";
  $respuesta = aspiranteControlador::ctrCargarProcedencias();
  echo json_encode($respuesta);
  exit;
}



} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
    exit;
}
