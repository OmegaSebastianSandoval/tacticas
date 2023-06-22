<style>
    header {
        display: none;
    }

    .menu__side {
        display: none;
    }

    .contenedor-general {

        height: 100vh;
        padding: 0;
        margin: 0;
        display: block;
    }
</style>

<div class="container contianer-home pb-5">
    <div class="d-flex align-items-center justify-content-between pt-2">
        <img style="width:200px" src="/skins/page/images/logotacticas.png" alt="Logo tacticas panama">
        <div class="d-flex align-items-center justify-content-center">
            <span class="titulo-home">Sistema de nómina</span>
        </div>
    </div>
    <hr>
    <div class="row ">

        <!-- <?php echo Session::getInstance()->get("kt_login_id") ?> -->

        <?php foreach ($this->empresas as $empresa) { ?>
            <div class="col-6 col-md-4 col-lg-2 d-flex justify-content-center mb-4">
                <a href="/page/login?img=<?php echo $empresa->logo ?>">
                    <img src="/images/<?php echo $empresa->logo ?>" class="img-thumbnail shadow" alt="Logo de la empresa <?php echo $empresa->nombre ?>">
                </a>
            </div>
        <?php } ?>
    </div>
</div>