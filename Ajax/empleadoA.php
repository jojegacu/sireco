<?php
require_once "../Controladores/usuariosControlador.php";
require_once "../Modelos/usuariosModelo.php";

if (isset($_POST["validarEmpleado"]) && isset($_POST["idPersona"])) {
    $respuesta = usuariosControlador::verificaExistenciaEmpleadoControlador($_POST["idPersona"]);
    echo json_encode(["existe" => $respuesta]);
    exit;
}

if (isset($_POST["crearEmpleado"])) {
    $datos = [
        "idPersonaFk"  => $_POST["idPersonaEmpleado"],
        "puesto"       => $_POST["puesto"],
        "departamento" => $_POST["departamento"],
        "noe" => $_POST["noe"],
    ];
    $respuesta = usuariosControlador::registrarEmpleadoControlador($datos);
    echo json_encode($respuesta);
    exit;
}

// Solo se ejecuta si ninguna condición fue verdadera
echo json_encode(["error" => "Solicitud inválida"]);
exit;
