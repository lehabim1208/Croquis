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
                        $sql = "SELECT idCatalogo, nombre, capacidad, numeroEdificio, zona FROM catalogo";
                        $resultado = $conn->query($sql);

                        if ($resultado->num_rows > 0) {
                            // Itera sobre los resultados
                            while ($fila = $resultado->fetch_assoc()) {
                                $id = $fila['idCatalogo'];
                                $nombreEspacio = $fila['nombre'];
                                $capacidadEspacio = $fila['capacidad'];
                                $nombreEdificio = $fila['numeroEdificio'];
                                $zonaRegion = $fila['zona'];
                                ?>

                                <div class="col-md-4">
                                    <div class="card mb-3" style="border-width: 1px; border-style: solid; border-color: #000;">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo $nombreEspacio; ?></h5>
                                            <p class="card-text">Capacidad: <?php echo $capacidadEspacio; ?> personas</p>
                                            <p class="card-text">Nombre Edifico: <?php echo $nombreEdificio; ?></p>
                                            <p class="card-text">Zona o región: <?php echo $zonaRegion; ?></p>
                                            <button type="button" class="btn btn-primary reservar-btn" data-nombre="<?php echo $nombreEspacio; ?>" data-id="<?php echo $id; ?>">Reservar</button>
                                            <button type="button" class="btn btn-warning editar-btn" data-nombre="<?php echo $nombreEspacio; ?>" data-capacidad="<?php echo $capacidadEspacio; ?>" data-id="<?php echo $id; ?>" data-edificio="<?php echo $nombreEdificio; ?>" data-zona="<?php echo $zonaRegion; ?>"><i class="fas fa-pencil-alt"></i></button>
                                            <button type="button" class="btn btn-danger eliminar-btn" data-nombre="<?php echo $nombreEspacio; ?>" data-id="<?php echo $id; ?>"><i class="fas fa-trash-alt"></i></button>
                                        </div>
                                    </div>
                                </div>




                                <?php
                            }
                        } else {
                            // No se encontraron registros
                            echo "No se encontraron espacios disponibles en la base de datos.";
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
                        <input name="nombreEspacio" type="text" class="form-control" id="nombreEspacio" placeholder="Nombre del Espacio">
                    </div>
                    <div class="form-group">
                        <label for="capacidadEspacio">Capacidad:</label>
                        <input name="capacidadEspacio" type="number" class="form-control" id="capacidadEspacio" placeholder="Capacidad" min="1" max="1000">
                    </div>
                    <div class="form-group">
                        <label for="nombreEdificio">Nombre del Edificio</label>
                        <input name="nombreEdificio" type="text" class="form-control" id="nombreEdificio" placeholder="Nombre del Edificio">
                    </div>
                    <div class="form-group">
                        <label for="zonaRegion">Zona o Región</label>
                        <select name="zonaRegion" class="form-control" id="zonaRegion">
                            <option value="Xalapa-Veracruz">Xalapa-Veracruz</option>
                            <option value="Veracruz-Puerto">Veracruz-Puerto</option>
                            <option value="Poza-Rica">Poza-Rica</option>
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
function mostrarModalEdicion(nombreEspacio, capacidadEspacio, idEspacio, nombreEdificio, zona) {
    Swal.fire({
        title: `Editando: ${nombreEspacio}`,
        html: `
            <label class="small-label" for="nombreEspacio">Nombre:</label>
            <input id="nombreEspacio" class="small-input swal2-input" placeholder="Nombre del espacio" value="${nombreEspacio}"><br>
            <label class="small-label" for="capacidadEspacio">Capacidad:</label>
            <input id="capacidadEspacio" class="small-input swal2-input" type="number" placeholder="Capacidad" min="1" max="1000" value="${capacidadEspacio}"><br>
            <label class="small-label" for="nombreEdificio">Edificio:</label>
            <input id="nombreEdificio" class="small-input swal2-input" placeholder="Nombre del edificio" value="${nombreEdificio}"><br>
            <label class="small-label" for="zona">Zona:</label>
            <input id="zona" class="small-input swal2-input" placeholder="Zona o región" value="${zona}"><br>
        `,
        showCancelButton: true,
        confirmButtonText: 'Guardar',
        preConfirm: () => {
            const nombreEspacio = Swal.getPopup().querySelector('#nombreEspacio').value;
            const capacidadEspacio = Swal.getPopup().querySelector('#capacidadEspacio').value;
            const nombreEdificio = Swal.getPopup().querySelector('#nombreEdificio').value;
            const zona = Swal.getPopup().querySelector('#zona').value;

            // Crea un objeto FormData para enviar los datos del formulario
            const formData = new FormData();
            formData.append('idEspacio', idEspacio);
            formData.append('nombreEspacio', nombreEspacio);
            formData.append('capacidadEspacio', capacidadEspacio);
            formData.append('nombreEdificio', nombreEdificio);
            formData.append('zona', zona);

            // Realiza la solicitud POST a editarEspacio.php
            fetch('editarEspacio.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                Swal.fire({
                    title: 'Edición exitosa',
                    icon: 'success'
                });
                // Realiza otras acciones si es necesario
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error en la edición',
                    icon: 'error',
                    text: 'Error al editar el espacio: ' + error
                });
            });
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
            eliminarEspacio(idEspacio);
        }
    });
}

// Función para eliminar un espacio
function eliminarEspacio(idEspacio) {
    // Realiza una solicitud POST para eliminar el espacio
    fetch('../actions/borrarEspacio.php', {
        method: 'POST',
        body: new URLSearchParams({ idEspacio: idEspacio }),
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    })
    .then(response => response.text())
    .then(data => {
        Swal.fire({
            title: data,
            icon: 'success', // Mostrar el mensaje
            timer: 1500,
            showConfirmButton: false, // No mostrar botón de confirmación
            timerProgressBar: true, // Mostrar una barra de progreso
        });
        setTimeout(function () {
            location.reload(); // Recargar la página después de 2 segundos
        }, 1500);
    })
    .catch(error => {
        Swal.fire('Error al eliminar el espacio: ' + error);
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
            const nombreEdificio = btn.getAttribute('data-edificio');
            const zona = btn.getAttribute('data-zona');
            mostrarModalEdicion(nombreEspacio, capacidadEspacio, idEspacio, nombreEdificio, zona);
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