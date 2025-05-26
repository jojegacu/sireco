<?php

if (!tieneAcceso("editarUsrEmp")) {
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
              <h3 class="box-title">REGISTRO DE DATOS DE EMPLEADOS</h3>
            </div>
            <!-- /.box-header -->
				
					
						  <?php

                $editUsr = new usuariosControlador();
                $editUsr -> editarUsrEmpControlador(); 
                $editUsr -> actualizarUsrEmpControlador();       
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
              