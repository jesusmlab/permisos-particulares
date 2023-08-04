/**********************************************
 * Filtrar Usuarios
 **********************************************/
$("#filtro").on("change", function (evento) {
  refrescarVista();
});

function refrescarVista() {
  document.frmBuscar.filtro.value = $("#filtro").val();
  document.frmBuscar.submit();
}

/****************************************
 * Evento borrar Usuario
 ****************************************/
$(".btnBorrar").on("click", function (evento) {
  Swal.fire({
    title: "Estás seguro?",
    text: "Esta acción no se puede revertir!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, borrar!",
  }).then((result) => {
    if (result.isConfirmed) {
      // Borrar usuario
      let usu = $(this).parents("tr").children("td").eq(0).html();
      // Hacer la llamada ajax
      $.post(
        base_url + "Usuarios_c/borrarLinea",
        { usuario: usu },
        function (dev) {
          // Referescar
          location.reload();
        }
      );
    }
  });
});
/********************************************
 * Modificar Usuario
 ********************************************/

$(".btnModificar").on("click", function (evento) {
  //Obtener referencia del articulo
  let usu = $(this).parents("tr").children("td").eq(0).html();
  // Leer mediante AJAX el usuario
  $.post(
    base_url + "Usuarios_c/leerUsuarioAjax",
    { usuario: usu },
    function (datosdev) {
      // Cargar todos los valores de los campos del formulario con los datos recibidos
      // bucle por todos los campos del formulario
      let usuario = JSON.parse(datosdev);
      for (let indice in usuario) {
        if (indice != "password") {
          if (document.frmUsuarios[indice]) {
            document.frmUsuarios[indice].value = usuario[indice];
          }
        }
      }
      document.frmUsuarios.action = base_url + "Usuarios_c/modificar";
      $("#titulousuariosModal").html("Modificar Usuarios");
      document.frmUsuarios.usuario.readOnly = true;
      // Visualizar la ventana modal
      const miModal = new bootstrap.Modal("#usuariosModal");
      miModal.show();
    }
  );
});
$("#usuariosModal").on("shown.bs.modal", function (evento) {
  document.frmUsuarios.usuario.focus();
});

$("#btnNuevo").on("click", function (evento) {
  document.frmUsuarios.reset(); // Resetear campos del formulario
});
// Ver password
$("#btnVerPass").on("click", function (evento) {
  if ($(document.frmUsuarios.password).attr("type") == "password") {
    $(document.frmUsuarios.password).attr("type", "text");
    this.innerHTML = '<i class="bi bi-eye-slash"></i>';
  } else {
    $(document.frmUsuarios.password).attr("type", "password");
    this.innerHTML = '<i class="bi bi-eye"></i>';
  }
});
