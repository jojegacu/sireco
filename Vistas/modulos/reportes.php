<?php
if (!tieneAcceso("reportes")) {
    $baseUrl = rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/");
    echo '<script>window.location.href = "' . $baseUrl . '/inicio";</script>';
    return;
}
// Agregar la variable baseUrl para JavaScript
$baseUrl = rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/");
?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>REPORTES DEL SISTEMA</h1>
  </section>
  <section class="content">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title">Generar reportes de candidatos</h3>
      </div>
      <div class="box-body" style="min-height: calc(100vh - 250px);">
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
                  <div class="col-xs-12" style="margin-bottom: 10px;">
                    <button type="button" class="btn btn-success btn-block" id="btnLimpiar"><i class="fa fa-refresh"></i> LIMPIAR FILTROS</button>
                  </div>                  
                  <div class="col-xs-12" style="margin-bottom: 10px;">
                    <button type="button" class="btn btn-primary btn-block" id="btnExportarCSV"><i class="fa fa-file-excel-o"></i> EXPORTAR CSV</button>
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

          <div class="table-responsive" style="margin: 20px 0; border-radius: 5px; box-shadow: 0 3px 15px rgba(0,48,99,0.15); background-color: white; padding: 15px; overflow-x: auto; border: 1px solid #E8E2E2;">
            <table id="tablaReporte" class="table table-bordered table-hover table-striped display nowrap" width="100%" style="font-size: 12px; margin-bottom: 0;">
              <thead style="background-color: #0F225B; color: white; text-transform: uppercase; letter-spacing: 0.5px;">
                <tr id="encabezadoTabla"></tr>
              </thead>
              <tbody id="cuerpoTabla"></tbody>
            </table>
          </div>

        </div>
      </div> <!-- row -->
    </div> <!-- box-body -->

    <div class="box-footer text-center">
      <small>© SIRECO - Grupo ASE</small>
    </div>
  </div>
  </section>
</div>

<style>
  .content-wrapper label {
    color: #0F225B;
    font-weight: 600;
    margin-bottom: 5px;
  }
  
  .content-wrapper .form-control:focus {
    border-color: #F85938;
    box-shadow: 0 0 0 0.2rem rgba(248, 89, 56, 0.25);
  }
</style>
