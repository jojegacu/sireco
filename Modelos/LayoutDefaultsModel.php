<?php
namespace Modelos;

class LayoutDefaultsModel {

    public function obtenerColumnasPorDefecto() {
        return [
            'idAspirante',
            'nombre',
            'apPaterno',
            'apMaterno',
            'fechaRegistro',
            'nuevo'
            // Puedes agregar más columnas según la base de datos y necesidad del reporte
        ];
    }
}
