<?php

class municipiosControlador {

    // Buscar municipios por código postal
    public static function buscarPorCPControlador($codigoPostal) {
        return municipiosModelo::buscarPorCPModelo($codigoPostal);
    }
}
?>
