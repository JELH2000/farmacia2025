<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
        <a href="inicio" class="logo d-flex align-items-center">
            <img src="app/views/assets/img/logo.png" alt="">
            <span class="d-none d-lg-block text-decoration-none">Farmacia</span>
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->


    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">

            <li class="nav-item dropdown pe-3">

                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <img src="app/views/assets/img/user.png" alt="Profile" class="rounded-circle">
                    <span class="d-none d-md-block dropdown-toggle ps-2">
                        <?php
                        if (isset($_SESSION['empleado']) && isset($_SESSION['empleado']['Nombre'])) {
                            echo htmlspecialchars($_SESSION['empleado']['Nombre']);
                        } else if (isset($_SESSION['usuario']) && isset($_SESSION['usuario']['Nombre'])) {
                            echo htmlspecialchars($_SESSION['usuario']['Nombre']);
                        } else {
                            echo "Usuario";
                        }
                        ?>
                    </span>
                </a><!-- End Profile Iamge Icon -->

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <h6>
                            <?php
                            if (isset($_SESSION['empleado']) && isset($_SESSION['empleado']['Nombre'])) {
                                echo htmlspecialchars($_SESSION['empleado']['Nombre']);
                            } else if (isset($_SESSION['usuario']) && isset($_SESSION['usuario']['Nombre'])) {
                                echo htmlspecialchars($_SESSION['usuario']['Nombre']);
                            } else {
                                echo "Usuario";
                            }
                            ?>
                        </h6>
                        <span>
                            <?php
                            if (isset($_SESSION['empleado']) && isset($_SESSION['empleado']['Estado'])) {
                                echo htmlspecialchars($_SESSION['empleado']['Estado']);
                            } else if (isset($_SESSION['usuario']) && isset($_SESSION['usuario']['Estado'])) {
                                echo htmlspecialchars($_SESSION['usuario']['Estado']);
                            } else {
                                echo "Empleado";
                            }
                            ?>
                        </span>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <button class="dropdown-item d-flex align-items-center" onclick="window.location.href='cerrar.php'">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Cerrar Sesión</span>
                        </button>
                    </li>

                </ul><!-- End Profile Dropdown Items -->
            </li><!-- End Profile Nav -->

        </ul>
    </nav><!-- End Icons Navigation -->

    <!-- Script para cerrar sesión -->


    <script src="app/ajax/cerrar.js"></script>

</header>