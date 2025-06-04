<?php


class contratacionControlador {

    static public function ctrObtenerInfoAspirante($id) {
        $aspirante = contratacionModelo::mdlObtenerInfoAspirante($id);

        // Calcular edad a partir de fechaNacimiento
        if ($aspirante && isset($aspirante["fechaNacimiento"])) {
    $nacimiento = new DateTime($aspirante["fechaNacimiento"]);
    $hoy = new DateTime();
    
    $edad = $hoy->diff($nacimiento)->y;
    $aspirante["edad"] = $edad;
    
    }

        return $aspirante;
    }


        static public function ctrObtenerCatalogoPorGrupo($grupo) {
        return contratacionModelo::mdlObtenerCatalogoPorGrupo($grupo);
    }

    static public function ctrObtenerDatosContratacion($idAspirante) {
    return contratacionModelo::mdlObtenerDatosContratacion($idAspirante);
    }

    public static function ctrGuardarContratacion($datos) {
    require_once "../Modelos/contratacionModelo.php";
    return contratacionModelo::mdlGuardarContratacion($datos);
    }

    static public function ctrSubirDocumentos($post, $files) {
    $id = $post["idAspiranteDocs"];
    return contratacionModelo::mdlGuardarDocumentos($id, $files);
}

static public function ctrEliminarAspirante($id) {
    return contratacionModelo::mdlEliminarAspirante($id);
}

// Nuevo método para actualizar rutas de documentos
static public function ctrActualizarDocumentos($idAspirante, $datos) {
    return contratacionModelo::mdlActualizarDocumentos($idAspirante, $datos);
} 

static public function ctrObtenerDocumentos($idAspirante) {
    return contratacionModelo::mdlObtenerDocumentos($idAspirante);
}

static public function ctrActualizarContratacion($datos) {
    return contratacionModelo::mdlActualizarContratacion($datos);
}

public static function guardarActualizarContratacion($datos)
{
    $existe = contratacionModelo::mdlObtenerDatosContratacion($datos["idAspirante"]);

    if ($existe) {
        // Ya existe, entonces actualizamos
        return contratacionModelo::mdlActualizarContratacion($datos);
    } else {
        // No existe, insertamos
        return contratacionModelo::mdlGuardarContratacion($datos);
    }
}

public static function descargarDocumentos($idAspirante) {
    require_once "../Modelos/contratacionModelo.php";

    $cv = contratacionModelo::mdlObtenerCVPorIDAspirante($idAspirante);
    $cvPath = "../publico/cv/" . $cv;

    $documentosPath = "../publico/documentos/" . $idAspirante . "/";

    $zip = new ZipArchive();
    $tmpFile = tempnam(sys_get_temp_dir(), 'zip');
    $zip->open($tmpFile, ZipArchive::CREATE);

    // Agregar CV si existe
    if ($cv && file_exists($cvPath)) {
        $zip->addFile($cvPath, "CV_" . basename($cv));
    }

    // Agregar documentos si existen
    if (is_dir($documentosPath)) {
        $archivos = scandir($documentosPath);
        foreach ($archivos as $archivo) {
            if (!in_array($archivo, [".", ".."])) {
                $archivoPath = $documentosPath . $archivo;
                if (is_file($archivoPath)) {
                    $zip->addFile($archivoPath, "Documentos/" . $archivo);
                }
            }
        }
    }

    $zip->close();

    // Descargar el ZIP
    header('Content-Type: application/zip');
    header('Content-disposition: attachment; filename=documentos_aspirante_' . $idAspirante . '.zip');
    header('Content-Length: ' . filesize($tmpFile));
    readfile($tmpFile);
    unlink($tmpFile);
    exit;
}




}
