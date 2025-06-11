<?php

require_once "conexion.php";

class consultaCandidatoModelo extends conexion {

    public static function obtenerAspiranteModelo($id) {
        try {
            $stmt = conexion::conexionBD()->prepare("
                SELECT a.*, 
                       CASE WHEN a.cv IS NOT NULL AND a.cv != '' THEN 1 ELSE 0 END AS tiene_cv
                FROM aspirante a
                WHERE a.idAspirante = :id
            ");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    public static function obtenerEstatusModelo($idEstatus) {
        try {
            $stmt = conexion::conexionBD()->prepare("
                SELECT concepto, estatus 
                FROM estatus 
                WHERE idestatus = :id
            ");
            $stmt->bindParam(":id", $idEstatus, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    public static function obtenerContratacionModelo($idAspirante) {
    try {
        $stmt = conexion::conexionBD()->prepare("
            SELECT c.*,
                   (SELECT valor FROM catalogos WHERE idCatalogo = c.genero AND grupo = 2) AS genero_desc,
                   (SELECT valor FROM catalogos WHERE idCatalogo = c.edoCivil AND grupo = 1) AS edoCivil_desc
            FROM contratacion c
            WHERE c.idAspiranteFk = :id
        ");
        $stmt->bindParam(":id", $idAspirante, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return null;
    }
}

public static function reincorporarCandidatoModelo($idAspirante) {
    try {
        $stmt = conexion::conexionBD()->prepare("UPDATE aspirante SET nuevo = 0 WHERE idAspirante = :id");
        $stmt->bindParam(":id", $idAspirante, PDO::PARAM_INT);
        return $stmt->execute();
    } catch (PDOException $e) {
        return false;
    }
}

public static function eliminarAspiranteTotal($id) {
    try {
        $pdo = conexion::conexionBD();

        // 🔵 Primero eliminar archivos físicos

        // 1.1 Eliminar CV si existe
        $stmtCV = $pdo->prepare("SELECT cv FROM aspirante WHERE idAspirante = :id");
        $stmtCV->execute([":id" => $id]);
        $cv = $stmtCV->fetchColumn();

        if ($cv) {
            $rutaCV = "../publico/cv/" . $cv;
            if (file_exists($rutaCV)) {
                unlink($rutaCV); // 🔥 Eliminar CV
            }
        }

        // 1.2 Eliminar documentos si existe carpeta
        $rutaDocumentos = "../publico/documentos/" . $id;
        if (is_dir($rutaDocumentos)) {
            $archivos = scandir($rutaDocumentos);
            foreach ($archivos as $archivo) {
                if ($archivo != "." && $archivo != "..") {
                    unlink($rutaDocumentos . "/" . $archivo); // Elimina cada archivo
                }
            }
            rmdir($rutaDocumentos); // Elimina la carpeta
        }

        // 🔵 Luego eliminar en base de datos

        // 2. Eliminar de contratacion
        $pdo->prepare("DELETE FROM contratacion WHERE idAspiranteFk = :id")
            ->execute([":id" => $id]);

        // 3. Eliminar de comentarios
        $pdo->prepare("DELETE FROM comentarios WHERE idAspiranteFk = :id")
            ->execute([":id" => $id]);

        // 4. Eliminar de standby
        $pdo->prepare("DELETE FROM standby WHERE idAspiranteFk = :id")
            ->execute([":id" => $id]);

        // 5. Finalmente eliminar de aspirante
        $pdo->prepare("DELETE FROM aspirante WHERE idAspirante = :id")
            ->execute([":id" => $id]);

        return "ok";

    } catch (PDOException $e) {
        return "error";
    }
}

public static function mdlActualizarCampo($tabla, $campo, $valor, $campoId, $id) {
    $stmt = conexion::conexionBD()->prepare("UPDATE $tabla SET $campo = :valor WHERE $campoId = :id");

    $stmt->bindParam(":valor", $valor, PDO::PARAM_INT);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

public static function actualizarNotificadoModelo($id) {
    try {
        $stmt = conexion::conexionBD()->prepare("UPDATE aspirante SET notificado = 0 WHERE idAspirante = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        return $stmt->execute() ? "ok" : "error";
    } catch (PDOException $e) {
        return "error";
    }
}

public static function verificarCurpDuplicadaModelo($curp) {
  $stmt = conexion::conexionBD()->prepare("SELECT COUNT(*) FROM contratacion WHERE curpAsp = :curp");
  $stmt->bindParam(":curp", $curp, PDO::PARAM_STR);
  $stmt->execute();
  return $stmt->fetchColumn() > 0;
}

public static function verificarCurpModelo($tabla, $curp) {
    $stmt = conexion::conexionBD()->prepare("SELECT COUNT(*) FROM $tabla WHERE curpAsp = :curp");
    $stmt->bindParam(":curp", $curp, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchColumn() > 0;
}


public static function actualizarAspiranteCurpModelo($tabla, $curp, $idAspirante) {
    $stmt = conexion::conexionBD()->prepare("UPDATE $tabla SET nuevo = 3, idEstatusFk = 7 WHERE idAspirante = :idAspirante");
    $stmt->bindParam(":idAspirante", $idAspirante, PDO::PARAM_INT);
    return $stmt->execute();
}

public static function obtenerVacantePorAspirante($idAspirante) {
    try {
        $stmt = conexion::conexionBD()->prepare("SELECT puesto FROM aspirante WHERE idAspirante = :id");
        $stmt->bindParam(":id", $idAspirante, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn(); // Devuelve idVacante
    } catch (PDOException $e) {
        return false;
    }
}


}
