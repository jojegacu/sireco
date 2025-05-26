<?php

class conexion {

    public static function conexionBD() {
        // Detectar entorno: si es localhost usamos una config, si no, usamos la de producción
        if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
            // Entorno local
            $host = "localhost";
            $dbname = "sireco";
            $user = "root";
            $pass = "";
        } else {
            // Entorno en hosting
            $host = "sql208.byethost11.com";
            $dbname = "b11_38565310_sireco";
            $user = "b11_38565310";
            $pass = "Entrada#123";
        }

        $bd = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $bd->exec("set names utf8");
        return $bd;
    }
}
