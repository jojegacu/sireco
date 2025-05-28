<?php

class usuariosControlador{

	//Iniciar sesion

	public function iniciarSesionControlador(){

		if (isset($_POST["usuario"])) {
			
			if (preg_match('/^[a-zA-Z0-9]+$/', $_POST["usuario"]) && preg_match('/^[a-zA-Z0-9.]+$/', $_POST["password"])) {
				
				
				$datosC = array("usuario"=>$_POST["usuario"], "password"=>$_POST["password"]);
				$resultado = usuariosModelo::iniciarSesionModelo(null, $datosC);
				
				
					if (is_array($resultado)) {
							if($resultado["usuario"] == $_POST["usuario"] && $resultado["password"] == $_POST["password"]){

							$_SESSION["ingresar"] = true;
              $_SESSION["idRol"] = $resultado["idRol"];
							$_SESSION["rol"] = $resultado["rol"];

							$_SESSION["idPersona"] = $resultado["idPersona"];
              usuariosModelo::actualizarConexionModelo($resultado["idPersona"], 1);
              $_SESSION["nombre"] = $resultado["nombre"];
							$_SESSION["apellidos"] = $resultado["apellidos"];
							$_SESSION["curp"] = $resultado["curp"];
							$_SESSION["rfc"] = $resultado["rfc"];
							
							
							$_SESSION["usuario"] = $resultado["usuario"];
							$_SESSION["password"] = $resultado["password"];
							$_SESSION["id"] = $resultado["idPersonaFk"];



							echo '<script>
								window.location = "inicio";
							</script>';
							
					}
						}	
						else{
							echo '<br><div class="alert alert-danger">
								Error al ingresar
							</div>';
						}
			

			}
		}

	}	

	//CREAR USUSARIOS

	public function crearUsuarioControlador(){

		if (isset($_POST["apellidosUsr"])) {			
			$tablaBD = "persona";

			$datosC = array("apellidos"=>$_POST["apellidosUsr"], "nombre"=>$_POST["nombreUsr"], "curp"=>$_POST["curpUsr"], "rfc"=>$_POST["rfcUsr"], "email"=>$_POST["emailUsr"], "telefono"=>$_POST["telefonoUsr"], "nuevo"=>$_POST["nuevo"], "fechaCaptura"=>$_POST["fechaCap"], "idRolFk"=>$_POST["idRolFk"]);
			$resultado = usuariosModelo::crearUsuarioModelo($tablaBD, $datosC);

			if ($resultado == true) {

				echo '<script>
					window.location = "usuarios";
				</script>';
			}
		}
 
	}

	//VER USUARIOS

	static public function verUsuariosControlador($columna, $valor){
		$tablaBD = "persona";
		$resultado = usuariosModelo::verUsuariosModelo($tablaBD, $columna, $valor);
		return $resultado;
	}

	//OBTENER DATOS GENERALES

	static public function editarUsrControlador(){
  $tablaBD = "persona";
  $exp = explode("/", $_GET["url"]);
  $id = $exp[1];
  $respuesta = usuariosModelo::editarUsrModelo($tablaBD, $id);

  if ($respuesta && is_array($respuesta)) {
    echo'<form method="post">
      <div class="box-body">
        <div class="row">              
        <div class="col-lg-6">
          <div class="form-group">
            <input type="hidden" name="uId" value="'.$respuesta["idPersona"].'">
             <h2>Apellidos</h2>
            <input class="form-control input-lg" type="text" name="apellidosUsr" value="'.$respuesta["apellidos"].'" required>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="form-group">
            <h2>Nombre</h2>
            <input class="form-control input-lg" type="text" name="nombreUsr" value="'.$respuesta["nombre"].'" required>
          </div>
        </div>
        </div>

        <div class="row">
        <div class="col-lg-6">
          <div class="form-group">
             <h2>CURP</h2>
             <input class="form-control input-lg" type="text" name="curpUsr" onkeyup="javascript:this.value=this.value.toUpperCase();" value="'.$respuesta["curp"].'" required>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="form-group">
            <h2>RFC</h2>
            <input class="form-control input-lg" type="text" name="rfcUsr" onkeyup="javascript:this.value=this.value.toUpperCase();" value="'.$respuesta["rfc"].'" required>
          </div>
        </div>
        </div>
        <div class="row">
        <div class="col-lg-6">
          <div class="form-group">
             <h2>Correo</h2>
            <input class="form-control input-lg" type="text" name="emailUsr" value="'.$respuesta["email"].'" required>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="form-group">
            <h2>Telefono</h2>             
            <input class="form-control input-lg" type="text" name="telefonoUsr" value="'.$respuesta["telefono"].'" required>                      
          </div>
        </div>
        </div>
        <div class="row">              
        <div class="col-lg-6">
          <div class="form-group">
             <h2>Modificado: '.$respuesta["fechaCaptura"].'</h2>';
             date_default_timezone_set('America/Mexico_City'); 
            $fecha_actual = date("d-m-Y h:i:s"); 

          echo'<input class="form-control input-lg" type="text" name="fechaCaptura" value="'.$fecha_actual.'" required>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="form-group">';
          $tabla = "rol";

          if (isset($respuesta["idRolFk"])) {
            $rolActual = rolModelo::verRolModelo($respuesta["idRolFk"], $tabla);
            echo '<h2>Rol actual: '.$rolActual["rol"].'</h2>';
          }

          echo '<select name="idRolFk" class="form-control" required>';
          echo '<option value="">Cambiar Rol...</option>';

          $roles = rolModelo::verRolModelo(null, $tabla);
          foreach ($roles as $value) {
            $selected = ($value["idRol"] == $respuesta["idRolFk"]) ? "selected" : "";
            echo '<option value="'.$value["idRol"].'" '.$selected.'>'.$value["rol"].'</option>';
          }
          echo '</select>
          <input type="hidden" name="nuevo" value="1">
          </div>
        </div>
        </div>
        <div class="form-group" align="center">
        <button type="submit" class="btn btn-success">MODIFICAR</button>
        <a href="../usuarios" class="btn btn-danger btn-flat">CANCELAR</a>                               
        </div>
      </form>';
  } else {
    echo '<script>
        window.location = "../usuarios";
        </script>';
  }
}


	//ACTUALIZAR DATOS GENERALES
	public static function actualizarUsrControlador(){
		if (isset($_POST["uId"])) {
			
			$tablaBD = "persona";
			$datosC = array("idPersona" => $_POST["uId"], "nombre" => $_POST["nombreUsr"], "apellidos" => $_POST["apellidosUsr"], "curp" => $_POST["curpUsr"], "rfc" => $_POST["rfcUsr"], "email" => $_POST["emailUsr"], "telefono" => $_POST["telefonoUsr"], "fechaCaptura" => $_POST["fechaCaptura"], "nuevo" => $_POST["nuevo"],"idRolFk" => $_POST["idRolFk"]);
			

			$respuesta = usuariosModelo::actualizarUsrModelo($tablaBD, $datosC);

			if ($respuesta == true) {
				echo '<script>
					window.location = "usuarios";
				</script>';
			}
		}
	}

//VER DATOS EMPLEADO
  public function editarUsrEmpControlador(){
    $tablaBD = "empleado";

    // Intentar recuperar el ID desde POST o GET
    if (isset($_POST["idUser"])) {
        $id = $_POST["idUser"];
    } elseif (isset($_GET["url"])) {
        $exp = explode("/", $_GET["url"]);
        $id = $exp[1] ?? null;
    } else {
        $id = null;
    }

    // Si no hay ID válido, mostrar error
    if (!$id) {
        echo '<div class="alert alert-danger">❌ No se encontró el usuario solicitado (ID faltante).</div>';
        return;
    }

    $respuesta = usuariosModelo::editarUsrEmpModelo($tablaBD, $id);  
     $fechaActual = date("Y-m-d");

    if (!$respuesta) {
        echo '<script>
          window.location = "../usuarios";
        </script>';
        return;
    }
      
    echo'  
          <form method="post">
            <div class="box-body">
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                       <h2>No. Seguro Social</h2>
                        <input class="form-control input-lg" type="text" name="nue" value="'.$respuesta["nue"].'">
                        <input type="hidden" name="idEmp" value="'.$respuesta["idEmpleado"].'">                        
                  </div>
                  <!-- /input-group -->
                </div>
                <!-- /.col-lg-6 -->
                <div class="col-lg-6">
                  <div class="form-group">
                        <h2>Puesto</h2>
                        <input class="form-control input-lg" type="text" name="puesto" value="'.$respuesta["puesto"].'">
                  </div>
                  <!-- /input-group -->
                </div>
                <!-- /.col-lg-6 -->
              </div>
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                       <h2>Area</h2>
                       <input class="form-control input-lg" type="text" name="area" value="'.$respuesta["area"].'">
                  </div>
                  <!-- /input-group -->
                </div>
                <!-- /.col-lg-6 -->
                <div class="col-lg-6">
                  <div class="form-group">
                      <h2>Fecha de Alta</h2>
                      <input class="form-control input-lg" type="text" name="fechaAlta" value="'.$fechaActual.'" readonly>
                      <input type="hidden" name="usrId" value="'.$respuesta["idPersonaFk"].'">
                  </div>
                  <!-- /input-group -->
                </div>
                <!-- /.col-lg-6 -->
              </div>
            
                  <!-- /.row -->
              <div class="form-group" align="center">
                <button class="btn btn-success" type="submint">ACTUALIZAR</button>
        <a href="../usuarios" class="btn btn-danger" btn-flat>CANCELAR</a>         
            </div>
          </form>';
            
  }
  //ACTUALIZAR DATOS EMPLEADO
  public function actualizarUsrEmpControlador(){
    if (isset($_POST["idEmp"])) {
      $tablaBD = "empleado";
      $datosC = array("idEmpleado"=>$_POST["idEmp"], "nue"=>$_POST["nue"], "puesto"=>$_POST["puesto"], "area"=>$_POST["area"], "fechaAlta"=>$_POST["fechaAlta"], "idPersonaFk"=>$_POST["usrId"]);
      $resultado = usuariosModelo::actualizarUsrEmpModelo($tablaBD, $datosC);
             if ($resultado === true) {
            echo '<script>
              alert("✅ Datos actualizados correctamente.");
              window.location = "usuarios";
            </script>';
            exit;
        } else {
            echo '<script>
              alert("❌ Error al actualizar.");
            </script>';
        }
    }
  }



  //VER DATOS USUARIO
  public function editarUsrUsControlador() {
    $tablaBD = "users";

    // Intentar recuperar el ID desde POST o GET
    if (isset($_POST["idUser"])) {
        $id = $_POST["idUser"];
    } elseif (isset($_GET["url"])) {
        $exp = explode("/", $_GET["url"]);
        $id = $exp[1] ?? null;
    } else {
        $id = null;
    }

    // Si no hay ID válido, mostrar error
    if (!$id) {
        echo '<div class="alert alert-danger">❌ No se encontró el usuario solicitado (ID faltante).</div>';
        return;
    }

    $respuesta = usuariosModelo::editarUsrUsModelo($tablaBD, $id);
    $fechaActual = date("Y-m-d");

    if (!$respuesta) {
        echo '<script>
          window.location = "../usuarios";
        </script>';
        return;
    }

    echo '<form method="post">
        <div class="box-body">
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                   <h2>USUARIO</h2>
                    <input class="form-control input-lg" type="text" name="usuario" value="'.$respuesta["usuario"].'">
                    <input type="hidden" name="idUser" value="'.$respuesta["idUser"].'">
                    <input type="hidden" name="usrId" value="'.$respuesta["idPersonaFk"].'">
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group">
                    <h2>CONTRASEÑA</h2>
                    <input class="form-control input-lg" type="text" name="password" value="'.$respuesta["password"].'">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                   <h2>FECHA REGISTRO</h2>
                   <input class="form-control input-lg" type="text" name="fechaAlta" value="'.$fechaActual.'" readonly>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group">
                  <h2>ACTIVO</h2>
                  <input class="form-control input-lg" type="text" name="activo" value="'.$respuesta["activo"].'" required>
              </div>
            </div>
          </div>

          <div class="form-group" align="center">
            <button class="btn btn-success" type="submit">ACTUALIZAR</button>
            <a href="../usuarios" class="btn btn-danger" btn-flat>CANCELAR</a>         
          </div>
        </div>
      </form>';
}


       

  //ACTUALIZAR DATOS USUARIO
 public function actualizarUsrUsControlador() {
    if (isset($_POST["usrId"])) {

        $tablaBD = "users";
        $datosC = array(
            "idUser"      => $_POST["idUser"],
            "usuario"     => $_POST["usuario"],
            "password"    => $_POST["password"],
            "fechaAlta"   => $_POST["fechaAlta"],
            "activo"      => $_POST["activo"],
            "idPersonaFk" => $_POST["usrId"]
        );

        $respuesta = usuariosModelo::actualizarUsrUsModelo($tablaBD, $datosC);

        if ($respuesta === true) {
            echo '<script>
              alert("✅ Datos actualizados correctamente.");
              window.location = "usuarios";
            </script>';
            exit;
        } else {
            echo '<script>
              alert("❌ Error al actualizar.");
            </script>';
        }
    }
}




	//ELIMINAR USUARIO

	public function borrarUsrControlador(){
		$exp = explode("/", $_GET["url"]);
		if (isset($exp[1])) {
    $id = $exp[1];
    // Continúa con el proceso de borrado...
  } else {
    
  }

		if (isset($id)) {
			$tablaBD = "persona";
			$respuesta = usuariosModelo::borrarUsrModelo($tablaBD, $id);

			if ($respuesta == true) {
				echo '<script>
					window.location = "../usuarios";
				</script>';
			}
		}
	}

  public static function verificaExistenciaUsuarioControlador($idPersona) {
    return usuariosModelo::verificaExistenciaUsuarioModelo("users", $idPersona);
  }

  public static function verificaExistenciaEmpleadoControlador($idPersona) {
      return usuariosModelo::verificaExistenciaEmpleadoModelo("empleado", $idPersona);
  }

  public static function registrarUsuarioControlador($datos) {
    return usuariosModelo::registrarUsuarioModelo("users", $datos);
}

public static function registrarEmpleadoControlador($datos) {
    return usuariosModelo::registrarEmpleadoModelo("empleado", $datos);
}


}