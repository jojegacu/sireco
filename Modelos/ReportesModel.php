<?php
require_once "conexion.php";

class ReportesModel
{
    public static function obtenerAspirantesPorEstatus($estatus)
    {
        try {
            $stmt = conexion::conexionBD()->prepare("
                SELECT 
                    a.idAspirante,
                    a.nombre,
                    a.apPaterno,
                    a.apMaterno,
                    a.fechaNacimiento,
                    a.codPostal,
                    a.estado,
                    a.ciudadMun,
                    a.colBarrio,
                    a.calleNo,
                    a.telefonoCel,
                    a.fechaCaptura,
                    a.puesto AS puesto_id,
                    v.clave AS clave_vacante,
                    v.id AS categoria_vacante,
                    v.categoria AS categoria_vacante,
                    c.concepto AS procedencia_concepto,
                    c.valor AS procedencia_valor,
                    a.anotacion
                FROM aspirante a
                LEFT JOIN vacantes v ON a.puesto = v.idVacante
                LEFT JOIN catalogos c ON a.idProcedenciaFk = c.idCatalogo
                WHERE a.nuevo = :estatus
            ");

            $stmt->bindParam(":estatus", $estatus, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        }
    }

    public static function obtenerPorEstatusYFechas($estatus, $fechaInicio = null, $fechaFin = null) {
    try {
        $conexion = conexion::conexionBD();
        $sql = "
            SELECT 
                a.idAspirante, a.nombre, a.apPaterno, a.apMaterno, a.fechaNacimiento,
                a.codPostal, a.estado, a.ciudadMun, a.colBarrio, a.calleNo,
                a.telefonoCel, a.fechaCaptura, a.puesto AS puesto_id,
                v.clave AS clave_vacante, v.id AS categoria_vacante, v.categoria AS categoria_vacante,
                c.concepto AS procedencia_concepto, c.valor AS procedencia_valor,
                a.anotacion
            FROM aspirante a
            LEFT JOIN vacantes v ON a.puesto = v.idVacante
            LEFT JOIN catalogos c ON a.idProcedenciaFk = c.idCatalogo
            WHERE a.nuevo = :estatus
        ";

        if (!empty($fechaInicio)) {
            $sql .= " AND a.fechaCaptura >= :fechaInicio";
        }

        if (!empty($fechaFin)) {
            $sql .= " AND a.fechaCaptura <= :fechaFin";
        }

        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(":estatus", $estatus, PDO::PARAM_INT);

        if (!empty($fechaInicio)) {
            $stmt->bindParam(":fechaInicio", $fechaInicio);
        }

        if (!empty($fechaFin)) {
            $stmt->bindParam(":fechaFin", $fechaFin);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return ["error" => $e->getMessage()];
    }
}

    public static function obtenerDatosAspiranteLayout($idAspirante) {
        try {
            // Verificar que el ID sea válido
            if (empty($idAspirante) || !is_numeric($idAspirante)) {
                return ["error" => "ID de aspirante inválido o vacío: " . print_r($idAspirante, true)];
            }

            $conexion = conexion::conexionBD();
            if (!$conexion) {
                return ["error" => "No se pudo establecer conexión con la base de datos"];
            }
            
            // Primero verificamos si el aspirante existe (sin filtro de estatus)
            $checkSql = "SELECT idAspirante, nuevo, nombre, apPaterno, apMaterno FROM aspirante WHERE idAspirante = :idAspirante";
            $checkStmt = $conexion->prepare($checkSql);
            $checkStmt->bindParam(":idAspirante", $idAspirante, PDO::PARAM_INT);
            $checkStmt->execute();
            $aspirante = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$aspirante) {
                return ["error" => "Aspirante no encontrado con ID: " . $idAspirante];
            }
            
            // Vamos a obtener los datos básicos y continuar con el proceso
            // sin verificar la tabla de contratación, ya que no sabemos su estructura exacta
            
            // Consulta principal con los nombres exactos de los campos en cada tabla
            $sql = "
                SELECT 
                    a.idAspirante, a.nombre, a.apPaterno, a.apMaterno, a.fechaNacimiento,
                    a.codPostal, a.colBarrio, a.calleNo, a.telefonoCel,
                    c.nss, c.rfcAsp, c.curpAsp, c.genero, c.edoCivil
                FROM aspirante a
                LEFT JOIN contratacion c ON a.idAspirante = c.idAspiranteFk
                WHERE a.idAspirante = :idAspirante
            ";

            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(":idAspirante", $idAspirante, PDO::PARAM_INT);
            $stmt->execute();
            
            $datos = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$datos) {
                // Si no se encontraron datos completos, devolvemos al menos la info básica del aspirante
                return [
                    "idAspirante" => $aspirante["idAspirante"],
                    "nombre" => $aspirante["nombre"] ?? '',
                    "apPaterno" => $aspirante["apPaterno"] ?? '',
                    "apMaterno" => $aspirante["apMaterno"] ?? '',
                    "nuevo" => $aspirante["nuevo"] ?? '',
                    "warning" => "Datos incompletos del aspirante"
                ];
            }
            
            return $datos;
        } catch (PDOException $e) {
            return ["error" => "Error en la base de datos: " . $e->getMessage()];
        } catch (Exception $e) {
            return ["error" => "Error general: " . $e->getMessage()];
        }
    }

}
