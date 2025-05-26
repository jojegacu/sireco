<?php

require_once __DIR__ . '/../Modelos/usuariosModelo.php';

session_start();

$tiempoInactividad = 600;

// Obtener la URL base del sistema (funciona en localhost y hosting)
$baseUrl = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

// Obtener el nombre de la vista actual (sin parámetros)
$currentPage = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// 👉 Lista de vistas públicas (que se pueden ver sin estar logueado)
$vistasPublicas = ["ingreso", "ingresar", "login", "candidatos", "contratos"];

// Verifica inactividad
if (isset($_SESSION['ultimoAcceso'])) {
    $tiempoActual = time();
    $tiempoTranscurrido = $tiempoActual - $_SESSION['ultimoAcceso'];

    if ($tiempoTranscurrido > $tiempoInactividad) {
       if (isset($_SESSION["idPersona"])) {
          usuariosModelo::actualizarConexionModelo($_SESSION["idPersona"], 0); 
        }
        session_unset();
        session_destroy();


        if (!in_array($currentPage, $vistasPublicas)) {
            echo '<script>window.location.href = "' . $baseUrl . '/ingresar";</script>';
        }
        exit;
    }
}

// Actualiza último acceso
$_SESSION['ultimoAcceso'] = time();

// Si no está logueado y no estamos en una vista pública, redirige
if (!isset($_SESSION['usuario']) && !in_array($currentPage, $vistasPublicas)) {
    echo '<script>window.location.href = "' . $baseUrl . '/ingresar";</script>';
    exit;
}
?>
