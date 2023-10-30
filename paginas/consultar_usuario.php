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
            <h3 class="mb-4">Reservaciones:</h3>
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
                $sql = "SELECT idReserva, idCatalogo, horaMax, horaMin, fecha, id_Cliente FROM reserva WHERE id_cliente = '$usuarioSelect'";
                $resultado = $conn->query($sql);

                if ($resultado->num_rows > 0) {
                    while ($fila = $resultado->fetch_assoc()) {
                        $idReserva = $fila['idReserva'];
                        $idCatalogo = $fila['idCatalogo'];
                        $horaMax = $fila['horaMax'];
                        $horaMin = $fila['horaMin'];
                        $fecha = $fila['fecha'];
                        $idCliente = $fila['id_Cliente'];

                        $sqlCatalogo = "SELECT nombre FROM catalogo WHERE idCatalogo = '$idCatalogo'";
                        $resultadoCatalogo = $conn->query($sqlCatalogo);

                        if ($resultadoCatalogo) {
                            $filaCatalogo = $resultadoCatalogo->fetch_assoc();
                            $nombreEspacio = $filaCatalogo['nombre'];
                        } else {
                            echo 'Error en la consulta de catálogo: ' . $conn->error;
                        }

                        $sqlUsuario = "SELECT nombre FROM usuario WHERE idUsuario = '$idCliente'";
                        $resultadoUsuario = $conn->query($sqlUsuario);

                        if ($resultadoUsuario) {
                            $filaUsuario = $resultadoUsuario->fetch_assoc();
                            $nombreUsuario = $filaUsuario['nombre'];
                        } else {
                            echo 'Error en la consulta de catálogo: ' . $conn->error;
                        }
                        ?>
                        <!-- Tarjeta Bootstrap para cada reserva -->
                        <div class="col-lg-4">
                            <div class="card" style="display: block;">
                            <div class="card-body" style="border-width: 1px; border-style: solid; border-color: #000;">
                                <h5 class="card-title">Id Reserva: <?php echo $idReserva; ?></h5>
                                <p class="card-text" data-tipo="nombreEspacio">Espacio: <?php echo $nombreEspacio; ?></p>
                                <p class="card-text">Hora Mínima: <?php echo $horaMin; ?></p>
                                <p class="card-text">Hora Máxima: <?php echo $horaMax; ?></p>
                                <p class="card-text" data-tipo="fecha">Fecha: <?php echo $fecha; ?></p>
                                <button type="button" class="btn btn-warning editar-btn" data-id="<?php echo $idReserva; ?>"><i class="fas fa-pencil-alt"></i></button>
                                <button type="button" class="btn btn-danger eliminar-btn" data-id="<?php echo $idReserva; ?>"><i class="fas fa-trash-alt"></i></button>
                            </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    // No se encontraron registros
                    echo "Aún no tienes ninguna reservación";
                }
                ?>
            </div>
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
                <!-- Formulario para agregar un nuevo espacio -->
                <form action="../actions/registrarDentro.php" method="POST">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input name="nombre" type="text" class="form-control" id="nombre" placeholder="Juán Pérez">
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña:</label>
                        <input name="password" type="password" class="form-control" id="password" placeholder="*********">
                    </div>
                    <div class="form-group">
                        <label for="correo">Correo:</label>
                        <input name="correo" type="email" class="form-control" id="correo" placeholder="ejemplo@correo.com">
                    </div>
                    <div class="form-group">
                        <label for="celular">Celular:</label>
                        <input name="celular" type="number" class="form-control" id="celular" placeholder="A 10 dígitos" maxlength="10">
                    </div>
                    <div class="form-group">
                        <label for="rol">Rol:</label>
                        <select name="rol" class="form-control" id="rol">
                            <option value="usuario">Usuario</option>
                            <option value="administrador">Administrador</option>
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
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../scripts/sweetAlert.js"></script>

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