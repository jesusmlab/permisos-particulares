<main>
    <div class="row d-flex align-items-center mt-2">
        <div class="col-lg-8 mx-auto">
            <div id="calendario"></div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="verDiaModal" tabindex="-1" aria-labelledby="verDiaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="verDiaModalLabel">Dia </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="cuerpoVerDia"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</main>
<link rel="stylesheet" href="<?= BASE_URL; ?>app/assets/libs/fullcalendar/lib/main.min.css">
<script src="<?= BASE_URL; ?>app/assets/libs/fullcalendar/lib/main.min.js"></script>
<script src='<?= BASE_URL; ?>app/assets/libs/fullcalendar/lib/locales-all.js'></script>
<script>
    let rol = "<?= $_SESSION['sesion']['rol']; ?>";
    var calendarEl = document.getElementById("calendario"); // Apuntar al elemento del DOM para calendario
    var calendar = new FullCalendar.Calendar(calendarEl, {
        // Instanciar Calendario
        customButtons: { // Añadir boton de volver en la cabecera del calendario
            btnVolver: {
                text: 'Volver a permisos',
                click: function() {
                    location.href = base_url + "Permisos_c/index";
                }
            }
        },
        headerToolbar: { // Disposición de botones del calendario
            left: 'prev',
            center: 'title',
            right: 'today next btnVolver'
        },
        locale: "es", // Poner en español
        themeSystem: 'bootstrap5', // Poner tema bootstrap5
        editable: false, // No editable. No se permiten ediciones
        selectable: false, // No se puede seleccionar
        allDaySlot: false,
        firstDay: 1, // Primer dia Lunea
        height: 550, // Alto
        events: { // Definir los parametros pasados por AJAX para leer datos
            startParam: "fdesde",
            endParam: "fhasta",
            url: base_url + "Permisos_c/leerTodosPorFecha", // Este método proporciona los datos para el calendario. El objeto calendar hace una llamada AJAX con los parametros que contienen startParam y endParam
            method: 'POST', // Ojo . EL framework utilizado no soporta AJAX con metodos GET
            data: {}, // Al hace la llamada AJAX se envian fdesde y fhasta. Si hubiera que enviar algun dato adiccional se pondrian aqui
            error: function() {
                alert('Error leyendo datos!');
            }
        },
        eventContent: function(arg) { // Tratar lo que va a salir en el calendario. arg es el contenido del evento
            var event = arg.event;
            var customHtml = '';
            customHtml += "<div class='text-center'>Total: <span class='badge bg-dark'>" + event.title + "</span></div>";
            customHtml += "<span>Dia: <span class='badge bg-danger'>" + event.extendedProps.diurno + "</span></span> ";
            customHtml += "<span>Tarde: <span class='badge bg-danger'>" + event.extendedProps.vespertino + "</span></span>";
            /* El objeto event tiene todos los campos recibidos. Si las propiedades de tus objetos JSON coinciden con las
            las del objeto event, serán directas (p.e. event.title), si no seran propiedades del objeto extendedProps
            */
            return {
                html: customHtml // retornamos el HTML que queremos que se vea en la celda en un objeto.
            }
        },
        dateClick: function(info) { // Este evento se dispara cuando se hace click en el dia del mes en el calendario
            //Si el rol es de Director llama a la funcion verDia
            if (rol == "D") verDia(info.dateStr);
        },
        eventDrop: function(info) {
            alert(info.event.title + " ha sido soltado en " + info.event.start.toISOString());

            if (!confirm("Estas seguro de hacer el cambio?")) {
                info.revert();
            } else {
                // Hacer llamada AJAX y cambiar las fechas el evento
            }
        }
    });
    calendar.render(); // Se renderiza el calendario

    function verDia(fecha) {
        // Leer mediante AJAX las anotaciones concedidas de la fecha y lo presenta en una ventana Modal
        $.post(
            base_url + "Permisos_c/leerPermisosFechaDet", {
                fecha: fecha
            },
            function(datosdev) {
                let permisos = JSON.parse(datosdev);
                let cadena = "<table class='table'>";
                cadena +=
                    "<th>Nombre</th><th>Hor.</th><th>NºPet.</th><th>F.Petición.</th><th>F.Registro</th><th>Estado</th>";
                for (permi of permisos) {
                    cadena += "</tr>";
                    //cadena += `<td>${permi.usuario}</td>`;
                    cadena += `<td>${permi.apenom}</td>`;
                    cadena += `<td>${permi.horario}</td>`;
                    cadena += `<td>${permi.npeticion}</td>`;
                    cadena += `<td>${moment(permi.fecha_peticion).format(
          "DD/MM/YYYY HH:mm"
        )}</td>`;
                    cadena += `<td>${moment(permi.fecha_registro).format(
          "DD/MM/YYYY HH:mm"
        )}</td>`;
                    cadena += `<td>${permi.estado}</td>`;
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
</script>