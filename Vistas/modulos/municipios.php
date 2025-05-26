
<?php

if (!tieneAcceso("municipios")) {
    $baseUrl = rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/");
    echo '<script>window.location.href = "' . $baseUrl . '/inicio";</script>';
    return;
}

?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>CONSULTA EN ESTADOS</h1>
    </section>
    <section class="content">

        <div class="box">                   
            <div class="box-body">
                <h2>Buscar Codigo Postal</h2>
                <form method="post">
                    <div class="row">
                        
                        <div class="col-md-6 col-xs-6">                                     
                            <input type="number" id="cpFiltro" name="cpFiltro" class="form-control" placeholder="Escribe un código postal..." required>
                
                        </div>
                    </div>                      
                    
                </form>

                <div class="box-body">
                      <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No Estado</th>
                                    <th>Estado</th>
                                    <th>Municipio</th>
                                    <th>Colonia</th>
                                    <th>Código Postal</th>
                                </tr>
                            </thead>
                            <tbody id="tablaResultados">
                                 <!-- Aquí se mostrarán los resultados dinámicos -->
                            </tbody>                    
                      </table>
                      <script src="Vistas/js/municipios.js"></script>
                </div>
                
            </div>
            
        </div>
        
    </section>

</div>
