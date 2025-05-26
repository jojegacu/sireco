<?php 

require_once "conexion.php";

class usuariosModelo extends conexion{

	
	static public function iniciarSesionModelo($tablaBD, $datosC){
    $pdo = conexion::conexionBD()->prepare("
        SELECT 
            u.usuario,
            u.password,
            p.idPersona,
            p.nombre,
            p.apellidos,
            p.curp,
            p.rfc,
            r.idRol,
            r.rol,
            p.idPersona AS idPersonaFk
        FROM users u
        INNER JOIN persona p ON u.idPersonaFk = p.idPersona
        INNER JOIN rol r ON p.idRolFk = r.idRol
        WHERE u.usuario = :usuario
    ");

    $pdo->bindParam(":usuario", $datosC["usuario"], PDO::PARAM_STR);
    $pdo->execute();
    
    return $pdo->fetch(PDO::FETCH_ASSOC);
}

	
	//CREAR USUARIOS

	static public function crearUsuarioModelo($tablaBD, $datosC){

		$pdo = conexion::conexionBD()->prepare("INSERT INTO $tablaBD (nombre, apellidos, curp, rfc, email, telefono, nuevo, fechaCaptura, idRolFk) VALUES (:nombre, :apellidos, :curp, :rfc, :email, :telefono, :nuevo, :fechaCaptura, :idRolFk)");
		
		$pdo -> bindParam(":nombre", $datosC["nombre"], PDO::PARAM_STR);
		$pdo -> bindParam(":apellidos", $datosC["apellidos"], PDO::PARAM_STR);
		$pdo -> bindParam(":curp", $datosC["curp"], PDO::PARAM_STR);
		$pdo -> bindParam(":rfc", $datosC["rfc"], PDO::PARAM_STR);
		$pdo -> bindParam(":email", $datosC["email"], PDO::PARAM_STR);
		$pdo -> bindParam(":telefono", $datosC["telefono"], PDO::PARAM_STR);
		$pdo -> bindParam(":nuevo", $datosC["nuevo"], PDO::PARAM_STR);
		$pdo -> bindParam(":fechaCaptura", $datosC["fechaCaptura"], PDO::PARAM_STR);
		$pdo -> bindParam(":idRolFk", $datosC["idRolFk"], PDO::PARAM_INT);

		if ($pdo -> execute()) {
			return true;
		}

		$pdo -> close();
		$pdo = null;

	}

	//VER USUARIOS

	static public function verUsuariosModelo($tablaBD, $columna, $valor){

		if ($columna != null) {
			$pdo = conexion::conexionBD()->prepare("SELECT * FROM $tablaBD WHERE $columna = :$columna");
			$pdo -> bindParam(":".$columna, $valor, PDO::PARAM_STR);
			$pdo -> execute();
			return $pdo -> fetch();
		}else{
			$pdo = conexion::conexionBD()->prepare("SELECT * FROM $tablaBD");
			$pdo -> execute();
			return $pdo ->fetchAll();
		}
		$pdo -> close();
		$pdo = null;
	}

	//VER DATOS GENERALES

	static public function editarUsrModelo($tablaBD, $id){
		$pdo = conexion::conexionBD()->prepare("SELECT * FROM $tablaBD WHERE idPersona = :idPersona");
		$pdo -> bindParam(":idPersona", $id, PDO::PARAM_INT);
		$pdo -> execute();
		return $pdo -> fetch();
		$pdo -> close();
		$pdo = null;
	}

	//ACTUALIZAR DATOS GENERALES

	static public function actualizarUsrModelo($tablaBD, $datosC){

		$pdo = conexion::conexionBD()->prepare("UPDATE $tablaBD SET nombre = :nombre, apellidos = :apellidos, curp = :curp, rfc = :rfc, email = :email, telefono = :telefono, fechaCaptura = :fechaCaptura, nuevo = :nuevo, idRolFk = :idRolFk WHERE idPersona = :idPersona");

		$pdo -> bindParam(":idPersona", $datosC["idPersona"], PDO::PARAM_INT);
		$pdo -> bindParam(":nombre", $datosC["nombre"], PDO::PARAM_STR);
		$pdo -> bindParam(":apellidos", $datosC["apellidos"], PDO::PARAM_STR);
		$pdo -> bindParam(":curp", $datosC["curp"], PDO::PARAM_STR);
		$pdo -> bindParam(":rfc", $datosC["rfc"], PDO::PARAM_STR);		
		$pdo -> bindParam(":telefono", $datosC["telefono"], PDO::PARAM_STR);
		$pdo -> bindParam(":email", $datosC["email"], PDO::PARAM_STR);
		$pdo -> bindParam(":fechaCaptura", $datosC["fechaCaptura"], PDO::PARAM_STR);
		$pdo -> bindParam(":nuevo", $datosC["nuevo"], PDO::PARAM_INT);
		$pdo -> bindParam(":idRolFk", $datosC["idRolFk"], PDO::PARAM_INT);

		if ($pdo -> execute()) {
			return true;
		}

		$pdo -> close();
		$pdo = null;
	}

	//CREAR DATOS DE USUARIOS

	static public function altUsrUsModelo($tablaBD, $datosC){

		$pdo = conexion::conexionBD()->prepare("INSERT INTO $tablaBD (usuario, password, fechaAlta, activo, ifPersonaFk) VALUES (:usuario, :password, :fechaAlta, :activo, :ifPersonaFk)");
		$pdo -> bindParam(":usuario", $datosC["usuario"], PDO::PARAM_STR);
		$pdo -> bindParam(":password", $datosC["password"], PDO::PARAM_STR);
		$pdo -> bindParam(":fechaAlta", $datosC["fechaAlta"], PDO::PARAM_STR);
		$pdo -> bindParam(":activo", $datosC["activo"], PDO::PARAM_INT);
		$pdo -> bindParam(":idPersonaFk", $datosC["idPersonaFk"], PDO::PARAM_INT);
		if ($pdo -> execute()) {
			return true;
		}

		$pdo -> close();
		$pdo = null;

	}

	//VER DATOS DE USUARIO
	static public function editarUsrUsModelo($tablaBD, $id){
		if ($id == null) {
			return false;
		}else{
		$pdo = conexion::conexionBD()->prepare("SELECT * FROM $tablaBD WHERE idPersonaFk = :idPersonaFk");
		$pdo -> bindParam(":idPersonaFk", $id, PDO::PARAM_INT);
		$pdo -> execute();

		return $pdo ->fetch(); 
		$pdo -> close();
		$pdo = null;
		}
	}

	//ACTUALIZAR DATOS DE USUARIOS
	static public function actualizarUsrUsModelo($tablaBD, $datosC){
		$pdo = conexion::conexionBD()->prepare("UPDATE $tablaBD SET usuario = :usuario, password = :password, fechaAlta = :fechaAlta, activo = :activo WHERE idPersonaFk = :idPersonaFk");

		$pdo -> bindParam(":idPersonaFk", $datosC["idPersonaFk"], PDO::PARAM_INT);
		$pdo -> bindParam(":usuario", $datosC["usuario"], PDO::PARAM_STR);
		$pdo -> bindParam(":password", $datosC["password"], PDO::PARAM_STR);
		$pdo -> bindParam(":fechaAlta", $datosC["fechaAlta"], PDO::PARAM_STR);
		$pdo -> bindParam(":activo", $datosC["activo"], PDO::PARAM_STR);		
		

		if ($pdo -> execute()) {
			return true;
		}

		$pdo -> close();
		$pdo = null;
	}

	//VER DATOS DE EMPLEADO
	static public function editarUsrEmpModelo($tablaBD, $id){
		if ($id == null) {
      		return false;
    	}else{
		$pdo = conexion::conexionBD()->prepare("SELECT * FROM $tablaBD WHERE idPersonaFk = :idPersonaFk");
		$pdo -> bindParam(":idPersonaFk", $id, PDO::PARAM_INT);
		$pdo -> execute();

		return $pdo ->fetch(); 
		$pdo -> close();
		$pdo = null;
		}
	}

	//ACTUALIZAR DATOS DE EMPLEADO
	static public function actualizarUsrEmpModelo($tablaBD, $datosC){
		$pdo = conexion::conexionBD()->prepare("UPDATE $tablaBD SET nue = :nue, puesto = :puesto, area = :area, fechaAlta = :fechaAlta, idPersonaFk = :idPersonaFk WHERE idPersonaFk = :idPersonaFk");
		
		$pdo -> bindParam(":nue", $datosC["nue"], PDO::PARAM_INT);
		$pdo -> bindParam(":nue", $datosC["nue"], PDO::PARAM_INT);
		$pdo -> bindParam(":puesto", $datosC["puesto"], PDO::PARAM_STR);
		$pdo -> bindParam(":area", $datosC["area"], PDO::PARAM_STR);
		$pdo -> bindParam(":fechaAlta", $datosC["fechaAlta"], PDO::PARAM_STR);		
		$pdo -> bindParam(":idPersonaFk", $datosC["idPersonaFk"], PDO::PARAM_INT);

		if ($pdo -> execute()) {
			return true;
		}

		$pdo -> close();
		$pdo = null;
	}


	//BORRAR USUARIOS

	static public function borrarUsrModelo($tablaBD, $id){
		
		$pdo = conexion::conexionBD()->prepare("DELETE FROM $tablaBD WHERE idPersona = :idPersona");
		$pdo -> bindParam(":idPersona", $id, PDO::PARAM_INT);
		if ($pdo -> execute()) {
			return true;
		} 
		$pdo -> close();
		$pdo = null;
	}
	
	public static function verificaExistenciaUsuarioModelo($tabla, $idPersona) {
	    $stmt = conexion::conexionBD()->prepare("SELECT idPersonaFk FROM $tabla WHERE idPersonaFk = :idPersonaFk");
	    $stmt->bindParam(":idPersonaFk", $idPersona, PDO::PARAM_INT);
	    $stmt->execute();
	    return $stmt->fetch() ? true : false;
	}

	public static function verificaExistenciaEmpleadoModelo($tabla, $idPersona) {
	    $stmt = conexion::conexionBD()->prepare("SELECT idEmpleado FROM $tabla WHERE idPersonaFk = :idPersona");
	    $stmt->bindParam(":idPersona", $idPersona, PDO::PARAM_INT);
	    $stmt->execute();
	    return $stmt->fetch() ? true : false;
	}
	public static function registrarUsuarioModelo($tabla, $datos) {
	    $stmt = conexion::conexionBD()->prepare("INSERT INTO $tabla (usuario, password, idPersonaFk) VALUES (:usuario, :password, :idPersonaFk)");
	    $stmt->bindParam(":usuario", $datos["usuario"], PDO::PARAM_STR);
	    $stmt->bindParam(":password", $datos["password"], PDO::PARAM_STR);
	    $stmt->bindParam(":idPersonaFk", $datos["idPersonaFk"], PDO::PARAM_INT);
	    return $stmt->execute();
	}

	public static function registrarEmpleadoModelo($tabla, $datos) {
	    $stmt = conexion::conexionBD()->prepare("INSERT INTO $tabla (nue, puesto, area, idPersonaFk ) VALUES (:nue, :puesto, :area, :idPersonaFk)");

	    $stmt->bindParam(":nue", $datos["noe"], PDO::PARAM_STR);
	    $stmt->bindParam(":puesto", $datos["puesto"], PDO::PARAM_STR);
	    $stmt->bindParam(":area", $datos["departamento"], PDO::PARAM_STR);
	    $stmt->bindParam(":idPersonaFk", $datos["idPersonaFk"], PDO::PARAM_INT);
	    return $stmt->execute();
	}

	public static function actualizarConexionModelo($idPersona, $estado) {
    $stmt = conexion::conexionBD()->prepare("UPDATE persona SET conectado = :estado WHERE idPersona = :id");
    $stmt->bindParam(":estado", $estado, PDO::PARAM_INT);
    $stmt->bindParam(":id", $idPersona, PDO::PARAM_INT);
    return $stmt->execute();
}



}