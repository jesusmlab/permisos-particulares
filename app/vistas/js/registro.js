$(".btnRegistrar").on("click", function (evento) {
  let id = $(this).parents("tr").data("id");

  document.frmRegistros.id.value = id;
  // Visualizar la ventana modal
  const miModal = new bootstrap.Modal("#registrosModal");
  miModal.show();
});
$("#registrosModal").on("shown.bs.modal", function (evento) {
  document.frmRegistros.nregistro.focus();
});
