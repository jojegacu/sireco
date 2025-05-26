<?php
if (!tieneAcceso("reportes")) {
    $baseUrl = rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/");
    echo '<script>window.location.href = "' . $baseUrl . '/inicio";</script>';
    return;
}
// Agregar la variable baseUrl para JavaScript
$baseUrl = rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/");
?>
<section class="content">
  <div class="box">
    <div class="box-body" style="min-height: calc(100vh - 150px);">
      <div class="row">
        <div class="col-md-12" style="padding-left: 260px; padding-right: 10px;">
          <form id="formReporte">
            <div class="row" style="margin-bottom: 15px;">
              <!-- Fecha inicio -->
              <div class="col-md-3">
                <label>Fecha inicio</label>
                <input type="date" name="fechaInicio" class="form-control">
              </div>

              <!-- Fecha fin -->
              <div class="col-md-3">
                <label>Fecha fin</label>
                <input type="date" name="fechaFin" class="form-control">
              </div>

              <!-- Estatus -->
              <div class="col-md-3">
                <label>Estatus</label>
                <select name="estatus" class="form-control">
                  <option value="">Seleccione reporte...</option>
                  <option value="0">Candidatos</option>
                  <option value="1">VoBo</option>
                  <option value="2">En contratación</option>
                  <option value="3">Standby</option>
                  <option value="4">Contratado</option>
                </select>
              </div>

              <!-- Botones agrupados -->
              <div class="col-md-3" id="botoneraReportes">
                <div class="row">
                  <div class="col-xs-12" style="margin-bottom: 5px;">
                    <button type="button" class="btn btn-success btn-block" id="btnLimpiar">Limpiar</button>
                  </div>                  
                  <div class="col-xs-12" style="margin-bottom: 5px;">
                    <button type="button" class="btn btn-primary btn-block" id="btnExportarCSV">Exportar CSV</button>
                  </div>
                 
                  <div class="col-xs-12">
                    <!-- Aquí insertaremos el botón de columnas desde JS -->
                  </div>
                </div>
              </div>
            </div>

            <hr>
            
          </form>

          <hr>

          <div class="table-responsive">
            <table id="tablaReporte" class="table table-bordered table-striped display nowrap" width="100%">
              <thead>
                <tr id="encabezadoTabla"></tr>
              </thead>
              <tbody id="cuerpoTabla"></tbody>
            </table>
          </div>

        </div>
      </div> <!-- row -->
    </div> <!-- box-body -->

    <div class="box-footer text-center">
      <small>© SIRECO</small>
    </div>
  </div>
</section>
