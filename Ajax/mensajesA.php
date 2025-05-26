<?php
require_once "../Controladores/mensajesControlador.php";
require_once "../Modelos/mensajesModelo.php";

session_start();

if (isset($_POST["obtenerUsuarios"])) {
    $usuarios = mensajesModelo::mdlObtenerUsuarios();
    echo json_encode($usuarios);
    if (isset($_POST["usuariosConMensajes"])) {
        $id = $_SESSION["idPersona"];
        $usuarios = mensajesModelo::mdlUsuariosConMensajesNoLeidos($id);
        echo json_encode($usuarios);
        exit;
    }
    exit;
}

if (isset($_POST["obtenerMensajes"])) {
    echo json_encode(mensajesControlador::ctrObtenerMensajes($_POST["receptor"], $_SESSION["idPersona"]));
    return;
}

if (isset($_POST["enviarMensaje"])) {
    $emisorId = $_SESSION["idPersona"];
    $receptorId = $_POST["receptorId"];
    $mensaje = $_POST["mensaje"];
    echo mensajesControlador::ctrEnviarMensaje($emisorId, $receptorId, $mensaje);
    return;
}

if (isset($_POST["checarNotificaciones"])) {
    $idUsuario = $_SESSION["idPersona"];
    echo mensajesControlador::ctrChecarNotificaciones($idUsuario);
    exit;
}

/*if (isset($_POST["marcarVistos"])) {
    $id = $_SESSION["idPersona"];
    $stmt = Conexion::conexionBD()->prepare("UPDATE messages SET visto = 1 WHERE receiver_id = ?");
    $stmt->execute([$id]);
    exit;
}*/
if (isset($_POST["marcarVistosDe"])) {
    $receptor = $_SESSION["idPersona"];
    $emisor = $_POST["marcarVistosDe"];
    $stmt = Conexion::conexionBD()->prepare("UPDATE messages SET visto = 1 WHERE sender_id = ? AND receiver_id = ?");
    $stmt->execute([$emisor, $receptor]);
    exit;
}


if (isset($_POST["usuariosYNotificaciones"])) {
    $id = isset($_SESSION["idPersona"]) ? $_SESSION["idPersona"] : null;
    if (!$id) {
        echo json_encode(["usuarios" => [], "conMensajes" => [], "error" => "Sesión no iniciada"]);
        exit;
    }
    $usuarios = mensajesModelo::mdlObtenerUsuarios();
    $conMensajes = mensajesModelo::mdlUsuariosConMensajesNoLeidos($id);
    header('Content-Type: application/json');
    echo json_encode([
        "usuarios" => $usuarios,
        "conMensajes" => $conMensajes
    ]);
    exit;
}

// NUEVO: obtener notificaciones de candidatos
if (isset($_POST["notificacionesCandidatos"])) {
    echo json_encode(mensajesControlador::ctrObtenerNotificacionesCandidatos());
    exit;
}

// NUEVO: borrar notificación por ID
if (isset($_POST["borrarNotificacion"])) {
    $id = $_POST["borrarNotificacion"];
    echo mensajesControlador::ctrEliminarNotificacion($id);
    exit;
}
