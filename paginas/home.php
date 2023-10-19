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
                        <a class="nav-link active" href="home.php">
                            <i class="fas fa-home"></i> Espacios
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="usuarios.php">
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
                        <input name="capacidadEspacio" type="number" class="form-control" id="capacidadEspacio" placeholder="Máximo 1000" min="1" max="1000">
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../scripts/sweetAlert.js"></script>

    <script>
// Función para mostrar el modal de reserva con horarios desde la base de datos
function mostrarModalNombreFecha(nombreEspacio, idEspacio) {
    // Calcular la fecha actual
    const currentDate = new Date();
    
    // Calcular la fecha dentro de un mes
    const oneMonthFromNow = new Date(currentDate);
    oneMonthFromNow.setMonth(currentDate.getMonth() + 1);
    
    // Convertir las fechas en formato ISO para establecer los atributos min y max
    const minDate = currentDate.toISOString().split('T')[0];
    const maxDate = oneMonthFromNow.toISOString().split('T')[0];
    
    Swal.fire({
        title: `Reservar ${nombreEspacio}`,
        html: `
            <input id="nombreCliente" class="swal2-input" placeholder="Nombre del cliente">
            <input id="fecha" class="swal2-input" placeholder="Fecha" type="date" min="${minDate}" max="${maxDate}">`,
        showCancelButton: true,
        confirmButtonText: 'Siguiente',
        preConfirm: () => {
            const nombreCliente = Swal.getPopup().querySelector('#nombreCliente').value;
            const fecha = Swal.getPopup().querySelector('#fecha').value;

            // Llama a la segunda parte para mostrar los horarios
            mostrarModalHorarios(nombreEspacio, idEspacio, nombreCliente, fecha);
        }
    });

    // Obtener el botón "Siguiente"
    const confirmButton = Swal.getConfirmButton();

    // Obtener los elementos de entrada
    const nombreClienteInput = Swal.getPopup().querySelector('#nombreCliente');
    const fechaInput = Swal.getPopup().querySelector('#fecha');

    // Deshabilitar el botón de confirmación al principio
    confirmButton.disabled = true;

    // Agregar eventos de cambio a los campos
    nombreClienteInput.addEventListener('input', toggleConfirmButton);
    fechaInput.addEventListener('input', toggleConfirmButton);

    function toggleConfirmButton() {
        const nombreClienteValue = nombreClienteInput.value;
        const fechaValue = fechaInput.value;
        
        // Habilitar el botón si ambos campos tienen contenido y la fecha es válida
        confirmButton.disabled = !(nombreClienteValue && fechaValue && isValidDate(fechaValue));
    }

    function isValidDate(dateString) {
        const selectedDate = new Date(dateString);
        return !isNaN(selectedDate) && selectedDate >= currentDate && selectedDate <= oneMonthFromNow;
    }
}


function mostrarModalHorarios(nombreEspacio, idEspacio, nombreCliente, fecha) {
    // Realizar una petición AJAX para obtener los horarios desde la base de datos
    $.ajax({
        url: '../actions/consultarHorarios.php', // Ajusta la ruta al script PHP
        type: 'POST',
        data: { idEspacio: idEspacio },
        dataType: 'json',
        success: function(horarios) {
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
            checkboxesHTML += '</div>';

            Swal.fire({
                title: `Reservar ${nombreEspacio}`,
                html: `
                    <p>Nombre del cliente: ${nombreCliente}</p>
                    <p>Fecha: ${fecha}</p>
                    <br>
                    <div class="swal2-checkboxes">
                        ${checkboxesHTML}
                    </div>`,
                showCancelButton: true,
                confirmButtonText: 'Reservar',
                showDenyButton: true,
                denyButtonText: 'Regresar',
                preConfirm: () => {
                    const selectedCheckboxes = Swal.getPopup().querySelectorAll('.swal2-checkbox input:checked');
                    const horariosSeleccionados = Array.from(selectedCheckboxes).map(checkbox => checkbox.getAttribute('data-horario'));

                    // Aquí puedes hacer lo que necesites con los datos ingresados, incluyendo los horarios seleccionados
                }
            }).then((result) => {
                if (result.isDenied) {
                    mostrarModalNombreFecha(nombreEspacio, idEspacio);
                }
            });
        },
        error: function() {
            Swal.fire('No disponible', 'Por el momento no hay horarios disponibles para este espacio. Vuelva más tarde', 'warning');
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
            fetch('../actions/editarEspacio.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                Swal.fire({
                    title: 'Edición Exitosa',
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
            mostrarModalNombreFecha(nombreEspacio, idEspacio);
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