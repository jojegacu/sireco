<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../Controladores/municipiosControlador.php";
require_once "../Modelos/municipiosModelo.php";

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action == 'municipios') {
    $codigoPostal = isset($_GET["cp"]) ? $_GET["cp"] : "";
    echo json_encode(municipiosControlador::buscarPorCPControlador($codigoPostal));
}
?>
