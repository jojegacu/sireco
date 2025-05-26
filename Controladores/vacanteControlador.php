<?php

use PhpOffice\PhpSpreadsheet\IOFactory;
require __DIR__ . "/../vendor/autoload.php";

class vacanteControlador {

    static public function ctrObtenerRegiones() {
        return vacanteModelo::mdlObtenerRegiones("region");
    }

    static public function ctrObtenerPuestos() {
        return vacanteModelo::mdlObtenerPuestos("catalogos");
    }

    static public function ctrRegistrarVacante($datos) {
	 	return vacanteModelo::mdlRegistrarVacante("vacantes", $datos);
	}

	static public function ctrVerificarClave($clave) {
	  return vacanteModelo::mdlVerificarClave("vacantes", $clave);
	}

	static public function ctrMostrarVacantes() {
	  return vacanteModelo::mdlMostrarVacantes("vacantes");
	}

	static public function ctrEliminarVacante($id) {
	  return vacanteModelo::mdlEliminarVacante("vacantes", $id);
	}

	static public function ctrObtenerVacantePorId($id) {
	  return vacanteModelo::mdlObtenerVacantePorId("vacantes", $id);
	}

	static public function ctrActualizarVacante($datos) {
	  return vacanteModelo::mdlActualizarVacante("vacantes", $datos);
	}

	public static function ctrProcesarExcel($archivo) {    

    $nombreTemporal = $archivo["tmp_name"];

    try {
      $documento = IOFactory::load($nombreTemporal);
      $hojas = $documento->getSheetNames();
      $datos = [];

      // ✅ Generar clave para todo el bloque
      $fecha = date("ymd");
		$vocales = ['A', 'E', 'I', 'O', 'U'];
		$intentos = 0;

		do {
		  $numAleatorio = str_pad(rand(0, 99), 2, "0", STR_PAD_LEFT);
		  $vocal = $vocales[array_rand($vocales)];
		  $claveBloque = $fecha . $numAleatorio . $vocal;
		  $existe = vacanteModelo::mdlExisteCategoria("vacantes", $claveBloque);
		  $intentos++;
		  if ($intentos > 10) {
		    return ["success" => false, "mensaje" => "❌ No se pudo generar una clave única. Intenta nuevamente."];
		  }
		} while ($existe);


      foreach ($documento->getAllSheets() as $hoja) {
        $filas = $hoja->toArray(null, true, true, true);

        foreach ($filas as $i => $fila) {
          if (!isset($fila["A"], $fila["B"], $fila["C"], $fila["D"], $fila["E"])) {
			  continue; // Saltar filas incompletas o vacías
			}

			if (
			  strtoupper(trim($fila["B"])) === "REGIÓN" || 
			  strtoupper(trim($fila["E"])) === "VACANTE"
			) {
			  continue; // Saltar encabezados duplicados
		}

          // Normaliza los campos
			$id            = isset($fila["A"]) ? trim($fila["A"]) : null;
			$regionNombre  = isset($fila["B"]) ? trim($fila["B"]) : null;
			$tienda        = isset($fila["C"]) ? trim($fila["C"]) : null;
			$responsable   = isset($fila["D"]) ? trim($fila["D"]) : null;
			$vacanteNombre = isset($fila["E"]) ? trim($fila["E"]) : null;

			// Valida que realmente tengan contenido
			if (
			    empty($id) ||
			    empty($regionNombre) ||
			    empty($tienda) ||
			    empty($responsable) ||
			    empty($vacanteNombre)
			) {
			  return ["success" => false, "mensaje" => "❌ Datos incompletos en fila $i"];
			}


          // 🔄 Buscar IDs relacionados
          $idRegionFk = vacanteModelo::mdlBuscarRegionPorNombre($regionNombre);
          if (!$idRegionFk) {
            return ["success" => false, "mensaje" => "❌ Región no encontrada: $regionNombre (fila $i)"];
          }

          $idCatalogoFk = vacanteModelo::mdlBuscarCatalogoPorConcepto($vacanteNombre);
          if (!$idCatalogoFk) {
            return ["success" => false, "mensaje" => "❌ Vacante no válida: $vacanteNombre (fila $i)"];
          }

          $datos[] = [
            "id" => $id,
            "categoria" => $claveBloque,
            "idRegionFk" => $idRegionFk,
            "tienda" => $tienda,
            "responsable" => $responsable,
            "idCatalogoFk" => $idCatalogoFk,
            "fechaAlta" => date("Y-m-d"),
            "clave" => $claveBloque
          ];
        }
      }

      // 🔄 Insertar todas las vacantes
      $resultado = vacanteModelo::mdlInsertarBloqueVacantes($datos);
      if ($resultado === "ok") {
        return ["success" => true, "mensaje" => "✅ Se cargaron " . count($datos) . " vacantes con clave $claveBloque"];
      } else {
        return ["success" => false, "mensaje" => "❌ Error al insertar el bloque en la base de datos."];
      }

    } catch (Exception $e) {
      return ["success" => false, "mensaje" => "❌ Error al leer Excel: " . $e->getMessage()];
    }
  }

  static public function ctrEliminarPorCategoria($categoria) {
  return vacanteModelo::mdlEliminarPorCategoria("vacantes", $categoria);
}

public static function obtenerVacantesControlador() {
  $tabla = "vacantes";
  return vacanteModelo::mdlListarVacantesParaSeleccion($tabla);
}

public static function generarClaveVacanteControlador($idVacante) {
  $tabla = "vacantes";
  return vacanteModelo::mdlGenerarYGuardarClave($tabla, $idVacante);
}


}
