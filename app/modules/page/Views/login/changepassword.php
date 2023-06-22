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
            <?php if ($this->error != '') { ?>



                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $this->error; ?>

                </div>

            <?php } else { ?>
                <?php if ($this->message != '') { ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">

                        <?php echo $this->message; ?>
                    </div>

                <?php } else { ?>
                    <form class="form-login shadow rounded-lg pb-4" autocomplete="off" action="/page/login/changepassword" method="post">

                        <div class="header-form"></div>
                        <h2 class="pt-2">Bienvenido</h2>
                        <div class="w-100 d-flex justify-content-center">

                            <img src="/skins/page/images/logotacticas.png" class="img-fluid" alt="Imagen de seguro">
                        </div>
                        <div class="body-form px-3 text-center">
                            <hr>
                            <h3>Cambiar contraseña</h3>

                            <span class="">Por favor ingrese su nueva contraseña.</span>
                            <input type="hidden" name="code" value="<?php echo $this->code; ?>" />
                            <div class="d-flex align-items-center mt-2 mb-3">
                                <i class="fa fa-user form-control-feedback"></i>

                                <input type="text" placeholder="Ingrese su usuario" class="form-control ml-1" id="user" name="user" value="<?php echo $this->usuario ?>" readonly>
                            </div>

                            <div class="d-flex align-items-center mt-2 mb-3">
                                <i class="fa fa-lock form-control-feedback"></i>

                                <input type="password" placeholder="Ingrese su contraseña" class="form-control ml-1" id="password" name="password" onkeyup="validarPassword(); validarNuevoPass()" required>
                            </div>

                            <div class="d-flex align-items-center mt-2 mb-3">
                                <i class="fa fa-lock form-control-feedback"></i>
                                <input type="password" placeholder="Repita su contraseña" class="form-control ml-1" onkeyup="validarPassword(); validarNuevoPass()" id="repassword" name="repassword" required>
                            </div>
                            <div class=" align-items-center mt-2 ">
                                <div id="text-alert" class="alert alert-danger  alert-dismissible fade show" role="alert" style="display:none; transition: display 1s ease">
                                    Las contraseñas tienen que ser iguales
                                </div>
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
                            <div class="d-none">
                                <a href="/page/login/" class="">Volver al login</a>
                            </div>
                            <input type="hidden" id="csrf" name="csrf" value="<?php echo $this->csrf; ?>" />
                            <?php if (!$this->error || $this->error != 1) { ?>
                                <button class="btn-primary mt-2 px-2" id="btn-recuperar" type="submit">
                                    <span>Cambiar contraseña</span>


                                </button>
                            <?php } ?>



                        </div>
                    </form>

                <?php } ?>
            <?php } ?>
        </div>
    </div>

</div>
<script>
    $(document).ready(function() {
        validarPassword()
    });
</script>

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
        let password = document.getElementById('password').value
        let nume = document.getElementById('nume')
        let espe = document.getElementById('espe')
        let mayus = document.getElementById('mayus')
        let caract = document.getElementById('caract')
        let contenedorAlerta = document.getElementById('contenedor-alerta')

        let btnAct = document.getElementById('btn-recuperar')

        if (validarNumeros(password) != true) {
            nume.innerText = "*Mínimo 1 número"
        } else {
            nume.innerText = ""

        }
        if (validarLetras(password) != true) {
            caract.innerText = "*Mínimo 8 caracteres"
        } else {
            caract.innerText = ""

        }
        if (validarCaracteres(password) != true) {
            espe.innerText = "*La contraseña debe contener mínimo 1 caracter especial"
        } else {
            espe.innerText = ""

        }
        if (validarMayus(password) != true) {
            mayus.innerText = "*La contraseña debe contener mínimo 1 caracter en mayúscula"
        } else {
            mayus.innerText = ""

        }
        if (validarNumeros(password) === true && validarLetras(password) === true && validarCaracteres(password) === true && validarMayus(password) === true) {
            contenedorAlerta.style.display = "none"
            btnAct.disabled = false

        } else {
            contenedorAlerta.style.display = "block"
            btnAct.disabled = true

        }
    }
</script>