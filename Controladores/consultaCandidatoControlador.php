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
    $tabla = "aspirante";
    $campo = "nuevo";
    $valor = 4;

    $respuesta = consultaCandidatoModelo::mdlActualizarCampo($tabla, $campo, $valor, "idAspirante", $idAspirante);
    return $respuesta ? "ok" : "error";
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
