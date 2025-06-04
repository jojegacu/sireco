<div class="candidatos-container">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content">
      

          <div class="box">
            <div class="box-header" align="center">
              <div class="logo-container">
                <img src="<?php echo $base_url; ?>Vistas/img/logoase-white.png" class="logo-candidatos" alt="Logo Grupo ASE">
              </div>
              <h3 class="box-title">REGISTRO DE CANDIDATOS</h3>
            </div>
            <!-- /.box-header -->
          <form method="post" enctype="multipart/form-data">
            <div class="box-body">
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                       <h2>NOMBRE(S)</h2>
                        <input class="form-control input-lg" type="text" name="nombre" onkeyup="this.value = this.value.toUpperCase();" required>
                  </div>
                  <!-- /input-group -->
                </div>
                <!-- /.col-lg-6 -->
                <div class="col-lg-6">
                  <div class="form-group">
                        <h2>APELLIDO PATERNO</h2>
                        <input class="form-control input-lg" type="text" name="apPaterno" onkeyup="this.value = this.value.toUpperCase();" required>
                  </div>
                  <!-- /input-group -->
                </div>
                <!-- /.col-lg-6 -->
              </div>
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                       <h2>APELLIDO MATERNO</h2>
                       <input class="form-control input-lg" type="text" name="apMaterno" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                  </div>
                  <!-- /input-group -->
                </div>
                <!-- /.col-lg-6 -->
                <div class="col-lg-6">
                  <div class="form-group">
                      <h2>FECHA DE NACIMIENTO</h2>
                      <input class="form-control input-lg" type="date" name="fechaNacimiento" required>
                  </div>
                  <!-- /input-group -->
                </div>
                <!-- /.col-lg-6 -->
              </div>
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                       <h2>CODIGO POSTAL</h2>
                       <input class="form-control input-lg" type="text" id="codPostal" name="codPostal" onkeyup="javascript:this.value=this.value.toUpperCase();" required autocomplete="off">
                  </div>
                  <!-- /input-group -->
                </div>
                <!-- /.col-lg-6 -->
                <div class="col-lg-6">
                  <div class="form-group">
                      <h2>ESTADO</h2>
                      <input class="form-control input-lg" type="text" id="estado" name="estado" onkeyup="javascript:this.value=this.value.toUpperCase();" required readonly>
                  </div>
                  <!-- /input-group -->
                </div>
                <!-- /.col-lg-6 -->
              </div>
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                       <h2>CIUDAD/MUNICIPIO</h2>
                       <input class="form-control input-lg" type="text" id="ciudadMun" name="ciudadMun" onkeyup="javascript:this.value=this.value.toUpperCase();" required readonly>
                  </div>
                  <!-- /input-group -->
                </div>
                <!-- /.col-lg-6 -->
                <div class="col-lg-6">
                  <div class="form-group">
                      <h2>COLONIA/BARRIO</h2>
                      <input class="form-control input-lg" type="text" id="colBarrio" name="colBarrio" onkeyup="javascript:this.value=this.value.toUpperCase();" required readonly>
                      <script src="Vistas/js/aspirantes.js"></script>
                  </div>
                  <!-- /input-group -->
                </div>
                <!-- /.col-lg-6 -->
              </div>
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                       <h2>CALLE/NUMERO </h2>
                        <input class="form-control input-lg" type="text" name="calleNo" required>
                  </div>
                  <!-- /input-group -->
                </div>
                <!-- /.col-lg-6 -->
                <div class="col-lg-6">
                  <div class="form-group">
                       <h2>CARGAR CV </h2>
                        <input type="file" name="cvFile" id="cvFile" accept="application/pdf" required>
                  </div>
                  
                  <!-- /input-group -->
                </div>
                <!-- /.col-lg-6 -->                
              </div>
              <div class="row">
                <!-- Procedencia -->
                <div class="col-lg-6">
                  <div class="form-group">
                    <h2>PROCEDENCIA</h2>
                    <select class="form-control input-lg" name="idProcedenciaFk" id="idProcedenciaFk" required>
                      <option value="">Seleccione una opción</option>
                    </select>
                  </div>
                </div>

                <!-- Anotación -->
                <div class="col-lg-6">
                  <div class="form-group">
                    <h2>NOMBRE DE MEDIO, RED SOCIAL O PERSONA</h2>
                    <input type="text" id="inputAnotacion" name="anotacion" class="form-control input-lg" onkeyup="this.value = this.value.toUpperCase();" required>
                  </div>
                </div>
              </div>
              <div class="row">
                  <!-- Teléfono -->
                  <div class="col-lg-4">
                    <div class="form-group">
                      <h2>TELEFONO</h2>             
                      <input class="form-control input-lg" type="text" name="telefonoCel" id="telefonoCel" minlength="10" maxlength="10" 
                             oninput="if(this.value.length > 10) this.value = this.value.slice(0,10);" required>
                    </div>
                  </div>

                  <!-- Correo electrónico -->
                  <div class="col-lg-4">
                    <div class="form-group">
                      <h2>CORREO ELECTRÓNICO</h2>
                      <input class="form-control input-lg" type="email" name="correo" id="correo" placeholder="ejemplo@correo.com" required>
                    </div>
                  </div>

                  <!-- Puesto -->
                  <div class="col-lg-4">
                    <div class="form-group">
                      <h2>SELECCIONA EL PUESTO</h2>
                      <div class="input-group">
                        <input type="text" class="form-control" id="buscarClaveVacante" placeholder="Teclea la clave..." autocomplete="off">
                        <span class="input-group-btn">
                          <button class="btn btn-danger" type="button" id="btnBuscarPuesto" data-toggle="modal" data-target="#modalVacantes">
                            <i class="fa fa-search"></i>
                          </button>
                        </span>
                      </div>
                      <input type="hidden" id="puestoGenerales" name="puesto">
                    </div>
                  </div>
                </div>

              <input type="hidden" id="fechaCap" name="fechaCap">
                <script>
                      window.addEventListener('load', function() {
                          const inputFecha = document.getElementById('fechaCap');
                          const fechaActual = new Date().toISOString().slice(0, 19).replace('T', ' ');
                          inputFecha.value = fechaActual; // formato: yyyy-mm-dd hh:mm:ss
                      });
                </script> 
                <input type="hidden" id="nuevo" name="nuevo" value=0 >             
                  <!-- /.row -->
              <div class="form-group" align="center">
                <button type="submit" class="btn btn-success" onclick="setFechaRegistro()">REGISTRAR</button>
                <a href="<?php echo $base_url; ?>ingresar" class="btn btn-danger btn-flat">CANCELAR</a>
            </div>
          </form>
            </div>              
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- Modal Seleccionar Vacante -->
          <div class="modal fade" id="modalVacantes" tabindex="-1" role="dialog" aria-labelledby="modalVacantesLabel">
            <div class="modal-dialog modal-xl" role="document">
              <div class="modal-content">
                <div class="modal-header bg-red">
                  <h4 class="modal-title">Seleccionar Vacante</h4>
                </div>
                <div class="modal-body">
                  <input type="text" id="filtroVacantes" class="form-control" placeholder="Filtrar vacantes por cualquier campo..." autocomplete="off">
                  <br>
                  <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="tablaVacantes">
                      <thead>
                        <tr>
                          <th>Tienda</th>
                          <th>ID</th>
                          <th>Clave</th>
                          <th>Categoría</th>
                          <th>CP</th>
                          <th>Estado</th>
                          <th>Municipio</th>
                          <th>Colonia</th>                                                  
                        </tr>
                      </thead>
                      <tbody></tbody>
                    </table>
                    <div id="paginadorVacantes" class="text-center mt-3"></div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
              </div>
            </div>
          </div>

          
          <!-- Modal de Validación de CURP -->
          <div class="modal fade" id="modalValidarCurp" tabindex="-1" role="dialog" aria-labelledby="modalValidarCurpLabel" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
              <div class="modal-content text-center">
                <div class="modal-header bg-primary text-white">
                  <h4 class="modal-title w-100">Validar CURP</h4>
                </div>
                <div class="modal-body">
                  <label>Ingresa tu CURP</label>
                  <input type="text" id="inputCurp" class="form-control text-uppercase" maxlength="18" minlength="18" pattern="[A-Z0-9]{18}" required>
                  <small class="form-text text-muted">Debe contener exactamente 18 caracteres.</small>
                </div>
                <div class="modal-footer justify-content-center">
                  <button id="btnValidarCurp" class="btn btn-success">Validar</button>
                </div>
              </div>
            </div>
          </div>


       
    </section>
    <!-- /.content -->
 </div><!-- Fin de candidatos-container -->

  <?php
      $crearUsr = new aspiranteControlador();
      $crearUsr -> altaAspiranteControlador();
  ?>