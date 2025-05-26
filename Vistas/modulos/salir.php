<?php
// Verifica si la sesión está iniciada antes de llamarla
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Solo si la sesión tiene idPersona, actualiza el campo "conectado"
if (isset($_SESSION["idPersona"])) {   
    usuariosModelo::actualizarConexionModelo($_SESSION["idPersona"], 0);
}

// Destruye la sesión
session_destroy();

// Redirige
echo '<script>window.location = "ingresar";</script>';
exit;
?>
