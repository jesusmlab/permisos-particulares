moment.locale("es");
let tabla = $("#tblpermisos").DataTable({
  language: {
    sProcessing: "Procesando...",
    sLengthMenu: "Mostrar _MENU_ registros",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sInfo:
      "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
    sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
    sInfoPostFix: "",
    sSearch: "Buscar:",
    sUrl: "",
    sInfoThousands: ",",
    sLoadingRecords: "Cargando...",
    oPaginate: {
      sFirst: "Primero",
      sLast: "Último",
      sNext: "Siguiente",
      sPrevious: "Anterior",
    },
    oAria: {
      sSortAscending: ": Activar para ordenar la columna de manera ascendente",
      sSortDescending:
        ": Activar para ordenar la columna de manera descendente",
    },
  },
  stateSave: true,
  stateSaveCallback: function (settings, data) {
    // guardar los filtros
    localStorage.setItem("dataTables_filterSettings", JSON.stringify(data));
  },
  stateLoadCallback: function (settings) {
    // leer los filtros
    return JSON.parse(localStorage.getItem("dataTables_filterSettings"));
  },
  dom: "Pfrtip",
  columnDefs: [
    {
      searchPanes: {
        show: false,
      },
      targets: [0, 1, 2, 3, 5, 6, 7, 8, 10],
    },
  ],
});
/****************************************
 * Refrescar eventos en datatable
 ****************************************/
$("#tblpermisos").on("page.dt search.dt length.dt draw.dt", function () {
  $(".btnBorrar").off("click").on("click", borrar);
  $(".btnModificar").off("click").on("click", modificar);
  $(".btn-fechap").off("click").on("click", verDia);
  $(".infoDiasUsu").off("click").on("click", infoDiasUsu);
});

/****************************************
 * Informacion dias concedidos Usuario
 ****************************************/

$(".infoDiasUsu").on("click", infoDiasUsu);

function infoDiasUsu(evento) {
  let usu = $(evento.currentTarget).data("usu");
  // Leer mediante AJAX las anotaciones concedidas de la fecha
  estados = {
    S: "Solicitado",
    R: "Registrado",
    C: "Concedido",
    D: "Denegado",
    A: "Anulado",
  };
  $.post(
    base_url + "Permisos_c/leerPermisosUsu",
    { usu: usu },
    function (datosdev) {
      let permisos = JSON.parse(datosdev);
      let cadena = "<table class='table'>";
      cadena +=
        "<th>NºPet.</th><th>F.Petición</th><th>F.Pedida</th><th>F.Registro</th><th>Lectivo</th><th>Estado</th>";
      for (permi of permisos) {
        cadena += "</tr>";
        cadena += `<td>${permi.npeticion}</td>`;
        cadena += `<td>${moment(permi.fecha_peticion).format(
          "DD/MM/YYYY HH:mm"
        )}</td>`;
        cadena += `<td>${moment(permi.fecha_pedida).format("DD/MM/YYYY")}</td>`;
        if (permi.fecha_registro != "0000-00-00 00:00:00") {
          cadena += `<td>${moment(permi.fecha_registro).format(
            "DD/MM/YYYY HH:mm"
          )}</td>`;
        } else {
          cadena += `<td></td>`;
        }
        cadena += `<td>${permi.dia_lectivo ? "S" : "N"}</td>`;
        cadena += `<td>${estados[permi.estado]}</td>`;
        cadena += "</tr>";
      }
      cadena += "</table>";
      $("#cuerpoInfoUsu").html(cadena);
    }
  );
  $("#verInfoModalLabel").html(
    "Usuario " + $(evento.currentTarget).parent("td").html()
  );
  const miModal2 = new bootstrap.Modal("#verInfoModal");
  miModal2.show();
}

/****************************************
 * Evento borrar permiso
 ****************************************/
$(".btnBorrar").on("click", borrar);

function borrar(evento) {
  Swal.fire({
    title: "Estás seguro?",
    text: "Esta acción no se puede revertir.Si procede, borrar en aplicación de guardias la falta de ese día",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, borrar!",
  }).then((result) => {
    if (result.isConfirmed) {
      // Borrar permiso
      let id = $(evento.currentTarget).parents("tr").data("id");
      // Hacer la llamada ajax
      $.post(base_url + "Permisos_c/borrarLinea", { id: id }, function (dev) {
        // Referescar
        location.reload();
      });
    }
  });
}
/**************************************
 * Estado D
 *************************************/
$(frmPermisos.estado).on("change", function () {
  if (this.value == "D") {
    $("#causad").show();
  } else {
    $("#causad").hide();
  }
});
/********************************************
 * Modificar permiso
 ********************************************/
$(".btnModificar").on("click", modificar);

function modificar(evento) {
  //Obtener id del permiso
  let id = $(evento.currentTarget).parents("tr").data("id");
  // Leer mediante AJAX el registro dle permiso
  $.post(base_url + "Permisos_c/leerPermiso", { id: id }, function (datosdev) {
    // Cargar todos los valores de los campos del formulario con los datos recibidos
    // bucle por todos los campos del formulario
    let permiso = JSON.parse(datosdev);
    for (let indice in permiso) {
      if (indice == "dia_lectivo") {
        $("#dia_lectivo").attr("checked", permiso[indice] == 1 ? true : false);
      } else {
        if (document.frmPermisos[indice]) {
          document.frmPermisos[indice].value = permiso[indice];
        }
      }
    }
    document.frmPermisos.action = base_url + "Permisos_c/modificar";
    $("#titulopermisosModal").html("Modificar Permiso");
    // Visualizar la ventana modal
    const miModal = new bootstrap.Modal("#permisosModal");
    miModal.show();
  });
}
$("#permisosModal").on("shown.bs.modal", function (evento) {
  document.frmPermisos.usuario.focus();
});

$("#btnNuevo").on("click", function (evento) {
  document.frmPermisos.reset(); // Resetear campos del formulario
});
$(".btn-fechap").on("click", verDia);

function verDia(evento) {
  let fecha = evento.currentTarget.dataset.fechap;
  // Leer mediante AJAX las anotaciones concedidas de la fecha
  estados = {
    S: "Solicitado",
    R: "Registrado",
    C: "Concedido",
    D: "Denegado",
    A: "Anulado",
  };
  $.post(
    base_url + "Permisos_c/leerPermisosFechaDet",
    { fecha: fecha },
    function (datosdev) {
      let permisos = JSON.parse(datosdev);
      let cadena = "<table class='table'>";
      cadena +=
        "<th>Nombre</th><th>Hor.</th><th>NºPet.</th><th>F.Petición</th><th>F.Registro</th><th>Lectivo</th><th>Estado</th>";
      for (permi of permisos) {
        cadena += "</tr>";
        cadena += `<td>${permi.apenom}</td>`;
        cadena += `<td>${permi.horario}</td>`;
        cadena += `<td>${permi.npeticion}</td>`;
        cadena += `<td>${moment(permi.fecha_peticion).format(
          "DD/MM/YYYY"
        )}</td>`;
        if (permi.fecha_registro != "0000-00-00 00:00:00") {
          cadena += `<td>${moment(permi.fecha_registro).format(
            "DD/MM/YYYY HH:mm"
          )}</td>`;
        } else {
          cadena += `<td></td>`;
        }
        cadena += `<td>${permi.dia_lectivo ? "S" : "N"}</td>`;
        cadena += `<td>${estados[permi.estado]}</td>`;
        cadena += "</tr>";
      }
      cadena += "</table>";
      $("#cuerpoVerDia").html(cadena);
    }
  );
  $("#verDiaModalLabel").html("Día " + moment(fecha).format("DD/MM/YYYY"));
  const miModal2 = new bootstrap.Modal("#verDiaModal");
  miModal2.show();
}
