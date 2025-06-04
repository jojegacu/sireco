<?php

class aspiranteControlador{

	//BUSQUEDA DEL CP que si jala
	/*public static function buscarCPControlador($codigoPostal) {
        return aspiranteModelo::buscarCPModelo($codigoPostal);
    }*/
    public static function buscarCPControlador($codigoPostal){
    	$respuesta = aspiranteModelo::buscarCPModelo($codigoPostal);
    	foreach($respuesta as &$r){
        	$r['codPostal'] = $codigoPostal; // <= agregamos codPostal al array devuelto
    	}
    	return $respuesta;
	}
	
	
	public function altaAspiranteControlador() {

	    if (isset($_POST["nombre"])) {

	        // ============================
	        // SUBIDA DEL CV
	        // ============================

	        $cvFinal = ""; // valor por defecto

	        if(isset($_FILES['cvFile']) && $_FILES['cvFile']['error'] == 0){

	            // Validar tipo de archivo (sólo PDF)
	            $permitidos = array('application/pdf');
	            if (in_array($_FILES['cvFile']['type'], $permitidos)) {

	                $directorioDestino = "publico/cv/";
	                // Crear carpeta si no existe
	                if (!file_exists($directorioDestino)) {
	                    mkdir($directorioDestino, 0777, true);
	                }

	                // Generar nombre único
	                $fecha = date("Ymd_His");
	                $random = rand(100,999);
	                $extension = pathinfo($_FILES['cvFile']['name'], PATHINFO_EXTENSION);
	                $cvFinal = "cv_{$fecha}_{$random}.".$extension;

	                $rutaDestino = $directorioDestino . $cvFinal;

	                if (!move_uploaded_file($_FILES['cvFile']['tmp_name'], $rutaDestino)) {
	                    echo '<script>alert("Error al subir el archivo CV");</script>';
	                    return; // cancelamos alta si falla
	                }

	            } else {
	                echo '<script>alert("Sólo se permiten archivos PDF.");</script>';
	                return; // cancelamos alta si no es PDF
	            }
	        }

	        // ============================
	        // ALTA DEL ASPIRANTE
	        // ============================

	        $tablaBD = "aspirante";

	        $datosC = array(
	            "nombre"         => $_POST["nombre"],
	            "apPaterno"      => $_POST["apPaterno"],
	            "apMaterno"      => $_POST["apMaterno"],
	            "fechaNacimiento"=> $_POST["fechaNacimiento"],
	            "codPostal"      => $_POST["codPostal"],
	            "estado"         => $_POST["estado"],
	            "ciudadMun"      => $_POST["ciudadMun"],
	            "colBarrio"      => $_POST["colBarrio"],
	            "calleNo"        => $_POST["calleNo"],
	            "telefonoCel"    => $_POST["telefonoCel"],
	            "correo"    	 => $_POST["correo"],
	            "anotacion"    	 => $_POST["anotacion"],
	            "idProcedenciaFk"=> $_POST["idProcedenciaFk"],
	            "cv"             => $cvFinal, // guarda el nuevo nombre
	            "fechaCap"       => $_POST["fechaCap"],
	            "puesto"          => $_POST["puesto"],
	            "nuevo"          => $_POST["nuevo"]
	        );

	        $resultado = aspiranteModelo::altaAspiranteModelo($tablaBD, $datosC);

	        if ($resultado == true) {
			    echo '<script>
	                alert("El registro fue exitoso, comunícate con Soporte para validar el registro");
	                window.location = "ingresar";
	            </script>';
			}
	    }
	}

	//VER CANDIDATOS

	static public function verAspiranteControlador($columna, $valor){
		$tablaBD = "aspirante";
		$resultado = aspiranteModelo::verAspiranteModelo($tablaBD, $columna, $valor);
		return $resultado;
	}

	public static function obtenerCvControlador($id) {
    $cv = aspiranteModelo::obtenerCvModelo("aspirante", $id);
    if ($cv && !empty($cv["cv"])) {
        $ruta = "publico/cv/" . $cv["cv"];
        if (file_exists("../" . $ruta)) {
            return ["ruta" => $ruta];
        }
    }
    return ["ruta" => null];
	}

	//ELIMINAR ASPIRANTE

	public static function borrarAspControlador(){
    if (isset($_GET["url"])) {
        $exp = explode("/", $_GET["url"]);

        if (isset($exp[1])) {
            $id = $exp[1];
            $tablaBD = "aspirante";
            $respuesta = aspiranteModelo::borrarAspModelo($tablaBD, $id);

            if ($respuesta == true) {
                echo '<script>
                    window.location = "../nuevos";
                </script>';
            }
        } 
    } 
}

public function actualizarAspiranteControlador($datosPost, $archivoCV, $idAspirante) {
    $tabla = "aspirante";

    // Si se subió un nuevo CV, procesarlo
    $nombreArchivo = null;
    if (isset($archivoCV["cvFile"]) && $archivoCV["cvFile"]["error"] == 0) {
        $nombreArchivo = "cv_" . date("Ymd_His") . "_" . rand(100, 999) . ".pdf";
        $rutaDestino = "Vistas/cv/" . $nombreArchivo;
        move_uploaded_file($archivoCV["cvFile"]["tmp_name"], $rutaDestino);
    }

    // Preparar array de datos
    $datos = [
        "idAspirante" => $idAspirante,
        "nombre" => $datosPost["nombre"],
        "apPaterno" => $datosPost["apPaterno"],
        "apMaterno" => $datosPost["apMaterno"],
        "fechaNacimiento" => $datosPost["fechaNacimiento"],
        "codPostal" => $datosPost["codPostal"],
        "estado" => $datosPost["estado"],
        "ciudadMun" => $datosPost["ciudadMun"],
        "colBarrio" => $datosPost["colBarrio"],
        "calleNo" => $datosPost["calleNo"],
        "telefonoCel" => $datosPost["telefonoCel"],
        "puesto" => $datosPost["puesto"]
    ];

    if ($nombreArchivo !== null) {
        $datos["cv"] = $nombreArchivo;
    }

    // Llamar al modelo
    return aspiranteModelo::actualizarAspiranteModelo($tabla, $datos);
	}


	public static function actualizarCvControlador($id, $nombreArchivo) {
	    return aspiranteModelo::actualizarCvModelo("aspirante", $id, $nombreArchivo);
	}

	
	public static function obtenerDatosCandidatoControlador($id) {
    return aspiranteModelo::obtenerDatosCandidatoModelo("aspirante", $id);
	}

	public static function obtenerComentariosControlador($id) {
	    return aspiranteModelo::obtenerComentariosModelo("comentarios", $id);
	}

	public static function agregarComentarioControlador($idAspirante, $comentario, $idPersona) {
	    return aspiranteModelo::agregarComentarioModelo("comentarios", $idAspirante, $comentario, $idPersona);
	}

	public static function autorizarCandidatoControlador($id) {
	    return aspiranteModelo::autorizarCandidatoModelo("aspirante", $id);
	}

	public static function actualizarEstadoCandidatoControlador($id, $nuevo) {
    return aspiranteModelo::actualizarEstadoCandidatoModelo("aspirante", $id, $nuevo);
	}

	public static function actualizarGeneralesControlador($datos) {

    $datosFiltrados = [
        "idAspirante"     => $datos["id"],
        "nombre"          => $datos["nombre"],
        "apPaterno"       => $datos["apPaterno"],
        "apMaterno"       => $datos["apMaterno"],
        "fechaNacimiento" => $datos["fechaNacimiento"],
        "codPostal"       => $datos["codPostal"],
        "estado"          => $datos["estado"],
        "ciudadMun"       => $datos["ciudadMun"],
        "colBarrio"       => $datos["colBarrio"],
        "calleNo"         => $datos["calleNo"],
        "telefonoCel"     => $datos["telefonoCel"],
        "correo"          => $datos["correo"],
        "fechaCaptura"    => $datos["fechaCaptura"],
        "puesto"    => $datos["puesto"],
        "nuevo"    => $datos["nuevo"]
    ];
    return aspiranteModelo::actualizarGeneralesModelo("aspirante", $datosFiltrados);
	}

	public static function actualizarEstatusAspirante($idAspirante, $idEstatus) {
    return aspiranteModelo::actualizarIdEstatusFkModelo($idAspirante, $idEstatus);
	}

	public static function obtenerEstatusClave($claveStatus) {
    return aspiranteModelo::obtenerEstatusClaveModelo($claveStatus);
	}

	public static function guardarStandbyYActualizarAspirante($idAspirante, $comentario, $idEstatus) {
    $ok = aspiranteModelo::guardarStandbyModelo($idAspirante, $comentario, $idEstatus);

    if ($ok === "ok") {
        // ✅ Actualizar directamente nuevo=3 e idestatusFk al mismo tiempo
        return aspiranteModelo::actualizarAspiranteEstadoYStatusModelo($idAspirante, 3, $idEstatus);
    }
    
    return "error";
	}

	public static function ctrBuscarClaveVacante($clave) {
	  return aspiranteModelo::mdlBuscarClaveVacante($clave);
	}

	public static function obtenerVacanteControlador($idVacante) {
    return aspiranteModelo::mdlObtenerVacante($idVacante);
	}
 
	public static function filtrarVacantesControlador($filtro) {
	    return aspiranteModelo::mdlFiltrarVacantes($filtro);
	}

	public static function actualizarVacanteControlador($idAspirante, $idVacante) {
	    return aspiranteModelo::mdlActualizarVacante($idAspirante, $idVacante);
	}

public static function actualizarNotificado($id) {
    return aspiranteModelo::actualizarNotificadoModelo($id, "aspirante");
}

public static function ctrCargarProcedencias() {
  return aspiranteModelo::mdlCargarProcedencias();
}


}