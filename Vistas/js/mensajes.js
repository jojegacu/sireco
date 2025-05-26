// chat.js actualizado con notificaciones para aspirantes
let usuarioSeleccionado = null;
let notificacionesPendientes = 0;
let contadorMensajes = 0;
let intervaloMensajes = null;

function cargarUsuarios() {
  $.ajax({
    url: baseUrl + "Ajax/mensajesA.php",
    method: "POST",
    data: { usuariosYNotificaciones: true },
    dataType: "json",
    success: function (data) {
      const usuarios = data.usuarios;
      const conMensajes = data.conMensajes.map(id => parseInt(id));
      let html = "";

      usuarios.forEach(u => {
        const id = parseInt(u.id);
        const icon = u.logueado == 1 ? "🟢" : "🔴";
        const tieneMensaje = conMensajes.includes(id);

        let contenido = `${icon} ${u.nombre}`;
        if (tieneMensaje) {
          contenido = `<span style="font-weight:bold;">${u.nombre}<span class="campanita" style="margin-left: 5px; color: #ff9800;">🔔</span></span>`;
        }

        if (u.logueado == 1) {
          html += `<li class="list-group-item usuario-chat" data-id="${id}" data-mensaje="${tieneMensaje}" style="cursor:pointer;">${contenido}</li>`;
        } else {
          html += `<li class="list-group-item text-muted" style="opacity: 0.5;">${icon} ${u.nombre}</li>`;
        }
      });

      $("#listaUsuarios").html(html);
    }
  });
}

function cargarMensajes() {
  if (!usuarioSeleccionado) return;
  $.ajax({
    url: baseUrl + "Ajax/mensajesA.php",
    method: "POST",
    data: {
      obtenerMensajes: true,
      receptor: usuarioSeleccionado
    },
    dataType: "json",
    success: function (mensajes) {
      let html = "";
      mensajes.forEach(m => {
        html += `<p><strong>${m.emisor}</strong>: ${m.mensaje} <small>${m.fecha}</small></p>`;
      });

      const $chat = $("#chatHistorial");
      const mensajesPrevios = $chat.find("p").length;
      $chat.html(html);

      if (mensajes.length > mensajesPrevios) {
        const ultimoMensaje = mensajes[mensajes.length - 1];
        const esDeOtro = ultimoMensaje.emisor !== $("#chatHeader").text().trim();

        if (esDeOtro) {
          contadorMensajes++;
          $("#contadorMensajes").text(contadorMensajes).fadeIn();
        }
      }

      $chat.scrollTop($chat[0].scrollHeight);
    }
  });
}

$(document).on("click", "#startWebSocket", function () {
  $("#modalChat").modal("show");
  setTimeout(cargarUsuarios, 200);
  $.ajax({ url: baseUrl + "Ajax/mensajesA.php", method: "POST", data: { marcarVistos: true } });
  notificacionesPendientes = 0;
  $("#chatNotificacion").hide();
});

$(document).on("click", ".usuario-chat", function () {
  usuarioSeleccionado = $(this).data("id");
  $("#chatHeader").text($(this).text());

  $.ajax({
    url: baseUrl + "Ajax/mensajesA.php",
    method: "POST",
    data: { marcarVistosDe: usuarioSeleccionado }
  });

  cargarMensajes();
  $(this).find(".campanita").remove();

  clearInterval(intervaloMensajes);
  intervaloMensajes = setInterval(cargarMensajes, 5000);
});

$(document).on("click", "#enviarMensaje", function () {
  const mensaje = $("#mensajeInput").val().trim();
  if (!mensaje || !usuarioSeleccionado) return;

  $.ajax({
    url: baseUrl + "Ajax/mensajesA.php",
    method: "POST",
    data: {
      enviarMensaje: true,
      receptorId: usuarioSeleccionado,
      mensaje: mensaje
    },
    success: function (resp) {
      if (resp.trim() === "ok") {
        $("#mensajeInput").val("");
        cargarMensajes();
      } else {
        alert("❌ No se pudo enviar el mensaje: " + resp);
      }
    },
    error: function () {
      alert("❌ Error de conexión con el servidor");
    }
  });
});

function revisarNotificaciones() {
  $.ajax({
    url: baseUrl + "Ajax/mensajesA.php",
    method: "POST",
    data: { checarNotificaciones: true },
    success: function (total) {
      const cantidad = parseInt(total);
      if (cantidad > 0) {
        $("#chatNotificacion").text(cantidad).show();
      } else {
        $("#chatNotificacion").hide();
      }
    }
  });
}

function cargarNotificacionesCandidatos() {
  $.ajax({
    url: baseUrl + "Ajax/mensajesA.php",
    method: "POST",
    data: { notificacionesCandidatos: true },
    dataType: "json",
    success: function (notifs) {
      const contenedor = $("#notificacionLista");
      contenedor.html("");
      if (Array.isArray(notifs) && notifs.length > 0) {
        $("#notificacionCantidad").text(notifs.length).show();
        notifs.forEach(n => {
          const texto = {
            0: "🆕 Nuevo candidato",
            1: "📋 Candidato en VoBo",
            2: "📤 En contratación",
            3: "❌ Eliminado",
            4: "✅ Nuevo contratado"
          }[n.nuevo] || "Actualización";

          const destino = {
            0: "nuevos",
            1: "checkManager",
            2: "contratacion",
            3: "standby",
            4: "empleados"
          }[n.nuevo];

          const li = $(`<li><a href="${baseUrl + destino}" class="notificacion-enlace" data-id="${n.idAspirante}">${texto}</a></li>`);
          contenedor.append(li);
        });
      } else {
        $("#notificacionCantidad").hide();
      }
    }
  });
}

$(document).on("click", "#notificacionesBtn", function () {
  $("#notificacionDropdown").toggle();
  cargarNotificacionesCandidatos();
});

$(document).on("click", ".notificacion-enlace", function (e) {
  e.preventDefault();
  const id = $(this).data("id");
  const enlace = $(this);
  const urlDestino = enlace.attr("href");

  $.ajax({
    url: baseUrl + "Ajax/mensajesA.php",
    method: "POST",
    data: { borrarNotificacion: id },
    success: function () {
      enlace.closest("li").remove();
      const actual = parseInt($("#notificacionCantidad").text()) || 0;
      const nuevoValor = Math.max(0, actual - 1);
      if (nuevoValor > 0) {
        $("#notificacionCantidad").text(nuevoValor);
      } else {
        $("#notificacionCantidad").hide();
        $("#notificacionDropdown").hide();
      }
      window.location.href = urlDestino;
    }
  });
});

setInterval(revisarNotificaciones, 5000);
setInterval(cargarNotificacionesCandidatos, 5000);
setInterval(() => {
  if (!$("#modalChat").is(":visible")) {
    cargarUsuarios();
  }
}, 5000);

$('#modalChat').on('hidden.bs.modal', function () {
  clearInterval(intervaloMensajes);
});
