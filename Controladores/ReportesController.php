<?php
require_once dirname(__DIR__) . "/Modelos/ReportesModel.php";

class ReportesController
{
    public function generarPorEstatus($estatus, $fechaInicio = null, $fechaFin = null) {
        return ReportesModel::obtenerPorEstatusYFechas($estatus, $fechaInicio, $fechaFin);
    }
    
    public function exportarCSV($estatus, $fechaInicio = null, $fechaFin = null, $columnasVisibles = null) {
        // Obtener todos los datos según los filtros de fechas
        $datos = ReportesModel::obtenerPorEstatusYFechas($estatus, $fechaInicio, $fechaFin);
        
        if (empty($datos) || isset($datos['error'])) {
            return false;
        }
        
        // Filtrar columnas si se especificaron columnas visibles
        if ($columnasVisibles && !empty($columnasVisibles)) {
            $columnasArray = json_decode($columnasVisibles, true);
            if (is_array($columnasArray) && !empty($columnasArray)) {
                // Filtrar los datos para incluir solo las columnas visibles
                $datosFiltrados = [];
                foreach ($datos as $fila) {
                    $filaFiltrada = [];
                    foreach ($fila as $columna => $valor) {
                        if (in_array($columna, $columnasArray)) {
                            $filaFiltrada[$columna] = $valor;
                        }
                    }
                    $datosFiltrados[] = $filaFiltrada;
                }
                $datos = $datosFiltrados;
            }
        }
        
        // Configurar encabezados para descarga CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=reporte_' . date('Y-m-d') . '.csv');
        
        // Crear salida PHP para CSV
        $output = fopen('php://output', 'w');
        
        // Agregar BOM para UTF-8 (para que Excel reconozca caracteres especiales)
        fputs($output, "\xEF\xBB\xBF");
        
        if (!empty($datos)) {
            // Escribir encabezados
            fputcsv($output, array_keys($datos[0]));
            
            // Escribir datos
            foreach ($datos as $fila) {
                fputcsv($output, $fila);
            }
        } else {
            // Si no hay datos, escribir un encabezado indicando esto
            fputcsv($output, ['No hay datos para exportar']);
        }
        
        fclose($output);
        exit;
    }
    
    public function exportarLayoutAspirante($idAspirante) {
        // Validar que tengamos un ID de aspirante válido
        if (empty($idAspirante) || !is_numeric($idAspirante) || $idAspirante <= 0) {
            echo json_encode(['error' => 'ID de aspirante inválido: ' . $idAspirante]);
            return false;
        }
        
        // Obtener datos del aspirante
        $datos = ReportesModel::obtenerDatosAspiranteLayout($idAspirante);
        
        // Si hay un error específico del modelo, mostrarlo
        if (isset($datos['error'])) {
            echo json_encode(['error' => $datos['error']]);
            return false;
        }
        
        // Si no hay datos, mostrar mensaje genérico
        if (empty($datos)) {
            echo json_encode(['error' => 'No se encontraron datos del aspirante con ID: ' . $idAspirante]);
            return false;
        }
        
        // Definir las columnas del layout en el orden requerido
        $columnas = [
            'NSS',
            'Apellido Paterno',
            'Apellido Materno',
            'Nombre',
            'RFC',
            'CURP',
            'Género',
            'Fecha de nacimiento',
            'Estado civil (código)',
            'Email',
            'Tipo de contrato (código)',
            'Régimen de contratación (código)',
            'Fecha antigüedad',
            'Fecha de ingreso',
            'Tipo de trabajador',
            'Prestación (código)',
            'Registro patronal',
            'Estado (clave o descripción)',
            'Municipio',
            'Patrona (código)',
            'Sucursal (código)',
            'Estructura (código)',
            'Puesto (código)',
            'Tipo Pago (Neto, Bruto)',
            'Periodicidad',
            'Tipo de cálculo',
            'Salario diario',
            'SBC',
            'Forma pago (código)',
            'Banco',
            'Cuenta',
            'Clabe',
            'Emisora/Cuenta patronal (código)',
            'Fondo privado (código)',
            'Emisora/Cuenta fondo (código)',
            'Recibo Previsión',
            'Proveedor del Servicio (código)',
            'Salario ordinario',
            'Horario (código)',
            'Tipo esquema (Trabajador, Asimilado, Fondo)',
            'Folio IFE',
            'Calle',
            'Número',
            'Colonia',
            'Código Postal',
            'Afiliar a (código)',
            'Clave Trabajador',
            'Proceso',
            'SBC por antigüedad (sí, no)'
        ];
        
        // Extraer datos con los nombres exactos de los campos
        $calle = $datos['calleNo'] ?? '';
        $numero = $datos['telefonoCel'] ?? '';
        
        // Mapear los datos del aspirante al formato del layout (con valores predefinidos)
        // Usando SOLO los campos que existen en las tablas según lo indicado
        $layoutData = [
            $datos['nss'] ?? '',                             // NSS (de contratacion)
            $datos['apPaterno'] ?? '',                       // Apellido Paterno (de aspirante)
            $datos['apMaterno'] ?? '',                       // Apellido Materno (de aspirante)
            $datos['nombre'] ?? '',                          // Nombre (de aspirante)
            $datos['rfcAsp'] ?? '',                          // RFC (de contratacion)
            $datos['curpAsp'] ?? '',                         // CURP (de contratacion)
            '0' . ($datos['genero'] ?? ''),                  // Género (de contratacion) con cero al inicio
            $datos['fechaNacimiento'] ?? '',                 // Fecha de nacimiento (de aspirante)
            '0' . ($datos['edoCivil'] ?? ''),                // Estado civil (código) (de contratacion) con cero al inicio
            '',                                              // Email - vacío
            '01',                                            // Tipo de contrato (código) - predefinido
            '02',                                            // Régimen de contratación (código) - predefinido
            '',                                              // Fecha antigüedad - vacío (a pedido del usuario)
            '',                                              // Fecha de ingreso - vacío (a pedido del usuario)
            'CONFIANZA',                                     // Tipo de trabajador - predefinido
            'PML',                                           // Prestación (código) - predefinido
            'Y6263077106',                                   // Registro patronal - predefinido
            'Ciudad de México',                             // Estado (clave o descripción) - predefinido
            'BENITO JUAREZ',                                 // Municipio - predefinido
            'OBX',                                           // Patrona (código) - predefinido
            '',                                              // Sucursal (código) - vacío
            '',                                              // Estructura (código) - vacío
            '',                                              // Puesto (código) - vacío
            'NETO',                                          // Tipo Pago (Neto, Bruto) - predefinido
            '04',                                            // Periodicidad - predefinido
            '',                                              // Tipo de cálculo - vacío
            '',                                              // Salario diario - vacío
            '',                                              // SBC - vacío
            '',                                              // Forma pago (código) - vacío
            '',                                              // Banco - vacío
            '',                                              // Cuenta - vacío
            '',                                              // Clabe - vacío
            '18434-SALARIOS',                                // Emisora/Cuenta patronal (código) - predefinido
            'OBX',                                           // Fondo privado (código) - predefinido
            '42871-FONDOS',                                  // Emisora/Cuenta fondo (código) - predefinido
            'NO',                                            // Recibo Previsión - predefinido
            'OBX',                                           // Proveedor del Servicio (código) - predefinido
            '',                                              // Salario ordinario - vacío
            '01',                                            // Horario (código) - predefinido
            'Trabajador',                                    // Tipo esquema - predefinido
            '',                                              // Folio IFE - vacío
            $calle,                                          // Calle (de aspirante)
            $numero,                                         // Número (de aspirante)
            $datos['colBarrio'] ?? '',                       // Colonia (de aspirante)
            isset($datos['codPostal']) ? strval($datos['codPostal']) : '',  // Código Postal (de aspirante) - respetando ceros
            'SJRZ',                                          // Afiliar a (código) - predefinido
            '',                                              // Clave Trabajador - vacío
            '',                                              // Proceso - vacío
            'SI'                                             // SBC por antigüedad - predefinido
        ];
        
        // Crear un archivo temporal
        $tmpFile = tempnam(sys_get_temp_dir(), 'layout_');
        $handle = fopen($tmpFile, 'w');
        
        // Agregar BOM para UTF-8
        fputs($handle, "\xEF\xBB\xBF");
        
        // Escribir encabezados
        fputcsv($handle, $columnas);
        
        // Escribir la fila de datos (convirtiendo todo a texto)
        $layoutDataStrings = array_map('strval', $layoutData);
        fputcsv($handle, $layoutDataStrings);
        
        fclose($handle);
        
        // Leer el contenido generado
        $content = file_get_contents($tmpFile);
        unlink($tmpFile); // Eliminar el archivo temporal
        
        // Modificar el contenido para asegurarnos que los códigos postales se tratan como texto
        // Excel interpreta como texto cuando hay un espacio antes del valor
        $content = preg_replace('/(,)(0+\d+)/', '$1="$2"', $content);
        
        // Configurar encabezados para descarga CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=layout_' . ($datos['nombre'] ?? 'aspirante') . '_' . date('Y-m-d') . '.csv');
        header('Content-Length: ' . strlen($content));
        
        // Enviar el contenido modificado y finalizar
        echo $content;
        exit;
    }
}
