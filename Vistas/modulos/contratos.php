<div class="candidatos-container">
<!-- Input ID y botón Buscar -->
<section class="content">
  <div class="box">
    <div class="box-header" align="center">
      <div class="logo-container">
        <img src="<?php echo $base_url; ?>Vistas/img/logoase-white.png" class="logo-candidatos" alt="Logo Grupo ASE">
      </div>
      <h3 class="box-title">CONSULTA DE CANDIDATO</h3>
    </div>
    <div class="box-body">
      <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-6 col-md-offset-3">
          <div class="input-group">
            <input type="text" class="form-control" id="buscarId" placeholder="Teclea el ID del candidato...">
            <span class="input-group-btn">
              <button class="btn" id="btnBuscarCandidato" type="button"
                onclick="
                  document.getElementById('idAspirante').value = document.getElementById('buscarId').value;
                  document.getElementById('idAspiranteArchivos').value = document.getElementById('buscarId').value;
                  document.querySelector('.btnGenerarContrato').setAttribute('data-id', document.getElementById('buscarId').value);
                  document.querySelector('.btnCargarDocs').setAttribute('data-id', document.getElementById('buscarId').value);
                ">
                Buscar
              </button>
            </span>
          </div>
        </div>
      </div>

      <!-- Resultado del aspirante -->
      <div id="datosAspirante" style="display: none;">
        <h1>INFORMACION DEL CANDIDATOS</h1>
        <div class="row">
          <div class="col-md-4"><label><b>Nombre(s):</b> <span id="aspNombre"></span></label></div>
          <div class="col-md-4"><label><b>Apellido Paterno:</b> <span id="aspPaterno"></span></label></div>
          <div class="col-md-4"><label><b>Apellido Materno:</b> <span id="aspMaterno"></span></label></div>
        </div>
        <div class="row">
          <div class="col-md-4"><label><b>Fecha Nacimiento:</b> <span id="aspFecha"></span></label></div>
          <div class="col-md-4"><label><b>Teléfono:</b> <span id="aspTel"></span></label></div>
          <div class="col-md-4"><label><b>Dirección:</b> <span id="aspDir"></span></label></div>
        </div>
      </div>

      <!-- Resultado de vacante -->
      <div id="datosVacante" style="display: none; margin-top: 30px;">
        <h1>VACANTE ASIGNADA</h1>
        <div class="row">
          <div class="col-md-4"><label><b>Clave:</b> <span id="vacClave"></span></label></div>
          <div class="col-md-4"><label><b>Id:</b> <span id="vacId"></span></label></div>
          <div class="col-md-4"><label><b>Responsable:</b> <span id="vacResponsable"></span></label></div>
        </div>
        <div class="row">
          <div class="col-md-4"><label><b>Tienda:</b> <span id="vacTienda"></span></label></div>
          <div class="col-md-4"><label><b>Dirección:</b> <span id="vacDir"></span></label></div>
          <div class="col-md-4"><label><b>CP:</b> <span id="vacCp"></span></label></div>
        </div>
      </div>

      <!-- Botones de acción -->
      <div id="botonesAccion" style="display: none; margin-top: 30px;" align="center">
        <button class="btn btn-primary btnGenerarContrato" data-id="">REGISTRO DE DATOS</button>
        <button class="btn btn-warning btnCargarDocs" data-id="">CARGA DE DOCUMENTOS</button>
        <a href="<?php echo $base_url; ?>contratos" class="btn btn-danger">CANCELAR</a>
      </div>

      <!-- Inputs ocultos para reutilizar -->
      <input type="hidden" id="idAspirante" name="idAspirante">
      

    </div>
  </div>
</section>


<!-- Modal Contratación -->
<div class="modal fade" id="modalContratacion" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <h4 class="modal-title">Registrar Contratación</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        
        <hr>


<!-- FORMULARIO Contratación -->
        <form id="formContratacion" enctype="multipart/form-data">
          <input type="hidden" name="idAspirante" id="idAspirante">
          <div class="form-row">
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
          <div class="form-row">
            <div class="form-group col-md-4">
              <label>NSS</label>
             <input type="text" class="form-control" name="nss" required maxlength="10" pattern="^\d{10}$" inputmode="numeric" title="Debe contener exactamente 10 dígitos">

            </div>
            <div class="form-group col-md-4">
              <label>Estado Civil</label>
              <select class="form-control" name="edoCivil" required></select>
            </div>
            <div class="form-group col-md-4">
              <label>Tel. de Emergencia</label>
               <input type="text" class="form-control" name="numEmergencia" required maxlength="10" pattern="^\d{10}$" inputmode="numeric" title="Debe contener exactamente 10 dígitos numéricos">

            </div>
            <div class="form-group col-md-12">
              <label>Contacto de Emergencia</label>
              <input type="text" class="form-control" name="contEmergencia" required>
            </div>
            
          </div>
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

          <!-- Inputs de documentos -->
          <div class="form-group">
            <label>Acta de Nacimiento</label>
            <input type="file" name="ac" id="ac" class="form-control">
            <input type="checkbox" id="chk_ac" class="check-doc" disabled> Documento cargado
            <a id="link_ac" href="#" target="_blank" style="margin-left: 10px; display: none;">Ver Documento</a>
          </div>

          <div class="form-group">
            <label>Comprobante de Domicilio</label>
            <input type="file" name="cp" id="cp" class="form-control">
            <input type="checkbox" id="chk_cp" class="check-doc" disabled> Documento cargado
            <a id="link_cp" href="#" target="_blank" style="margin-left: 10px; display: none;">Ver Documento</a>
          </div>

          <div class="form-group">
            <label>Situacion Fiscal</label>
            <input type="file" name="sf" id="sf" class="form-control">
            <input type="checkbox" id="chk_sf" class="check-doc" disabled> Documento cargado
            <a id="link_sf" href="#" target="_blank" style="margin-left: 10px; display: none;">Ver Documento</a>
          </div>

          <div class="form-group">
            <label>Identificación Oficial</label>
            <input type="file" name="in" id="in" class="form-control">
            <input type="checkbox" id="chk_in" class="check-doc" disabled> Documento cargado
            <a id="link_in" href="#" target="_blank" style="margin-left: 10px; display: none;">Ver Documento</a>
          </div>

          <div class="form-group">
            <label>Contrato Bancario</label>
            <input type="file" name="cb" id="cb" class="form-control">
            <input type="checkbox" id="chk_cb" class="check-doc" disabled> Documento cargado
            <a id="link_cb" href="#" target="_blank" style="margin-left: 10px; display: none;">Ver Documento</a>
          </div>

          <div class="form-group">
            <label>Formato Numero de Seguro Social</label>
            <input type="file" name="dn" id="dn" class="form-control">
            <input type="checkbox" id="chk_dn" class="check-doc" disabled> Documento cargado
            <a id="link_dn" href="#" target="_blank" style="margin-left: 10px; display: none;">Ver Documento</a>
          </div>

          <div class="form-group">
            <label>CURP</label>
            <input type="file" name="dc" id="dc" class="form-control">
            <input type="checkbox" id="chk_dc" class="check-doc" disabled> Documento cargado
            <a id="link_dc" href="#" target="_blank" style="margin-left: 10px; display: none;">Ver Documento</a>
          </div>

          <div class="form-group">
            <label>Carta INFONAVIT/FONACOT</label>
            <input type="file" name="ci" id="ci" class="form-control">
            <input type="checkbox" id="chk_ci" class="check-doc" disabled> Documento cargado
            <a id="link_ci" href="#" target="_blank" style="margin-left: 10px; display: none;">Ver Documento</a>
          </div>

          <div class="form-group">
            <label>Foto del Trabajador</label>
            <input type="file" name="ft" id="ft" accept=".pdf,.jpg,.jpeg,.png" class="form-control">
            <input type="checkbox" id="chk_ft" class="check-doc" disabled> Documento cargado
            <a id="link_ft" href="#" target="_blank" style="margin-left: 10px; display: none;">Ver Documento</a>
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

<script>
document.querySelector(".btnCargarDocs").addEventListener("click", function () {
  const id = this.getAttribute("data-id");
  document.getElementById("idAspiranteArchivos").value = id;
});
</script>

</div> <!-- Cierre de candidatos-container -->
