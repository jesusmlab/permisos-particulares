// Boton Borrar Linea
$(".btnBorrar").on("click", function (evento) {
  let id = $(this).parents("tr").data("id");

  Swal.fire({
    title: "Estas seguro?",
    text: "Lo borrado no se puede recuperar!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, Borralo!",
  }).then((result) => {
    if (result.isConfirmed) {
      // Hacer la llamada ajax
      $.post(base_url + "Permisos_c/borrarLinea", { id: id }, function (dev) {
        // Referescar
        location.reload();
      });
    }
  });
});
$(".btnAnular").on("click", function (evento) {
  let id = $(this).parents("tr").data("id");

  Swal.fire({
    title: "Estas seguro?",
    text: "El permiso quedará anulado!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, anulalo!",
  }).then((result) => {
    if (result.isConfirmed) {
      // Hacer la llamada ajax
      $.post(base_url + "Permisos_c/anularLinea", { id: id }, function (dev) {
        // pantalla
        location.reload();
      });
    }
  });
});

moment.locale("es");

$(document.frmSolicitar).on("submit", function (evento) {
  evento.preventDefault();
  // Comprobar fecha. No puede ser menor que la de hoy ni mayor que el tope de config
  let hoy = moment();
  let fechaPedida = moment(this.fecha_pedida.value);
  if (fechaPedida <= hoy) {
    Swal.fire({
      icon: "error",
      text: "La fecha no puede ser inferior a hoy",
    });
    return;
  }

  if (fechaPedida > moment(fechaMax)) {
    Swal.fire({
      icon: "error",
      text: "La fecha no puede ser superior al fin de curso.",
    });
    return;
  }
  // Comprobar que la fecha no sea menor que 15 dias antes o 3 meses despues
  if (fechaPedida < hoy.add(14, "days")) {
    Swal.fire({
      icon: "error",
      text: "La fecha MINIMA de antelación no puede ser inferior a 15 dias.",
    });
    return;
  }
  if (fechaPedida > hoy.add(3, "months")) {
    if (fechaPedida <= hoy.add(14, "days")) {
      Swal.fire({
        icon: "error",
        text: "La fecha MAXIMA de antelación no puede ser superior a 3 meses.",
      });
      return;
    }
  }

  if ($(this.dia_lectivo).is(":checked")) {
    if (dias_lectivos > 2) {
      Swal.fire({
        icon: "error",
        text: "Ya tienes pedidos todos tus dias lectivos.",
      });
      return;
    }
  }
  // Si se llega aqui, contar los permisos concedidos diurnos y vespertinos para comparar
  $.post(
    base_url + "Permisos_c/leerPermisosFecha",
    { fecha: this.fecha_pedida.value },
    function (dev) {
      let permisos = JSON.parse(dev);
      // Ver si ya está lleno ese dia
      if (permisos.totaldia >= parseInt(topeDia) + parseInt(topeVes)) {
        Swal.fire({
          icon: "error",
          text: "Ese dia ya está completo.",
        });
      } else {
        if (frmSolicitar.horario.value == "D") {
          if (permisos.diurno >= topeDia) {
            Swal.fire({
              icon: "error",
              text: "Ese dia en diurno ya está completo.",
            });
            return;
          }
        } else if (frmSolicitar.horario.value == "V") {
          if (permisos.vespertino >= topeVes) {
            Swal.fire({
              icon: "error",
              text: "Ese dia en vespertino ya está completo.",
            });
            return;
          }
        } else {
          if (permisos.vespertino < topeVes) {
            frmSolicitar.horario.value = "V";
          } else {
            frmSolicitar.horario.value = "D";
          }
        }
        // Guardar registro
        document.frmSolicitar.submit();
      }
    }
  );
});

$("#signatureArea").signaturePad({
  drawOnly: true,
  drawBezierCurves: true,
  lineTop: 140,
});

$("#btnFirmar").on("click", function (evento) {
  // Quitar Border a la firma
  html2canvas([document.getElementById("signaturePad")], {
    onrendered: function (canvas) {
      var canvas_img_data = canvas.toDataURL("image/jpeg");
      var img_data = canvas_img_data.replace(
        /^data:image\/(png|jpeg);base64,/,
        ""
      );
      document.frmSolicitar.firmaBase64.value =
        "data:image/jpeg;base64," + img_data;
      $("#btnSolicitar").attr("disabled", false);
    },
  });
});

$("#btnLimpiar").on("click", function (evento) {
  $("#signatureArea").signaturePad().clearCanvas();
});
