<div class="container-fluid ">

    <form class="text-left" enctype="multipart/form-data" method="post" action="<?php echo $this->routeform; ?>" data-bs-toggle="validator">
        <div class="content-dashboard mb-0">

            <div class="row d-flex justify-content-end" >

                <div class="col-12 col-md-3 form-group">
                    <label for="nombre" class="control-label">Nombre</label>
                    <label class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text input-icono  fondo-azul "><i class="fas fa-pencil-alt"></i></span>
                        </div>
                        <input type="text" value="<?= $this->buscar?>" name="buscar" id="buscar" class="form-control" >
                    </label>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="col-12 col-md-3 form-group">
                    <label for="nombre" class="control-label">Cedula</label>
                    <label class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text input-icono  fondo-azul "><i class="fas fa-pencil-alt"></i></span>
                        </div>
                        <input type="text" value="<?= $this->cedula?>" name="cedula" id="cedula" class="form-control" >
                    </label>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="col-12 col-md-3 form-group d-grid align-items-end" >
                    <button class="btn btn-guardar" type="submit">Buscar</button>
                </div>
                <div class="col-12 col-md-3 form-group d-grid align-items-end" >
                    <a class="btn  btn-azul-claro" href="/page/planillaasignacion/buscarcedulas?cleanfilter=1">Limpiar filtro</a>
                </div>


            </div>
        </div>

    </form>
    <div class="content-table table-responsive mt-2">
        <table class=" table table-striped  table-hover table-administrator text-left">
            <thead>
                <tr>


                    <td>C&eacute;dula</td>
                    <td>Nombre</td>
                    <td>Apellido</td>

                    <td width="100"></td>
                </tr>
            </thead>
            <tbody>

                <?php

                foreach ($this->cedulas as $content) { ?>

                    <?php $id =  $content->id; ?>
                    <tr>


                        <td><?= $content->documento; ?></td>
                        <td><?= $content->nombres; ?></td>

                        <td><?= $content->apellidos; ?></td>

                        <td class="text-right">
                            <div>
                                <span class="btn btn-azul btn-sm" onclick="top.set_cedula('<?php echo $content->documento ?>','<?php echo $content->nombres; ?> <?php echo $content->apellidos; ?>');" title="Agregar"><i class="fas fa-plus"></i></span>
                            </div>

                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>


<style>
    footer {
        display: none;
    }

    .contenedor-general {
        height: auto;
        margin-top: 0px;
        padding: 0px;
        margin-left: 0px;

    }
</style>