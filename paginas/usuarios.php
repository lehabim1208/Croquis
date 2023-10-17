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
                        <a class="nav-link" href="apartos.php">
                            <i class="fas fa-building"></i> Apartos
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

        <div class="col-md-10">
            <!-- AQUÍ VA EL CONTENIDO DE USUARIOS -->
            
        </div>



    <?php include "footer.html"; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../scripts/sweetAlert.js"></script>
</body>
</html>