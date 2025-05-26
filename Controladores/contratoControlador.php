<?php
class contratoControlador {
    public static function ctrBuscarCandidato($idAspirante) {
        $aspirante = contratoModelo::mdlObtenerAspirante($idAspirante);
        if (!$aspirante) {
            return ["success" => false];
        }

        $vacante = contratoModelo::mdlObtenerVacante($aspirante["puesto"]);
        return [
            "success" => true,
            "aspirante" => $aspirante,
            "vacante" => $vacante
        ];
    }
}
