<?php

if (!tieneAcceso("proyectos")) {
    $baseUrl = rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/");
    echo '<script>window.location.href = "' . $baseUrl . '/inicio";</script>';
    return;
}

?>

<div class="content-wrapper">
	<section class="content-header">
		<h1>GESTOR DE VACANTES</h1>
	</section>
	<section class="content">
		<div class="box">
			<div class="box-header">
				<button class="btn btn-primary" data-toggle="modal" data-target="#crearProy">CREAR VACANTE</button>
			</div>
			<div class="box-body">
				
			</div>
			
		</div>
		
	</section>
</div>

