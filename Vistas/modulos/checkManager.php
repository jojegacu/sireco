<?php

if (!tieneAcceso("checkManager")) {
    $baseUrl = rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/");
    echo '<script>window.location.href = "' . $baseUrl . '/inicio";</script>';
    return;
}

?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>SEGUNDA VALIDACION</h1>
    </section>
    <section class="content">
        <div class="box">                   
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ASP.</th>
                            <th>NOMBRE</th>
                            <th>A. PATERNO</th>
                            <th>A. MATERNO</th>
                            <th>COD POSTAL</th>
                            <th>ESTADO</th>
                            <th>TELEFONO</th>
                            <th>CLASIFICAR</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $columna = null;
                        $valor = null;
                        $resultado = aspiranteControlador::verAspiranteControlador($columna, $valor);
                        foreach ($resultado as $key => $value) {
                            if ($value["nuevo"] == 1) {
                                echo '<tr>
                                    <td>'.$value["idAspirante"].'</td>
                                    <td>'.$value["nombre"].'</td>
                                    <td>'.$value["apPaterno"].'</td>
                                    <td>'.$value["apMaterno"].'</td>
                                    <td>'.$value["codPostal"].'</td>
                                    <td>'.$value["estado"].'</td>
                                    <td>'.$value["telefonoCel"].'</td>
                                    <td>
                                        <div class="btn-group clasificar-btns">
                                            <!-- Ver CV -->
                                            <button class="btn btn-info btnVerCv" data-id="'.$value["idAspirante"].'" title="Ver CV">
                                                <i class="fa fa-file-pdf-o"></i>
                                            </button>

                                            <!-- Generales -->
                                            <button class="btn btn-primary btnGenerales" data-id="'.$value["idAspirante"].'" title="Generales">
                                                <i class="fa fa-address-card"></i>
                                            </button>                                            

                                            <!-- Reasignar Vacante -->
                                            <button class="btn btn-warning btnReasignarVacante" data-id="'.$value["idAspirante"].'" title="Reasignar Vacante">
                                                <i class="fa fa-cogs"></i>
                                            </button>

                                            <!-- WhatsApp -->
                                            <button class="btn btn-success btnChatWhatsapp" data-id="'.$value["idAspirante"].'" data-tel="'.$value["telefonoCel"].'" title="Chatear por WhatsApp">
                                                <i class="fa fa-whatsapp"></i>
                                            </button>

                                            <!-- Autorizar -->
                                            <button class="btn btn-secondary btnAutorizar" data-id="'.$value["idAspirante"].'" title="Autorizar">
                                                <i class="fa fa-check-square-o"></i>
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

<!-- Modal para mostrar CV -->
<div class="modal fade" id="modalCV" tabindex="-1" role="dialog" aria-labelledby="modalCVLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h4 class="modal-title" id="modalCVLabel">Currículum del Aspirante</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body text-center">
        <div id="contenidoCV"></div>
        <hr>
        
      </div>
    </div>
  </div>
</div>

<!-- Modal de Generales -->
<div class="modal fade" id="modalGenerales" tabindex="-1" role="dialog" aria-labelledby="modalGeneralesLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="formGenerales">
        <div class="modal-header">
          <h5 class="modal-title" id="modalGeneralesLabel">Información General del Candidato</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <input type="hidden" id="idGenerales" name="idGenerales">

          <div class="row">
            <div class="form-group col-md-4">
              <label>Nombre</label>
              <input type="text" class="form-control" name="nombre" id="nombreGenerales">
            </div>
            <div class="form-group col-md-4">
              <label>Apellido Paterno</label>
              <input type="text" class="form-control" name="apPaterno" id="apPaternoGenerales">
            </div>
            <div class="form-group col-md-4">
              <label>Apellido Materno</label>
              <input type="text" class="form-control" name="apMaterno" id="apMaternoGenerales">
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-4">
              <label>Fecha de Nacimiento</label>
              <input type="date" class="form-control" name="fechaNacimiento" id="fechaNacimientoGenerales">
            </div>
            <div class="form-group col-md-4">
              <label>Código Postal</label>
              <input type="text" class="form-control" name="codPostal" id="codPostalGenerales" autocomplete="off">
              <ul id="lista-sugerencias-cp" class="list-group" style="position:absolute; z-index:1000;"></ul>
            </div>
            <div class="form-group col-md-4">
              <label>Estado</label>
              <input type="text" class="form-control" name="estado" id="estadoGenerales" readonly>
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-4">
              <label>Ciudad/Municipio</label>
              <input type="text" class="form-control" name="ciudadMun" id="ciudadGenerales" readonly>
            </div>
            <div class="form-group col-md-4">
              <label>Colonia / Barrio</label>
              <input type="text" class="form-control" name="colBarrio" id="coloniaGenerales" readonly>
            </div>
            <div class="form-group col-md-4">
              <label>Calle y Número</label>
              <input type="text" class="form-control" name="calleNo" id="calleNoGenerales">
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-4">
              <label>Teléfono Celular</label>
              <input type="text" class="form-control" name="telefonoCel" id="telefonoCelGenerales">
            </div>
            <div class="form-group col-md-4">
              <label>Fecha de Captura</label>
              <input type="date" class="form-control" name="fechaCaptura" id="fechaCapturaGenerales" readonly>
            </div>
          </div>

          <!-- Campos ocultos -->
          <input type="hidden" name="puesto" id="puestoGenerales">
          <input type="hidden" name="nuevo" id="nuevoGenerales">

        </div>

        <div class="modal-footer">
          
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        </div>
      </form>
    </div>
  </div>
</div>



<!-- Modal WhatsApp -->
<div class="modal fade" id="modalWhatsapp" tabindex="-1" role="dialog" aria-labelledby="modalWhatsappLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h4 class="modal-title" id="modalWhatsappLabel">Chatear por WhatsApp</h4>
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

<!-- Modal de Autorización -->
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

          <!-- Botón autorizar dinámico -->
          <button class="btn btn-primary" id="btnAutorizarCandidato">
            <i class="fa fa-check-square-o"></i> Autorizar
          </button>

          <!-- Botón regresar visible solo si nuevo == 1 -->
          <button class="btn btn-warning" id="btnRegresarCandidato" style="display: none;">
            <i class="fa fa-undo"></i> Regresar
          </button>

          <button class="btn btn-secondary" data-dismiss="modal">
            <i class="fa fa-times"></i> Cancelar
          </button>
        </div>

    </div>
  </div>
</div>


<!-- Modal de ELIMINAR -->
<div class="modal fade" id="modalEliminarCandidato" tabindex="-1" role="dialog" aria-labelledby="modalEliminarCandidatoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    
      <div class="modal-header bg-danger">
        <h4 class="modal-title" id="modalEliminarCandidatoLabel">Eliminar candidato</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <div class="modal-body">
        <input type="hidden" id="idAspiranteEliminar">
        
        <div class="form-group">
          <label for="comentarioEliminar">Comentario:</label>
          <textarea id="comentarioEliminar" class="form-control" rows="3" placeholder="Escribe un motivo..."></textarea>
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

<!-- ======================= MODAL REASIGNAR VACANTE ======================= -->
<div class="modal fade" id="modalReasignarVacante" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-info">
        <h4 class="modal-title">Reasignar Vacante</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">

        <!-- 🔹 SECCIÓN 1: DATOS DEL CANDIDATO -->
        <div>
          <h5><strong>Información del Candidato</strong></h5>
          <hr>
          <div class="row">
            <div class="col-md-4"><label id="infoNombre"></label></div>
            <div class="col-md-4"><label id="infoApPaterno"></label></div>
            <div class="col-md-4"><label id="infoApMaterno"></label></div>
          </div>
          <div class="row">
            <div class="col-md-4"><label id="infoCP"></label></div>
            <div class="col-md-4"><label id="infoEstado"></label></div>
            <div class="col-md-4"><label id="infoMunicipio"></label></div>
          </div>
        </div>

        <!-- 🔹 SECCIÓN 2: VACANTE ACTUAL -->
        <div class="mt-4">
          <h5><strong>Vacante Actual Asignada</strong></h5>
          <hr>
          <div class="row">
            <div class="col-md-2"><label id="infoVacID"></label></div>
            <div class="col-md-2"><label id="infoClave"></label></div>
            <div class="col-md-2"><label id="infoTienda"></label></div>
            <div class="col-md-2"><label id="infoVacCP"></label></div>
            <div class="col-md-2"><label id="infoVacEstado"></label></div>
            <div class="col-md-2"><label id="infoVacMun"></label></div>
          </div>
        </div>        

        <input type="hidden" id="idVacanteSeleccionada">
        <input type="hidden" id="idAspiranteReasignar">
      </div>

      <div class="modal-footer">        
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
