<!-- <div class="header-info">
  <div class="container">
  </div>
</div> -->
<header class="d-none">

    <div class="container-fluid  d-flex  order-1 order-md-2 ">

        <span class="titulo-header w-100 d-flex align-items-center" style="margin-left:80px">Sistema de nómina</span>
        <div id="content-sesion" class="d-flex  flex-direction justify-content-end content-sesion">
            <div class="d-grid  px-2">
                <span class="text-secondary d-flex align-items-end fs-6" style="white-space: nowrap;"> <?php echo $_SESSION['kt_login_name']; ?></span>
                <span class="text-secondary text-end fw-bold fs-5"> Bienvenido</span>
            </div>

            <!--  <div class="d-flex  justify-content-start align-items-center px-2 ">
                <a href="/page/login/logout" class="d-flex gap-1" style="all:unset; cursor:pointer">
                    <span class="text-secondary mr-2">Salir</span>
                    <i class="fa-solid fa-arrow-right-from-bracket ml-2 fs-4" style="color:var(--primary)"></i>
                </a>
            </div> -->
            <div class="d-flex  justify-content-end align-items-center ">
                <?php if ((Session::getInstance()->get("kt_login_level") == '4')) { ?>
                    <a href="/page/loginempleados/logout" class="btn-salir">

                    <?php } else { ?>

                        <a href="/page/login/logout" class="btn-salir">
                        <?php } ?>

                        <div class="sign"><svg viewBox="0 0 512 512">
                                <path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"></path>
                            </svg></div>

                        <div class="text">Salir</div>
                        </a>
            </div>
        </div>
    </div>
</header>
<!-- <hr class="separador-header"> -->
<div class="menu__side d-none" id="menu_side">
    <!--    <div class="icon__menu " id="btn_open">
        <i class="fas fa-bars"></i>
    </div> -->
    <!--  <div class="name__page">
        <img src="/skins/page/images/logotacticas.png" class="img-fluid" alt="Logo tácticas panama">
        <hr>
    </div>
 -->
    <div class="options__menu p-0 " style="top:0px;">


        <label class="hamburger icon__menu">
            <input type="checkbox" id="btn_open">
            <svg viewBox="0 0 32 32">
                <path class="line line-top-bottom" d="M27 10 13 10C10.8 10 9 8.2 9 6 9 3.5 10.8 2 13 2 15.2 2 17 3.8 17 6L17 26C17 28.2 18.8 30 21 30 23.2 30 25 28.2 25 26 25 23.8 23.2 22 21 22L7 22"></path>
                <path class="line" d="M7 16 27 16"></path>
            </svg>
        </label>



    </div>
    <div class="options__menu mt-5">
    <?php if (Session::getInstance()->get("kt_login_level") != 4) { ?>

        <a href="/page/panel" <?php if ($this->botonpanel == 0) { ?>class="selected" <?php } ?>>
            <div class="option">
                <i class="fas fa-home" title="Inicio"></i>
                <h5>Inicio</h5>
                <div class="w-100 d-flex justify-content-end">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
            </div>
        </a>
        <?php } ?>

        <?php if (Session::getInstance()->get("kt_login_level") == 1) { ?>

            <a href="/page/empresas" <?php if ($this->botonpanel == 1) { ?>class="selected" <?php } ?>>
                <div class="option">

                    <i class="fa-solid fa-building" title="Empresas"></i>
                    <h5>Empresas</h5>
                    <div class="w-100 d-flex justify-content-end">
                        <i class="fa-solid fa-chevron-right"></i>
                    </div>
                </div>
            </a>

            <a href="/page/usuarios" <?php if ($this->botonpanel == 2) { ?>class="selected" <?php } ?>>
                <div class="option">
                    <i class="fas fa-user" title="Usuarios"></i>
                    <h5>Usuarios</h5>
                    <div class="w-100 d-flex justify-content-end">
                        <i class="fa-solid fa-chevron-right"></i>
                    </div>
                </div>
            </a>
        <?php } ?>



    <?php if (Session::getInstance()->get("kt_login_level") == 4) { ?>
        <a href="/page/hojadevida/manage?cc=<?php echo Session::getInstance()->get("kt_login_id") ?>#pills-documentos" <?php if ($this->botonpanel == 3) { ?>class="selected" <?php } ?>>

        <?php } else{ ?>        
            <a href="/page/hojadevida" <?php if ($this->botonpanel == 3) { ?>class="selected" <?php } ?>>
        <?php } ?>

        <div class="option">
                <i class="fa-regular fa-newspaper" title="Hoja de vida"></i>
                <h5>Hojas de vida</h5>
                <div class=" d-flex justify-content-end">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
            </div>
        </a>
        <?php if (Session::getInstance()->get("kt_login_level") != 4) { ?>
            <a href="/page/vencimientos" <?php if ($this->botonpanel == 4) { ?>class="selected" <?php } ?>>

                <div class="option">
                    <i class="fa-solid fa-calendar-xmark" title="Vencimientos"></i>
                    <h5>Vencimientos</h5>
                    <div class="w-100 d-flex justify-content-end">
                        <i class="fa-solid fa-chevron-right"></i>
                    </div>
                </div>
            </a>
        <?php } ?>

        <?php if (Session::getInstance()->get("kt_login_level") != 4) { ?>

        <a href="/page/reportes" <?php if ($this->botonpanel == 5) { ?>class="selected" <?php } ?>>

            <div class="option">
                <i class="fa-solid fa-clipboard" title="Reportes"></i>
                <h5>Reportes</h5>
                <div class="w-100 d-flex justify-content-end">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
            </div>
        </a>
        <?php } ?>

        <?php if ((Session::getInstance()->get("kt_login_level") != '2')) { ?>

            <a href="/page/nomina" <?php if ($this->botonpanel == 6) { ?>class="selected" <?php } ?>>
                <div class="option">
                    <i class="fa-solid fa-money-check-dollar" title="Nómina"></i>
                    <h5>Nómina</h5>
                    <div class="w-100 d-flex justify-content-end">
                        <i class="fa-solid fa-chevron-right"></i>
                    </div>
                </div>
            </a>
        <?php     } ?>


        <!--         <a href="/page/login/logout">
            <div class="option">

                <i class="fa-solid fa-right-from-bracket" title="Nosotros"></i>
                <h5>Cerrar sesión</h5>
            </div>
        </a> -->


    </div>
</div>
<script>
    //Ejecutar función en el evento click
    document.getElementById("btn_open").addEventListener("click", open_close_menu);

    //Declaramos variables
    var side_menu = document.getElementById("menu_side");
    var btn_open = document.getElementById("btn_open");
    var body = document.getElementById("body");
    var contentSesion = document.getElementById("content-sesion");
    //  contenedor = document.getElementById('contenedor-general')

    //Evento para mostrar y ocultar menú
    function open_close_menu() {
        // body.classList.toggle("body_move");
        side_menu.classList.toggle("menu__side_move");
        btn_open.classList.toggle("ml-15");

        // contentSesion.classList.toggle("content-sesion2");
        /*    console.log(contenedor);
           contenedor.classList.toggle("p-20"); */



    }

    //Si el ancho de la página es menor a 760px, ocultará el menú al recargar la página

    if (window.innerWidth < 760) {

        body.classList.add("body_move");
        side_menu.classList.add("menu__side_move");
    }

    //Haciendo el menú responsive(adaptable)

    window.addEventListener("resize", function() {

        if (window.innerWidth > 760) {

            body.classList.remove("body_move");
            side_menu.classList.remove("menu__side_move");
        }

        if (window.innerWidth < 760) {

            body.classList.add("body_move");
            side_menu.classList.add("menu__side_move");
        }

    });
</script>