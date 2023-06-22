<style>
    header {
        display: none;
    }

    .menu__side {
        display: none;
    }

    .contenedor-general {

        height: calc(100vh - 60px);
        padding: 0;
        margin: 0;
        background: url(/skins/page/images/fondologin.jpg);
        background-size: cover;
        

    }
</style>

<div class="container h-100">
    <div class="d-flex align-items-end" style="height:60px;">
        <a href="/page">
            <button class="btn-primary btn-volver mt-2" type="submit">
                <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd">
                    <path d="M2.117 12l7.527 6.235-.644.765-9-7.521 9-7.479.645.764-7.529 6.236h21.884v1h-21.883z" />
                </svg>
                <span>Regresar</span>

            </button>

        </a>

    </div>
    <div class="row  h-100">
        <div class="col-md-6 d-flex justify-content-end  align-items-end align-items-md-center">
            <div class="d-grid">
                <span class="titulo-login m-0">Sistema de </span>

                <span class="titulo-login" style="margin-left:79px;">
                    <span class="m-0">Nómina</span>
                </span>
            </div>

        </div>
        <div class="col-md-6 d-flex justify-content-center  align-items-start align-items-md-center">
            <form class="form-login shadow rounded-lg pb-4" autocomplete="off" action="/page/login/validar?debug=1" method="post">
                <div class="header-form"></div>
                <h2 class="py-3">Bienvenido</h2>
                <div class="w-100 d-flex justify-content-center">

                    <img src="/images/<?php echo $this->img ?>" class="img-fluid" alt="Imagen de seguro">
                </div>
                <div class="body-form px-3 text-center">
                    <hr>
                    <h3>INICIAR SESIÓN</h3>

                    <span class="">Ingrese sus datos</span>
                    <div class="d-flex align-items-center mt-2 mb-3">

                        <i class="fa fa-user form-control-feedback"></i>
                        <input type="text" placeholder="Usuario" class="form-control ml-1" id="user" name="user" required>
                    </div>
                    <div class="d-flex align-items-center mb-3">

                        <i class="fa fa-lock form-control-feedback"></i>
                        <input type="password" placeholder="Contraseña" class="form-control ml-1" id="password" name="password" required>
                    </div>
                    <?php if ($this->error) { ?>

                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>¡Hubo un error!</strong>
                            <?php if ($this->error == 1) { ?>
                                Lo sentimos ocurrio un error intente de nuevo.
                            <?php } ?>
                            <?php if ($this->error == 2) { ?>
                                El Usuario o Contraseña son incorrectos.
                            <?php } ?>
                            <?php if ($this->error == 3) { ?>
                                El usuario se encuentra inactivo.
                            <?php } ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php } ?>
                    <div class="">
                        <a href="/page/login/recuperar" class="">¿Olvidó su contraseña?</a>
                    </div>
                    <input type="hidden" id="csrf" name="csrf" value="<?php echo $this->csrf; ?>" />
                    <input type="hidden" id="img" name="img" value="<?php echo $this->img; ?>" />

                    <button class="btn-primary mt-2" type="submit">
                        <span>Ingresar</span>
                        <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd">
                            <path d="M21.883 12l-7.527 6.235.644.765 9-7.521-9-7.479-.645.764 7.529 6.236h-21.884v1h21.883z" />
                        </svg>

                    </button>


                </div>
            </form>
        </div>
    </div>
</div>