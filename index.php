<?php

require_once "Controladores/plantillaControlador.php";

require_once "Controladores/aspiranteControlador.php";
require_once "Modelos/aspiranteModelo.php";

require_once "Controladores/misDatosControlador.php";
require_once "Modelos/misDatosModelo.php";

require_once "Controladores/usuariosControlador.php";
require_once "Modelos/usuariosModelo.php";

require_once "Controladores/municipiosControlador.php";
require_once "Modelos/municipiosModelo.php";

require_once "Controladores/rolControlador.php";
require_once "Modelos/rolModelo.php";

require_once "Controladores/contratacionControlador.php";
require_once "Modelos/contratacionModelo.php";

require_once "Controladores/consultaCandidatoControlador.php";
require_once "Modelos/consultaCandidatoModelo.php";

require_once "Controladores/vacanteControlador.php";
require_once "Modelos/vacanteModelo.php";

require_once "Controladores/contratoControlador.php";
require_once "Modelos/contratoModelo.php";

require_once "Controladores/mensajesControlador.php";
require_once "Modelos/mensajesModelo.php";

require_once "Controladores/ReportesController.php";
require_once "Modelos/ReportesModel.php";
require_once "Modelos/LayoutDefaultsModel.php";


$plantilla = new plantilla();
$plantilla -> llamarPlantilla();