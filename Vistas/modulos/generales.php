<?php

if (!tieneAcceso("generales")) {
    $baseUrl = rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/");
    echo '<script>window.location.href = "' . $baseUrl . '/inicio";</script>';
    return;
}

$aspirante = null;

if (isset($_GET["url"])) {
    $exp = explode("/", $_GET["url"]); // 'clasificar/6' → ['clasificar', '6']

    if (isset($exp[1])) {
        $id = $exp[1];
        $columna = "idAspirante";

        $aspirante = aspiranteControlador::verAspiranteControlador($columna, $id);

       if ($_SERVER["REQUEST_METHOD"] === "POST") {
		    $actualizar = new aspiranteControlador();
		    $respuesta = $actualizar->actualizarAspiranteControlador($_POST, $_FILES, $aspirante["idAspirante"]);

		    if ($respuesta === "ok") {
		        echo '<script>
				    alert("Información actualizada correctamente");
				    window.location = "../nuevos";
				</script>';
		    } else {
		        echo '<script>alert("Hubo un error al actualizar");</script>';
		    }
		} 
        
    }
}
?>

<div class="content-wrapper">
	<section class="content-header">
		<h1>OPERADORES DEL SISTEMA</h1>
	</section>
	<section class="content">
		<div class="box">
			<form method="post" enctype="multipart/form-data">
				<div class="box-body">
					<div class="row">
						<div class="col-lg-6">
							<div class="form-group">
								<h2>NOMBRE(S)</h2>
								<input class="form-control input-lg" type="text" onkeyup="this.value = this.value.toUpperCase();" name="nombre" value="<?php echo $aspirante['nombre'] ?? ''; ?>" required>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<h2>APELLIDO PATERNO</h2>
								<input class="form-control input-lg" type="text" onkeyup="this.value = this.value.toUpperCase();" name="apPaterno" value="<?php echo $aspirante['apPaterno'] ?? ''; ?>" required>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-lg-6">
							<div class="form-group">
								<h2>APELLIDO MATERNO</h2>
								<input class="form-control input-lg" type="text" onkeyup="this.value = this.value.toUpperCase();" name="apMaterno" value="<?php echo $aspirante['apMaterno'] ?? ''; ?>" required>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<h2>FECHA DE NACIMIENTO</h2>
								<input class="form-control input-lg" type="date" name="fechaNacimiento" value="<?php echo $aspirante['fechaNacimiento'] ?? ''; ?>" required>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-lg-6">
							<div class="form-group">
								<h2>CODIGO POSTAL</h2>
								<input class="form-control input-lg" type="text" id="codPostal" name="codPostal" value="<?php echo $aspirante['codPostal'] ?? ''; ?>" required autocomplete="off" readonly>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<h2>ESTADO</h2>
								<input class="form-control input-lg" type="text" id="estado" name="estado" value="<?php echo $aspirante['estado'] ?? ''; ?>" required readonly>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-lg-6">
							<div class="form-group">
								<h2>CIUDAD/MUNICIPIO</h2>
								<input class="form-control input-lg" type="text" id="ciudadMun" name="ciudadMun" value="<?php echo $aspirante['ciudadMun'] ?? ''; ?>" required readonly>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<h2>COLONIA/BARRIO</h2>
								<input class="form-control input-lg" type="text" id="colBarrio" name="colBarrio" value="<?php echo $aspirante['colBarrio'] ?? ''; ?>" required readonly>
								
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-lg-6">
							<div class="form-group">
								<h2>CALLE/NUMERO</h2>
								<input class="form-control input-lg" type="text" name="calleNo" value="<?php echo $aspirante['calleNo'] ?? ''; ?>" required>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<h2>TELEFONO</h2>
								<input class="form-control input-lg" type="number" name="telefonoCel" id="telefonoCel" minlength="10" maxlength="10"
									value="<?php echo $aspirante['telefonoCel'] ?? ''; ?>"
									oninput="if(this.value.length > 10) this.value = this.value.slice(0,10);" required>
							</div>
						</div>
					</div>
										
					<input type="hidden" id="fechaCap" name="fechaCap">
					<script>
						window.addEventListener('load', function () {
							const inputFecha = document.getElementById('fechaCap');
							const fechaActual = new Date().toISOString().slice(0, 19).replace('T', ' ');
							inputFecha.value = fechaActual;
						});
					</script>

					<input type="hidden" id="nuevo" name="nuevo" value="0">

					<div class="form-group" align="center">
						<button type="submit" class="btn btn-success">ACTUALIZAR</button>
						<a href="<?php echo $base_url; ?>nuevos" class="btn btn-danger btn-flat">CANCELAR</a>
					</div>
				</div>
			</form>
		</div>
	</section>
</div>
