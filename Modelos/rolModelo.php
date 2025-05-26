<?php

require_once "conexion.php";

class rolModelo extends conexion {

  // Alta de roles
  static public function crearRolModelo($tablaBD, $rol) {
    $stmt = conexion::conexionBD()->prepare(
      "INSERT INTO $tablaBD (rol, descripcion, fechAlta) 
       VALUES (:rol, :descripcion, :fechAlta)"
    );

    $stmt->bindParam(":rol", $rol["rol"], PDO::PARAM_STR);
    $stmt->bindParam(":descripcion", $rol["descripcion"], PDO::PARAM_STR);
    $stmt->bindParam(":fechAlta", $rol["fechAlta"], PDO::PARAM_STR);

    $ok = $stmt->execute();
    return $ok ? true : false;
  }

  // Ver roles
  static public function verRolModelo($id, $tabla) {
    if ($id != null) {
      $stmt = conexion::conexionBD()->prepare("SELECT * FROM $tabla WHERE idRol = :idRol");
      $stmt->bindParam(":idRol", $id, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
      $stmt = conexion::conexionBD()->prepare("SELECT * FROM $tabla");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
  }

  // Editar rol
  public static function editarRolModelo($tablaBD, $id) {
    $stmt = conexion::conexionBD()->prepare("SELECT * FROM $tablaBD WHERE idRol = :idRol");
    $stmt->bindParam(":idRol", $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
      $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
      return $resultado ?: false;
    }

    return false;
  }

  // Actualizar rol
  public static function actualizarRolModelo($tablaBD, $datosC) {
    $stmt = conexion::conexionBD()->prepare(
      "UPDATE $tablaBD SET rol = :rol, descripcion = :descripcion, fechAlta = :fechAlta 
       WHERE idRol = :idRol"
    );

    $stmt->bindParam(":idRol", $datosC["id"], PDO::PARAM_INT);
    $stmt->bindParam(":rol", $datosC["rol"], PDO::PARAM_STR);
    $stmt->bindParam(":descripcion", $datosC["descripcion"], PDO::PARAM_STR);
    $stmt->bindParam(":fechAlta", $datosC["fechAlta"], PDO::PARAM_STR);

    return $stmt->execute();
  }

  // Eliminar rol
  public static function borrarRolModelo($tablaBD, $id) {
    $stmt = conexion::conexionBD()->prepare("DELETE FROM $tablaBD WHERE idRol = :idRol");
    $stmt->bindParam(":idRol", $id, PDO::PARAM_INT);
    return $stmt->execute();
  }

}
