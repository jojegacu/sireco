<header class="main-header">
    <!-- Logo -->
    <a href="<?php echo $base_url; ?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><img src="<?php echo $base_url; ?>Vistas/img/logoase-white.png" class="img-responsive" style="max-height: 30px; margin: 10px auto;"></span>
      
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><img src="<?php echo $base_url; ?>Vistas/img/logoase-white.png" class="img-responsive"></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      
        
      
     
      <div class="navbar-custom-menu"> 
        <ul class="nav navbar-nav">
          <li class="dropdown messages-menu">
            <a href="#" id="startWebSocket" class="btn btn-success btn-flat position-relative">
                <i class="fa fa-commenting"></i>
                <span id="chatNotificacion"
                      style="position: absolute; top: -5px; right: -5px; background: red; color: white; border-radius: 50%; padding: 2px 6px; font-size: 12px; display: none;">
                </span>
              </a> 
          </li>
          <li class="dropdown notifications-menu">
              <a href="#" id="notificacionesBtn" class="btn btn-warning btn-flat position-relative">
                <i class="fa fa-bell"></i>
                <span id="notificacionCantidad"
                      style="position: absolute; top: -5px; right: -5px; background: red; color: white; border-radius: 50%; padding: 2px 6px; font-size: 12px; display: none;">
                </span>
              </a>
              <ul id="notificacionDropdown" class="dropdown-menu" style="display: none; position: absolute; right: 0; background: white; border: 1px solid #ccc; padding: 10px; width: 250px; z-index: 9999;">
                  <li class="header">Notificaciones recientes</li>
                  <li>
                    <ul id="notificacionLista" class="menu" style="list-style: none; padding-left: 0;"></ul>
                  </li>
                </ul>
            </li>


          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              
              <?php

                echo'<span class="hidden-xs">'.$_SESSION["apellidos"]." ".$_SESSION["nombre"].' </span>';

              ?>
             
            </a>
            <ul class="dropdown-menu" style="border: 1px solid black;">
              <!-- User image -->
              <li class="user-header" style="height: 100px;">

                <?php

                  if ($_SESSION["rol"] == "ADMINISTRADOR") {

                      echo '<p>'.$_SESSION["apellidos"]." ".$_SESSION["nombre"].' - ADMINISTRADOR</p>' ;
          
                  }
                ?>
                
              </li>
              <!-- Menu Body -->
             
              <li class="user-footer">
                <div class="pull-left">
                  <a href="misDatos" class="btn btn-primary btn-flat">MIS DATOS</a>
                </div>
                <div class="pull-right">
                  <a href="salir" class="btn btn-danger btn-flat">SALIR</a>
                </div>
              </li>
            </ul>
          </li>
    
        </ul>
      </div>
    </nav>

  </header>