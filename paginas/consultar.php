<?php 
include '../config/conexion.php'; 
session_start();

$currentPage = 'consultas';

// Verificar si la variable de sesión 'idUsuario' está definida
if (!isset($_SESSION['idUsuario']) || $_SESSION['rol'] !== 'administrador') {
    // El usuario no ha iniciado sesión o no tiene el rol de "administrador", redirigir a la página de acceso denegado
    header('Location: error-403.html');
    exit();
}

?>
<body>
<?php include "header.php"; ?>
<!-- Contenido del dashboard -->
<div class="container-fluid pr-4 pl-4 pt-4">
    <div class="row">
        <div class="col-md-12 p-4" id="contenido">
            <h3 class="mb-4">Mis reservaciones:</h3>
            <select class="form-control" name="filtro" id="filtro">
                <option value="1">Todas</option>
                <option value="2">Pendientes</option>
                <option value="3">Hoy</option>
                <option value="4">Pasadas</option>
            </select>
            <div class="row">
    <div class="col-md-3">
        <input type="date" class="form-control" id="fechaBusqueda" placeholder="Buscar por fecha">
    </div>
    <div class="col-md-3">
        <input type="text" class="form-control" id="clienteBusqueda" placeholder="Buscar por cliente">
    </div>
    <div class="col-md-3">
        <input type="text" class="form-control" id="folioBusqueda" placeholder="Buscar por folio">
    </div>
    <div class="col-md-3">
        <input type="text" class="form-control" id="espacioBusqueda" placeholder="Buscar por espacio">
    </div>
</div>
            <br>
            <div class="row">
                <!-- Tarjetas Bootstrap para mostrar las reservaciones -->
                <?php
            $sql = "SELECT r.idReserva, r.idCatalogo, r.horaMax, r.horaMin, r.fecha, r.id_Cliente, c.nombre AS nombreEspacio, u.nombre AS nombreUsuario
                    FROM reserva r
                    JOIN catalogo c ON r.idCatalogo = c.idCatalogo
                    JOIN usuario u ON r.id_Cliente = u.idUsuario
                    ORDER BY r.fecha, r.idCatalogo, r.horaMin";

            $resultado = $conn->query($sql);

            if ($resultado->num_rows > 0) {
                $reservas_agrupadas = array();

                while ($fila = $resultado->fetch_assoc()) {
                    $idReserva = $fila['idReserva'];
                    $idCatalogo = $fila['idCatalogo'];
                    $horaMax = $fila['horaMax'];
                    $horaMin = $fila['horaMin'];
                    $fecha = $fila['fecha'];
                    $idCliente = $fila['id_Cliente'];
                    $nombreEspacio = $fila['nombreEspacio'];
                    $nombreUsuario = $fila['nombreUsuario'];
                    $idsReservasAgrupadas = array();

                    if (!count($reservas_agrupadas) ||
                        $reservas_agrupadas[count($reservas_agrupadas) - 1]['fecha'] !== $fecha ||
                        $reservas_agrupadas[count($reservas_agrupadas) - 1]['idCatalogo'] !== $idCatalogo ||
                        $reservas_agrupadas[count($reservas_agrupadas) - 1]['idCliente'] !== $idCliente ||
                        $reservas_agrupadas[count($reservas_agrupadas) - 1]['horaMax'] !== $horaMin
                    ) {
                        // Agregar nueva reserva agrupada
                        $reservas_agrupadas[] = array(
                            'idReserva' => $idReserva,
                            'idCatalogo' => $idCatalogo,
                            'horaMin' => $horaMin,
                            'horaMax' => $horaMax,
                            'fecha' => $fecha,
                            'idCliente' => $idCliente,
                            'nombreEspacio' => $nombreEspacio,
                            'nombreUsuario' => $nombreUsuario,
                            'idsReservasAgrupadas' => array($idReserva), // Inicializar con el primer ID
                        );
                    } else {
                        // Actualizar la hora máxima de la última reserva agrupada
                        $reservas_agrupadas[count($reservas_agrupadas) - 1]['horaMax'] = $horaMax;
                        // Agregar el ID de la reserva agrupada
                        $reservas_agrupadas[count($reservas_agrupadas) - 1]['idsReservasAgrupadas'][] = $idReserva;
                    }
                }

                function compararFechas($a, $b) {
                    // Convierte las fechas en formato 'Y-m-d' a timestamps para comparar
                    $fechaTimestampA = strtotime($a['fecha']);
                    $fechaTimestampB = strtotime($b['fecha']);

                    // Compara las fechas en orden descendente
                    if ($fechaTimestampA == $fechaTimestampB) {
                        return 0;
                    }
                    return ($fechaTimestampA > $fechaTimestampB) ? -1 : 1;
                }

                // Ordenar el arreglo de reservas por fecha en orden descendente
                usort($reservas_agrupadas, 'compararFechas');

                // Obtener la fecha actual
                $fechaActual = date('Y-m-d');
                
                // Mostrar las reservas agrupadas en las tarjetas
                foreach ($reservas_agrupadas as $reserva) {
                    $fechaReserva = $reserva['fecha'];
                    $botonDeshabilitado = $fechaReserva < $fechaActual;
                    ?>
                    <div class="col-lg-4">
                        <div class="card" data-ids-reservas="<?php echo implode(',', $idsReservasAgrupadas); ?>" style="display: block; margin-bottom:30px;">
                            <div class="card-body" style="border-width: 1px; border-style: solid; border-color: #000;">
                                <h5 class="card-title">Folio: 0000<?php echo $reserva['idReserva'] ?></h5>
                                <p class="card-text" data-tipo="nombreEspacio">Espacio: <?php echo $reserva['nombreEspacio']; ?></p>
                                <p class "card-text">Hora Inicio: <?php echo $reserva['horaMin']; ?> hrs.</p>
                                <p class="card-text">Hora Final: <?php echo $reserva['horaMax']; ?> hrs.</p>
                                <p class="card-text" data-tipo="fecha">Fecha: <?php echo $reserva['fecha']; ?></p>
                                
                               <?php // Obtener nombre del cliente a partir del id
                                    $idClienteComparar = $reserva['idCliente'];
                                    $sqlnombreCliente = "SELECT nombre FROM usuario WHERE idUsuario = '$idClienteComparar'";
                                    $resultadonombreCliente = $conn->query($sqlnombreCliente);
                                
                                    if ($resultadonombreCliente->num_rows > 0) {
                                        $filaNombre = $resultadonombreCliente->fetch_assoc();
                                        $nombreCliente = $filaNombre['nombre'];
                                    } else {
                                        $nombreCliente = "No se encontró el nombre";
                                    }
                                 ?>
                                <p class="card-text" data-tipo="nombreCliente">Cliente: <?php echo $nombreCliente; ?></p>


                                <?php if ($botonDeshabilitado): ?>
                                    <p style="margin-bottom: 48px;"></p>
                                <?php endif; ?>
                                <?php if (!$botonDeshabilitado): 
                                        
                                        //CÓDIGO PARA BOTÓN EDICIÓN
                                        //Obtener primer id reserva
                                        $idReserva = implode(',', $reserva['idsReservasAgrupadas']);
                                        $idsArray = explode(',', $idReserva);
                                        $primerIdReserva = $idsArray[0];

                                        
                                        // Realizar una consulta para obtener el id del espacio
                                        $sqlEspacio = "SELECT idCatalogo FROM reserva WHERE idReserva = '$primerIdReserva'";
                                        $resultadoEspacio = $conn->query($sqlEspacio);
                                    
                                        if ($resultadoEspacio->num_rows > 0) {
                                            $filaEspacio = $resultadoEspacio->fetch_assoc();
                                            $idEspacioEdicion = $filaEspacio['idCatalogo'];
                                        } else {
                                            $idEspacioEdicion = "No se encontró el id del espacio";
                                        }

                                        // Realizar una consulta para obtener el nombre del espacio y id
                                        $sqlNEspacio = "SELECT nombre FROM catalogo WHERE idCatalogo = '$idEspacioEdicion'";
                                        $resultadoNEspacio = $conn->query($sqlNEspacio);
                                    
                                        if ($resultadoNEspacio->num_rows > 0) {
                                            $filaNEspacio = $resultadoNEspacio->fetch_assoc();
                                            $nombreEspacioEdicion = $filaNEspacio['nombre'];
                                        } else {
                                            $nombreEspacioEdicion = "No se encontró el id del espacio";
                                        }
                                    ?>
                                    <button type="button" class="btn btn-warning editar-btn" data-idedicion="<?php echo implode(',', $reserva['idsReservasAgrupadas']); ?>" data-idespacioedicion="<?php echo $idEspacioEdicion; ?>" data-nombreespacioedicion="<?php echo $nombreEspacioEdicion; ?>" data-nombreclientee="<?php echo $nombreCliente; ?>" data-idclientee="<?php echo $idClienteComparar; ?>"><i class="fas fa-pencil-alt"></i></button>
                                    <button type="button" class="btn btn-danger eliminar-btn" data-ideliminacion="<?php echo implode(',', $reserva['idsReservasAgrupadas']); ?>"><i class="fas fa-trash-alt"></i></button>
                                    <button style="font-size:12px;" type="button" class="btn btn-info reportar-btn" data-toggle="modal" data-target="#reportModal" data-id="<?php echo implode(',', $reserva['idsReservasAgrupadas']); ?>">Reportar</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }

                echo '</div>
                    </div>
                </div>
            </div>';
            } else
            {
            // No se encontraron registros
            echo "Aún no tienes ninguna reservación";
        }
        ?>

<!-- Modal para enviar el reporte -->
<div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reportModalLabel">Reportar reservación: 0000<span id="idReservaDisplay"></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Formulario para enviar el reporte -->
        <form id="reportForm">
          <div class="form-group">
            <label for="asunto">Asunto:</label>
            <input type="text" class="form-control" id="asunto" name="asunto">
          </div>
          <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="4"></textarea>
          </div>
          <!-- Campo oculto para enviar el idReserva -->
          <input type="hidden" id="idReserva" name="idReserva" value="">
          <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
      </div>
    </div>
  </div>
</div>


    <?php include "footer.html"; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        //REPORTE
        // JavaScript para obtener el idReserva del botón y establecerlo en el formulario
        $('.reportar-btn').click(function() {
            var idReserva = $(this).data('id');
            $('#idReserva').val(idReserva);

            //Recuperar el primero id de la reserva:
            var idsArray = idReserva.split(',');
            var primerIdReserva = idsArray[0];
            $('#idReservaDisplay').text(primerIdReserva);
        });

        // JavaScript para enviar el formulario a través de AJAX como solicitud JSON
        $('#reportForm').submit(function(e) {
            e.preventDefault();
            var formData = {
                idReserva: $('#idReserva').val(),
                asunto: $('#asunto').val(),
                descripcion: $('#descripcion').val(),
            };

            $.ajax({
                type: 'POST',
                url: '../actions/enviarReporte.php',
                data: JSON.stringify(formData),
                contentType: 'application/json; charset=utf-8',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Utiliza SweetAlert2 para mostrar un modal de éxito
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: 'Reporte enviado con éxito.',
                            timer: 2000, // 2000 milisegundos = 2 segundos
                            timerProgressBar: true,
                            showConfirmButton: false
                        }).then(() => {
                            $('#reportModal').modal('hide');
                            // Agrega un temporizador para recargar la página después de 2 segundos
                            setTimeout(function() {
                                window.location.reload();
                            },);
                        });

                    }
                    else {
                        // Utiliza SweetAlert2 para mostrar un modal de error
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al enviar el reporte: ' + response.message,
                        });
                    }
                },
                error: function() {
                    // Utiliza SweetAlert2 para mostrar un modal de error
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un error al enviar el reporte.',
                    });
                }
            });
        });
    </script>


    <script>
        //ELIMINACIÓN Y EDICIÓN DE RESERVACIONES
        document.addEventListener("DOMContentLoaded", function() {
            var filtroSelect = document.getElementById("filtro");
            var tarjetas = document.querySelectorAll(".col-lg-4");
            
            document.querySelectorAll('.eliminar-btn').forEach(function(button) {
            button.addEventListener('click', function(event) {
                const idReserva = event.currentTarget.getAttribute('data-ideliminacion');
                eliminarReserva(idReserva);
                console.log(idReserva);
            });
        });

        // Función para eliminar una reserva usando AJAX
        function eliminarReserva(idReserva) {
            Swal.fire({
                title: "¿Estás seguro?",
                text: "Esta acción eliminará la reserva",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar",
            }).then((result) => {
                if (result.isConfirmed) {
                    // Realiza la solicitud AJAX para eliminar la reserva
                    var data = { idReserva: idReserva }; // Pasa los IDs como una cadena
                    fetch("../actions/eliminarReserva.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify(data),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "success") {
                            Swal.fire({
                                title: "Reserva eliminada",
                                text: data.message,
                                icon: "success",
                                timer: 2000, // 2 segundos
                                showConfirmButton: false
                            }).then(function() {
                                // Realiza una recarga de la página después de 2 segundos
                                setTimeout(function() {
                                    location.reload();
                                },);
                            });
                        } else {
                            Swal.fire("Error", data.message, "error");
                        }
                    })
                    .catch(error => {
                        Swal.fire("Error", "Ocurrió un error al eliminar la reserva", "error");
                    });
                }
            });
        }

            // Agrega un evento click a los botones de editar (puedes personalizar esto según tus necesidades)
            document.querySelectorAll(".editar-btn").forEach(function(botonEditar) {
                botonEditar.addEventListener("click", function() {
                    var idReserva = this.getAttribute("data-idedicion");
                    var idEspacioEdicion = this.getAttribute("data-idespacioedicion");
                    var nombreEspacioEdicion = this.getAttribute("data-nombreespacioedicion");
                    var nombreUsuario = this.getAttribute("data-nombreclientee");
                    var idUsuario = this.getAttribute("data-idclientee");
                    console.log(idReserva);
                    console.log(idEspacioEdicion);
                    console.log(nombreEspacioEdicion);
                    console.log(nombreUsuario);
                    console.log(idUsuario);
                    mostrarModalNombreFecha(nombreEspacioEdicion, idEspacioEdicion, idReserva, nombreUsuario, idUsuario);
                });
            });
        });
    </script>
    <script>
        //EDICIÓN DE RESERVACIÓN
        function mostrarModalNombreFecha(nombreEspacio, idEspacio, idReserva, nombreUsuario, idUsuario, fecha = null) {

        //Recuperar el primero id de la reserva:
        var idsArray = idReserva.split(',');
        var primerIdReserva = idsArray[0];

            // Obtener la fecha actual en el huso horario de Ciudad de México
            const currentDate = new Date();
            const mexicoCityTime = new Date(currentDate.toLocaleString("en-US", { timeZone: "America/Mexico_City" }));

            // Calcular la fecha dentro de 6 meses
            const sixMonthsFromNow = new Date(mexicoCityTime);
            sixMonthsFromNow.setMonth(mexicoCityTime.getMonth() + 6);

            // Ajustar el día al último día del mes si la fecha actual está en el mes de febrero
            if (mexicoCityTime.getMonth() === 1 && sixMonthsFromNow.getMonth() === 2) {
                sixMonthsFromNow.setDate(0);
            }

            // Obtener el formato ISO de la fecha actual
            const minDate = mexicoCityTime.toISOString().split('T')[0];

            // Obtener el formato ISO de la fecha dentro de 6 meses
            const maxDate = sixMonthsFromNow.toISOString().split('T')[0];

            console.log(minDate); // Fecha actual en formato ISO
            console.log(maxDate); // Fecha dentro de 6 meses en formato ISO

        // Crear un select deshabilitado con una única opción
        const usuarioSelect = document.createElement('select');
        usuarioSelect.id = 'usuario';
        usuarioSelect.classList.add('swal2-input');
        usuarioSelect.disabled = true;
        const usuarioOption = document.createElement('option');
        usuarioOption.value = idUsuario;
        usuarioOption.textContent = nombreUsuario;
        usuarioSelect.appendChild(usuarioOption);

        // Agregar el select al modal
        Swal.fire({
        title: `Editando reserva: 0000${primerIdReserva}`,
        html: `
        <p class="text-info">Seleccione la nueva fecha</p>
            <div id="usuarioContainer" style="display: none;">
            
            </div>
            <input id="fecha" class="swal2-input" placeholder="Fecha" type="date" min="${minDate}" max="${maxDate}" value="${fecha}">`,
        showCancelButton: true,
        confirmButtonText: 'Siguiente',
        didRender: () => {
            const usuarioContainer = document.getElementById('usuarioContainer');
            usuarioContainer.appendChild(usuarioSelect);
        },
        preConfirm: () => {
            const selectedUserId = idUsuario;
            const selectedUserName = nombreUsuario; // Obtener el nombre del usuario desde la variable JavaScript
            const fecha = Swal.getPopup().querySelector('#fecha').value;

            // Llama a la segunda parte para mostrar los horarios
            mostrarModalHorarios(nombreEspacio, idEspacio, idReserva, selectedUserId, selectedUserName, fecha, idUsuario);
        }
        });

        // Obtener el botón "Siguiente"
        const confirmButton = Swal.getConfirmButton();

        // Obtener el elemento de entrada de fecha
        const fechaInput = Swal.getPopup().querySelector('#fecha');

        // Deshabilitar el botón de confirmación al principio
        confirmButton.disabled = true;

        // Agregar un evento de cambio al campo de fecha
        fechaInput.addEventListener('input', toggleConfirmButton);

        function getRangeOfAllowedDates() {
                const currentDate = new Date();
                const sixMonthsFromNow = new Date(currentDate);
                sixMonthsFromNow.setMonth(currentDate.getMonth() + 6);
                return { currentDate, sixMonthsFromNow };
            }

            // Función que activa o desactiva el botón
            function toggleConfirmButton() {
                const fechaValue = fechaInput.value;
                const { currentDate, sixMonthsFromNow } = getRangeOfAllowedDates();

                // Habilitar el botón si ambos campos tienen contenido y la fecha es válida
                confirmButton.disabled = !(fechaValue && isValidDate(fechaValue, currentDate, sixMonthsFromNow));
            }


            function isValidDate(fechaValue, currentDate, sixMonthsFromNow) {
                const currentDate2 = new Date(currentDate);
                currentDate2.setHours(0, 0, 0, 0);

                const [year, month, day] = fechaValue.split('/');
                const formattedFechaValue = `${day}/${month}/${year}`;
                const selectedDate = new Date(Date.parse(formattedFechaValue));
                selectedDate.setHours(0, 0, 0, 0);

                return selectedDate >= currentDate2 && selectedDate <= sixMonthsFromNow;
            }

        }



        function mostrarModalHorarios(nombreEspacio, idEspacio, idReserva, selectedUserId, nombreCliente, fecha) {
       
        //Recuperar el primero id de la reserva:
        var idsArray = idReserva.split(',');
        var primerIdReserva = idsArray[0];

        // Realizar una petición AJAX para obtener los horarios desde la base de datos
        $.ajax({
        url: '../actions/consultarHorarios.php', // Ajusta la ruta al script PHP
        type: 'POST',
        data: { idEspacio: idEspacio, fecha: fecha },
        dataType: 'json',
        success: function (horarios) {
        if (horarios.length === 0) {
            // No hay horarios disponibles, muestra el modal de advertencia
            Swal.fire({
                title: 'No disponible',
                text: 'No hay horarios para esta fecha. Consulte otro día',
                icon: 'warning',
                confirmButtonText: 'Cambiar fecha' // Cambiar el texto del botón de confirmación
            }).then(() => {
                // Cuando el usuario haga clic en "Cambiar fecha", llama a mostrarModalNombreFecha
                mostrarModalNombreFecha(nombreEspacio, idEspacio, idReserva, nombreCliente, selectedUserId, fecha);
            });
        } else {
            // Generar checkboxes con estilos de Bootstrap en un diseño de 3x3
            let checkboxesHTML = '<div class="row">';
            horarios.forEach((horario, index) => {
                if (index % 3 === 0) {
                    checkboxesHTML += '</div><div class="row">';
                }
                checkboxesHTML += `
                    <div class="col-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" data-horario="${horario}" id="checkbox-${index}">
                            <label class="form-check-label" for="checkbox-${index}">${horario}</label>
                        </div>
                    </div>`;
            });
            checkboxesHTML += '</div';

            Swal.fire({
                title: `Editando reserva: 0000${primerIdReserva}`,
                html: `
                <p class="text-info">Seleccione los nuevos horarios que necesita el cliente para su evento</p>
                        <p>Nombre del cliente: ${nombreCliente}</p>
                    <p>Nueva fecha: ${fecha}</p>
                    <br>
                    <div class="swal2-checkboxes checkcheck">
                        ${checkboxesHTML}
                    </div>`,
                showDenyButton: true,
                denyButtonText: 'Regresar',
                customClass: {
                    confirmButton: 'swalBtnColor',
                    denyButton: 'swalBtnColor2',
                    cancelButton: 'swalBtnColor3'
                },
                showCancelButton: true,
                confirmButtonText: 'Editar',
                preConfirm: () => {
                    const horariosSeleccionados = [];
                    const checkboxes = document.querySelectorAll('.swal2-checkboxes input[type="checkbox"]');

                    checkboxes.forEach(checkbox => {
                        if (checkbox.checked) {
                            // Accede al atributo 'data-horario' en lugar del valor del checkbox
                            horariosSeleccionados.push(checkbox.getAttribute('data-horario'));
                        }
                    });

                    // Crear un objeto que contenga los datos que deseas enviar al servidor
                    const data = {
                        horariosSeleccionados: horariosSeleccionados,
                        fecha: fecha,
                        selectedUserId: selectedUserId,
                        idEspacio: idEspacio,
                        idReserva: idReserva,
                    };

                    // Realizar una solicitud POST al archivo PHP
                    fetch('../actions/editarReserva.php', {
                        method: 'POST',
                        body: JSON.stringify(data)
                    })
                        .then(response => {
                            if (response.ok) {
                                Swal.fire({
                                    title: 'Edición exitosa!',
                                    icon: 'success',
                                    text: 'Se le ha asignado un nuevo folio a esta edición',
                                    timer: 2000, // 2 segundos
                                    showConfirmButton: false, // No mostrar el botón de confirmación
                                }).then(function(result) {
                                    // Esta función se ejecutará cuando se cierre la notificación (automáticamente después de 2 segundos).
                                    if (result.dismiss === Swal.DismissReason.timer) {
                                        location.reload();
                                    }
                                });
                            } else {
                                // La solicitud no se completó con éxito
                                Swal.fire({
                                    title: 'Error!',
                                    icon: 'error',
                                    text: 'Ocurrió un error al editar. Vuelve a intentarlo'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error en la solicitud: ', error);
                        });
                }
            }).then((result) => {
                if (result.isDenied) {
                    mostrarModalNombreFecha(nombreEspacio, idEspacio, idReserva, nombreCliente, selectedUserId, fecha);
                }
            });
        } 
        },
        error: function () {
        Swal.fire('No disponible', 'Por el momento no hay horarios disponibles para este espacio. Vuelva más tarde', 'warning');
        }
        });
        }
    </script>

<script>
    //VISUALIZACIÓN DE CARDS
        document.addEventListener("DOMContentLoaded", function() {
            var filtroSelect = document.getElementById("filtro");
            var tarjetas = document.querySelectorAll(".col-lg-4");

            filtroSelect.addEventListener("change", function() {
                var valorSeleccionado = filtroSelect.value;
                tarjetas.forEach(function(tarjeta) {
                    tarjeta.style.display = "block"; // Restaurar la visualización de todas las tarjetas
                });

                if (valorSeleccionado === "2") {
                    // Mostrar solo las tarjetas pendientes (fecha de hoy en adelante)
                    var fechaHoy = new Date();
                    tarjetas.forEach(function(tarjeta) {
                        var fechaTarjeta = new Date(tarjeta.querySelector(".card-text[data-tipo='fecha']").textContent);
                        if (fechaTarjeta < fechaHoy) {
                            tarjeta.style.display = "none";
                        }
                    });
                } else if (valorSeleccionado === "3") {
                    // Mostrar solo las tarjetas de hoy
                    var fechaHoy = new Date();
                    fechaHoy.setHours(0, 0, 0, 0);
                    tarjetas.forEach(function(tarjeta) {
                        var fechaTarjeta = new Date(tarjeta.querySelector(".card-text[data-tipo='fecha']").textContent);
                        if (fechaTarjeta.getTime() !== fechaHoy.getTime()) {
                            tarjeta.style.display = "none";
                        }
                    });
                } else if (valorSeleccionado === "4") {
                    // Mostrar solo las tarjetas pasadas
                    var fechaHoy = new Date();
                    fechaHoy.setHours(0, 0, 0, 0);
                    tarjetas.forEach(function(tarjeta) {
                        var fechaTarjeta = new Date(tarjeta.querySelector(".card-text[data-tipo='fecha']").textContent);
                        if (fechaTarjeta >= fechaHoy) {
                            tarjeta.style.display = "none";
                        }
                    });
                }
            });
        });
    </script>
    <script>
        //FILTRADO DE RESERVACIONES
       document.addEventListener("DOMContentLoaded", function() {
    var tarjetas = document.querySelectorAll(".col-lg-4");

    // Función para filtrar por fecha
    function filtrarPorFecha() {
        var fechaBusqueda = new Date(document.getElementById("fechaBusqueda").value); // Obtener la fecha del campo de búsqueda

        tarjetas.forEach(function(tarjeta) {
            var fechaTarjeta = new Date(tarjeta.querySelector(".card-text[data-tipo='fecha']").textContent); // Obtener la fecha de la tarjeta

            // Comparar solo las fechas (ignorando la hora)
            if (fechaTarjeta.toISOString().split('T')[0] !== fechaBusqueda.toISOString().split('T')[0] && fechaBusqueda !== "") {
                tarjeta.style.display = "none";
            } else {
                tarjeta.style.display = "block";
            }
        });
    }

    // Escuchar cambios en el campo de fecha
    document.getElementById("fechaBusqueda").addEventListener("input", filtrarPorFecha);

    // Resto del código para filtrar por folio, espacio y cliente
    document.getElementById("folioBusqueda").addEventListener("input", filtrarTarjetas);
    document.getElementById("espacioBusqueda").addEventListener("input", filtrarTarjetas);
    document.getElementById("clienteBusqueda").addEventListener("input", filtrarTarjetas);

    // Función para filtrar por folio, espacio y cliente
    function filtrarTarjetas() {
        var folio = document.getElementById("folioBusqueda").value.trim().toLowerCase();
        var espacio = document.getElementById("espacioBusqueda").value.trim().toLowerCase();
        var cliente = document.getElementById("clienteBusqueda").value.trim().toLowerCase(); // Variable para el filtro por nombre del cliente

        tarjetas.forEach(function(tarjeta) {
            var folioTarjeta = tarjeta.querySelector(".card-title").textContent.trim().toLowerCase();
            var espacioTarjeta = tarjeta.querySelector(".card-text[data-tipo='nombreEspacio']").textContent.trim().toLowerCase();
            var nombreClienteTarjeta = tarjeta.querySelector(".card-text[data-tipo='nombreCliente']").textContent.trim().toLowerCase(); // Seleccionar el nombre del cliente

            if (
                (folio !== "" && !folioTarjeta.includes(folio)) ||
                (espacio !== "" && espacioTarjeta.indexOf(espacio) === -1) ||
                (cliente !== "" && nombreClienteTarjeta.indexOf(cliente) === -1) // Condición para filtrar por nombre del cliente
            ) {
                tarjeta.style.display = "none";
            } else {
                tarjeta.style.display = "block";
            }
        });
    }
});



    </script>

    <script>
    //AJUSTAR FOOTER
    function ajustarPosicionFooter() {
        const footer = document.getElementById("myFooter");
        const container = document.getElementById("contenido");
        
        if (container.offsetHeight >= 500) {
            footer.style.position = "relative";
        } else {
            footer.style.position = "absolute";
        }
    }

    // Ajusta la posición del footer al cargar la página
    ajustarPosicionFooter();

    // Escucha el evento resize de la ventana para ajustar en caso de cambios en la altura
    window.addEventListener("resize", ajustarPosicionFooter);
</script>

</body>
</html>