<?php

require_once "conexion.php";

class contratacionModelo {

    static public function mdlObtenerInfoAspirante($id) {
    $stmt = conexion::conexionBD()->prepare("
        SELECT 
            a.nombre, a.apPaterno, a.apMaterno, a.fechaNacimiento, a.codPostal, a.estado, 
            a.ciudadMun, a.colBarrio, a.calleNo, a.telefonoCel, a.puesto,
            v.clave, v.tienda, v.cp AS vacCP, v.edo, v.mun, v.col, v.responsable
        FROM aspirante a
        LEFT JOIN vacantes v ON a.puesto = v.idVacante
        WHERE a.idAspirante = :id
    ");
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


        static public function mdlObtenerCatalogoPorGrupo($grupo) {
    $stmt = conexion::conexionBD()->prepare("SELECT idCatalogo, valor FROM catalogos WHERE grupo = :grupo");
    $stmt->bindParam(":grupo", $grupo, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


public static function mdlGuardarContratacion($datos) {
    $stmt = conexion::conexionBD()->prepare(
        "INSERT INTO contratacion (
            idAspiranteFk, genero, curpAsp, rfcAsp, nss, edoCivil, contEmergencia, numEmergencia
        ) VALUES (
            :idAspirante, :genero, :curpAsp, :rfcAsp, :nss, :edoCivil, :contEmergencia, :numEmergencia
        )"
    );

    $stmt->bindParam(":idAspirante", $datos["idAspirante"], PDO::PARAM_INT);
    $stmt->bindParam(":genero", $datos["genero"], PDO::PARAM_INT);
    $stmt->bindParam(":curpAsp", $datos["curpAsp"], PDO::PARAM_STR);
    $stmt->bindParam(":rfcAsp", $datos["rfcAsp"], PDO::PARAM_STR);
    $stmt->bindParam(":nss", $datos["nss"], PDO::PARAM_STR);
    $stmt->bindParam(":edoCivil", $datos["edoCivil"], PDO::PARAM_INT);
    $stmt->bindParam(":contEmergencia", $datos["contEmergencia"], PDO::PARAM_STR);
    $stmt->bindParam(":numEmergencia", $datos["numEmergencia"], PDO::PARAM_STR);

    return $stmt->execute() ? "ok" : "error";
}

static public function mdlGuardarDocumentos($id, $files) {
    $carpeta = "../publico/documentos/" . $id;

    if (!file_exists($carpeta)) {
        mkdir($carpeta, 0777, true);
    }

    $prefijos = [
        "actaNac" => "ac",
        "compDom" => "cp",
        "sitFiscal" => "sf",
        "ine" => "in",
        "cuentaBanco" => "cb",
        "docNss" => "dn",
        "docCurp" => "dc",
        "cartaInfonavit" => "ci",
        "foto" => "ft"
    ];

    foreach ($prefijos as $campo => $prefijo) {
        if (isset($files[$campo]) && $files[$campo]["error"] === UPLOAD_ERR_OK) {
            $nombreTmp = $files[$campo]["tmp_name"];
            $fecha = date("Ymd_His");
            $clave = substr(str_shuffle("0123456789"), 0, 6) . "_" . substr(str_shuffle("0123456789"), 0, 3);
            $nombreFinal = "{$prefijo}_{$fecha}_{$clave}.pdf";
            $rutaFinal = "$carpeta/$nombreFinal";

            if (!move_uploaded_file($nombreTmp, $rutaFinal)) {
                return "Error al guardar archivo $campo";
            }
        }
    }

    return "ok";
}

static public function mdlEliminarAspirante($id) {
    $conexion = conexion::conexionBD();

    try {
        $conexion->beginTransaction();

        // Primero eliminar de la tabla contratacion
        $stmt = $conexion->prepare("DELETE FROM contratacion WHERE idAspiranteFk = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        // Luego eliminar de la tabla aspirante
        $stmt = $conexion->prepare("DELETE FROM aspirante WHERE idAspirante = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        $conexion->commit();
        
        // Eliminar carpeta de documentos
        $carpeta = "../publico/documentos/" . $id;
        if (is_dir($carpeta)) {
            // Borrar todos los archivos dentro
            $archivos = glob($carpeta . "/*");
            foreach ($archivos as $archivo) {
                if (is_file($archivo)) {
                    unlink($archivo);
                }
            }
            // Borrar la carpeta
            rmdir($carpeta);
        }


        
        return "ok";
    } catch (Exception $e) {
        $conexion->rollBack();
        return "error: " . $e->getMessage();
    }
}

// Nuevo método para actualizar rutas de documentos en BD
static public function mdlActualizarDocumentos($idAspirante, $datos) {
    $conexion = conexion::conexionBD();

    $set = [];
    foreach ($datos as $campo => $ruta) {
        $set[] = "$campo = :$campo";
    }
    $setSql = implode(", ", $set);

    $sql = "UPDATE contratacion SET $setSql WHERE idAspiranteFk = :idAspirante";

    $stmt = $conexion->prepare($sql);

    foreach ($datos as $campo => $ruta) {
        $stmt->bindParam(":$campo", $ruta, PDO::PARAM_STR);
    }
    $stmt->bindParam(":idAspirante", $idAspirante, PDO::PARAM_INT);

    return $stmt->execute() ? "ok" : "error al actualizar documentos";
}

static public function mdlObtenerDocumentos($idAspirante) {
    $stmt = conexion::conexionBD()->prepare("
        SELECT actaNac, compDom, sitFiscal, ine, cuentaBanco, docNss, docCurp, cartaInfonavit, foto 
        FROM contratacion 
        WHERE idAspiranteFk = :id
    ");
    $stmt->bindParam(":id", $idAspirante, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

static public function mdlObtenerDatosContratacion($idAspirante) {
    $stmt = conexion::conexionBD()->prepare("
        SELECT genero, curpAsp, rfcAsp, nss, edoCivil, contEmergencia, numEmergencia 
        FROM contratacion 
        WHERE idAspiranteFk = :id
    ");
    $stmt->bindParam(":id", $idAspirante, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

static public function mdlActualizarContratacion($datos) {
    $stmt = conexion::conexionBD()->prepare("
        UPDATE contratacion 
        SET genero = :genero, curpAsp = :curpAsp, rfcAsp = :rfcAsp, nss = :nss, edoCivil = :edoCivil, contEmergencia = :contEmergencia, numEmergencia = :numEmergencia
        WHERE idAspiranteFk = :idAspirante
    ");

    $stmt->bindParam(":genero", $datos["genero"], PDO::PARAM_INT);
    $stmt->bindParam(":curpAsp", $datos["curpAsp"], PDO::PARAM_STR);
    $stmt->bindParam(":rfcAsp", $datos["rfcAsp"], PDO::PARAM_STR);
    $stmt->bindParam(":nss", $datos["nss"], PDO::PARAM_STR);
    $stmt->bindParam(":edoCivil", $datos["edoCivil"], PDO::PARAM_INT);
    $stmt->bindParam(":contEmergencia", $datos["contEmergencia"], PDO::PARAM_STR);
    $stmt->bindParam(":numEmergencia", $datos["numEmergencia"], PDO::PARAM_STR);
    $stmt->bindParam(":idAspirante", $datos["idAspirante"], PDO::PARAM_INT);

    if (!$stmt->execute()) {
        // Nuevo: Imprimir error detallado
        print_r($stmt->errorInfo());
        return "error al actualizar contratación";
    }

    return "ok";
}

static public function mdlObtenerCVPorIDAspirante($id) {
    $stmt = conexion::conexionBD()->prepare("SELECT cv FROM aspirante WHERE idAspirante = :id");
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchColumn();
}


}
