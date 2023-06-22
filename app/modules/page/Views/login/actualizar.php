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
            <form class="form-login shadow rounded-lg pb-5 mb-5" autocomplete="off" action="/page/login/actualizar" method="post">
                <div class="header-form"></div>
                <h2 class="py-3">Bienvenido</h2>
                <div class="w-100 d-flex justify-content-center">

                    <img src="/images/<?php echo $this->img ?>" class="img-fluid" alt="Imagen de seguro">
                </div>
                <div class="body-form px-3 text-center">
                    <hr>
                    <h3>ACTUALIZACIÓN DE CONTRASEÑA</h3>

                    <span class="mt-1">Ingrese su nueva contraseña</span>

                    <div class="d-flex align-items-center mb-3">

                        <i class="fa fa-lock form-control-feedback"></i>
                        <input type="password" autocomplete="off" placeholder="Nueva contraseña" class="form-control my-1" id="password_new" name="password_new" onkeyup="validarNuevoPass()" required>
                    </div>
                    <div id="contenedor-alerta" class="text-left alert  alert-danger text-left" role="alert" style="text-align:left; display:none;">
                        <span class="fw-bolder">La contraseña debe estar compuesta por:</span><br>
                        <ul>
                            <li class="list-group-item" id="caract"></li>
                            <li class="list-group-item" id="mayus"></li>
                            <li class="list-group-item" id="espe"></li>
                            <li class="list-group-item" id="nume"></li>

                        </ul>







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
                    <div class="d-none">
                        <a href="/page/login/recuperar" class="">¿Olvidó su contraseña?</a>
                    </div>
                    <input type="hidden" id="id" name="id" value="<?php echo $this->id; ?>" />
                    <input type="hidden" id="csrf" name="csrf" value="<?php echo $this->csrf2; ?>" />
                    <input type="hidden" id="img" name="img" value="<?php echo $this->img; ?>" />

                    <button class="btn-primary mt-2" id="btn-act" type="submit">
                        <span>Actualizar</span>
                    </button>


                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const regexNumeros = /\d/;
    const regexLetras = /^.{8,}$/;
    const regexCaracteres = /[!@#$%^&*()]/;
    const regexMayus = /[A-Z]/;

    function validarNumeros(cadena) {
        return regexNumeros.test(cadena);
    }

    function validarLetras(cadena) {
        return regexLetras.test(cadena);
    }

    function validarCaracteres(cadena) {
        return regexCaracteres.test(cadena);
    }

    function validarMayus(cadena) {
        return regexMayus.test(cadena);
    }


    function validarNuevoPass() {
        let password_new = document.getElementById('password_new').value
        let nume = document.getElementById('nume')
        let espe = document.getElementById('espe')
        let mayus = document.getElementById('mayus')
        let caract = document.getElementById('caract')
        let contenedorAlerta = document.getElementById('contenedor-alerta')

        let btnAct = document.getElementById('btn-act')

        if (validarNumeros(password_new) != true) {
            nume.innerText = "*Mínimo 1 número"
        } else {
            nume.innerText = ""

        }
        if (validarLetras(password_new) != true) {
            caract.innerText = "*Mínimo 8 caracteres"
        } else {
            caract.innerText = ""

        }
        if (validarCaracteres(password_new) != true) {
            espe.innerText = "*La contraseña debe contener mínimo 1 caracter especial"
        } else {
            espe.innerText = ""

        }
        if (validarMayus(password_new) != true) {
            mayus.innerText = "*La contraseña debe contener mínimo 1 caracter en mayúscula"
        } else {
            mayus.innerText = ""

        }
        if (validarNumeros(password_new) === true && validarLetras(password_new) === true && validarCaracteres(password_new) === true && validarMayus(password_new) === true) {
            contenedorAlerta.style.display = "none"
            btnAct.disabled = false

        } else {
            contenedorAlerta.style.display = "block"
            btnAct.disabled = true

        }
    }
</script>