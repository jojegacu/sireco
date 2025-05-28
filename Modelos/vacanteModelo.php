<?php

require_once "conexion.php";

class vacanteModelo extends conexion{

    static public function mdlObtenerRegiones($tabla) {
        $stmt = conexion::conexionBD()->prepare("SELECT * FROM $tabla");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    static public function mdlObtenerPuestos($tabla) {
        $stmt = conexion::conexionBD()->prepare("SELECT * FROM $tabla WHERE grupo = 3");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    static public function mdlRegistrarVacante($tabla, $datos) {

      $sql = "INSERT INTO $tabla 
        (id, idRegionFk, tienda, responsable, cp, edo, mun, col, idCatalogoFk, fechaAlta, clave)
        VALUES 
        (:id, :idRegionFk, :tienda, :responsable, :cp, :edo, :mun, :col, :idCatalogoFk, :fechaAlta, :clave)";

      $stmt = conexion::conexionBD()->prepare($sql);

      $stmt->bindParam(":id", $datos["id"], PDO::PARAM_STR);
      $stmt->bindParam(":idRegionFk", $datos["idRegionFk"], PDO::PARAM_INT);
      $stmt->bindParam(":tienda", $datos["tienda"], PDO::PARAM_STR);
      $stmt->bindParam(":responsable", $datos["responsable"], PDO::PARAM_STR);
      $stmt->bindParam(":cp", $datos["codPostal"], PDO::PARAM_STR);
      $stmt->bindParam(":edo", $datos["estado"], PDO::PARAM_STR);
      $stmt->bindParam(":mun", $datos["ciudadMun"], PDO::PARAM_STR);
      $stmt->bindParam(":col", $datos["colBarrio"], PDO::PARAM_STR);
      $stmt->bindParam(":idCatalogoFk", $datos["idCatalogoFk"], PDO::PARAM_INT);
      $stmt->bindParam(":fechaAlta", $datos["fechaCap"], PDO::PARAM_STR);
      $stmt->bindParam(":clave", $datos["clave"], PDO::PARAM_STR);

      if ($stmt->execute()) {
        return "ok";
      } else {
        return "error";
      }
    }

    static public function mdlVerificarClave($tabla, $clave) {
      $stmt = conexion::conexionBD()->prepare("SELECT COUNT(*) FROM $tabla WHERE clave = :clave");
      $stmt->bindParam(":clave", $clave, PDO::PARAM_STR);
      $stmt->execute();
      return $stmt->fetchColumn() > 0; // true si existe
    }

    static public function mdlMostrarVacantes($tabla) {
      $sql = "SELECT v.*, 
                     c.valor AS puesto,
                     r.region AS region  
              FROM $tabla v 
              LEFT JOIN catalogos c ON v.idCatalogoFk = c.idCatalogo
              LEFT JOIN region r ON v.idRegionFk = r.idRegion
              ORDER BY v.fechaAlta DESC";

      $stmt = conexion::conexionBD()->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    static public function mdlEliminarVacante($tabla, $id) {
      $stmt = conexion::conexionBD()->prepare("DELETE FROM $tabla WHERE idVacante = :idVacante");
      $stmt->bindParam(":idVacante", $id, PDO::PARAM_STR);

      if ($stmt->execute()) {
        return "ok";
      } else {
        return "error";
    }
}


static public function mdlObtenerVacantePorId($tabla, $id) {
  $stmt = conexion::conexionBD()->prepare("SELECT * FROM $tabla WHERE idVacante = :id");
  $stmt->bindParam(":id", $id, PDO::PARAM_STR);
  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

static public function mdlActualizarVacante($tabla, $datos) {
  $sql = "UPDATE $tabla SET     
    id = :id,
    idRegionFk = :idRegionFk,
    tienda = :tienda,
    responsable = :responsable,
    cp = :codPostal,
    edo = :estado,
    mun = :ciudadMun,
    col = :colBarrio,
    idCatalogoFk = :idCatalogoFk,
    fechaAlta = :fechaCap
    WHERE idVacante = :idVacante";

  $stmt = conexion::conexionBD()->prepare($sql);

  $stmt->bindParam(":idVacante", $datos["idVacante"], PDO::PARAM_STR);
  $stmt->bindParam(":id", $datos["id"], PDO::PARAM_STR);
  $stmt->bindParam(":idRegionFk", $datos["idRegionFk"], PDO::PARAM_INT);
  $stmt->bindParam(":tienda", $datos["tienda"], PDO::PARAM_STR);
  $stmt->bindParam(":responsable", $datos["responsable"], PDO::PARAM_STR);
  $stmt->bindParam(":codPostal", $datos["codPostal"], PDO::PARAM_STR);
  $stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_STR);
  $stmt->bindParam(":ciudadMun", $datos["ciudadMun"], PDO::PARAM_STR);
  $stmt->bindParam(":colBarrio", $datos["colBarrio"], PDO::PARAM_STR);
  $stmt->bindParam(":idCatalogoFk", $datos["idCatalogoFk"], PDO::PARAM_INT);
  $stmt->bindParam(":fechaCap", $datos["fechaCap"], PDO::PARAM_STR);

  if ($stmt->execute()) {
    return "ok";
  } else {
    return "error";
  }
}

// Buscar ID de región por nombre
public static function mdlBuscarRegionPorNombre($nombre) {
  $stmt = conexion::conexionBD()->prepare("SELECT idRegion FROM region WHERE region = ?");
  $stmt->execute([$nombre]); // ✅ variable correcta
  return $stmt->fetchColumn();
}


// Buscar ID de vacante por concepto (grupo 3)
public static function mdlBuscarCatalogoPorConcepto($concepto) {
  $stmt = conexion::conexionBD()->prepare("SELECT idCatalogo FROM catalogos WHERE valor = ? AND grupo = 3");
  $stmt->execute([$concepto]);
  return $stmt->fetchColumn(); // Devuelve solo el idCatalogo
}

// Insertar múltiples vacantes como bloque
public static function mdlInsertarBloqueVacantes($datos) {
  $bd = conexion::conexionBD();
  $bd->beginTransaction();

  $sql = "INSERT INTO vacantes (id, categoria, idRegionFk, tienda, responsable, idCatalogoFk, fechaAlta, clave, cp, edo, mun, col)
          VALUES (:id, :categoria, :idRegionFk, :tienda, :responsable, :idCatalogoFk, :fechaAlta, :clave, '', '', '', '')";

  $stmt = $bd->prepare($sql);

  try {
    foreach ($datos as $fila) {
      $stmt->bindParam(":id", $fila["id"], PDO::PARAM_STR);
      $stmt->bindParam(":categoria", $fila["categoria"], PDO::PARAM_STR);
      $stmt->bindParam(":idRegionFk", $fila["idRegionFk"], PDO::PARAM_INT);
      $stmt->bindParam(":tienda", $fila["tienda"], PDO::PARAM_STR);
      $stmt->bindParam(":responsable", $fila["responsable"], PDO::PARAM_STR);
      $stmt->bindParam(":idCatalogoFk", $fila["idCatalogoFk"], PDO::PARAM_INT);
      $stmt->bindParam(":fechaAlta", $fila["fechaAlta"], PDO::PARAM_STR);
      $claveVacia = "";
      $stmt->bindParam(":clave", $claveVacia, PDO::PARAM_STR);
      $stmt->execute();
    }

    $bd->commit();
    return "ok";

  } catch (PDOException $e) {
    $bd->rollBack();
    return "error";
  }
}

public static function mdlExisteCategoria($tabla, $categoria) {
  $stmt = conexion::conexionBD()->prepare("SELECT COUNT(*) FROM $tabla WHERE categoria = :categoria");
  $stmt->bindParam(":categoria", $categoria, PDO::PARAM_STR);
  $stmt->execute();
  return $stmt->fetchColumn() > 0;
}

static public function mdlEliminarPorCategoria($tabla, $categoria) {
  $stmt = conexion::conexionBD()->prepare("DELETE FROM $tabla WHERE categoria = :categoria");
  $stmt->bindParam(":categoria", $categoria, PDO::PARAM_STR);
  return $stmt->execute() ? "ok" : "error";
}

// Obtener todas las vacantes con nombre del puesto (JOIN con catalogos)
static public function mdlListarVacantesParaSeleccion($tabla) {
  $sql = "SELECT v.idVacante, v.clave, v.categoria, v.cp, v.edo AS estado, v.mun AS municipio, 
                 v.col AS colonia, v.tienda, v.responsable, c.valor AS puesto
          FROM $tabla v
          LEFT JOIN catalogos c ON v.idCatalogoFk = c.idCatalogo
          ORDER BY v.fechaAlta DESC";

  $stmt = conexion::conexionBD()->prepare($sql);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Generar y guardar nueva clave para una vacante
static public function mdlGenerarYGuardarClave($tabla, $idVacante) {
  // Generar clave aleatoria de 6 caracteres
  $claveGenerada = substr(str_shuffle("1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);

  // Verificar si ya existe
  $existe = vacanteModelo::mdlVerificarClave($tabla, $claveGenerada);
  if ($existe) {
    return vacanteModelo::mdlGenerarYGuardarClave($tabla, $idVacante); // Recursividad
  }

  // Actualizar
  $stmt = conexion::conexionBD()->prepare("UPDATE $tabla SET clave = :clave WHERE idVacante = :id");
  $stmt->bindParam(":clave", $claveGenerada, PDO::PARAM_STR);
  $stmt->bindParam(":id", $idVacante, PDO::PARAM_STR);

  if ($stmt->execute()) {
    return $claveGenerada;
  } else {
    return null;
  }
}


}
