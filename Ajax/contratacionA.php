<?php

require_once "../Controladores/contratacionControlador.php";
require_once "../Modelos/contratacionModelo.php";

if (isset($_POST["grupo"])) {
    $grupo = $_POST["grupo"];
    $respuesta = contratacionControlador::ctrObtenerCatalogoPorGrupo($grupo);
    echo json_encode($respuesta);
    exit();
}

if (isset($_POST["consultarDocumentos"])) {
    $idAspirante = $_POST["idAspirante"];
    $respuesta = contratacionControlador::ctrObtenerDocumentos($idAspirante);
    echo json_encode($respuesta);
    exit();
}

if (isset($_POST["consultarContratacion"])) {
    $idAspirante = $_POST["idAspirante"];
    $respuesta = contratacionControlador::ctrObtenerDatosContratacion($idAspirante);
    echo json_encode($respuesta);
    exit();
}

if (isset($_POST["guardarActualizarContratacion"])) {
    $datos = array(
        "idAspirante" => $_POST["idAspirante"],
        "genero" => $_POST["genero"],
        "curpAsp" => $_POST["curpAsp"],
        "rfcAsp" => $_POST["rfcAsp"],
        "nss" => $_POST["nss"],
        "edoCivil" => $_POST["edoCivil"],
        "contEmergencia" => $_POST["contEmergencia"],
        "numEmergencia" => $_POST["numEmergencia"],
    );
    $respuesta = contratacionControlador::guardarActualizarContratacion($datos);
    echo json_encode($respuesta);
    exit();
}

if (isset($_POST["idAspirante"]) && !isset($_POST["guardarContratacion"])) {
    $aspirante = contratacionControlador::ctrObtenerInfoAspirante($_POST["idAspirante"]);
    echo json_encode($aspirante);
    exit();
}

if (isset($_POST["actualizarContratacion"])) {
    $respuesta = contratacionControlador::ctrActualizarContratacion($_POST);
    echo json_encode($respuesta);
    exit();
}

if (isset($_POST["guardarContratacion"])) {
    $respuesta = contratacionControlador::ctrGuardarContratacion($_POST);
    echo json_encode($respuesta);
    exit();
}

if (isset($_POST["subirDocumentos"])) {
    $idAspirante = $_POST["idAspiranteArchivos"];
    $directorio = "../publico/documentos/" . $idAspirante . "/";

    if (!is_dir($directorio)) {
        mkdir($directorio, 0777, true);
    }

    $documentos = [
        "ac" => "actaNac",
        "cp" => "compDom",
        "sf" => "sitFiscal",
        "in" => "ine",
        "cb" => "cuentaBanco",
        "dn" => "docNss",
        "dc" => "docCurp",
        "ci" => "cartaInfonavit",
        "ft" => "foto",
        "fonacot" => "fonacot" // ✅ Nuevo campo
    ];

    $nuevosDatos = [];

    foreach ($documentos as $input => $campoBD) {
        if (isset($_FILES[$input]) && $_FILES[$input]["error"] == 0) {
            $extension = strtolower(pathinfo($_FILES[$input]["name"], PATHINFO_EXTENSION));
            $nombreArchivo = $input . "_" . date("Ymd_His") . "_" . rand(100, 999) . "." . $extension;
            $rutaDestino = $directorio . $nombreArchivo;

            if (move_uploaded_file($_FILES[$input]["tmp_name"], $rutaDestino)) {
                $nuevosDatos[$campoBD] = "publico/documentos/" . $idAspirante . "/" . $nombreArchivo;
            }
        }
    }

    if (!empty($nuevosDatos)) {
        $respuesta = contratacionControlador::ctrActualizarDocumentos($idAspirante, $nuevosDatos);
        echo json_encode($respuesta);
    } else {
        echo json_encode("No se subió ningún archivo.");
    }
    exit();
} 

if (isset($_POST["eliminarAspirante"])) {
    $respuesta = contratacionControlador::ctrEliminarAspirante($_POST["idAspirante"]);
    echo json_encode($respuesta);
    exit();
}

if (isset($_GET["accion"]) && $_GET["accion"] == "descargarDocumentos" && isset($_GET["idAspirante"])) {
    require_once "../Controladores/contratacionControlador.php";
    $id = intval($_GET["idAspirante"]);
    contratacionControlador::descargarDocumentos($id);
    exit;
}
