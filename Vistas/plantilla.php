<?php
include_once 'includes/depurar.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$carpeta = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$base_url = $protocolo . "://$host$carpeta/";

?>

<!DOCTYPE html>
<html>
<head>
  <script>
    const baseUrl = "<?php echo $base_url; ?>";
  </script>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>sireco</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- Estilos personalizados -->
<link rel="stylesheet" href="<?php echo $base_url; ?>Vistas/dist/css/Style.css?v=<?php echo time(); ?>">
  <!-- CSS -->
  <link rel="stylesheet" href="<?php echo $base_url; ?>Vistas/bower_components/bootstrap/dist/css/bootstrap.min.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="<?php echo $base_url; ?>Vistas/bower_components/font-awesome/css/font-awesome.min.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="<?php echo $base_url; ?>Vistas/bower_components/Ionicons/css/ionicons.min.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="<?php echo $base_url; ?>Vistas/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="<?php echo $base_url; ?>Vistas/dist/css/AdminLTE.min.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="<?php echo $base_url; ?>Vistas/dist/css/skins/_all-skins.min.css?v=<?php echo time(); ?>">

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js?v=<?php echo time(); ?>"></script>
 

  <!-- IE support -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js?v=<?php echo time(); ?>"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js?v=<?php echo time(); ?>"></script>
  <![endif]-->
</head>

<body class="hold-transition skin-black-light sidebar-mini login-page">

<?php
if (isset($_SESSION["ingresar"]) && $_SESSION["ingresar"] == true) {
    echo '<div class="wrapper">';
    include "modulos/cabecera.php";

    include_once "Modelos/permisos.php";

    $menuPermitido = false;
    $vistasMenu = [
        "usuarios", "nuevos", "checkManager", "contratacion", "roles", "municipios",  "reportes", "empleados", "standby", "vacante", "altaVac", "dashboard", "masiva"
    ];

    foreach ($vistasMenu as $vista) {
        if (tieneAcceso($vista)) {
            $menuPermitido = true;
            break;
        }
    }

    if ($menuPermitido) {
        include "modulos/menu.php";
    }

    $url = [];

    if (isset($_GET["url"])) {
        $url = explode("/", $_GET["url"]);
        if (in_array($url[0], [
            "inicio", "salir", "misDatos", "misDatosU", "misDatosE", "roles", "editarRol",
            "nuevos", "crearUsr", "editarUsr", "editarUsrUs", "editarUsrEmp", "editarUsrEst",
            "editarUsrAct", "usuarios", "asignarUsr", "proyectos", "altaVac", "masiva", "reportes", "proyectoTecnico", "municipios", "empleados", "standby", "autoridades", "vacante",
            "contratista", "clasificarProy", "dashboard", "generales", "generalMan", "checkManager", "contratacion"
        ])) {
            include "modulos/" . $url[0] . ".php";
        }
    } else {
        include "modulos/inicio.php";
    }

    echo '</div>';

} else if (isset($_GET["url"])) {

    $url = $_GET["url"];
    $vistasPublicas = ["ingresar", "candidatos", "contratos"];

    if (in_array($url, $vistasPublicas)) {
        include "modulos/" . $url . ".php";
    } else {
        // Redirige al login si la ruta no es válida
        echo '<script>window.location.href = "'.$base_url.'ingresar";</script>';
        exit;
    }

}
 else {
    include "modulos/ingresar.php";
}
?>

<!-- JS -->
<script src="<?php echo $base_url; ?>Vistas/bower_components/jquery/dist/jquery.min.js?v=<?php echo time(); ?>"></script>
<script src="<?php echo $base_url; ?>Vistas/bower_components/bootstrap/dist/js/bootstrap.min.js?v=<?php echo time(); ?>"></script>
<script src="<?php echo $base_url; ?>Vistas/bower_components/jquery-slimscroll/jquery.slimscroll.min.js?v=<?php echo time(); ?>"></script>
<script src="<?php echo $base_url; ?>Vistas/bower_components/fastclick/lib/fastclick.js?v=<?php echo time(); ?>"></script>
<script src="<?php echo $base_url; ?>Vistas/dist/js/adminlte.min.js?v=<?php echo time(); ?>"></script>
<script src="<?php echo $base_url; ?>Vistas/dist/js/pages/dashboard2.js?v=<?php echo time(); ?>"></script>
<script src="<?php echo $base_url; ?>Vistas/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js?v=<?php echo time(); ?>"></script>
<script src="<?php echo $base_url; ?>Vistas/dist/js/demo.js?v=<?php echo time(); ?>"></script>

<script src="<?php echo $base_url; ?>Vistas/bower_components/datatables.net/js/jquery.dataTables.min.js?v=<?php echo time(); ?>"></script>
<script src="<?php echo $base_url; ?>Vistas/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js?v=<?php echo time(); ?>"></script>
<script src="<?php echo $base_url; ?>Vistas/bower_components/chart.js/Chart.js?v=<?php echo time(); ?>"></script>

<script src="<?php echo $base_url; ?>Vistas/js/aspirantes.js?v=<?php echo time(); ?>"></script>
<script src="<?php echo $base_url; ?>Vistas/js/usuarios.js?v=<?php echo time(); ?>"></script>
<script src="<?php echo $base_url; ?>Vistas/js/empleado.js?v=<?php echo time(); ?>"></script>
<script src="<?php echo $base_url; ?>Vistas/js/contratacion.js?v=<?php echo time(); ?>"></script>
<script src="<?php echo $base_url; ?>Vistas/js/consultaCandidato.js?v=<?php echo time(); ?>"></script>
<script src="<?php echo $base_url; ?>Vistas/js/vacante.js?v=<?php echo time(); ?>"></script>
<script src="<?php echo $base_url; ?>Vistas/js/contrato.js?v=<?php echo time(); ?>"></script>
<script src="<?php echo $base_url; ?>Vistas/js/mensajes.js?v=<?php echo time(); ?>"></script>
<script src="<?php echo $base_url; ?>Vistas/js/reportes.js?v=<?php echo time(); ?>"></script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  $(document).ready(function () {
    $('.sidebar-menu').tree();
  });

  $(function () {
    $('#example1').DataTable();
    $('#example2').DataTable({
      'paging': true,
      'lengthChange': false,
      'searching': false,
      'ordering': true,
      'info': true,
      'autoWidth': false
    });
  });
</script>

<!-- Modal Chat -->
<div class="modal fade" id="modalChat" tabindex="-1" role="dialog" aria-labelledby="chatModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document" style="width: 90%;">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Chat en línea</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body row">
        <!-- Columna de usuarios -->
        <div class="col-md-4 border-right" style="max-height: 500px; overflow-y: auto;">
          <h5>Operadores conectados</h5>
          <ul id="listaUsuarios" class="list-group"></ul>
        </div>

        <!-- Columna del chat -->
        <div class="col-md-8">
          <div id="chatHeader" class="mb-2 font-weight-bold"></div>
          <div id="chatHistorial" style="height: 350px; overflow-y: auto; border: 1px solid #ccc; padding: 10px; background: #f9f9f9;"></div>

          <div class="input-group mt-3">
            <input type="text" class="form-control" id="mensajeInput" placeholder="Escribe un mensaje...">
            <div class="input-group-append">
              <button class="btn btn-primary" id="enviarMensaje">Enviar</button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>


</body>
</html>
