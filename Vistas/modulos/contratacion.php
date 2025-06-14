<?php

if (!tieneAcceso("contratacion")) {
    $baseUrl = rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/");
    echo '<script>window.location.href = "' . $baseUrl . '/inicio";</script>';
    return;
}

?>

<div class="content-wrapper">
  <section class="content-header">
    <h1>EN PROCESO DE CONTRATACION</h1>
  </section>
  <section class="content">
    <div class="box">
      <div class="box-body">
        <table id="example1" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>ASP.</th>
              <th>NOMBRE</th>
              <th>AP. PAT</th>
              <th>AP. MAT</th>
              <th>CP</th>
              <th>ESTADO</th>
              <th>TELEFONO</th>
              <th>MAIL</th>
              <th>CLASIFICAR</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $columna = null;
            $valor = null;
            $resultado = aspiranteControlador::verAspiranteControlador($columna, $valor);
            foreach ($resultado as $key => $value) {
              if ($value["nuevo"] == 2) {
                echo '<tr>
                    <td>'.$value["idAspirante"].'</td>
                    <td>'.$value["nombre"].'</td>
                    <td>'.$value["apPaterno"].'</td>
                    <td>'.$value["apMaterno"].'</td>
                    <td>'.$value["codPostal"].'</td>
                    <td>'.$value["estado"].'</td>
                    <td>'.$value["telefonoCel"].'</td>
                    <td>'.$value["correo"].'</td>
                    <td>
                      <div class="btn-group">
                        <button class="btn btn-success btnChatWhatsapp" data-id="'.$value["idAspirante"].'" data-tel="'.$value["telefonoCel"].'" title="Chatear por WhatsApp">
                          <i class="fa fa-whatsapp"></i>
                        </button>
                         <!-- Comentarios -->
                          <button class="btn btn-secondary btnAutorizar" data-id="'.$value["idAspirante"].'" title="Comentarios">
                              <i class="fa fa-bullhorn"></i>
                          </button>
                        <button class="btn btn-primary btnGenerarContrato" data-id="'.$value["idAspirante"].'" title="Contratacion">
                          <i class="fa fa-file-text-o"></i>
                        </button>                        
                        <button class="btn btn-warning btnCargarDocs" data-id="'.$value["idAspirante"].'" title="Cargar Documentos">
                          <i class="fa fa-upload"></i>
                        </button>                                               
                        <!-- Eliminar -->
                        <button class="btn btn-danger btnEliminarCandidato" data-id="'.$value["idAspirante"].'" data-nuevo="'.$value["nuevo"].'" title="Eliminar">
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

<!-- Modal WhatsApp -->
<div class="modal fade" id="modalWhatsapp" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h4 class="modal-title">Chatear por WhatsApp</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body text-center">
        <p id="nombreAspiranteWA" class="font-weight-bold mb-2"></p>
        <div class="form-group text-left">
          <label for="mensajeWA">Mensaje:</label>
          <textarea id="mensajeWA" class="form-control" rows="3">Hola, soy del área de reclutamiento de SIRECO, ¿podemos hablar?</textarea>
        </div>
        <a id="linkWhatsapp" href="#" class="btn btn-success" target="_blank">
          <i class="fa fa-whatsapp"></i> Abrir chat
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Modal Eliminar -->
<div class="modal fade" id="modalEliminarCandidato" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger">
        <h4 class="modal-title">Eliminar candidato</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="idAspiranteEliminar">
        <div class="form-group">
          <label for="comentarioEliminar">Comentario:</label>
          <textarea id="comentarioEliminar" class="form-control" rows="3"></textarea>
        </div>
        <div class="form-group">
          <label for="estatusEliminar">Estatus:</label>
          <select id="estatusEliminar" class="form-control">
            <option value="1">Abandono</option>
            <option value="2">No viable</option>
            <option value="3">Lista negra</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" id="confirmarEliminar">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Contratación -->
<div class="modal fade" id="modalContratacion" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <h4 class="modal-title">Registrar Contratación</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div id="infoAspirante" class="mb-3"></div>
        <hr>


<!-- FORMULARIO Contratación -->
        <!-- FORMULARIO Contratación -->
<form id="formContratacion" enctype="multipart/form-data">
  <input type="hidden" name="idAspirante" id="idAspirante">

  <!-- Fila 1 -->
  <div class="row">
    <div class="form-group col-md-4">
      <label>Género</label>
      <select class="form-control" name="genero" required></select>
    </div>
    <div class="form-group col-md-4">
      <label>CURP</label>
      <input type="text" class="form-control" name="curpAsp" required maxlength="18" pattern="^[A-Z0-9]{18}$" style="text-transform:uppercase;" title="Debe contener exactamente 18 caracteres alfanuméricos">
    </div>
    <div class="form-group col-md-4">
      <label>RFC</label>
      <input type="text" class="form-control" name="rfcAsp" required maxlength="13" pattern="^[A-Z0-9]{12,13}$" style="text-transform:uppercase;" title="Debe contener 12 o 13 caracteres alfanuméricos">
    </div>
  </div>

  <!-- Fila 2 -->
  <div class="row">
    <div class="form-group col-md-4">
      <label>NSS</label>
      <input type="text" class="form-control" name="nss" required maxlength="11" pattern="^\d{11}$" inputmode="numeric" title="Debe contener exactamente 11 dígitos">
    </div>
    <div class="form-group col-md-4">
      <label>Estado Civil</label>
      <select class="form-control" name="edoCivil" required></select>
    </div>
    <div class="form-group col-md-4">
      <label>Tel. de Emergencia</label>
      <input type="text" class="form-control" name="numEmergencia" required maxlength="10" pattern="^\d{10}$" inputmode="numeric" title="Debe contener exactamente 10 dígitos numéricos">
    </div>
  </div>

  <!-- Fila 3 -->
  <div class="row">
    <div class="form-group col-md-12">
      <label>Nombre del contacto de emergencia</label>
      <input type="text" class="form-control" name="contEmergencia" required>
    </div>
  </div>
</form>

        </form>

        <hr>        
      </div>
      <div class="modal-footer">
        <button type="submit" form="formContratacion" class="btn btn-primary">Guardar Contrato</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

 
<!-- Modal Archivos -->
<div class="modal fade" id="modalArchivos" tabindex="-1" role="dialog" aria-labelledby="modalArchivosLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header bg-warning">
        <h4 class="modal-title" id="modalArchivosLabel">Cargar Documentos del Aspirante</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="formularioArchivos" enctype="multipart/form-data">
          <input type="hidden" id="idAspiranteArchivos" name="idAspiranteArchivos">

          <div class="row">
            <!-- Acta de Nacimiento -->
            <div class="col-md-6 form-group">
              <label>Acta de Nacimiento</label>
              <input type="file" name="ac" id="ac" class="form-control">
              <input type="checkbox" id="chk_ac" class="check-doc" disabled> Documento cargado
              <a id="link_ac" href="#" target="_blank" style="margin-left: 10px; display: none;">Ver Documento</a>
            </div>

            <!-- Comprobante de Domicilio -->
            <div class="col-md-6 form-group">
              <label>Comprobante de Domicilio</label>
              <input type="file" name="cp" id="cp" class="form-control">
              <input type="checkbox" id="chk_cp" class="check-doc" disabled> Documento cargado
              <a id="link_cp" href="#" target="_blank" style="margin-left: 10px; display: none;">Ver Documento</a>
            </div>
          </div>

          <div class="row">
            <!-- Situación Fiscal -->
            <div class="col-md-6 form-group">
              <label>Situación Fiscal (RFC)</label>
              <input type="file" name="sf" id="sf" class="form-control">
              <input type="checkbox" id="chk_sf" class="check-doc" disabled> Documento cargado
              <a id="link_sf" href="#" target="_blank" style="margin-left: 10px; display: none;">Ver Documento</a>
            </div>

            <!-- Identificación Oficial -->
            <div class="col-md-6 form-group">
              <label>Identificación Oficial (INE)</label>
              <input type="file" name="in" id="in" class="form-control">
              <input type="checkbox" id="chk_in" class="check-doc" disabled> Documento cargado
              <a id="link_in" href="#" target="_blank" style="margin-left: 10px; display: none;">Ver Documento</a>
            </div>
          </div>

          <div class="row">
            <!-- Contrato Bancaria -->
            <div class="col-md-6 form-group">
              <label>Estado de Cuenta Bancaria</label>
              <input type="file" name="cb" id="cb" class="form-control">
              <input type="checkbox" id="chk_cb" class="check-doc" disabled> Documento cargado
              <a id="link_cb" href="#" target="_blank" style="margin-left: 10px; display: none;">Ver Documento</a>
            </div>

            <!-- Formato NSS -->
            <div class="col-md-6 form-group">
              <label>Formato Número de Seguro Social</label>
              <input type="file" name="dn" id="dn" class="form-control">
              <input type="checkbox" id="chk_dn" class="check-doc" disabled> Documento cargado
              <a id="link_dn" href="#" target="_blank" style="margin-left: 10px; display: none;">Ver Documento</a>
            </div>
          </div>

          <div class="row">
            <!-- CURP -->
            <div class="col-md-6 form-group">
              <label>CURP</label>
              <input type="file" name="dc" id="dc" class="form-control">
              <input type="checkbox" id="chk_dc" class="check-doc" disabled> Documento cargado
              <a id="link_dc" href="#" target="_blank" style="margin-left: 10px; display: none;">Ver Documento</a>
            </div>
            <div class="col-md-6 form-group">
              <label>Foto del Trabajador</label>
              <input type="file" name="ft" id="ft" accept=".pdf,.jpg,.jpeg,.png" class="form-control">
              <input type="checkbox" id="chk_ft" class="check-doc" disabled> Documento cargado
              <a id="link_ft" href="#" target="_blank" style="margin-left: 10px; display: none;">Ver Documento</a>
            </div>            
            
          </div>

          <div class="row">
            <!-- Foto del Trabajador -->
            
            <div class="col-md-6 form-group">
              <label>INFONAVIT</label>
              <input type="file" name="ci" id="ci" class="form-control">
              <input type="checkbox" id="chk_ci" disabled> Documento cargado
              <a id="link_ci" href="#" target="_blank" style="margin-left: 10px; display: none;">Ver Documento</a>
            </div>

            <!-- Fonacot -->
            <div class="col-md-6 form-group">
              <label>FONACOT</label>
              <input type="file" name="fonacot" id="fonacot" class="form-control">
              <input type="checkbox" id="chk_fonacot"  disabled> Documento cargado
              <a id="link_fonacot" href="#" target="_blank" style="margin-left: 10px; display: none;">Ver Documento</a>
            </div>
          </div>
        </form>


      </div>

      <div class="modal-footer">
        <button id="btnFinalizarContrato" class="btn btn-success" style="display:none;">
          Finalizar Contrato
        </button>
        <button type="button" class="btn btn-success" onclick="guardarArchivos()">Guardar Documentos</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
      </div>

    </div>
  </div>
</div>

<!-- Modal de Observaciones -->
<div class="modal fade" id="modalAutorizar" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-secondary text-white">
        <h4 class="modal-title">Autorizar Candidato</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
        <h5 id="infoCandidato" class="text-center font-weight-bold"></h5>
        <div class="form-group">
          <label for="comentarioNuevo">Agregar observación:</label>
          <textarea id="comentarioNuevo" class="form-control" rows="3"></textarea>
        </div>

        <div id="historialComentarios" class="mt-4">
          <h6>Historial de observaciones:</h6>
          <ul class="list-group" id="listaComentarios"></ul>
        </div>
      </div>

      <div class="modal-footer">
          <button class="btn btn-success" id="btnAgregarComentario">
            <i class="fa fa-commenting-o"></i> Agregar Comentario
          </button>
         
          <button class="btn btn-secondary" data-dismiss="modal">
            <i class="fa fa-times"></i> Cancelar
          </button>
        </div>

    </div>
  </div>
</div>
