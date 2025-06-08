<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

require_once "../Controladores/consultaCandidatoControlador.php";
require_once "../Modelos/consultaCandidatoModelo.php";

if (isset($_POST["consultaCandidato"]) && isset($_POST["idAspirante"])) {
    $idAspirante = intval($_POST["idAspirante"]);

    try {
        $aspirante = consultaCandidatoControlador::obtenerAspiranteControlador($idAspirante);
        $estatus = consultaCandidatoControlador::obtenerEstatusControlador($aspirante['idestatusFk']);
        $contratacion = consultaCandidatoControlador::obtenerContratacionControlador($idAspirante);

        echo json_encode([
            "success" => true,
            "aspirante" => $aspirante,
            "estatus" => $estatus,
            "contratacion" => $contratacion
        ]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }

    exit;
}

if (isset($_POST["reincorporarCandidato"]) && isset($_POST["idAspirante"])) {
    $id = $_POST["idAspirante"];
    $respuesta = consultaCandidatoControlador::reincorporarCandidatoControlador($id);
    echo json_encode(["success" => $respuesta ? true : false]);
    exit;
}

if (isset($_POST["finalizarContrato"])) {
    $idAspirante = $_POST["idAspirante"];
    $respuesta = consultaCandidatoControlador::ctrFinalizarContrato($idAspirante);
    echo json_encode(["success" => $respuesta === "ok"]);
    exit();
}

if (isset($_POST["eliminarCandidatoCompleto"]) && isset($_POST["idAspirante"])) {
    $id = intval($_POST["idAspirante"]);
    $resultado = consultaCandidatoControlador::eliminarAspiranteCompleto($id);
    echo json_encode(["success" => $resultado === "ok"]);
    exit;
}


if (isset($_POST["actualizarNotificado"]) && isset($_POST["idAspirante"])) {
    $id = intval($_POST["idAspirante"]);
    $respuesta = consultaCandidatoControlador::actualizarNotificadoControlador($id);
    echo json_encode(["success" => $respuesta === "ok"]);
    exit;
}

if (isset($_POST["validarCurpDuplicada"])) {
  $curp = $_POST["curp"];
  $existe = consultaCandidatoControlador::verificarCurpDuplicada($curp);
  echo json_encode(["duplicado" => $existe]);
  return;
}

if (isset($_POST["accion"])) {

    if ($_POST["accion"] == "verificarCURP") {
        $respuesta = consultaCandidatoControlador::verificarCurpControlador($_POST["curp"]);
        echo json_encode(["existe" => $respuesta]);
        return;
    }

    if ($_POST["accion"] == "actualizarAspirantePorCURP") {
    session_start(); // Asegura acceso a la sesión

    $resp = consultaCandidatoControlador::actualizarAspiranteCurpControlador($_POST["curp"], $_POST["idAspirante"]);

    $sesionActiva = isset($_SESSION["idPersona"]) ? true : false;

    echo json_encode([
        "ok" => $resp,
        "sesion" => $sesionActiva
    ]);
    return;
}

}
