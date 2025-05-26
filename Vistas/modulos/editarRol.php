<?php

if (!tieneAcceso("editarRol")) {
    $baseUrl = rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/");
    echo '<script>window.location.href = "' . $baseUrl . '/inicio";</script>';
    return;
}

?>

<div class="content-wrapper">
	<section class="content-header">
		<h1>EDITAR ROLES</h1>
	</section>
	<section class="content">
		<div class="box">
			<div class="box-header">
				<form method="post">

					<?php
						$editarRol = new rolControlador();
						$editarRol -> editarRolControlador();
						$editarRol -> actualizarRolControlador();
					?>
				</form>				
			</div>			
		</div>		
	</section>
</div> 