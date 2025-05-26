<?php

class rolControlador {

  // Crear rol
  public function crearRolControlador() {
    if (isset($_POST["rol"], $_POST["descripcion"], $_POST["fechaProy"]) &&
        !empty($_POST["rol"]) && !empty($_POST["descripcion"])) {
      
      $tablaBD = "rol";
      $rol = [
        "rol" => $_POST["rol"],
        "descripcion" => $_POST["descripcion"],
        "fechAlta" => $_POST["fechaProy"]
      ];

      $respuesta = rolModelo::crearRolModelo($tablaBD, $rol);

      if ($respuesta) {
        echo '<script>window.location = "roles";</script>';
        exit;
      }
    }
  }

  // Ver roles
  public static function verRolControlador() {
    $tablaBD = "rol";
    $id = null;
    return rolModelo::verRolModelo($id, $tablaBD);
  }

  // Editar rol
  public function editarRolControlador() {
    $tablaBD = "rol";
    $exp = explode("/", $_GET["url"] ?? '');
    $id = isset($exp[1]) ? intval($exp[1]) : null;

    if (!$id) {
      echo '<div class="alert alert-warning">ID no válido.</div>';
      return;
    }

    $respuesta = rolModelo::editarRolModelo($tablaBD, $id);

    if (!$respuesta || !is_array($respuesta)) {
      echo '<div class="alert alert-danger">❌ No se pudo cargar la información del rol. Verifica si existe en la base de datos.</div>';
      return;
    }

    echo '<div class="row">
      <div class="col-md-6 col-xs-6">
        <h2>ROL DE OPERACIONES</h2>
        <input type="text" name="rol" class="form-control input-control input.lg" 
          value="'.htmlspecialchars($respuesta["rol"]).'" 
          onkeyup="javascript:this.value=this.value.toUpperCase();" required>
        <input type="hidden" name="rId" value="'.htmlspecialchars($respuesta["idRol"]).'">					
      </div>
      <div class="col-md-6 col-xs-6">
        <h2>DESCRIPCIÓN DEL ROL</h2>									
        <input type="text" name="descripcion" class="form-control input-control input.lg" 
          value="'.htmlspecialchars($respuesta["descripcion"]).'" required>				
      </div>
      <div class="col-md-6 col-xs-6">
        <input type="hidden" name="fechAlta" value="'.htmlspecialchars($respuesta["fechAlta"]).'">
      </div>
    </div>
    <br>
    <button class="btn btn-success" type="submit">ACTUALIZAR</button>';
   $baseUrl = rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/");
    echo '<a href="'.$baseUrl.'/roles" class="btn btn-danger btn-flat">CANCELAR</a>';
  }


  // Actualizar rol
  public function actualizarRolControlador() {
    if (isset($_POST["rId"], $_POST["rol"], $_POST["descripcion"], $_POST["fechAlta"]) &&
        !empty($_POST["rol"]) && !empty($_POST["descripcion"])) {

      $tablaBD = "rol";
      $datosC = [
        "id" => $_POST["rId"],
        "rol" => $_POST["rol"],
        "descripcion" => $_POST["descripcion"],
        "fechAlta" => $_POST["fechAlta"]
      ];

      $respuesta = rolModelo::actualizarRolModelo($tablaBD, $datosC);

      if ($respuesta) {
        $baseUrl = rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/");
         echo '<script>window.location.href = "' . $baseUrl . '/roles";</script>';
        exit;
      }
    }
  }

  // Borrar rol
  public function borrarRolControlador() {
  if (!isset($_GET["url"])) return;

  $exp = explode("/", $_GET["url"]);

  // Solo continúa si es una URL tipo roles/{id}
  if (count($exp) !== 2 || !is_numeric($exp[1])) {
    return; // ⛔ No mostrar ningún mensaje si solo estás en roles
  }

  $id = intval($exp[1]);
  $tablaBD = "rol";

  $respuesta = rolModelo::borrarRolModelo($tablaBD, $id);

  if ($respuesta) {
    $baseUrl = rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/");
    echo '<script>window.location.href = "' . $baseUrl . '/roles";</script>';
    exit;
  } else {
    echo '<div class="alert alert-danger">❌ No se pudo borrar el rol.</div>';
  }
}


}
