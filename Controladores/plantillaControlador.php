<?php

include_once "Controladores/sessionHandler.php";
include_once "Modelos/permisos.php";

class plantilla{

	public function llamarPlantilla(){

		include "Vistas/plantilla.php";
	}
}