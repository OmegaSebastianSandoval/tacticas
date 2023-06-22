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
            <form class="form-login shadow rounded-lg pb-4" autocomplete="off" action="/page/login/forgotpassword"
                method="post">
                <div class="header-form"></div>
                <h2 class="py-3">Bienvenido</h2>
                <div class="w-100 d-flex justify-content-center">

                    <img src="/skins/page/images/logotacticas.png" class="img-fluid" alt="Imagen de seguro">
                </div>
                <div class="body-form px-3 text-center">
                    <hr>
                    <h3>Recuperar contraseña</h3>

                    <span class="">Por favor ingrese su dirección de correo electrónico y recibirás un enlace para crear
                        una nueva contraseña.</span>
                    <div class="d-flex align-items-center mt-2 mb-3">

                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" placeholder="Ingrese su correo" class="form-control ml-1" id="correo"
                            name="correo" required>
                    </div>

                    <?php if ($this->error) { ?>


                        <?php if ($this->error == 1) { ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">

                                Se ha enviado a su correo un mensaje de recuperación de contraseña.
                            <?php } ?>
                            <?php if ($this->error == 2) { ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">

                                    <strong>¡Hubo un error!</strong><br>
                                    Lo sentimos ocurrio un error y no se pudo enviar su mensaje
                                <?php } ?>
                                <?php if ($this->error == 3) { ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">

                                        <strong>¡Hubo un error!</strong><br>
                                        Usuario no encontrado.
                                    <?php } ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            <?php } ?>
                            <div class="d-none">
                                <a href="/page/login/" class="">Volver al login</a>
                            </div>
                            <input type="hidden" id="csrf" name="csrf" value="<?php echo $this->csrf; ?>" />
                            <?php if (!$this->error || $this->error != 1) { ?>
                                <button class="btn-primary mt-2" type="submit">
                                    <span>Enviar correo</span>
                         

                                </button>
                            <?php } ?>



                        </div>
            </form>
        </div>
    </div>
</div>
