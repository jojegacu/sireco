<?php

require_once "conexion.php";

class municipiosModelo {

    // Buscar municipios por código postal
    public static function buscarPorCPModelo($codigoPostal) {
        try {
            $pdo = conexion::conexionBD()->prepare("
                SELECT * FROM estado 
                WHERE codigoPos LIKE :codigoPostal 
                LIMIT 50
            ");
            $codigoPostal = "%".$codigoPostal."%"; // Permite búsqueda parcial
            $pdo->bindParam(":codigoPostal", $codigoPostal, PDO::PARAM_STR);
            $pdo->execute();

            return $pdo->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }
}
?>
