<?php include '../config/conexion.php'; ?>
<body>
<?php include "header.html"; ?>
<!-- Contenido del dashboard -->
<div class="container mt-4">
    <div class="row">
    <div class="col-md-2">
    <div class="form-group">
        <!-- Menú desplegable en dispositivos móviles -->
        <select class="form-control d-md-none">
            <option value="espacios">Espacios</option>
            <option value="usuarios">Usuarios</option>
            <option value="apartos">Apartos</option>
            <option value="consultar">Consultar</option>
            <option value="reportes">Reportes</option>
        </select>
        
        <!-- Lista de botones en dispositivos de mayor tamaño -->
        <div class="list-group d-none d-md-block">
            <button type="button" class="list-group-item list-group-item-action active">
                Espacios
            </button>
            <button type="button" class="list-group-item list-group-item-action">
                Usuarios
            </button>
            <button type="button" class="list-group-item list-group-item-action">
                Apartos
            </button>
            <button type="button" class="list-group-item list-group-item-action">
                Consultar
            </button>
            <button type="button" class="list-group-item list-group-item-action">
                Reportes
            </button>
        </div>
    </div>
</div>

        <div class="col-md-10">
            <h3 class="mb-4">Espacios disponibles:</h3>
            <!-- Botón para abrir el modal de agregar espacio -->
            <button type="button" class="btn btn-primary mb-4" data-toggle="modal" data-target="#modalAgregarEspacio">
                <i class="fas fa-plus"></i> Agregar
            </button>

            <!-- Contenido del dashboard aquí -->
            <div class="row">
                <!-- Cards de Espacios -->
                    <?php
                        // Realiza la consulta a la base de datos
                        $sql = "SELECT idCatalogo, nombre, capacidad FROM catalogo";
                        $resultado = $conn->query($sql);

                        if ($resultado->num_rows > 0) {
                            // Itera sobre los resultados
                            while ($fila = $resultado->fetch_assoc()) {
                                $id = $fila['idCatalogo'];
                                $nombreEspacio = $fila['nombre'];
                                $capacidadEspacio = $fila['capacidad'];
                                ?>

                                <div class="col-md-4">
                                    <div class="card mb-3" style="border-width: 1px; border-style: solid; border-color: #000;">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo $nombreEspacio; ?></h5>
                                            <p class="card-text">Capacidad: <?php echo $capacidadEspacio; ?> personas</p>
                                            <button type="button" class="btn btn-primary reservar-btn" data-nombre="<?php echo $nombreEspacio; ?>" data-id="<?php echo $id; ?>">Reservar</button>
                                            <button type="button" class="btn btn-warning editar-btn" data-nombre="<?php echo $nombreEspacio; ?>" data-capacidad="<?php echo $capacidadEspacio; ?>" data-id="<?php echo $id; ?>"><i class="fas fa-pencil-alt"></i></button>
                                            <button type="button" class="btn btn-danger eliminar-btn" data-nombre="<?php echo $nombreEspacio; ?>" data-id="<?php echo $id; ?>"><i class="fas fa-trash-alt"></i></button>
                                        </div>
                                    </div>
                                </div>




                                <?php
                            }
                        } else {
                            // No se encontraron registros
                            echo "No se encontraron registros en la base de datos.";
                        }
                        ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar espacio -->
<div class="modal fade" id="modalAgregarEspacio" tabindex="-1" role="dialog" aria-labelledby="modalAgregarEspacioLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAgregarEspacioLabel">Agregar Espacio</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Formulario para agregar un nuevo espacio -->
                <form action="../actions/crearEspacio.php" method="POST">
                    <div class="form-group">
                        <label for="nombreEspacio">Nombre del Espacio</label>
                        <input name="nombreEspacio" type="text" class="form-control" id="nombreEspacio" placeholder="Nombre">
                    </div>
                    <div class="form-group">
                        <label for="capacidadEspacio">Capacidad:</label>
                        <select name="capacidadEspacio" class="form-control" id="capacidadEspacio">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
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
    <!-- Agrega los scripts de Bootstrap y jQuery desde el CDN -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../scripts/sweetAlert.js"></script>
    
    <script>
    // Función para mostrar el modal de reserva
function mostrarModalReserva(nombreEspacio, idEspacio) {
    Swal.fire({
        title: `Reservar ${nombreEspacio}`,
        html: `
            <input id="nombreCliente" class="swal2-input" placeholder="Nombre del cliente">
            <input id="fecha" class="swal2-input" placeholder="Fecha" type="date">
            <input id="horario" class="swal2-input" placeholder="Horario" type="time" step="900">`,
        showCancelButton: true,
        confirmButtonText: 'Reservar',
        preConfirm: () => {
            const nombreCliente = Swal.getPopup().querySelector('#nombreCliente').value;
            const fecha = Swal.getPopup().querySelector('#fecha').value;
            const horario = Swal.getPopup().querySelector('#horario').value;
            // Aquí puedes hacer lo que necesites con los datos ingresados
        }
    });
}


// Función para mostrar el modal de edición
function mostrarModalEdicion(nombreEspacio, capacidadEspacio, idEspacio) {
    Swal.fire({
        title: `Editar ${nombreEspacio}`,
        html: `
            <input id="nombreEspacio" class="swal2-input" placeholder="Nombre del espacio" value="${nombreEspacio}">
            <select id="capacidadEspacio" class="swal2-input">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>`,
        showCancelButton: true,
        confirmButtonText: 'Guardar',
        preConfirm: () => {
            const nombreEspacio = Swal.getPopup().querySelector('#nombreEspacio').value;
            const capacidadEspacio = Swal.getPopup().querySelector('#capacidadEspacio').value;
            // Aquí puedes hacer lo que necesites con los datos ingresados
        },
        didOpen: () => {
            const selectElement = Swal.getPopup().querySelector('#capacidadEspacio');
            selectElement.value = capacidadEspacio;
        }
    });
}


    // Función para mostrar el modal de eliminación
    function mostrarModalEliminacion(nombreEspacio, idEspacio) {
        Swal.fire({
            title: `Eliminar ${nombreEspacio}`,
            text: '¿Estás seguro de que deseas eliminar este espacio?',
            showCancelButton: true,
            confirmButtonText: 'Borrar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545'
        }).then((result) => {
            if (result.isConfirmed) {
                // Realiza la acción de eliminación
            }
        });
    }

    // Escucha los eventos de clic en los botones y muestra los modales correspondientes
    document.querySelectorAll('.reservar-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const nombreEspacio = btn.getAttribute('data-nombre');
            const idEspacio = btn.getAttribute('data-id');
            mostrarModalReserva(nombreEspacio, idEspacio);
        });
    });

    document.querySelectorAll('.editar-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const nombreEspacio = btn.getAttribute('data-nombre');
            const capacidadEspacio = btn.getAttribute('data-capacidad');
            const idEspacio = btn.getAttribute('data-id');
            mostrarModalEdicion(nombreEspacio, capacidadEspacio, idEspacio);
        });
    });

    document.querySelectorAll('.eliminar-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const nombreEspacio = btn.getAttribute('data-nombre');
            const idEspacio = btn.getAttribute('data-id');
            mostrarModalEliminacion(nombreEspacio, idEspacio);
        });
    });
</script>

</body>
</html>