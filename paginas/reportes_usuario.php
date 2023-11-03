<?php 
include '../config/conexion.php'; 
session_start();

$currentPage = 'reportes';

// Verificar si la variable de sesión 'idUsuario' está definida
if (!isset($_SESSION['idUsuario']) || $_SESSION['rol'] !== 'usuario') {
    // El usuario no ha iniciado sesión o no tiene el rol de "usuario", redirigir a la página de acceso denegado
    header('Location: error-403.html');
    exit();
}
?>

<!-- Agrega los estilos de Bootstrap para las tarjetas -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">

<body>
    <?php include "header.php"; ?>
    <!-- Contenido del dashboard -->
    <div class="container-fluid pr-4 pl-4 pt-4">
        <div class="row">
            <div class="col-md-10 p-4" id="contenido">
                <h3 class="mb-4">Mis reportes</h3>

                <!-- Agrega un contenedor para las tarjetas de reporte -->
                <div class="row" id="report-cards">
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
                            $mensaje_admin = $fila['mensaje_admin'];
                            $estado = $fila['estado'];
                    ?>
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Reporte #000<?php echo $idReporte; ?></h5>
                                    <h6>Estado: <span class="<?php
                                        if ($estado == "En proceso") {
                                            echo "text-success";
                                        } elseif ($estado == "En espera") {
                                            echo "text-warning";
                                        } elseif ($estado == "Concluido") {
                                            echo "text-secondary";
                                        } elseif ($estado == "Sin solución") {
                                            echo "text-danger";
                                        }
                                    ?>"><?php echo $estado; ?></span></h6>
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#detalleModal<?php echo $idReporte; ?>">Detalles</button>
                                </div>
                            </div>
                        </div>

                        <!-- Modal para mostrar detalles del reporte -->
                        <div class="modal fade" id="detalleModal<?php echo $idReporte; ?>" tabindex="-1" role="dialog" aria-labelledby="detalleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="detalleModalLabel">Detalles del Reporte</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Asunto:</strong> <?php echo $asunto; ?></p>
                                        <p><strong>Descripción:</strong> <?php echo $descripcion; ?></p>
                                        <p><strong>Fecha y hora:</strong> <?php echo $fecha_hora; ?></p>
                                        <p><strong>Id Reservación:</strong> <?php echo $idReserva; ?></p>
                                        <p><strong>Estado: </strong><span class="<?php
                                        if ($estado == "En proceso") {
                                            echo "text-success";
                                        } elseif ($estado == "En espera") {
                                            echo "text-warning";
                                        } elseif ($estado == "Concluido") {
                                            echo "text-secondary";
                                        } elseif ($estado == "Sin solución") {
                                            echo "text-danger";
                                        }
                                    ?>"><?php echo $estado; ?></span></h6>
                                        <p><strong>Respuesta: </strong> <?php
                                        if($mensaje_admin == ''){
                                            ?> 
                                            <span>Aún no hay mensajes recibidos</span> 
                                            <?php
                                        } echo $mensaje_admin; ?></p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                        }
                    } else {
                        // No se encontraron reportes
                        echo "No se encontraron reportes";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php include "footer.html"; ?>
    <!-- Agrega los scripts de Bootstrap y SweetAlert2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../scripts/sweetAlert.js"></script>

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