<?php

if (!tieneAcceso("empleados")) {
    $baseUrl = rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/");
    echo '<script>window.location.href = "' . $baseUrl . '/inicio";</script>';
    return;
}

?>

<div class="content-wrapper">
  <section class="content-header">
    <h1>CONTRATADOS</h1>
  </section>
  <section class="content">
    <div class="box">
      <div class="box-body">
        <table id="example1" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>ASP.</th>
              <th>NOMBRE</th>
              <th>AP. PATERNO</th>
              <th>AP. MATERNO</th>
              <th>CP</th>
              <th>ESTADO</th>
              <th>MUN./CIUDAD</th>
              <th>COL/BARRIO</th>
              <th>CONSULTA</th> <!-- 🔥 NUEVO -->
            </tr>
          </thead>
          <tbody>
            <?php
            $columna = null;
            $valor = null;
            $resultado = aspiranteControlador::verAspiranteControlador($columna, $valor);
            foreach ($resultado as $key => $value) {
              if ($value["nuevo"] == 4) {
                echo '<tr>
                    <td>'.$value["idAspirante"].'</td>
                    <td>'.$value["nombre"].'</td>
                    <td>'.$value["apPaterno"].'</td>
                    <td>'.$value["apMaterno"].'</td>
                    <td>'.$value["codPostal"].'</td>
                    <td>'.$value["estado"].'</td>
                    <td>'.$value["ciudadMun"].'</td>
                    <td>'.$value["colBarrio"].'</td>
                    <td>
                      <div class="btn-group">
                        <!-- Botón Consultar Información -->
                        <button class="btn btn-info btnConsultaCandidato" data-id="'.$value["idAspirante"].'" title="Consultar Información">
                          <i class="fa fa-search"></i>
                        </button>


                        <!-- Botón Reincorporar Candidato -->
                        <button class="btn btn-success btnReincorporarCandidato" data-id="'.$value["idAspirante"].'" title="Reincorporar Candidato">
                          <i class="fa fa-undo"></i>
                        </button>

                        <!-- Botón Descargar Docs -->
                        <button class="btn btn-info btnDescargarDocs" data-id="'.$value["idAspirante"].'" title="Descargar Documentos">
                          <i class="fa fa-download"></i>
                        </button>

                        <!-- Botón Eliminar Candidato -->
                        <button class="btn btn-danger btnEliminarCandidato" data-id="'.$value["idAspirante"].'" title="Eliminar Candidato">
                          <i class="fa fa-trash"></i>
                        </button>
                      </div>
                    </td>
                  </tr>';
              }
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</div>

<!-- Modal de Consulta de Información del Candidato -->
<div class="modal fade" id="modalConsultaCandidato" tabindex="-1" role="dialog" aria-labelledby="modalConsultaCandidatoLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document"> <!-- Modal tamaño extra grande -->
    <div class="modal-content">
    
      <div class="modal-header bg-primary text-white">
        <h4 class="modal-title" id="modalConsultaCandidatoLabel">Consulta de Información del Candidato</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">

        <!-- 🔷 BLOQUE 1: Datos de Aspirante -->
        <h5 class="mb-3">Información del Aspirante</h5>
        <div class="row">
          <div class="col-md-3">
            <label>ID Aspirante</label>
            <input type="text" id="consulta_idAspirante" class="form-control" readonly>
          </div>
          <div class="col-md-3">
            <label>Nombre</label>
            <input type="text" id="consulta_nombre" class="form-control" readonly>
          </div>
          <div class="col-md-3">
            <label>Apellido Paterno</label>
            <input type="text" id="consulta_apPaterno" class="form-control" readonly>
          </div>
          <div class="col-md-3">
            <label>Apellido Materno</label>
            <input type="text" id="consulta_apMaterno" class="form-control" readonly>
          </div>
        </div>

        <div class="row mt-2">
          <div class="col-md-3">
            <label>Teléfono</label>
            <input type="text" id="consulta_telefono" class="form-control" readonly>
          </div>
           <div class="col-md-3">
            <label>Código Postal</label>
            <input type="text" id="consulta_cp" class="form-control" readonly>
          </div>
          <div class="col-md-3">
            <label>Estado</label>
            <input type="text" id="consulta_estado" class="form-control" readonly>
          </div>
          <div class="col-md-3">
            <label>Ciudad / Municipio</label>
            <input type="text" id="consulta_municipio" class="form-control" readonly>
          </div>                            
        </div>

        <div class="row mt-2">
          <div class="col-md-6">
            <label>Colonia / Barrio</label>
            <input type="text" id="consulta_colonia" class="form-control" readonly>
          </div>
          <div class="col-md-6">
            <label>Calle y Número</label>
            <input type="text" id="consulta_calleNo" class="form-control" readonly>
          </div>          
        </div>

        <div class="row mt-2">
          <div class="col-md-3">
            <label>Nacimiento</label>
            <input type="text" id="consulta_fechaNacimiento" class="form-control" readonly>
          </div>
          <div class="col-md-3">
            <label>Puesto</label>
            <input type="text" id="consulta_puesto" class="form-control" readonly>
          </div>
          <div class="col-md-3">
            <label>Currículum (CV)</label><br>
            <input type="checkbox" id="consulta_cv" disabled>
          </div>          
        </div>

        <hr>

        <!-- 🔷 BLOQUE 2: Datos de Contratación -->
        <h5 class="mb-3">Información de Contratación</h5>
        <div class="row">
          <div class="col-md-3">
            <label>Género</label>
            <input type="text" id="consulta_genero" class="form-control" readonly>
          </div>
          <div class="col-md-3">
            <label>CURP</label>
            <input type="text" id="consulta_curp" class="form-control" readonly>
          </div>
          <div class="col-md-3">
            <label>RFC</label>
            <input type="text" id="consulta_rfc" class="form-control" readonly>
          </div>
          <div class="col-md-3">
            <label>NSS</label>
            <input type="text" id="consulta_nss" class="form-control" readonly>
          </div>
        </div>

        <div class="row mt-2">
          <div class="col-md-4">
            <label>Estado Civil</label>
            <input type="text" id="consulta_estadoCivil" class="form-control" readonly>
          </div>
          <div class="col-md-4">
            <label>Teléfono Emergencia</label>
            <input type="text" id="consulta_telEmergencia" class="form-control" readonly>
          </div>
          <div class="col-md-4">
            <label>Contacto Emergencia</label>
            <input type="text" id="consulta_contactoEmergencia" class="form-control" readonly>
          </div>
        </div>

        <div class="row mt-3">
          <div class="col-md-2">
            <label>Acta Nacimiento</label><br>
            <input type="checkbox" id="consulta_actaNacimiento" disabled>
          </div>
          <div class="col-md-2">
            <label>Comprobante Domicilio</label><br>
            <input type="checkbox" id="consulta_comprobanteDomicilio" disabled>
          </div>
          <div class="col-md-2">
            <label>Situación Fiscal</label><br>
            <input type="checkbox" id="consulta_situacionFiscal" disabled>
          </div>
          <div class="col-md-2">
            <label>INE</label><br>
            <input type="checkbox" id="consulta_ine" disabled>
          </div>
          <div class="col-md-2">
            <label>Cuenta Banco</label><br>
            <input type="checkbox" id="consulta_cuentaBanco" disabled>
          </div>
        </div>

        <div class="row mt-3">
          <div class="col-md-2">
            <label>Doc NSS</label><br>
            <input type="checkbox" id="consulta_docNss" disabled>
          </div>
          <div class="col-md-2">
            <label>Doc CURP</label><br>
            <input type="checkbox" id="consulta_docCurp" disabled>
          </div>
          <div class="col-md-2">
            <label>Carta INFONAVIT</label><br>
            <input type="checkbox" id="consulta_cartaInfonavit" disabled>
          </div>
          <div class="col-md-2">
            <label>Foto</label><br>
            <input type="checkbox" id="consulta_foto" disabled>
          </div>
        </div>

         <hr>

        <!-- 🔷 BLOQUE 3: Datos de DESERCION -->
        <h5 class="mb-3">Información de Deserción</h5>
        <div class="row mt-3">          
          <div class="col-md-5">
            <label>Proceso Realizado</label>
            <input type="text" id="consulta_concepto" class="form-control" readonly>
          </div>
          <div class="col-md-5">
            <label>Estatus</label>
            <input type="text" id="consulta_estatus" class="form-control" readonly>
          </div>
        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>

    </div>
  </div>
</div>

<!-- Modal Confirmación Reincorporar -->
<div class="modal fade" id="modalReincorporarCandidato" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Reincorporar Candidato</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <p>¿Deseas reincorporar este candidato al proceso?</p>
        <input type="hidden" id="idReincorporarCandidato">
      </div>
      <div class="modal-footer justify-content-center">
        <button class="btn btn-success" id="btnConfirmarReincorporar">Sí, reincorporar</button>
        <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalEliminarCandidato" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h4 class="modal-title">Confirmar Eliminación</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p>¿Estás seguro de que deseas eliminar por completo a este candidato?</p>
        <input type="hidden" id="idEliminarCandidato">
      </div>
      <div class="modal-footer">
        <button id="btnConfirmarEliminar" class="btn btn-danger">Sí, eliminar</button>
        <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>
