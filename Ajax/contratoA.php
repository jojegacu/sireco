<?php
require_once "../Controladores/contratoControlador.php";
require_once "../Modelos/contratoModelo.php";

if (isset($_POST["buscarCandidato"])) {
    $id = $_POST["idAspirante"];
    $respuesta = contratoControlador::ctrBuscarCandidato($id);
    echo json_encode($respuesta);
}
