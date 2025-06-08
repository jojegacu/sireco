<?php

if (!tieneAcceso("editarUsr")) {
    $baseUrl = rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/");
    echo '<script>window.location.href = "' . $baseUrl . '/inicio";</script>';
    return;
}

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content" style="padding-left: 0 !important; padding-right: 0 !important;">
      <div class="row" style="margin-left: 0 !important; margin-right: 0 !important; width: 100% !important;">
        <div class="col-xs-12" style="width: 100% !important; padding-left: 0 !important; padding-right: 0 !important;">

          <div class="box" >
            <div class="box-header" align="center">
              <h3 class="box-title">REGISTRO DE DATOS GENERALES</h3>
            </div>
            <!-- /.box-header -->
				
					
							<?php

								$editUsr = new usuariosControlador();
								$editUsr -> editarUsrControlador();	
								$editUsr ->	actualizarUsrControlador();				
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
              