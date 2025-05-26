<?php
function tieneAcceso($vista) {
    if (!isset($_SESSION["idRol"])) return false;

    require_once "conexion.php";
    
    $stmt = conexion::conexionBD()->prepare("SELECT COUNT(*) FROM accesos WHERE idRolFk = :idRol AND vista = :vista");
    $stmt->bindParam(":idRol", $_SESSION["idRol"], PDO::PARAM_INT);
    $stmt->bindParam(":vista", $vista, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetchColumn() > 0;
}
?>
