<aside class="main-sidebar">
  <section class="sidebar">
    <ul class="sidebar-menu">

      <li>
        <a href="<?php echo $base_url; ?>inicio">
          <i class="fa fa-home"></i>          
          <span>Inicio</span>
        </a>      
      </li>

      <?php if (
        tieneAcceso("usuarios") || tieneAcceso("nuevos") ||
        tieneAcceso("checkManager") || tieneAcceso("contratacion")
      ): ?>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-users"></i>
          <span>Usuarios</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <?php if (tieneAcceso("usuarios")): ?>
          <li><a href="<?php echo $base_url; ?>usuarios"><i class="fa fa-user-secret"></i> Personal</a></li>
          <?php endif; ?>
          <?php if (tieneAcceso("nuevos")): ?>
          <li><a href="<?php echo $base_url; ?>nuevos"><i class="fa fa-binoculars"></i> Reclutamiento</a></li>
          <?php endif; ?>
          <?php if (tieneAcceso("checkManager")): ?>
          <li><a href="<?php echo $base_url; ?>checkManager"><i class="fa fa-eye"></i> Vo.Bo.</a></li>
          <?php endif; ?>
          <?php if (tieneAcceso("contratacion")): ?>
          <li><a href="<?php echo $base_url; ?>contratacion"><i class="fa fa-user-plus"></i> Contratación</a></li>
          <?php endif; ?>
        </ul>
      </li>
      <?php endif; ?>

      <?php if (
        tieneAcceso("roles") || tieneAcceso("municipios") ||
        tieneAcceso("empleados") || tieneAcceso("standby")
      ): ?>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-edit"></i>
          <span>Catálogos</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <?php if (tieneAcceso("roles")): ?>
          <li><a href="<?php echo $base_url; ?>roles"><i class="fa fa-users"></i> Roles</a></li>
          <?php endif; ?>
          <?php if (tieneAcceso("municipios")): ?>
          <li><a href="<?php echo $base_url; ?>municipios"><i class="fa fa-university"></i> Municipios</a></li>
          <?php endif; ?>
          <?php if (tieneAcceso("empleados")): ?>
          <li><a href="<?php echo $base_url; ?>empleados"><i class="fa fa-suitcase"></i> Contratados</a></li>
          <?php endif; ?>
          <?php if (tieneAcceso("standby")): ?>
          <li><a href="<?php echo $base_url; ?>standby"><i class="fa fa-user-plus"></i> StandBy</a></li>
          <?php endif; ?>
        </ul>
      </li>
      <?php endif; ?>

      <?php if (tieneAcceso("vacante") || tieneAcceso("masiva")): ?>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-gears"></i>
          <span>Vacantes</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <?php if (tieneAcceso("vacante")): ?>
          <li><a href="<?php echo $base_url; ?>vacante"><i class="fa fa-file"></i> Alta individual</a></li>
          <?php endif; ?>
          <?php if (tieneAcceso("masiva")): ?>
          <li><a href="<?php echo $base_url; ?>masiva"><i class="fa fa-cloud-upload"></i> Carga masiva</a></li>
          <?php endif; ?>
        </ul>
      </li>
      <?php endif; ?>

      <?php if (tieneAcceso("reportes")): ?>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-address-book"></i>
          <span>Reportes</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="<?php echo $base_url; ?>reportes"><i class="fa fa-file"></i> Generar</a></li>
        </ul>
      </li>
      <?php endif; ?>

      <?php if (tieneAcceso("dashboard")): ?>
      <li>
        <a href="<?php echo $base_url; ?>dashboard">
          <i class="fa fa-table"></i>
          <span>Reportes</span>
        </a>
      </li>
      <?php endif; ?>

    </ul>
  </section>
</aside>
