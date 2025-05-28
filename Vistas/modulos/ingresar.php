<div class="login-box">
  <div class="login-logo">
    <img src="<?php echo $base_url; ?>Vistas/img/logo.png" class="img-responsive">
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Iniciar Sesión</p>

    <form method="post">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" name="usuario" placeholder="Ususario">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" name="password" placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">

        <!-- /.col -->
        <div class="col-xs-12">
          <button type="submit" class="btn btn-primary btn-block btn-flat">INICIAR</button>
          <br>
          <div align="center">
          <a href="<?php echo $base_url; ?>candidatos">>>REGISTRO DE CANDIDATOS<<</a>
          </div>
          
        </div>
        <!-- /.col -->
      </div>

      <?php

        $ingreso = new usuariosControlador();
        $ingreso -> iniciarSesionControlador();

      ?>


    </form>
    
  </div>
  <!-- /.login-box-body -->
</div>