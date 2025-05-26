<?php
require_once "conexion.php";

class contratoModelo {
    public static function mdlObtenerAspirante($id) {
        $stmt = conexion::conexionBD()->prepare("SELECT * FROM aspirante WHERE idAspirante = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch();

        if ($data) {
            $data["direccion"] = $data["calleNo"] . ", " . $data["colBarrio"] . ", " . $data["ciudadMun"] . ", " . $data["estado"];
        }

        return $data;
    }

    public static function mdlObtenerVacante($idVacante) {
        $stmt = conexion::conexionBD()->prepare("SELECT * FROM vacantes WHERE idVacante = :id");
        $stmt->bindParam(":id", $idVacante, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch();

        if ($data) {
    $data["direccion"] = $data["col"] . ", " . $data["mun"] . ", " . $data["edo"];
    $data["codPostal"] = $data["cp"];
    $data["responsable"] = $data["responsable"];
    }


        return $data;
    }
}
