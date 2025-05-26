<?php

if (!tieneAcceso("crearUsr")) {
    $baseUrl = rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/");
    echo '<script>window.location.href = "' . $baseUrl . '/inicio";</script>';
    return;
}

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) --> 
    <!-- Main content -->
    <section class="content" >
      <div class="row">
        <div class="col-xs-9">

          <div class="box" style="margin-left: 20%;">
            <div class="box-header" align="center">
              <h3 class="box-title">REGISTRO DE DATOS GENERALES</h3>
            </div>
            <!-- /.box-header -->

              <form method="post">
            <div class="box-body">
              <div class="row">              
                <div class="col-lg-6">
                  <div class="form-group">                      
                       <h2>Apellidos</h2>
                        <input class="form-control input-lg" type="text" name="apellidosUsr" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                  </div>
                  <!-- /input-group -->
                </div>
                <!-- /.col-lg-6 -->
                <div class="col-lg-6">
                  <div class="form-group">
                        <h2>Nombre</h2>
                        <input class="form-control input-lg" type="text" name="nombreUsr" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                  </div>
                  <!-- /input-group -->
                </div>
                <!-- /.col-lg-6 -->
              </div>

              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                       <h2>CURP</h2>
                       <input class="form-control input-lg" type="text" name="curpUsr" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                  </div>
                  <!-- /input-group -->
                </div>
                <!-- /.col-lg-6 -->
                <div class="col-lg-6">
                  <div class="form-group">
                      <h2>RFC</h2>
                      <input class="form-control input-lg" type="text" name="rfcUsr" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                  </div>
                  <!-- /input-group -->
                </div>
                <!-- /.col-lg-6 -->
              </div>
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                       <h2>Correo</h2>
                        <input class="form-control input-lg" type="text" name="emailUsr" required>
                  </div>
                  <!-- /input-group -->
                </div>
                <!-- /.col-lg-6 -->
                <div class="col-lg-6">
                  <div class="form-group">
                      <h2>Telefono</h2>             
                      <input class="form-control input-lg" type="text" name="telefonoUsr" required>                      
                  </div>
                  <!-- /input-group -->
                </div>
                <!-- /.col-lg-6 -->
              </div>
              <!-- /.row -->
              <div class="row">              
                <div class="col-lg-6">
                  <div class="form-group">   
                   <h2>Fecha de Captura</h2>                  
                  <input class="form-control input-lg" type="text" id="fechaCap" name="fechaCap" required readonly>
                    <script>
                          window.addEventListener('load', function() {
                              const inputFecha = document.getElementById('fechaCap');
                              const fechaActual = new Date().toISOString().slice(0, 19).replace('T', ' ');
                              inputFecha.value = fechaActual; // formato: yyyy-mm-dd hh:mm:ss
                          });
                    </script>
                  </div>
                  <!-- /input-group -->
                </div>
                <!-- /.col-lg-6 -->
                <div class="col-lg-6">
                  <div class="form-group"> 
                   <h2>Estatus ROL</h2> 
                   <input class="form-control input-lg" type="text" placeholder="NUEVO" readonly>
                   <input  type="hidden" id="idRolFk" name="idRolFk" value="13">
                  <input type="hidden" name="nuevo" value="1">
                  </div>
                  <!-- /input-group -->
                </div>
                <!-- /.col-lg-6 -->
              </div>
              <div class="form-group" align="center">
                                
                <a href="<?php echo $base_url; ?>usuarios" class="btn btn-danger btn-flat">CANCELAR</a>

                <button type="submit" class="btn btn-success">REGISTRAR</button>
              </div>
          </form>
        
          
              <?php
                $altaUsr = new usuariosControlador();
                $altaUsr -> crearUsuarioControlador();     
              ?>            
              
      </div>              
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
              