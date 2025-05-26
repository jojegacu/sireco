<?php

require_once "conexion.php";

class mensajesModelo extends conexion {

  public static function mdlObtenerUsuarios() {
    $idActual = isset($_SESSION["idPersona"]) ? $_SESSION["idPersona"] : 0;
    $stmt = Conexion::conexionBD()->prepare("SELECT idPersona AS id, CONCAT(nombre, ' ', apellidos) AS nombre, conectado AS logueado FROM persona WHERE idPersona != :id");
    $stmt->bindParam(":id", $idActual, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function mdlObtenerMensajes($receptorId, $emisorId) {
    $sql = "SELECT 
              p.nombre AS emisor,
              m.message AS mensaje,
              DATE_FORMAT(m.timestamp, '%d/%m/%Y %H:%i') AS fecha
            FROM messages m
            JOIN persona p ON m.sender_id = p.idPersona
            WHERE (m.sender_id = :emisor AND m.receiver_id = :receptor)
               OR (m.sender_id = :receptor AND m.receiver_id = :emisor)
            ORDER BY m.timestamp ASC";

    $stmt = conexion::conexionBD()->prepare($sql);
    $stmt->bindParam(":emisor", $emisorId, PDO::PARAM_INT);
    $stmt->bindParam(":receptor", $receptorId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function mdlEnviarMensaje($emisorId, $receptorId, $mensaje) {
    $stmt = conexion::conexionBD()->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    return $stmt->execute([$emisorId, $receptorId, $mensaje]) ? "ok" : "error";
  }

  public static function mdlContarMensajesNuevos($idReceptor) {
    $sql = "SELECT COUNT(*) AS total FROM messages WHERE receiver_id = :id AND visto = 0";
    $stmt = Conexion::conexionBD()->prepare($sql);
    $stmt->bindParam(":id", $idReceptor, PDO::PARAM_INT);
    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    return $res ? $res["total"] : 0;
  }

  public static function mdlUltimoMensajeId($idReceptor) {
    $stmt = Conexion::conexionBD()->prepare("SELECT MAX(id) AS maxId FROM messages WHERE receiver_id = :id");
    $stmt->bindParam(":id", $idReceptor, PDO::PARAM_INT);
    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    return $res ? intval($res["maxId"]) : 0;
  }

  public static function mdlUsuariosConMensajesNoLeidos($idReceptor) {
    $stmt = Conexion::conexionBD()->prepare("
      SELECT sender_id 
      FROM messages 
      WHERE receiver_id = :id AND visto = 0
      GROUP BY sender_id
    ");
    $stmt->bindParam(":id", $idReceptor, PDO::PARAM_INT);
    $stmt->execute();
    return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'sender_id');
  }

  public static function mdlObtenerNotificacionesCandidatos() {
  $stmt = Conexion::conexionBD()->prepare("
    SELECT idAspirante, nuevo 
    FROM aspirante 
    WHERE notificado = 0
    ORDER BY fechaCaptura DESC
  ");
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public static function mdlEliminarNotificacion($id) {
  $stmt = Conexion::conexionBD()->prepare("UPDATE aspirante SET notificado = 1 WHERE idAspirante = :id");
  $stmt->bindParam(":id", $id, PDO::PARAM_INT);
  return $stmt->execute();
}

}
