<?php

class mensajesControlador {

  static public function ctrObtenerUsuarios() {
    return mensajesModelo::mdlObtenerUsuarios();
  }

  static public function ctrObtenerMensajes($receptorId, $emisorId) {
    return mensajesModelo::mdlObtenerMensajes($receptorId, $emisorId);
  }

  static public function ctrEnviarMensaje($emisorId, $receptorId, $mensaje) {
    return mensajesModelo::mdlEnviarMensaje($emisorId, $receptorId, $mensaje);
  }

  static public function ctrChecarNotificaciones($idReceptor) {
  return mensajesModelo::mdlContarMensajesNuevos($idReceptor);
}

static public function ctrUltimoMensajeId($idReceptor) {
  return mensajesModelo::mdlUltimoMensajeId($idReceptor);
}

// NUEVAS FUNCIONES PARA NOTIFICACIONES DE CANDIDATOS
static public function ctrObtenerNotificacionesCandidatos() {
  return mensajesModelo::mdlObtenerNotificacionesCandidatos();
}

static public function ctrEliminarNotificacion($id) {
  return mensajesModelo::mdlEliminarNotificacion($id);
}

}
