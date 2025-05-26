<?php

require_once "conexion.php";

class misDatosModelo extends conexion{
	
	//Ver mis datos
	static public function vermisDatosModelo($tablaBD, $id){
		$pdo = conexion::conexionBD()->prepare("SELECT * FROM $tablaBD WHERE idPersona = :idPersona");
		$pdo -> bindParam(":idPersona", $id, PDO::PARAM_INT);
		$pdo -> execute();

		return $pdo ->fetch(); 
		$pdo -> close();
		$pdo = null;
	}

	//Actualizar mis datos
	static public function guardarDatosModelo($tablaBD, $datosC){
		$pdo = conexion::conexionBD()->prepare("UPDATE $tablaBD SET nombre = :nombre, apellidos = :apellidos, curp = :curp, rfc = :rfc, telefono = :telefono, email = :email, fechaCaptura = :fechaCaptura, nuevo = :nuevo, idRolFk = :idRolFk WHERE idPersona = :idPersona");

		$pdo -> bindParam(":idPersona", $datosC["idPersona"], PDO::PARAM_INT);
		$pdo -> bindParam(":nombre", $datosC["nombre"], PDO::PARAM_STR);
		$pdo -> bindParam(":apellidos", $datosC["apellidos"], PDO::PARAM_STR);
		$pdo -> bindParam(":curp", $datosC["curp"], PDO::PARAM_STR);
		$pdo -> bindParam(":rfc", $datosC["rfc"], PDO::PARAM_STR);
		$pdo -> bindParam(":telefono", $datosC["telefono"], PDO::PARAM_STR);
		$pdo -> bindParam(":email", $datosC["email"], PDO::PARAM_STR);
		$pdo -> bindParam(":fechaCaptura", $datosC["fechaCaptura"], PDO::PARAM_STR);
		$pdo -> bindParam(":nuevo", $datosC["nuevo"], PDO::PARAM_STR);
		$pdo -> bindParam(":idRolFk", $datosC["idRolFk"], PDO::PARAM_INT);

		if ($pdo -> execute()) {
			return true;
		}

		$pdo -> close();
		$pdo = null;
	}
	//Ver mis datos Usuario
	static public function vermisDatosUModelo($tablaBD, $id){
		$pdo = conexion::conexionBD()->prepare("SELECT * FROM $tablaBD WHERE idPersonaFk = :idPersonaFk");
		$pdo -> bindParam(":idPersonaFk", $id, PDO::PARAM_INT);
		$pdo -> execute();

		return $pdo ->fetch(); 
		$pdo -> close();
		$pdo = null;
	}

	//Actualizar mis datos Usuario
	static public function guardarDatosUModelo($tablaBD, $datosC){
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

	//Ver mis datos Empleado
	static public function vermisDatosEModelo($tablaBD, $id){
		$pdo = conexion::conexionBD()->prepare("SELECT * FROM $tablaBD WHERE idPersonaFk = :idPersonaFk");
		$pdo -> bindParam(":idPersonaFk", $id, PDO::PARAM_INT);
		$pdo -> execute();

		return $pdo ->fetch(); 
		$pdo -> close();
		$pdo = null;
	}

	//Actualizar mis datos Empleado
	static public function guardarDatosEModelo($tablaBD, $datosC){
		var_dump($datosC);
		$pdo = conexion::conexionBD()->prepare("UPDATE $tablaBD SET nue = :nue, puesto = :puesto, area = :area, fechaAlta = :fechaAlta, idPersonaFk = :idPersonaFk WHERE idEmpleado = :idEmpleado");
		
		$pdo -> bindParam(":idEmpleado", $datosC["idEmpleado"], PDO::PARAM_INT);
		$pdo -> bindParam(":nue", $datosC["nue"], PDO::PARAM_INT);
		$pdo -> bindParam(":nue", $datosC["nue"], PDO::PARAM_INT);
		$pdo -> bindParam(":puesto", $datosC["puesto"], PDO::PARAM_STR);
		$pdo -> bindParam(":area", $datosC["area"], PDO::PARAM_STR);
		$pdo -> bindParam(":fechaAlta", $datosC["fechaAlta"], PDO::PARAM_STR);		
		$pdo -> bindParam(":idPersonaFk", $datosC["idPerFk"], PDO::PARAM_INT);

		if ($pdo -> execute()) {
			return true;
		}

		$pdo -> close();
		$pdo = null;
	}

}