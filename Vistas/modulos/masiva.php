<?php

if (!tieneAcceso("masiva")) {
    $baseUrl = rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/");
    echo '<script>window.location.href = "' . $baseUrl . '/inicio";</script>';
    return;
}

?>

<div class="content-wrapper">
	<section class="content-header">
		<h1>VACANTES DISPONIBLES</h1>
	</section>
	<section class="content">
			 
		<div class="box">
			<div class="box-header">
				
				  <!-- Reemplazo del botón -->
						<button class="btn btn-success" data-toggle="modal" data-target="#modalCargaExcel">
								CARGAR DOCUMENTO
						</button>
						<button class="btn btn-danger" data-toggle="modal" data-target="#modalEliminarCategoria">
					  ELIMINAR CATEGORÍA
					</button> 

				
			</div>		
			<div class="box-body">
				<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>NO.</th>
							<th>ID</th>
							<th>REG</th>
							<th>CATEGORIA</th>							
							<th>TIENDA</th>
							<th>CP</th>
							<th>ESTADO</th>
							<th>MUNICIPIO</th>
							<th>RESPONSABLE</th>
							<th>VACANTE</th>
							<th>FECHA ALTA</th>
							<th>ACCION</th>
						</tr>
					</thead>
					<tbody>
						  <?php
						  $vacantes = vacanteControlador::ctrMostrarVacantes();
						  $contador = 1;

						  foreach ($vacantes as $v) {
						  	if (isset($v["categoria"]) && trim($v["categoria"]) != ""){
						    echo '
						      <tr>
						        <td>'.$v["idVacante"].'</td>
						        <td>'.$v["id"].'</td>
						        <td>'.$v["region"].'</td>
						        <td>'.$v["categoria"].'</td>
						        <td>'.$v["tienda"].'</td>
						        <td>'.$v["cp"].'</td>
										<td>'.$v["edo"].'</td>
										<td>'.$v["mun"].'</td>
						        <td>'.$v["responsable"].'</td>
						        <td>'.$v["puesto"].'</td>
						        <td>'.date("d/m/Y", strtotime($v["fechaAlta"])).'</td>
						        <td>
						          <div class="btn-group">
						            <button class="btn btn-success fa fa-file-text-o btnEditarVacante" data-id="'.$v["idVacante"].'" title="Editar">
						            </button>
						            <button class="btn btn-danger fa fa-trash btnEliminarVacante" data-id="'.$v["idVacante"].'" title="Eliminar">
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

<!-- Modal para cargar documento Excel -->
<div class="modal fade" id="modalCargaExcel" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="formCargaExcel" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Subir Documento Excel</h4>
        </div>
        <div class="modal-body">
          <input type="file" name="archivoExcel" accept=".xls,.xlsx,.xlsm" class="form-control mb-3" required>
          <!-- Barra de progreso -->
          <div class="progress" style="height: 25px; display: none;">
            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
              style="width: 0%">0%</div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Procesar archivo</button>
          <button type="button" class="btn btn-success d-none" id="btnFinalizado">Aceptar</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </form>
  </div>
</div>


<!-- Modal para eliminar por categoría -->
<div class="modal fade" id="modalEliminarCategoria" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form id="formEliminarCategoria">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Eliminar Vacantes por Categoría</h4>
        </div>
        <div class="modal-body">
          <label>Clave de categoría:</label>
          <input type="text" name="categoriaEliminar" class="form-control" placeholder="Ejemplo: 24051429A" required>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger">Eliminar</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </form>
  </div>
</div>



</div>
  
			</form>
		</div>
		
	</div>
	
</div>

<!-- Modal de Edición de Vacante -->
<div class="modal fade" id="modalEditarVacante" tabindex="-1" role="dialog" aria-labelledby="modalEditarLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="formEditarVacante">
        <div class="modal-header">
          <h4 class="modal-title">Editar Vacante</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body row">
          <input type="hidden" id="edit_idVacante" name="idVacante">
          <input type="hidden" id="edit_clave" name="clave">
          <input type="hidden" id="edit_id" name="id">

          <div class="col-lg-6">
            <label>Región</label>
            <select class="form-control" name="idRegionFk" id="edit_comboRegion" required></select>
          </div>
          <div class="col-lg-6">
            <label>Tienda</label>
            <input class="form-control" name="tienda" id="edit_tienda" required>
          </div>

          <div class="col-lg-6 mt-2">
            <label>Responsable</label>
            <input class="form-control" name="responsable" id="edit_responsable" required>
          </div>
          <div class="col-lg-6 mt-2">
            <label>Código Postal</label>
              <input type="text" class="form-control" name="codPostal" id="codPostalGenerales" autocomplete="off">
              <ul id="lista-sugerencias-cp" class="list-group" style="position:absolute; z-index:1000;"></ul>
          </div>
          <div class="col-lg-4 mt-2">
            <label>Estado</label>
            <input class="form-control" name="estado" id="estadoGenerales" readonly>
          </div>
          <div class="col-lg-4 mt-2">
            <label>Municipio</label>
            <input class="form-control" name="ciudadMun" id="ciudadGenerales" readonly>
          </div>
          <div class="col-lg-4 mt-2">
            <label>Colonia</label>
            <input class="form-control" name="colBarrio" id="coloniaGenerales" readonly>
          </div>
          <div class="col-lg-6 mt-2">
            <label>Vacante</label>
            <select class="form-control" name="idCatalogoFk" id="edit_comboPuesto" required></select>
          </div>
          <div class="col-lg-6 mt-2">
            <label>Fecha Alta</label>
            <input class="form-control" name="fechaCap" id="edit_fechaCap" readonly>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      </form>
    </div>
  </div>
</div>
