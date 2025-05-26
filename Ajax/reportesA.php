<?php
require_once "../Controladores/ReportesController.php";

$controlador = new ReportesController();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"])) {
    if ($_POST["accion"] === "generarPorEstatus") {
        $estatus = isset($_POST["estatus"]) ? intval($_POST["estatus"]) : -1;
        $fechaInicio = $_POST["fechaInicio"] ?? null;
        $fechaFin = $_POST["fechaFin"] ?? null;
        echo json_encode($controlador->generarPorEstatus($estatus, $fechaInicio, $fechaFin));
    } else if ($_POST["accion"] === "exportarCSV") {
        $estatus = isset($_POST["estatus"]) ? intval($_POST["estatus"]) : -1;
        $fechaInicio = isset($_POST["fechaInicio"]) && !empty($_POST["fechaInicio"]) ? $_POST["fechaInicio"] : null;
        $fechaFin = isset($_POST["fechaFin"]) && !empty($_POST["fechaFin"]) ? $_POST["fechaFin"] : null;
        $columnas = isset($_POST["columnas"]) ? $_POST["columnas"] : null;
        $controlador->exportarCSV($estatus, $fechaInicio, $fechaFin, $columnas);
        // No hay necesidad de retornar nada aquí porque exportarCSV maneja la salida
    } else if ($_POST["accion"] === "exportarLayoutAspirante") {
        $idAspirante = isset($_POST["idAspirante"]) ? intval($_POST["idAspirante"]) : 0;
        
        // Registrar en log para depuración
        error_log("Exportando layout para aspirante ID: " . $idAspirante);
        
        if ($idAspirante > 0) {
            // Intentar obtener los datos y exportar
            $resultado = $controlador->exportarLayoutAspirante($idAspirante);
            
            // Si no hay resultado (la función ya maneja sus propios errores)
            if ($resultado === false) {
                // No hacemos nada aquí porque el controlador ya envió un mensaje de error
            }
        } else {
            echo json_encode(["error" => "ID de aspirante inválido o no proporcionado: " . $idAspirante]);
        }
    }
}

