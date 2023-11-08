<?php 
include '../config/conexion.php'; 
session_start();

$currentPage = 'reportes';

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
            <h3 class="mb-4">Reportes de usuarios:</h3>
            <button class="btn btn-info" id="reporteSemanal">Reporte semanal</button><br><br>
            <!--<button type="button" class="btn btn-primary mb-4" data-toggle="modal" data-target="#modalAgregarEspacio">
                <i class="fas fa-plus"></i> Agregar
            </button> -->
            
<div class="table-responsive">
<table class="table table-light" id="tbl">
                <thead>
                    <tr class="table-info">
                    <th scope="col"># Reporte</th>
                    <th scope="col">Asunto</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Fecha y hora</th>
                    <th scope="col">Folio Reservación</th>
                    <th scope="col">Nombre Usuario</th>
                    <th scope="col">Mensaje de respuesta</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                        // Realiza la consulta a la base de datos
                        $sql = "SELECT idReporte, asunto, descripcion, fecha_hora, idReserva, idCliente, mensaje_admin, estado FROM reportes";
                        $resultado = $conn->query($sql);

                        if ($resultado->num_rows > 0) {
                            // Itera sobre los resultados
                            while ($fila = $resultado->fetch_assoc()) {
                                $idReporte = $fila['idReporte'];
                                $asunto = $fila['asunto'];
                                $descripcion = $fila['descripcion'];
                                $fecha_hora = $fila['fecha_hora'];
                                $idReserva = $fila['idReserva'];
                                $idCliente = $fila['idCliente'];
                                $mensaje_admin = $fila['mensaje_admin'];
                                $estado = $fila['estado'];

                                // Realizar una consulta para obtener el nombre del espacio desde la tabla "catalogo"
                            $sqlNombre = "SELECT nombre FROM usuario WHERE idUsuario = '$idCliente'";
                            $resultadoNombre = $conn->query($sqlNombre);
                        
                            if ($resultadoNombre->num_rows > 0) {
                                $filaNombre = $resultadoNombre->fetch_assoc();
                                $nombreUsuario = $filaNombre['nombre'];
                            } else {
                                $nombreUsuario = "No se encontró el nombre";
                            }
                                ?>
                                <tr>
                                    <th scope="row"><?php echo $idReporte; ?></th>
                                    <td><?php echo $asunto; ?></td>
                                    <td><?php echo $descripcion; ?></td>
                                    <td><?php echo $fecha_hora; ?></td>
                                    <td>0000<?php echo $idReserva; ?></td>
                                    <td><?php echo $nombreUsuario; ?></td>
                                    <td>
                                        <?php if($mensaje_admin == ''){
                                        ?> <span class="text-info">Aún no se le ha dado respuesta</span> <?php
                                        }else{
                                            echo $mensaje_admin; 
                                        }?>
                                    </td>
                                    <td><span class="<?php
                                        if ($estado == "En proceso") {
                                            echo "text-success";
                                        } elseif ($estado == "En espera") {
                                            echo "text-warning";
                                        } elseif ($estado == "Concluido") {
                                            echo "text-secondary";
                                        } elseif ($estado == "Sin solución") {
                                            echo "text-danger";
                                        }
                                    ?>"><?php echo $estado; ?></span></td>
                                    <td>
                                    <button style="margin: 2px;" class="btn btn-info acciones-reporte" data-idreporte="<?php echo $idReporte; ?>">Acciones</button>
                                        <button style="margin: 2px;" class="btn btn-warning correo-reporte" data-toggle="modal" data-target="#correoModal"
                                        data-idreporte="<?php echo $idReporte; ?>"
                                        data-asunto="<?php echo $asunto; ?>"
                                        data-descripcion="<?php echo $descripcion; ?>"
                                        data-estado="<?php echo $estado; ?>"
                                        data-fechahora="<?php echo $fecha_hora; ?>"
                                        data-idreserva="<?php echo $idReserva; ?>"
                                        data-idcliente="<?php echo $idCliente; ?>">Correo</button>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            // No se encontraron registros
                            echo "No se encontraron reportes";
                        }
                        ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
<!-- MODAL CORREO -->
<div class="modal fade" id="correoModal" tabindex="-1" role="dialog" aria-labelledby="correoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="correoModalLabel">Enviar Correo Jefe de carrera</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="correoForm">
                <h6>Folio Reserva: 0000<span id="reservaId"></span></h6>
                <h6>No. Reporte: <span id="reporteId"></span></h6><br>
                    <div class="form-group">
                        <label for="asuntoadmin">Asunto del Administrador:</label>
                        <input type="text" class="form-control" id="asuntoadmin" name="asuntoadmin">
                    </div>
                    <div class="form-group">
                        <label for="mensajeadmin">Mensaje del Administrador</label>
                        <textarea class="form-control" id="mensajeadmin" name="mensajeadmin" rows="5"></textarea>
                    </div>
                    <input type="hidden" id="idUsuario" name="idUsuario" value="<?php echo $_SESSION['idUsuario']; ?>">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="enviarCorreo">Enviar Correo</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ACCIONES -->
<div class="modal fade" id="accionesModal" tabindex="-1" role="dialog" aria-labelledby="accionesModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="accionesModalLabel">Acciones en el Reporte (<span id="reporteIdAcciones"></span>)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="accionesForm">
                    <input type="hidden" id="idReporteAcciones" name="idReporteAcciones" value="">
                    <div class="form-group">
                        <label for="estado">Cambiar Estado:</label>
                        <select class="form-control" id="estado" name="estado">
                            <option value="En espera">En espera</option>
                            <option value="En proceso">En proceso</option>
                            <option value="Concluido">Concluido</option>
                            <option value="Sin solución">Sin solución</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="mensaje">Mensaje:</label>
                        <textarea class="form-control" id="mensaje" name="mensaje" rows="5"><?php echo $mensaje_admin; ?></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="guardarAcciones">Guardar Acciones</button>
            </div>
        </div>
    </div>
</div>





    <?php include "footer.html"; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        //SCRIPT CORREO
        $(document).ready(function() {
        var idReporte;
        var asuntoUsuario;
        var descripcionUsuario;
        var estado;
        var fechaHora;
        var idReserva;
        var idCliente;

        $('.correo-reporte').click(function() {
            idReporte = $(this).data('idreporte');
            asuntoUsuario = $(this).data('asunto');
            descripcionUsuario = $(this).data('descripcion');
            estado = $(this).data('estado');
            fechaHora = $(this).data('fechahora');
            idReserva = $(this).data('idreserva');
            idCliente = $(this).data('idcliente');

            $('#idReporte').val(idReporte);
            $('#asuntoadmin').val('');
            $('#mensajeadmin').val('');

            // Actualiza el contenido de los elementos <span> con los IDs correspondientes
            $('#reservaId').text(idReserva);
            $('#reporteId').text(idReporte);

            $('#correoModal').modal('show');
        });

        $('#enviarCorreo').click(function() {
            var formData = {
                idUsuario: $('#idUsuario').val(),
                asuntoAdmin: $('#asuntoadmin').val(),
                mensajeAdmin: $('#mensajeadmin').val(),
                idReporte: idReporte,
                asuntoUsuario: asuntoUsuario,
                descripcionUsuario: descripcionUsuario,
                estado: estado,
                fechaHora: fechaHora,
                idReserva: idReserva,
                idCliente: idCliente
            };

            $.ajax({
                type: 'POST',
                url: '../actions/enviarCorreo.php',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Cerrar el modal de correo
                        $('#correoModal').modal('hide');
                        // Mostrar SweetAlert de éxito
                        Swal.fire({
                            title: 'Éxito',
                            text: response.message,
                            icon: 'success',
                            timer: 2000, // El tiempo en milisegundos que deseas mostrar la alerta (en este caso, 2 segundos)
                            showConfirmButton: false // Ocultar el botón "Aceptar" para que se cierre automáticamente
                        }).then(function() {
                            // Después de que se cierre la alerta, realiza una recarga de la página
                            location.reload();
                        });
                    } else {
                        // Mostrar SweetAlert de error
                        Swal.fire({
                            title: 'Error',
                            text: response.message,
                            icon: 'error'
                        });
                    }
                },
                error: function() {
                    // Mostrar SweetAlert de error en caso de error
                    Swal.fire({
                        title: 'Error',
                        text: 'Hubo un problema al enviar el correo.',
                        icon: 'error'
                    });
                }
            });
        });
    });
    </script>
    
    <script>
        //SCRIPT ACCIONES
    $(document).ready(function() {
        $('.acciones-reporte').click(function() {
            var idReporte = $(this).data('idreporte'); // Obtener el ID del reporte del botón

                // Asignar el ID del reporte al elemento <span> en el modal
                $('#reporteIdAcciones').text(idReporte);
                // Asignar el ID del reporte al campo oculto del formulario en el modal
                $('#idReporteAcciones').val(idReporte);

                //Recuperar valores para mensaje y estado
                $(document).ready(function() {
                $('.acciones-reporte').click(function() {
                    var idReporte = $(this).data('idreporte'); // Obtener el ID del reporte del botón

                    // Realizar una solicitud AJAX para recuperar los valores desde PHP
                    $.ajax({
                        url: '../actions/recuperarDatosReserva.php',
                        method: 'POST',
                        data: { idReporte: idReporte },
                        success: function(response) {
                            var data = JSON.parse(response); // Convertir la respuesta JSON en un objeto

                            // Asignar los valores recuperados a los campos del formulario
                            $('#idReporteAcciones').val(idReporte);
                            $('#estado').val(data.estado); // Asignar el estado recuperado
                            $('#mensaje').val(data.mensaje_admin); // Asignar el mensaje recuperado

                            // Abrir el modal de acciones
                            $('#accionesModal').modal('show');
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                        }
                    });
                });
            });
        });

        $('#guardarAcciones').click(function() {
            Swal.fire({
                title: 'Confirmación',
                text: '¿Estás seguro de realizar esta acción?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                        // Recopila los datos del formulario
                var formData = {
                    idReporte: $('#idReporteAcciones').val(),
                    estado: $('#estado').val(),
                    mensaje: $('#mensaje').val()
                };

                // Realiza una solicitud AJAX
                $.ajax({
                    type: 'POST',
                    url: '../actions/accionesReporte.php', // Ruta al archivo PHP
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        // Maneja la respuesta del servidor (puedes mostrar una alerta de éxito aquí)
                        Swal.fire({
                        title: 'Éxito',
                        text: 'Acción completada con éxito',
                        icon: 'success',
                        timer: 2000, // Tiempo en milisegundos (en este caso, 2 segundos)
                        showConfirmButton: false // No mostrar el botón de confirmación
                    }).then(function() {
                        // Recargar la página
                        location.reload();
                    });
                        // Cierra el modal de acciones
                        $('#accionesModal').modal('hide');
                    },
                    error: function(error) {
                        // Maneja los errores (puedes mostrar una alerta de error aquí)
                        Swal.fire('Error', 'Ha ocurrido un error', 'error');
                    }
                });
                }
            });
        });
    });
    </script>

    <script>
        //REPORTE SEMANAL
        // Agrega un evento click al botón para mostrar el modal de SweetAlert2
        document.getElementById('reporteSemanal').addEventListener('click', function () {
            Swal.fire({
                title: '¿Generar PDF o Excel?',
                text: 'Elige el formato que deseas generar:',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Generar PDF',
                confirmButtonColor: '#FF5733',  // Color rojo
                cancelButtonText: 'Generar Excel',
                cancelButtonColor: '#5DB75D',  // Color verde
            }).then((result) => {
                if (result.isConfirmed) {
                    // Lógica para generar PDF
                    window.open('../actions/generarPDF.php', '_blank');
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Lógica para generar Excel
                    window.open('../actions/generarEXCEL.php', '_blank');
                }
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