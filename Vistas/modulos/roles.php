<?php

if (!tieneAcceso("roles")) {
    $baseUrl = rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/");
    echo '<script>window.location.href = "' . $base_url . '/inicio";</script>';
    return;
}

?>

<div class="content-wrapper">
	<section class="content-header">
		<h1>GESTOR DE ROLES</h1>
	</section>
	<section class="content">
		<div class="box">
			<div class="box-header">
				<form method="post">
					<div class="row">
						<div class="col-md-6 col-xs-6">
							<input type="text" name="rol" class="form-control" placeholder="Ingresa el nuevo ROL" onkeyup="javascript:this.value=this.value.toUpperCase();" required>					
						</div>
						<div class="col-md-6 col-xs-6">										
							<input type="text" name="descripcion" class="form-control" placeholder="Ingresa la descripcion" required>
				
						</div>
					</div>						
					<div class="form-group">
							<?php
								date_default_timezone_set('America/Mexico_City'); 
  								$fecha_actual = date("d-m-Y h:i:s");

  								echo '<input class="form-control input-lg" type="hidden" name="fechaProy" value="'.$fecha_actual.'" required>';
							  ?>
					</div>
					<button type="submit" class="btn btn-primary">CREAR ROL</button>
				</form>
				<?php
					$crearRol = new rolControlador();
					$crearRol -> crearRolControlador();
				?>
			</div>
			<div class="box-body">
				<table class="table table-bordered table-hover table-striped">
					<thead>
						<tr>
							<th>ID ROL</th>
							<th>NOMBRE ROL</th>
							<th>DESCRIPCION ROL</th>
							<th>ACCIONES</th>							
						</tr>
					</thead>
					<tbody>
						<?php
							$respuesta = rolControlador::verRolControlador();
							foreach ($respuesta as $key => $value) {
							echo '<tr>
									<td>'.$value["idRol"].'</td>
									<td>'.$value["rol"].'</td>
									<td>'.$value["descripcion"].'</td>
									<td>
										<div class="btn-group">
											<a href="'.$base_url.'editarRol/'.$value["idRol"].'">
												<button class="btn btn-success">EDITAR</button>
											</a>
											<a href="'.$base_url.'roles/'.$value["idRol"].'">
												<button class="btn btn-danger">BORRAR</button>
											</a>

										</div>
									</td>
								</tr>';
							}

						?>

						
						 
						
					</tbody>
				</table>				
			</div>
		</div>		
	</section>
</div>

<?php

$borrarRol = new rolControlador();
$borrarRol -> borrarRolControlador();

?>