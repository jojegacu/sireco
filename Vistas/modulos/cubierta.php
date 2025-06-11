<?php

if (!tieneAcceso("vacante")) {
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

			<div class="box-body">
				<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>NO.</th>
							<th>ID</th>
							<th>REG</th>
							<th>CLAVE</th>
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
						  		if ($v["estatus"] == 1) {		  			
						  		
						    echo '
						      <tr>
						        <td>'.$v["idVacante"].'</td>
						        <td>'.$v["id"].'</td>
						        <td>'.$v["region"].'</td>
						        <td>'.$v["clave"].'</td>
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
        <div class="modal-body">
          <input type="hidden" id="edit_idVacante" name="idVacante">
          <input type="hidden" id="edit_clave" name="clave">
          <input type="hidden" id="edit_id" name="id">

          <!-- Fila 1 -->
          <div class="row">
            <div class="form-group col-md-4">
              <label>Región</label>
              <select class="form-control" name="idRegionFk" id="edit_comboRegion" required></select>
            </div>
            <div class="form-group col-md-4">
              <label>Tienda</label>
              <input class="form-control" name="tienda" id="edit_tienda" required>
            </div>
            <div class="form-group col-md-4">
              <label>Responsable</label>
              <input class="form-control" name="responsable" id="edit_responsable" required>
            </div>
          </div>

          <!-- Fila 2 -->
          <div class="row">
            <div class="form-group col-md-4">
              <label>Código Postal</label>
              <input type="text" class="form-control" name="codPostal" id="codPostalGenerales" autocomplete="off">
              <ul id="lista-sugerencias-cp" class="list-group" style="position:absolute; z-index:1000;"></ul>
            </div>
            <div class="form-group col-md-4">
              <label>Estado</label>
              <input class="form-control" name="estado" id="estadoGenerales" readonly>
            </div>
            <div class="form-group col-md-4">
              <label>Municipio</label>
              <input class="form-control" name="ciudadMun" id="ciudadGenerales" readonly>
            </div>
          </div>

          <!-- Fila 3 -->
          <div class="row">
            <div class="form-group col-md-4">
              <label>Colonia</label>
              <input class="form-control" name="colBarrio" id="coloniaGenerales" readonly>
            </div>
            <div class="form-group col-md-4">
              <label>Vacante</label>
              <select class="form-control" name="idCatalogoFk" id="edit_comboPuesto" required></select>
            </div>
            <div class="form-group col-md-4">
              <label>Fecha Alta</label>
              <input class="form-control" name="fechaCap" id="edit_fechaCap" readonly>
            </div>
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
