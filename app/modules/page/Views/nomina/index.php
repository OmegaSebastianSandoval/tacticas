<div class="container-fluid mb-5">
    <div class=" d-flex justify-content-start ">
        <h3 class="my-0"> <i class="fa-solid fa-calendar-xmark" title="Vencimientos"></i>
            <?php echo $this->titlesection; ?></h3>
    </div>
    <!--   <div class="d-grid gap-3">


        <a class=" btn-tab " href="/page/vencimiento">Vencimiento de documentos
            <span></span>
        </a>

        <a class=" btn-tab " href="/page/vencimiento">Vencimientos
            <span></span>
        </a>
        <a class=" btn-tab " href="/page/vencimiento">Vencimientos
            <span></span>
        </a>
        <a class=" btn-tab " href="/page/vencimiento">Vencimientos
            <span></span>
        </a>
    </div> -->
    <style>
        .home-nomina {
            display: grid;
            width: 100%;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 40px;
            margin-top: 20px;
        }
    </style>
        <div class="home-nomina">

        <div class="">
            <a href="/page/planilla">
                <div class="card-vencimiento mx-auto ">
                    <div class="image">
                        <img src="/skins/page/images/planilla.jpg" alt="Administrar Planilla">
                    </div>
                    <h2 class="title p-2">Planilla</h2>
                </div>
            </a>
        </div>

        <?php  if ((Session::getInstance()->get("kt_login_level") == '1' )) { ?>
        <div class="">
            <a href="/page/parametros/manage?id=1">
                <div class="card-vencimiento mx-auto ">
                    <div class="image">
                        <img src="/skins/page/images/parametros.jpg" alt="Administrar parámetros">
                    </div>
                    <h2 class="title p-2">Administrar parámetros de cálculo</h2>
                </div>
            </a>
        </div>
        <?php 	}?>

        <div class="">
            <a href="/page/localizaciones">
                <div class="card-vencimiento mx-auto ">
                    <div class="image">
                        <img src="/skins/page/images/puesto.jpg" alt="Administrar localizaciones">
                    </div>
                    <h2 class="title">Administrar localizaciones</h2>
                </div>
            </a>
        </div>
    

        <?php  if ((Session::getInstance()->get("kt_login_level") == '1' )) { ?>
        <div class="">
            <a href="/page/tipodotacion">
                <div class="card-vencimiento mx-auto ">
                    <div class="image">
                        <img src="/skins/page/images/dotac.jpg" alt="Administrar de dotacion">
                    </div>
                    <h2 class="title">Administrar dotación</h2>
                </div>
            </a>
        </div>
        <?php 	}?>

        <?php  if ((Session::getInstance()->get("kt_login_level") == '1' )) { ?>
        <div class="">
            <a href="/page/cargos">
                <div class="card-vencimiento mx-auto ">
                    <div class="image">
                        <img src="/skins/page/images/cargos2.jpg" alt="Administrar cargos">
                    </div>
                    <h2 class="title">Administrar cargos</h2>
                </div>
            </a>
        </div>
        <?php 	}?>

        <?php  if ((Session::getInstance()->get("kt_login_level") == '1' )) { ?>

        <div class="">
            <a href="/page/facturacion?cleanfilter=1">
                <div class="card-vencimiento mx-auto ">
                    <div class="image">
                        <img src="/skins/page/images/infofac.jpg" alt="Informe facturación">
                    </div>
                    <h2 class="title">Informe facturación</h2>
                </div>
            </a>
        </div>
        <?php 	}?>

        <?php  if ((Session::getInstance()->get("kt_login_level") == '1' )) { ?>
        <div class="">
            <a href="/page/provisiones?cleanfilter=1">
                <div class="card-vencimiento mx-auto ">
                    <div class="image">
                        <img src="/skins/page/images/infoprov.jpg" alt="Informe de provisiones">
                    </div>
                    <h2 class="title">Informe de provisiones</h2>
                </div>
            </a>
        </div>
        <?php 	}?>

        <?php  if ((Session::getInstance()->get("kt_login_level") == '1' )) { ?>
        <div class="">
            <a href="/page/segurosocial?cleanfilter=1">
                <div class="card-vencimiento mx-auto ">
                    <div class="image">
                        <img src="/skins/page/images/infoseguro.jpg" alt="Informe de seguro social">
                    </div>
                    <h2 class="title">Informe de seguro social</h2>
                </div>
            </a>
        </div>
        <?php 	}?>

        <?php  if ((Session::getInstance()->get("kt_login_level") == '1' )) { ?>
        <div class="">
            <a href="/page/viaticos?cleanfilter=1">
                <div class="card-vencimiento mx-auto ">
                    <div class="image">
                        <img src="/skins/page/images/infovia.jpg" alt="Informe de viaticos">
                    </div>
                    <h2 class="title">Informe de viaticos</h2>
                </div>
            </a>
        </div>
        <?php 	}?>

        <?php  if ((Session::getInstance()->get("kt_login_level") == '1' )) { ?>
        <div class="">
            <a href="/page/salario?cleanfilter=1">
                <div class="card-vencimiento mx-auto ">
                    <div class="image">
                        <img src="/skins/page/images/infosala.jpg" alt="Informe salario neto cliente">
                    </div>
                    <h2 class="title">Informe salario neto cliente</h2>
                </div>
            </a>
        </div>
        <?php 	}?>

        <?php  if ((Session::getInstance()->get("kt_login_level") == '1' )) { ?>
        <div class="">
            <a href="/page/infolocalizacion?cleanfilter=1">
                <div class="card-vencimiento mx-auto ">
                    <div class="image">
                        <img src="/skins/page/images/infoloca.jpg" alt="Localizaciones">
                    </div>
                    <h2 class="title">Informe localizaciones</h2>
                </div>
            </a>
        </div>
        <?php 	}?>


    </div>

</div>

<style>
    .card-vencimiento {
        max-width: 200px;
        height: 250px;
    }

    .card-vencimiento .title {
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        background-color: rgba(25, 169, 201, 0.7);
        font-size: 15px;
        height: 50px;
    }

    .card-vencimiento:hover .title {
        background-color: rgba(25, 169, 201, 1);
    }
</style>
<script>
  Fancybox.bind("[data-fancybox]", {
        //
      }) 

    </script>