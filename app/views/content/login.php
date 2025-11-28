<!DOCTYPE html>
<html lang="es">

<head>
    <?php include 'app/views/inc/head.php'; ?>
</head>

<body>

    <main>

        <div class="container">
            <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                            <div class="d-flex justify-content-center py-4">
                                <a href="#" class="logo d-flex align-items-center w-auto">
                                    <img src="app/views/assets/img/logo.png" alt="">
                                    <span class="d-none d-lg-block">Farmacia</span>
                                </a>
                            </div>

                            <div class="card shadow-lg border-0 rounded-4 text-center mb-3">

                                <div class="card-body">

                                    <div class="pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Inicio de Sesión</h5>
                                        <p class="text-center small">Entre con su usuario y contraseña</p>
                                    </div>

                                    <form class="row g-3" method="post" id="formLogin" autocomplete="off">
                                        <div class="mb-3">
                                            <div class="form-floating">
                                                <input class="form-control" id="usuario" name="usuario" type="text" placeholder=" " required/>
                                                <span class="mensaje-validacion">El usuario es obligatorio.</span>
                                                <label for="usuario">Usuario</label>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="input-group input-group-merge">
                                                <div class="form-floating">
                                                    <input class="form-control" id="contrasenia" name="contrasenia" type="password" placeholder=" " maxlength="16" minlength="8" required autocomplete="off"/>
                                                    <label for="contrasenia">Contraseña</label>
                                                </div>
                                                <div class="input-group-text" id="showPassword">
                                                    <span id="iconPassword" class="ri-eye-fill"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-grid mb-1 mb-2 text-center">
                                            <button class="btn btn-primary" type="submit"> Entrar </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </section>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.33/dist/sweetalert2.all.min.js"></script>
    <script src="app/ajax/login.js"></script>
</body>

</html>