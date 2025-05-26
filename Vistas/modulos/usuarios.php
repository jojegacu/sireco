<?php

if (!tieneAcceso("usuarios")) {
    $baseUrl = rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/");
    echo '<script>window.location.href = "' . $baseUrl . '/inicio";</script>';
    return;
}

?>

<div class="content-wrapper">
	<section class="content-header">
		<h1>OPERADORES DEL SISTEMA</h1>
	</section>
	<section class="content">
			 
		<div class="box">
			<div class="box-header">
				<a href="<?php echo $base_url; ?>crearUsr">
				  <button class="btn btn-primary">REGISTRO DE OPERADOR</button>
				</a>

			</div>		
			<div class="box-body">
				<table class="table table-bordered table-hover table-striped">
					<thead>
						<tr>
							<th>Apellidos</th>
							<th>Nombre(s)</th>
							<th>CURP</th>
							<th>RFC</th>
							<th>Telefono</th>
							<th>Email</th>
							<th>Editar</th>
							
						</tr>
					</thead>
					<tbody>
						<?php

							$columna = null;
							$valor = null;

							$resultado = usuariosControlador::verUsuariosControlador($columna, $valor);

							foreach ($resultado as $key => $value) {
							  if ($value["nuevo"] == 1) {
							    echo '<tr>
							      <td>'.$value["apellidos"].'</td>
							      <td>'.$value["nombre"].'</td>
							      <td>'.$value["curp"].'</td>
							      <td>'.$value["rfc"].'</td>
							      <td>'.$value["telefono"].'</td>
							      <td>'.$value["email"].'</td>
							      <td>
							        <div class="btn-group">
							          <a href="'.$base_url.'editarUsr/'.$value["idPersona"].'">
							            <button class="btn btn-success fa fa-file-text-o" title="Generales"></button>
							          </a>
							          <button class="btn btn-primary fa fa-user btnUsuario" 
							                  title="Usuario" 
							                  data-id="'.$value["idPersona"].'">
							          </button>
							          <button class="btn btn-primary fa fa-briefcase btnEmpleado" 
							                  title="Empleado" 
							                  data-id="'.$value["idPersona"].'">
							          </button>
							          <a href="'.$base_url.'usuarios/'.$value["idPersona"].'">
							            <button class="btn btn-danger fa fa-trash" title="Eliminar"></button>
							          </a>
							        </div>
							      </td>
							    </tr>';
							  }
							}


						?>
 					
 					
					<div class="modal fade" id="modalNuevoUsuario" tabindex="-1" role="dialog" aria-labelledby="modalUsuarioLabel">
					  <div class="modal-dialog" role="document">
					    <div class="modal-content">
					      <form id="formNuevoUsuario" method="post">
					        <div class="modal-header bg-primary">
					          <h4 class="modal-title">Registrar nuevo usuario</h4>
					          <button type="button" class="close" data-dismiss="modal">&times;</button>
					        </div>
					        <div class="modal-body">
					          <input type="hidden" id="idPersonaNueva" name="idPersonaNueva">
					          <div class="form-group">
					            <label>Nombre de Usuario</label>
					            <input type="text" class="form-control" name="usuario" required>
					          </div>
					          <div class="form-group">
					            <label>Contraseña</label>
					            <input type="password" class="form-control" name="password" required>
					          </div>
					        </div>
					        <div class="modal-footer">
					          <button type="submit" class="btn btn-primary">Guardar</button>
					          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
					        </div>
					      </form>
					    </div>
					  </div>
					</div>

					<div class="modal fade" id="modalNuevoEmpleado" tabindex="-1" role="dialog" aria-labelledby="modalEmpleadoLabel">
					  <div class="modal-dialog" role="document">
					    <div class="modal-content">
					      <form id="formNuevoEmpleado" method="post">
					        <div class="modal-header bg-warning">
					          <h4 class="modal-title">Registrar nuevo empleado</h4>
					          <button type="button" class="close" data-dismiss="modal">&times;</button>
					        </div>
					        <div class="modal-body">
					          <input type="hidden" id="idPersonaEmpleado" name="idPersonaEmpleado">
					          <div class="form-group">
					            <label>Puesto</label>
					            <input type="text" class="form-control" name="puesto" required>
					          </div>
					          <div class="form-group">
					            <label>Departamento</label>
					            <input type="text" class="form-control" name="departamento" required>
					          </div>
					          <div class="form-group">
					            <label>No Seguro Social</label>
					            <input type="text" class="form-control" name="noe" required>
					          </div>
					        </div>
					        <div class="modal-footer">
					          <button type="submit" class="btn btn-warning">Guardar</button>
					          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
					        </div>
					      </form>
					    </div>
					  </div>
					</div>


						
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

<?php
$borrarUsr = new usuariosControlador();
$borrarUsr -> borrarUsrControlador();
?>									    
