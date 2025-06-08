<?php 

require_once "conexion.php";

class aspiranteModelo extends conexion{
 
	public static function buscarCPModelo($codigoPostal) {
        try {
            $stmt = Conexion::conexionBD()->prepare("SELECT codigoPos, estado, ciudadMun, colBarrio FROM estado WHERE codigoPos = :codigoPos");
            $stmt->bindParam(":codigoPos", $codigoPostal, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

	
	//ALTA DESDE VENTANA INGRESAR

	static public function altaAspiranteModelo($tablaBD, $datosC){

		$pdo = conexion::conexionBD()->prepare("INSERT INTO $tablaBD (nombre, apPaterno, apMaterno, fechaNacimiento, codPostal, estado, ciudadMun, colBarrio, calleNo, telefonoCel, correo, idProcedenciaFk, anotacion, cv, fechaCaptura, puesto, nuevo) VALUES (:nombre, :apPaterno, :apMaterno, :fechaNacimiento, :codPostal, :estado, :ciudadMun, :colBarrio, :calleNo, :telefonoCel, :correo, :idProcedenciaFk, :anotacion, :cv, :fechaCaptura, :puesto, :nuevo)");

		$pdo -> bindParam(":nombre", $datosC["nombre"], PDO::PARAM_STR);
		$pdo -> bindParam(":apPaterno", $datosC["apPaterno"], PDO::PARAM_STR);
		$pdo -> bindParam(":apMaterno", $datosC["apMaterno"], PDO::PARAM_STR);
		$pdo -> bindParam(":fechaNacimiento", $datosC["fechaNacimiento"], PDO::PARAM_STR);	
		$pdo -> bindParam(":codPostal", $datosC["codPostal"], PDO::PARAM_INT);
		$pdo -> bindParam(":estado", $datosC["estado"], PDO::PARAM_STR);
		$pdo -> bindParam(":ciudadMun", $datosC["ciudadMun"], PDO::PARAM_STR);
		$pdo -> bindParam(":colBarrio", $datosC["colBarrio"], PDO::PARAM_STR);
		$pdo -> bindParam(":calleNo", $datosC["calleNo"], PDO::PARAM_STR);
		$pdo -> bindParam(":telefonoCel", $datosC["telefonoCel"], PDO::PARAM_STR);
        $pdo -> bindParam(":correo", $datosC["correo"], PDO::PARAM_STR);
        $pdo -> bindParam(":idProcedenciaFk", $datosC["idProcedenciaFk"], PDO::PARAM_INT);
        $pdo -> bindParam(":anotacion", $datosC["anotacion"], PDO::PARAM_STR);
		$pdo -> bindParam(":cv", $datosC["cv"], PDO::PARAM_STR);
		$pdo -> bindParam(":fechaCaptura", $datosC["fechaCap"], PDO::PARAM_STR);
		$pdo -> bindParam(":puesto", $datosC["puesto"], PDO::PARAM_INT);
		$pdo -> bindParam(":nuevo", $datosC["nuevo"], PDO::PARAM_INT);

		if ($pdo -> execute()) {
			return true;
		}

		$pdo -> close();
		$pdo = null;

	}

	//VER CANDIDATOS

	static public function verAspiranteModelo($tablaBD, $columna, $valor){
    if ($columna != null) {
        $pdo = conexion::conexionBD()->prepare("SELECT * FROM $tablaBD WHERE $columna = :$columna");
        $pdo->bindParam(":".$columna, $valor, PDO::PARAM_STR);
        $pdo->execute();
        return $pdo->fetch(PDO::FETCH_ASSOC);
    } else {
        $pdo = conexion::conexionBD()->prepare("
            SELECT a.*, e.concepto, e.estatus
            FROM $tablaBD a
            LEFT JOIN estatus e ON a.idEstatusFk = e.idestatus
            ORDER BY a.fechaCaptura DESC
        ");
        $pdo->execute();
        return $pdo->fetchAll(PDO::FETCH_ASSOC);
    }
    $pdo = null;
}

	public static function obtenerCvModelo($tabla, $id) {
	    $pdo = conexion::conexionBD()->prepare("SELECT cv FROM $tabla WHERE idAspirante = :id");
	    $pdo->bindParam(":id", $id, PDO::PARAM_INT);
	    $pdo->execute();
	    return $pdo->fetch(PDO::FETCH_ASSOC);
	}
	
	
	

	public static function actualizarAspiranteModelo($tabla, $datos) {
    $set = "nombre = :nombre, apPaterno = :apPaterno, apMaterno = :apMaterno, 
            fechaNacimiento = :fechaNacimiento, codPostal = :codPostal, estado = :estado, 
            ciudadMun = :ciudadMun, colBarrio = :colBarrio, calleNo = :calleNo, 
            telefonoCel = :telefonoCel, puesto = :puesto";

    if (isset($datos["cv"])) {
        $set .= ", cv = :cv";
    }

    $sql = "UPDATE $tabla SET $set WHERE idAspirante = :idAspirante";
    $stmt = Conexion::conexionBD()->prepare($sql);

    // Bind obligatorios
    $stmt->bindParam(":idAspirante", $datos["idAspirante"], PDO::PARAM_INT);
    $stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
    $stmt->bindParam(":apPaterno", $datos["apPaterno"], PDO::PARAM_STR);
    $stmt->bindParam(":apMaterno", $datos["apMaterno"], PDO::PARAM_STR);
    $stmt->bindParam(":fechaNacimiento", $datos["fechaNacimiento"], PDO::PARAM_STR);
    $stmt->bindParam(":codPostal", $datos["codPostal"], PDO::PARAM_STR);
    $stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_STR);
    $stmt->bindParam(":ciudadMun", $datos["ciudadMun"], PDO::PARAM_STR);
    $stmt->bindParam(":colBarrio", $datos["colBarrio"], PDO::PARAM_STR);
    $stmt->bindParam(":calleNo", $datos["calleNo"], PDO::PARAM_STR);
    $stmt->bindParam(":telefonoCel", $datos["telefonoCel"], PDO::PARAM_STR);
    $stmt->bindParam(":puesto", $datos["puesto"], PDO::PARAM_STR);

    if (isset($datos["cv"])) {
        $stmt->bindParam(":cv", $datos["cv"], PDO::PARAM_STR);
    }

    return $stmt->execute() ? "ok" : "error";
}

public static function actualizarCvModelo($tabla, $id, $nombreArchivo) {
    $stmt = Conexion::conexionBD()->prepare("UPDATE $tabla SET cv = :cv WHERE idAspirante = :id");

    $stmt->bindParam(":cv", $nombreArchivo, PDO::PARAM_STR);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);

    return $stmt->execute();
}

public static function obtenerDatosCandidatoModelo($tabla, $id) {
    $stmt = Conexion::conexionBD()->prepare("SELECT nombre, apPaterno, apMaterno, puesto, nuevo FROM $tabla WHERE idAspirante = :id");
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


public static function obtenerComentariosModelo($tabla, $idAspirante) {
    $stmt = Conexion::conexionBD()->prepare("
        SELECT c.comentario, c.fechaCaptura,
               p.nombre, p.apellidos
        FROM $tabla c
        INNER JOIN persona p ON c.idPersonaFk = p.idPersona
        WHERE c.idAspiranteFk = :id
        ORDER BY c.fechaCaptura DESC
    ");
    $stmt->bindParam(":id", $idAspirante, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


public static function agregarComentarioModelo($tabla, $idAspirante, $comentario, $idPersona) {
    $stmt = Conexion::conexionBD()->prepare("
        INSERT INTO $tabla (comentario, idAspiranteFk, idPersonaFk, fechaCaptura)
        VALUES (:comentario, :idAspirante, :idPersona, NOW())
    ");
    $stmt->bindParam(":comentario", $comentario, PDO::PARAM_STR);
    $stmt->bindParam(":idAspirante", $idAspirante, PDO::PARAM_INT);
    $stmt->bindParam(":idPersona", $idPersona, PDO::PARAM_INT);
    return $stmt->execute();
}

public static function autorizarCandidatoModelo($tabla, $id) {
    $stmt = Conexion::conexionBD()->prepare("UPDATE $tabla SET nuevo = 1 WHERE idAspirante = :id");
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    return $stmt->execute();
}

public static function actualizarEstadoCandidatoModelo($tabla, $id, $nuevo) {
    $stmt = Conexion::conexionBD()->prepare("UPDATE $tabla SET nuevo = :nuevo WHERE idAspirante = :id");
    $stmt->bindParam(":nuevo", $nuevo, PDO::PARAM_INT);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    return $stmt->execute();
}

public static function actualizarGeneralesModelo($tabla, $datos) {
	
    try {
    $stmt = Conexion::conexionBD()->prepare("
    UPDATE $tabla SET 
        nombre = :nombre,
        apPaterno = :apPaterno,
        apMaterno = :apMaterno,
        fechaNacimiento = :fechaNacimiento,
        codPostal = :codPostal,
        estado = :estado,
        ciudadMun = :ciudadMun,
        colBarrio = :colBarrio,
        calleNo = :calleNo,
        telefonoCel = :telefonoCel,
        correo = :correo,
        fechaCaptura = :fechaCaptura,
        puesto = :puesto,
        nuevo = :nuevo
    WHERE idAspirante = :idAspirante
");


    $stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
    $stmt->bindParam(":apPaterno", $datos["apPaterno"], PDO::PARAM_STR);
    $stmt->bindParam(":apMaterno", $datos["apMaterno"], PDO::PARAM_STR);
    $stmt->bindParam(":fechaNacimiento", $datos["fechaNacimiento"], PDO::PARAM_STR);
    $stmt->bindParam(":codPostal", $datos["codPostal"], PDO::PARAM_STR);
    $stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_STR);
    $stmt->bindParam(":ciudadMun", $datos["ciudadMun"], PDO::PARAM_STR);
    $stmt->bindParam(":colBarrio", $datos["colBarrio"], PDO::PARAM_STR);
    $stmt->bindParam(":calleNo", $datos["calleNo"], PDO::PARAM_STR);
    $stmt->bindParam(":telefonoCel", $datos["telefonoCel"], PDO::PARAM_STR);
    $stmt->bindParam(":correo", $datos["correo"], PDO::PARAM_STR);
    $stmt->bindParam(":fechaCaptura", $datos["fechaCaptura"], PDO::PARAM_STR);
    $stmt->bindParam(":puesto", $datos["puesto"], PDO::PARAM_STR);
	$stmt->bindParam(":nuevo", $datos["nuevo"], PDO::PARAM_INT);
    $stmt->bindParam(":idAspirante", $datos["idAspirante"], PDO::PARAM_INT);


    if ($stmt->execute()) {   
    return "ok";
} else {    
    return "error";
}
} catch (PDOException $e) {
    return "PDOException: " . $e->getMessage();
}
}

public static function guardarStandbyModelo($idAspirante, $comentario, $idEstatus) {
    try {
        $pdo = conexion::conexionBD()->prepare(
            "INSERT INTO standby (idAspiranteFk, comentario, idEstatusFk) 
             VALUES (:idAspirante, :comentario, :idEstatus)"
        );
        $pdo->bindParam(":idAspirante", $idAspirante, PDO::PARAM_INT);
        $pdo->bindParam(":comentario", $comentario, PDO::PARAM_STR);
        $pdo->bindParam(":idEstatus", $idEstatus, PDO::PARAM_INT);
        return $pdo->execute() ? "ok" : "error";
    } catch (Exception $e) {
        return "error_bd";
    }
}

public static function actualizarAspiranteEstatusModelo($idAspirante, $nuevoValor) {
    try {
        $pdo = conexion::conexionBD()->prepare(
            "UPDATE aspirante SET nuevo = :nuevo WHERE idAspirante = :id"
        );
        $pdo->bindParam(":nuevo", $nuevoValor, PDO::PARAM_INT);
        $pdo->bindParam(":id", $idAspirante, PDO::PARAM_INT);
        return $pdo->execute() ? "ok" : "error_actualizar";
    } catch (Exception $e) {
        return "error_bd";
    }
}

public static function actualizarIdEstatusFkModelo($idAspirante, $idEstatus) {
    try {
        $pdo = conexion::conexionBD()->prepare(
            "UPDATE aspirante SET idestatusFk = :idestatusFk WHERE idAspirante = :id"
        );
        $pdo->bindParam(":idestatusFk", $idEstatus, PDO::PARAM_INT);
        $pdo->bindParam(":id", $idAspirante, PDO::PARAM_INT);
        return $pdo->execute() ? "ok" : "error";
    } catch (Exception $e) {
        return "error_bd";
    }
}

public static function obtenerEstatusClaveModelo($claveStatus) {
    try {
        $stmt = conexion::conexionBD()->prepare("
            SELECT idestatus, estatus 
            FROM estatus 
            WHERE claveStatus = :claveStatus
        ");
        $stmt->bindParam(":claveStatus", $claveStatus, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return [];
    }
}


public static function actualizarAspiranteEliminarModelo($idAspirante, $nuevo, $idestatusFk) {
    try {
        $pdo = conexion::conexionBD()->prepare("
            UPDATE aspirante 
            SET nuevo = :nuevo, idestatusFk = :idestatusFk 
            WHERE idAspirante = :id
        ");
        $pdo->bindParam(":nuevo", $nuevo, PDO::PARAM_INT);
        $pdo->bindParam(":idestatusFk", $idestatusFk, PDO::PARAM_INT);
        $pdo->bindParam(":id", $idAspirante, PDO::PARAM_INT);
        return $pdo->execute() ? "ok" : "error_update";
    } catch (Exception $e) {
        return "error_bd";
    }
}


public static function actualizarAspiranteEstadoYStatusModelo($idAspirante, $nuevoValor, $idEstatus) {
    try {
        $pdo = conexion::conexionBD()->prepare(
            "UPDATE aspirante 
             SET nuevo = :nuevo, idestatusFk = :idestatusFk 
             WHERE idAspirante = :id"
        );
        $pdo->bindParam(":nuevo", $nuevoValor, PDO::PARAM_INT);
        $pdo->bindParam(":idestatusFk", $idEstatus, PDO::PARAM_INT);
        $pdo->bindParam(":id", $idAspirante, PDO::PARAM_INT);
        return $pdo->execute() ? "ok" : "error";
    } catch (Exception $e) {
        return "error_bd";
    }
}

public static function mdlBuscarClaveVacante($clave) {
  $stmt = Conexion::conexionBD()->prepare("SELECT idVacante, clave FROM vacantes WHERE clave LIKE :clave LIMIT 10");
  $stmt->bindValue(":clave", "%$clave%", PDO::PARAM_STR);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public static function mdlObtenerVacante($idVacante) {
    $stmt = Conexion::conexionBD()->prepare("SELECT * FROM vacantes WHERE idVacante = :id");
    $stmt->bindParam(":id", $idVacante, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public static function mdlFiltrarVacantes($filtro) {
    $stmt = Conexion::conexionBD()->prepare("
        SELECT idVacante, clave, tienda, cp, edo 
        FROM vacantes 
        WHERE clave LIKE :filtro 
           OR tienda LIKE :filtro 
           OR cp LIKE :filtro 
           OR edo LIKE :filtro
        LIMIT 20
    ");
    $like = "%$filtro%";
    $stmt->bindParam(":filtro", $like, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


public static function mdlActualizarVacante($idAspirante, $idVacante) {
    $stmt = Conexion::conexionBD()->prepare("UPDATE aspirante SET puesto = :puesto WHERE idAspirante = :id");
    $stmt->bindParam(":puesto", $idVacante, PDO::PARAM_INT);
    $stmt->bindParam(":id", $idAspirante, PDO::PARAM_INT);
    return $stmt->execute();
}

public static function actualizarNotificadoModelo($id, $tabla) {
    $stmt = Conexion::conexionBD()->prepare("UPDATE $tabla SET notificado = 0 WHERE idAspirante = :id");
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        return "ok";
    } else {
        return "error";
    }

    $stmt = null;
}

public static function mdlCargarProcedencias() {
  $stmt = Conexion::conexionBD()->prepare("SELECT idCatalogo, valor FROM catalogos WHERE concepto = 'procedencia'");
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


}