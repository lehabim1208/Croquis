<?php include '../config/conexion.php'; ?>
<body>
<?php include "header.html"; ?>
<!-- Contenido del dashboard -->
<div class="container-fluid">
    <div class="row">
        <!-- Barra lateral -->
        <nav id="sidebar" class="col-md-2">
            <div class="position-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">
                            <i class="fas fa-home"></i> Espacios
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="usuarios.php">
                            <i class="fas fa-user"></i> Usuarios
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="horarios.php">
                            <i class="fas fa-clock"></i> Horarios
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="consultar.php">
                            <i class="fas fa-search"></i> Apartados y Consultas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="reportes.php">
                            <i class="fas fa-chart-bar"></i> Reportes
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="col-md-10 p-4" id="contenido">
            <h3 class="mb-4">Horarios:</h3>
            <!-- Botón para abrir el modal de agregar usuario -->
            <button type="button" class="btn btn-primary mb-4" data-toggle="modal" data-target="#modalAgregarEspacio">
                <i class="fas fa-plus"></i> Agregar
            </button>
            
            <table class="table">
                <thead>
                    <tr class="table-info">
                    <th scope="col">IdHorario</th>
                    <th scope="col">Horario</th>
                    <th scope="col">IdCatalogo</th>
                    <th scope="col">Espacio</th>
                    <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                        // Realiza la consulta a la base de datos
                        $sql = "SELECT idHorario, horario, idCatalogo FROM horarios";
                        $resultado = $conn->query($sql);

                        if ($resultado->num_rows > 0) {
                            // Itera sobre los resultados
                        while ($fila = $resultado->fetch_assoc()) {
                            $idHorario = $fila['idHorario'];
                            $horario = $fila['horario'];
                            $idCatalogo = $fila['idCatalogo'];
                        
                            // Realizar una consulta para obtener el nombre del espacio desde la tabla "catalogo"
                            $sqlEspacio = "SELECT nombre FROM catalogo WHERE idCatalogo = '$idCatalogo'";
                            $resultadoEspacio = $conn->query($sqlEspacio);
                        
                            if ($resultadoEspacio->num_rows > 0) {
                                $filaEspacio = $resultadoEspacio->fetch_assoc();
                                $nombreEspacio = $filaEspacio['nombre'];
                            } else {
                                $nombreEspacio = "No se encontró el espacio";
                            }
                            ?>
                            <tr>
                                <th scope="row"><?php echo $idHorario; ?></th>
                                <td><?php echo $horario; ?></td>
                                <td><?php echo $idCatalogo; ?></td>
                                <td><?php echo $nombreEspacio; ?></td>
                                <td>
                                    <button class="btn btn-primary editar-horario" data-id="<?php echo $idHorario; ?>" data-horario="<?php echo $horario; ?>" data-catalogo="<?php echo $idCatalogo ?>"><i class="fas fa-pencil-alt"></i></button>
                                    <button class="btn btn-danger eliminar-horario" data-id="<?php echo $idHorario; ?>"><i class="fas fa-trash-alt"></i></button>
                                </td>

                            </tr>
                            <?php
                        }                            
                        } else {
                            // No se encontraron registros
                            echo "<br> No se encontraron horarios <br><br>";
                        }
                        ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Modal para agregar usuarios -->
<div class="modal fade" id="modalAgregarEspacio" tabindex="-1" role="dialog" aria-labelledby="modalAgregarEspacioLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAgregarEspacioLabel">Agregar Usuario o Administrador</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Formulario para agregar un nuevo horario -->
                <form action="../actions/crearHorario.php" method="POST">
                    <div class="form-group">
                        <label for="horario">Horario:</label>
                        <input name="horario" type="text" class="form-control" id="horario" placeholder="3pm-4pm">
                    </div>
                    <div class="form-group">
                        <label for="rol">Espacio:</label>
                        <select class="form-control" name="seleccionCatalogo">
                            <?php
                            // Consulta SQL para recuperar los registros de la tabla "catalogo"
                            $sql = "SELECT idCatalogo, nombre FROM catalogo";
                            $result = $conn->query($sql);

                            // Llenar el select con los registros
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row['idCatalogo'] . "'>" . $row['nombre'] . "</option>";
                                }
                            } else {
                                echo "<option value='0'>No hay espacios registrados</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>

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
    $(document).on('click', '.editar-horario', function() {
    const idHorario = $(this).data('id');
    const horario = $(this).data('horario');
    const catalogo = $(this).data('catalogo');

    // Realiza una petición AJAX para obtener las opciones de la tabla "catalogo"
    const options = <?php
        // Consulta SQL para recuperar los registros de la tabla "catalogo"
        $sql = "SELECT idCatalogo, nombre FROM catalogo";
        $result = $conn->query($sql);

        $options = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $options[] = $row;
            }
        }
        echo json_encode($options);
    ?>;

    
    const selectOptions = options.map(option => {
    return `<option value="${option.idCatalogo}" ${option.idCatalogo == catalogo ? 'selected' : ''}>${option.nombre}</option>`;
}).join('');

    Swal.fire({
        title: 'Editar Horario',
        html: 
            '<form id="editar-horario-form" action="../actions/editarHorario.php" method="POST">' +
            '<div class="form-group">' +
            '<label for="horario">Horario:</label>' +
            '<input name="horario" type="text" class="form-control" id="horario" value="' + horario + '">' +
            '</div>' +
            '<div class="form-group">' +
            '<label for="seleccionCatalogo">Espacio:</label>' +
            '<select class="form-control" name="seleccionCatalogo" id="seleccionCatalogo">' + selectOptions + '</select>' +
            '</div>' +
            '<input type="hidden" name="idHorario" value="' + idHorario + '">' +
            '</form>',
        showCancelButton: true,
        confirmButtonText: 'Guardar',
        preConfirm: () => {
            // Obtener los valores del formulario y realizar la petición AJAX para enviar los datos a editarHorario.php
            const horario = Swal.getPopup().querySelector('#horario').value;
            const seleccionCatalogo = Swal.getPopup().querySelector('#seleccionCatalogo').value;
            const idHorario = Swal.getPopup().querySelector('[name="idHorario"]').value;

            // Hacer una petición AJAX para enviar los datos de edición a editarHorario.php
            $.ajax({
                url: '../actions/editarHorario.php',
                type: 'POST',
                data: { idHorario: idHorario, horario: horario, seleccionCatalogo: seleccionCatalogo },
                success: function(response) {
                    if (response === 'success') {
                        Swal.fire({
                            title: 'Éxito',
                            text: 'El horario ha sido editado correctamente.',
                            icon: 'success',
                            timer: 1000, // Duración en milisegundos (1 segundo)
                            showConfirmButton: false // Ocultar el botón "Aceptar"
                        }).then(() => {
                                location.reload();
                        });
                    }
                    else {
                        Swal.fire('Error', 'Ocurrió un error al editar el horario.', 'error');
                    }
                }
            });
        }
    });
});
</script>


    <script>
    // Cuando se hace clic en el botón "Eliminar"
    $(document).on('click', '.eliminar-horario', function() {
    const idHorario = $(this).data('id');
    // Aquí debes mostrar el modal de SweetAlert2 para confirmar la eliminación
    Swal.fire({
        title: '¿Estás seguro?',
        text: `¿Quieres eliminar el horario con ID ${idHorario}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
    }).then((result) => {
        if (result.isConfirmed) {
            // Realizar una petición AJAX para enviar el idHorario a eliminarHorario.php
            $.ajax({
                url: '../actions/eliminarHorario.php',
                type: 'POST',
                data: { idHorario: idHorario },
                success: function(response) {
                    if (response === 'success') {
                        Swal.fire({
                            title: 'Éxito',
                            text: 'El horario ha eliminado correctamente.',
                            icon: 'success',
                            timer: 1000, // Duración en milisegundos (1 segundo)
                            showConfirmButton: false // Ocultar el botón "Aceptar"
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', 'Ocurrió un error al eliminar el horario.', 'error');
                    }
                }
            });
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