<?php

if (!tieneAcceso("altaVac")) {
    $baseUrl = rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/");
    echo '<script>window.location.href = "' . $baseUrl . '/inicio";</script>';
    return;
}

?>

<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-xs-12">

        <div class="box">
          <div class="box-header" align="center">
            <a href="#" class="logo"></a>
            <h1>VACANTES DISPONIBLES</h1>
          </div>

          <form id="formVacante" method="post" enctype="multipart/form-data">
            <div class="box-body">

              <div class="row">
                <div class="col-lg-4">
                  <div class="form-group">
                    <h2>ID</h2>
                    <input class="form-control input-lg" type="text" name="id" onkeyup="this.value = this.value.toUpperCase();" required>
                  </div>
                </div>

                <div class="col-lg-4">
                  <div class="form-group">
                    <h2>REGIÓN</h2>
                    <select class="form-control input-lg" name="idRegionFk" id="comboRegion" required>
                      <option value="">Cargando regiones...</option>
                    </select>
                  </div>
                </div>

                <div class="col-lg-4">
                  <div class="form-group">
                    <h2>TIENDA</h2>
                    <input class="form-control input-lg" type="text" name="tienda" onkeyup="this.value=this.value.toUpperCase();" required>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-4">
                  <div class="form-group">
                    <h2>RESPONSABLE</h2>
                    <input class="form-control input-lg" type="text" name="responsable" onkeyup="this.value=this.value.toUpperCase();" required>
                  </div>
                </div>

                <div class="col-lg-4">
                  <div class="form-group">
                    <h2>CÓDIGO POSTAL</h2>
                    <input class="form-control input-lg" type="text" id="codPostal" name="codPostal" onkeyup="this.value=this.value.toUpperCase();" autocomplete="off">
                  </div>
                </div>

                <div class="col-lg-4">
                  <div class="form-group">
                    <h2>ESTADO</h2>
                    <input class="form-control input-lg" type="text" id="estado" name="estado" onkeyup="this.value=this.value.toUpperCase();" readonly>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-4">
                  <div class="form-group">
                    <h2>CIUDAD/MUNICIPIO</h2>
                    <input class="form-control input-lg" type="text" id="ciudadMun" name="ciudadMun" onkeyup="this.value=this.value.toUpperCase();" readonly>
                  </div>
                </div>

                <div class="col-lg-4">
                  <div class="form-group">
                    <h2>COLONIA/BARRIO</h2>
                    <input class="form-control input-lg" type="text" id="colBarrio" name="colBarrio" onkeyup="this.value=this.value.toUpperCase();" readonly>                    
                  </div>
                </div>

                <div class="col-lg-4">
                  <div class="form-group">
                    <h2>VACANTE</h2>
                    <select class="form-control input-lg" name="idCatalogoFk" id="comboPuesto" required>
                      <option value="">Cargando puestos...</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="row">
                <input id="fechaCap" name="fechaCap" type="hidden" />
                <input id="clave" name="clave" type="hidden" />
                <script>
                  window.addEventListener('load', function () {
                    const inputFecha = document.getElementById('fechaCap');
                    const fechaActual = new Date().toISOString().slice(0, 19).replace('T', ' ');
                    inputFecha.value = fechaActual;
                  });
                </script>

                <div align="center" class="form-group col-lg-12">
                  <a class="btn btn-primary btn-flat" href="<?php echo $base_url; ?>vacante" id="btn-azul">FINALIZAR</a>
                  <button class="btn btn-success" type="submit" id="btn-verde">REGISTRAR</button>
                  <a class="btn btn-danger btn-flat" href="<?php echo $base_url; ?>altaVac" id="btn-rojo">LIMPIAR</a>
                </div>
              </div>

            </div>
          </form>
        </div> <!-- .box -->
      </div> <!-- .col-xs-12 -->
    </div> <!-- .row -->
  </section>
</div> <!-- .content-wrapper -->