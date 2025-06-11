<?php

class consultaCandidatoControlador {

    public static function obtenerAspiranteControlador($idAspirante) {
        return consultaCandidatoModelo::obtenerAspiranteModelo($idAspirante);
    }

    public static function obtenerEstatusControlador($idEstatus) {
        return consultaCandidatoModelo::obtenerEstatusModelo($idEstatus);
    }

    public static function obtenerContratacionControlador($idAspirante) {
        return consultaCandidatoModelo::obtenerContratacionModelo($idAspirante);
    }

    public static function reincorporarCandidatoControlador($idAspirante) {
    return consultaCandidatoModelo::reincorporarCandidatoModelo($idAspirante);
    }
    public static function eliminarAspiranteCompleto($id) {
    return consultaCandidatoModelo::eliminarAspiranteTotal($id);
    }


public static function ctrFinalizarContrato($idAspirante) {
    $valorNuevo = 4;

    // 1. Actualizar aspirante.nuevo = 4
    $okAspirante = consultaCandidatoModelo::mdlActualizarCampo("aspirante", "nuevo", $valorNuevo, "idAspirante", $idAspirante);

    // 2. Obtener el ID de la vacante vinculada
    $idVacante = consultaCandidatoModelo::obtenerVacantePorAspirante($idAspirante);

    // 3. Actualizar vacantes.estatus = 1
    $okVacante = false;
    if ($idVacante) {
        $okVacante = consultaCandidatoModelo::mdlActualizarCampo("vacantes", "estatus", 1, "idVacante", $idVacante);
    }

    // 4. Devolver éxito si ambos fueron exitosos
    return ($okAspirante && $okVacante) ? "ok" : "error";
}


public static function actualizarNotificadoControlador($id) {
    return consultaCandidatoModelo::actualizarNotificadoModelo($id);
}

public static function verificarCurpDuplicada($curp) {
  return consultaCandidatoModelo::verificarCurpDuplicadaModelo($curp);
}

public static function verificarCurpControlador($curp) {
    // Verifica si la CURP existe en la tabla contratacion
    return consultaCandidatoModelo::verificarCurpModelo("contratacion", $curp);
}

public static function actualizarAspiranteCurpControlador($curp, $idAspirante) {
    // Actualiza al aspirante marcándolo como duplicado
    return consultaCandidatoModelo::actualizarAspiranteCurpModelo("aspirante", $curp, $idAspirante);
}



}
