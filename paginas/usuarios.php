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
                        <a class="nav-link" href="apartados.php">
                            <i class="fas fa-building"></i> Apartados
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="consultar.php">
                            <i class="fas fa-search"></i> Consultar
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

        <div class="col-md-10 p-4">
            <h3 class="mb-4">Usuarios y Administradores:</h3>
            <!-- Botón para abrir el modal de agregar usuario -->
            <button type="button" class="btn btn-primary mb-4" data-toggle="modal" data-target="#modalAgregarEspacio">
                <i class="fas fa-plus"></i> Agregar
            </button>
            
            <table class="table">
                <thead>
                    <tr class="table-info">
                    <th scope="col">IdUsuario</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Correo</th>
                    <th scope="col">Rol</th>
                    <th scope="col">Celular</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                        // Realiza la consulta a la base de datos
                        $sql = "SELECT idUsuario, nombre, correo, rol, celular FROM usuario";
                        $resultado = $conn->query($sql);

                        if ($resultado->num_rows > 0) {
                            // Itera sobre los resultados
                            while ($fila = $resultado->fetch_assoc()) {
                                $idUsuario = $fila['idUsuario'];
                                $nombre = $fila['nombre'];
                                $correo = $fila['correo'];
                                $rol = $fila['rol'];
                                $celular = $fila['celular'];
                                ?>
                                <tr>
                                    <th scope="row"><?php echo $idUsuario; ?></th>
                                    <td><?php echo $nombre; ?></td>
                                    <td><?php echo $correo; ?></td>
                                    <td><?php echo $rol; ?></td>
                                    <td><?php echo $celular; ?></td>
                                </tr>
                                <?php
                            }
                        } else {
                            // No se encontraron registros
                            echo "No se encontraron usuarios ni administradores";
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
</body>
</html>