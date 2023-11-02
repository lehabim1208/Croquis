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
        <div class="col-md-10 p-4" id="contenido">
            <h3 class="mb-4">Reportes de usuarios:</h3>
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
                    <th scope="col">Id Reservación</th>
                    <th scope="col">Id Cliente</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                        // Realiza la consulta a la base de datos
                        $sql = "SELECT idReporte, asunto, descripcion, fecha_hora, idReserva, idCliente FROM reportes";
                        $resultado = $conn->query($sql);

                        if ($resultado->num_rows > 0) {
                            // Itera sobre los resultados
                            while ($fila = $resultado->fetch_assoc()) {
                                $idReporte = $fila['idReporte'];
                                $asunto = $fila['asunto'];
                                $descripcion = $fila['descripcion'];
                                $fecha_hora = $fila['fecha_hora'];
                                $idReserva = $fila['idReserva'];
                                $id_Cliente = $fila['id_Cliente'];

                                ?>
                                <tr>
                                    <th scope="row"><?php echo $idReporte; ?></th>
                                    <td><?php echo $asunto; ?></td>
                                    <td><?php echo $descripcion; ?></td>
                                    <td><?php echo $fecha_hora; ?></td>
                                    <td><?php echo $idReserva; ?></td>
                                    <td><?php echo $id_Cliente; ?></td>
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

    <?php include "footer.html"; ?>
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