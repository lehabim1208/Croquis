<?php 
include '../config/conexion.php'; 
session_start();

$currentPage = 'consultas';

// Verificar si la variable de sesión 'idUsuario' está definida
if (!isset($_SESSION['idUsuario']) || $_SESSION['rol'] !== 'usuario') {
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
        <div class="col-md-10 p-4" id="contenido">
            <h3 class="mb-4">Mis reservaciones:</h3>
            <select class="form-control" name="filtro" id="filtro">
                <option value="1">Todas</option>
                <option value="2">Pendientes</option>
                <option value="3">Hoy</option>
                <option value="4">Pasadas</option>
            </select>
            <br>
            <div class="row">
                <!-- Tarjetas Bootstrap para mostrar las reservaciones -->
        <?php
        $usuarioSelect = $_SESSION['idUsuario'];
        $sql = "SELECT r.idReserva, r.idCatalogo, r.horaMax, r.horaMin, r.fecha, r.id_Cliente, c.nombre AS nombreEspacio, u.nombre AS nombreUsuario
                FROM reserva r
                JOIN catalogo c ON r.idCatalogo = c.idCatalogo
                JOIN usuario u ON r.id_Cliente = u.idUsuario
                WHERE r.id_cliente = '$usuarioSelect'
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
                    );
                } else {
                    // Actualizar la hora máxima de la última reserva agrupada
                    $reservas_agrupadas[count($reservas_agrupadas) - 1]['horaMax'] = $horaMax;
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
            
            foreach ($reservas_agrupadas as $reserva) {
                $fechaReserva = $reserva['fecha'];
            
                $botonDeshabilitado = $fechaReserva < $fechaActual;
            
                ?>
                <div class="col-lg-4">
                    <div class="card" style="display: block; margin-bottom:30px;">
                        <div class="card-body" style="border-width: 1px; border-style: solid; border-color: #000;">
                            <h5 class="card-title">Folio: 0000<?php echo $reserva['idReserva']; ?></h5>
                            <p class="card-text" data-tipo="nombreEspacio">Espacio: <?php echo $reserva['nombreEspacio']; ?></p>
                            <p class "card-text">Hora Inicio: <?php echo $reserva['horaMin']; ?> hrs.</p>
                            <p class="card-text">Hora Final: <?php echo $reserva['horaMax']; ?> hrs.</p>
                            <p class="card-text" data-tipo="fecha">Fecha: <?php echo $reserva['fecha']; ?></p>
                            <?php if ($botonDeshabilitado): ?>
                                <p style="margin-bottom: 48px;"></p>
                            <?php endif; ?>
                            <?php if (!$botonDeshabilitado): ?>
                                <button type="button" class="btn btn-warning editar-btn" data-id="<?php echo $reserva['idReserva']; ?>"><i class="fas fa-pencil-alt"></i></button>
                                <button type="button" class="btn btn-danger eliminar-btn" data-id="<?php echo $reserva['idReserva']; ?>"><i class="fas fa-trash-alt"></i></button>
                                <button style="font-size:12px;" type="button" class="btn btn-info reportar-btn" data-toggle="modal" data-target="#reportModal" data-id="<?php echo $reserva['idReserva']; ?>">Reportar</button>
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
        } else {
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
        // JavaScript para obtener el idReserva del botón y establecerlo en el formulario
        $('.reportar-btn').click(function() {
            var idReserva = $(this).data('id');
            $('#idReserva').val(idReserva);
            $('#idReservaDisplay').text(idReserva);
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
        // Agrega un evento clic a los botones de edición y eliminación
        document.querySelectorAll('.editar-btn, .eliminar-btn').forEach(function(button) {
        button.addEventListener('click', function(event) {
            const idReserva = event.target.getAttribute('data-id');
            
            // Ahora tienes el ID de la reserva en la variable idReserva
            console.log('ID de la reserva:', idReserva);
        });
        });
    </script>
    <script>
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
        document.addEventListener("DOMContentLoaded", function() {
            var filtroSelect = document.getElementById("filtro");
            var tarjetas = document.querySelectorAll(".col-lg-4");
            
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
                var data = { idReserva: idReserva };
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
                            // Oculta la tarjeta correspondiente después de 2 segundos
                            var tarjetaAEliminar = document.querySelector(".eliminar-btn[data-id='" + idReserva + "']").closest(".col-lg-4");
                            tarjetaAEliminar.style.display = "none";
                            
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

    // Agrega un evento click a los botones de eliminar
    document.querySelectorAll(".eliminar-btn").forEach(function(botonEliminar) {
        botonEliminar.addEventListener("click", function() {
            var idReserva = this.getAttribute("data-id");
            eliminarReserva(idReserva);
        });
    });

            // Agrega un evento click a los botones de editar (puedes personalizar esto según tus necesidades)
            document.querySelectorAll(".editar-btn").forEach(function(botonEditar) {
                botonEditar.addEventListener("click", function() {
                    var idReserva = this.getAttribute("data-id");
                    // Puedes abrir un modal o redirigir a una página de edición aquí
                    // Por ejemplo, abre un SweetAlert2 personalizado para editar
                    Swal.fire({
                        title: "Editar Reserva",
                        html: "Pendiente :)",
                        icon: "info",
                        showCancelButton: true,
                        showConfirmButton: false,
                        cancelButtonText: "Cancelar",
                    });
                });
            });
        });
    </script>


    <script>
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