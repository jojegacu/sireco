<?php
//Hola 
class misDatosControlador{
	
  //Ver mis datos Generales
  public function vermisDatosControlador() {
    $tablaBD = "persona";
    $id = $_SESSION['id'];
    $resultado = misDatosModelo::vermisDatosModelo($tablaBD, $id);

    echo '
        <div class="col-xs-12">
          <div class="box">
          <form method="post">
            <div class="box-body">
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                    <h2>Apellidos</h2>
                    <input class="form-control input-lg" type="text" name="apellidos" value="' . $resultado["apellidos"] . '">
                    <input type="hidden" name="usrId" value="' . $_SESSION["id"] . '">
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <h2>Nombre</h2>
                    <input class="form-control input-lg" type="text" name="nomCompleto" value="' . $resultado["nombre"] . '">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                    <h2>CURP</h2>
                    <input class="form-control input-lg" type="text" name="curp" value="' . $resultado["curp"] . '" onkeyup="javascript:this.value=this.value.toUpperCase();">
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <h2>RFC</h2>
                    <input class="form-control input-lg" type="text" name="rfc" value="' . $resultado["rfc"] . '" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                    <h2>Correo</h2>
                    <input class="form-control input-lg" type="text" name="email" value="' . $resultado["email"] . '">
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <h2>Telefono</h2>
                    <input class="form-control input-lg" type="text" name="celular" value="' . $resultado["telefono"] . '">
                    <input type="hidden" name="nuevo" value="1">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">';
                    date_default_timezone_set('America/Mexico_City');
                    $fecha_actual = date("d-m-Y h:i:s");
                    echo '<h2>Modificado: ' . $resultado["fechaCaptura"] . '</h2>
                    <input class="form-control input-lg" type="text" name="fechaCaptura" value="' . $fecha_actual . '" required readonly>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">';
                    if (isset($resultado["idRolFk"])) {
                      $tabla = "rol";
                      $roles = rolModelo::verRolModelo($resultado["idRolFk"], $tabla);
                      if ($roles && isset($roles["rol"], $roles["idRol"])) {
                        echo '<h2>Rol</h2>
                        <input class="form-control input-lg" type="text" value="' . $roles["rol"] . '" readonly>
                        <input type="hidden" name="idRolFk" value="' . $roles["idRol"] . '">';
                      } else {
                        echo '<h2 class="text-danger">Rol no encontrado</h2>';
                      }
                    }
                  echo '</div>
                </div>
              </div>
              <div class="form-group" align="center">
                <button class="btn btn-success" type="submit">ACTUALIZAR</button>
                <a href="inicio" class="btn btn-danger" btn-flat>CANCELAR</a>
              </div>
            </div>
          </form>
          </div>
        </div>
      </div>
    </section>
    <div class="col-xs-12 btn-group" style="margin-left: 35%">
      <a href="misDatos"><button class="btn btn-primary">GENERALES</button></a>
      <a href="misDatosU"><button class="btn btn-primary">USUARIO</button></a>
      <a href="misDatosE"><button class="btn btn-primary">EMPLEADO</button></a>
    </div>
    </div>';
  }

  public function guardarDatosControlador() {
    if (isset($_POST["usrId"]) && isset($_POST["idRolFk"])) {
      $tablaBD = "persona";
      $datosC = array(
        "idPersona"     => $_POST["usrId"],
        "nombre"        => $_POST["nomCompleto"],
        "apellidos"     => $_POST["apellidos"],
        "curp"          => $_POST["curp"],
        "rfc"           => $_POST["rfc"],
        "telefono"      => $_POST["celular"] ?? '',
        "email"         => $_POST["email"] ?? '',
        "fechaCaptura"  => $_POST["fechaCaptura"],
        "nuevo"         => $_POST["nuevo"],
        "idRolFk"       => $_POST["idRolFk"]
      );
      $resultado = misDatosModelo::guardarDatosModelo($tablaBD, $datosC);
      if ($resultado == true) {
        echo '<script>alert("Tus datos fueron actualizados con éxito");</script>';
        echo '<script>window.location = "misDatos";</script>';

      }
    }
  }

	   //Ver mis datos Empleado
  public function vermisDatosEControlador(){
    $tablaBD = "empleado";
    $id = $_SESSION['id'];
    $resultado = misDatosModelo::vermisDatosEModelo($tablaBD, $id);    
    echo'  
        <div class="col-xs-12">
          <div class="box" >            
            <!-- /.box-header -->
          <form method="post">
            <div class="box-body">
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                       <h2>Num. Empleado</h2>
                        <input class="form-control input-lg" type="text" name="nue" value="'.$resultado["nue"].'">
                        <input type="hidden" name="idEmp" value="'.$resultado["idEmpleado"].'">                        
                  </div>
                  <!-- /input-group -->
                </div>
                <!-- /.col-lg-6 -->
                <div class="col-lg-6">
                  <div class="form-group">
                        <h2>Puesto</h2>
                        <input class="form-control input-lg" type="text" name="puesto" value="'.$resultado["puesto"].'">
                  </div>
                  <!-- /input-group -->
                </div>
                <!-- /.col-lg-6 -->
              </div>
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                       <h2>Area</h2>
                       <input class="form-control input-lg" type="text" name="area" value="'.$resultado["area"].'">
                  </div>
                  <!-- /input-group -->
                </div>
                <!-- /.col-lg-6 -->
                <div class="col-lg-6">
                  <div class="form-group">
                      <h2>Fecha de Alta</h2>
                      <input class="form-control input-lg" type="text" name="fechaAlta" value="'.$resultado["fechaAlta"].'" required>
                      <input type="hidden" name="idPerFk" value="'.$_SESSION["id"].'">
                  </div>
                  <!-- /input-group -->
                </div>
                <!-- /.col-lg-6 -->
              </div>
            
                  <!-- /.row -->
              <div class="form-group" align="center">
                <button class="btn btn-success" type="submint">ACTUALIZAR</button>
        <a href="inicio" class="btn btn-danger" btn-flat>CANCELAR</a>         
            </div>
          </form>
            </div>              
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
      <div class="col-xs-9 btn-group" style="margin-left: 35%">
        <a href="misDatos">
          <button class="btn btn-primary">GENERALES</button>&nbsp;&nbsp;&nbsp;
        </a>
        <a href="misDatosU">
          <button class="btn btn-primary" >USUARIO</button>&nbsp;&nbsp;&nbsp;
        </a>
        <a href="misDatosE">
            <button class="btn btn-primary ">EMPLEADO</button>
        </a>
      </div>
  </div>';
  }
  //Actualizar mis datos
  public function guardarDatosEControlador(){
    if (isset($_POST["idEmp"])) {
      $tablaBD = "empleado";
      $datosC = array("idEmpleado"=>$_POST["idEmp"], "nue"=>$_POST["nue"], "puesto"=>$_POST["puesto"], "area"=>$_POST["area"], "fechaAlta"=>$_POST["fechaAlta"], "idPerFk"=>$_POST["idPerFk"]);
      $resultado = misDatosModelo::guardarDatosEModelo($tablaBD, $datosC);
            if ($resultado == true) {
        echo '<script>alert("Tus datos fueron actualizados con éxito");</script>';
        echo '<script>
          window.location = "misDatosE";
        </script>';
      }
    }
  }



  //Ver mis datos Usuario
  public function vermisDatosUControlador(){
    $tablaBD = "users";
    $id = $_SESSION['id'];    
    $resultado = misDatosModelo::vermisDatosUModelo($tablaBD, $id);
    echo'  <div class="col-xs-12">

          <div class="box" >            
            <!-- /.box-header -->
          <form method="post">
            <div class="box-body">
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                       <h2>USUARIO</h2>
                        <input class="form-control input-lg" type="text" name="usuario" value="'.$resultado["usuario"].'">
                        <input type="hidden" name="idUser" value="'.$resultado["idUser"].'">
                        <input type="hidden" name="usrId" value="'.$_SESSION["id"].'">
                  </div>
                  <!-- /input-group -->
                </div>
                <!-- /.col-lg-6 -->
                <div class="col-lg-6">
                  <div class="form-group">
                        <h2>CONTRASEÑA</h2>
                        <input class="form-control input-lg" type="text" name="password" value="'.$resultado["password"].'">
                  </div>
                  <!-- /input-group -->
                </div>
                <!-- /.col-lg-6 -->
              </div>
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                       <h2>FECHA REGISTRO</h2>
                       <input class="form-control input-lg" type="text" name="fechaAlta" value="'.$resultado["fechaAlta"].'">
                  </div>
                  <!-- /input-group -->
                </div>
                <!-- /.col-lg-6 -->
                <div class="col-lg-6">
                  <div class="form-group">
                      <h2>ACTIVO</h2>
                      <input class="form-control input-lg" type="text" name="activo" value="'.$resultado["activo"].'" required>
                  </div>
                  <!-- /input-group -->
                </div>
                <!-- /.col-lg-6 -->
              </div>
            
                  <!-- /.row -->
              <div class="form-group" align="center">
                <button class="btn btn-success" type="submint">ACTUALIZAR</button>
        <a href="inicio" class="btn btn-danger" btn-flat>CANCELAR</a>         
            </div>
          </form>
            </div>              
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
      <div class="col-xs-9 btn-group" style="margin-left: 35%">
        <a href="misDatos">
          <button class="btn btn-primary">GENERALES</button>&nbsp;&nbsp;&nbsp;
        </a>
        <a href="misDatosU">
          <button class="btn btn-primary" >USUARIO</button>&nbsp;&nbsp;&nbsp;
        </a>
        <a href="misDatosE">
            <button class="btn btn-primary ">EMPLEADO</button>
        </a>
      </div>   
  </div>';
  }
  //Actualizar mis datos
  public function guardarDatosUControlador(){
    if (isset($_POST["usrId"])) {

      $tablaBD = "users";
      $datosC = array("idUser"=>$_POST["idUser"], "usuario"=>$_POST["usuario"], "password"=>$_POST["password"], "fechaAlta"=>$_POST["fechaAlta"], "activo"=>$_POST["activo"], "idPersonaFk"=>$_POST["usrId"]);
      $resultado = misDatosModelo::guardarDatosUModelo($tablaBD, $datosC);
      

      if ($resultado == true) {
        echo '<script>alert("Tus datos fueron actualizados con éxito");</script>';
        echo '<script>
          window.location = "misDatosU";
        </script>';
      }
    }
  }
  
}