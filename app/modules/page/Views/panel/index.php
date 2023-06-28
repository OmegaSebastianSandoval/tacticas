<div class="container-fluid" style="margin-bottom: 100px;">

    <?php if (Session::getInstance()->get("kt_login_level") == 1) { ?>

        <div class="row d-flex   align-items-center">
            <div class="col-12 col-lg-7 d-flex justify-content-start">
                <h3 class="my-0"><i class="fa-solid fa-building" title="Empresas"></i> Últimas empresas</h3>
            </div>
            <div class="col-12 col-lg-5 gap-3 d-flex justify-content-end">
                <a href="/page/empresas" class="btn btn-primary-home d-flex">Ver todas las empresas</a>
                <a href="/page/usuarios" class="btn btn-primary-home d-flex">Ver todos los usuarios</a>
            </div>
        </div>


        <div class="row mt-4">
            <?php foreach ($this->empresas as $empresa) { ?>
                <div class="col-12 col-md-6 col-lg-4 col-xl-3 d-flex justify-content-center">
                    <div class="card shadow card-empresas">
                        <img src="/images/<?php echo $empresa->logo ?>" class="card-img-top" alt="Logo de la empresa <?php echo $empresa->nombre ?>">

                        <h4 class="title-card"><?php echo $empresa->nombre ?></h4>
                        <div class="card-body">
                            <div class="row  p-0 m-0">
                                <div class="col-3  div-button div-divisor p-0 m-0">
                                    <a href="/page/empresas/manage?id=<?php echo $empresa->id ?>">EDITAR</a>

                                </div>

                                <div class="col-4  div-button div-divisor p-0 m-0">
                                    <a href="/page/usuarios?emp=<?php echo $empresa->id ?>">USUARIOS</a>

                                </div>
                                <div class="col-5 div-button p-0 m-0">
                                    <a href="/page/hojadevida?id=<?php echo $empresa->id ?>" style="font-size: 12px;">HOJAS DE VIDA</a>

                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            <?php } ?>

        </div>
        <hr>
    <?php } ?>
    <div class="row d-flex mt-2  align-items-center">
        <div class="col-12 col-lg-7 d-flex justify-content-start">
            <h3 class="my-0"><i class="fa-regular fa-newspaper" title="Hoja de vida"></i> Últimas hojas de vida</h3>

        </div>
        <div class="col-12 col-lg-5 gap-3 d-flex justify-content-end">

            <a href="/page/hojadevida" class="btn btn-primary-home d-flex">Ver todas las hojas de vida</a>

        </div>


    </div>


    <div class="content-table table-responsive">
        <table class=" table table-striped  table-hover table-administrator text-center">
            <thead>
                <tr class="text-center">

                    <td>Nombres</td>
                    <td>Apellidos</td>
                    <td>Tipo documento</td>
                    <td>N&uacute;mero de documento</td>
                    <td>Empresa</td>
                    <td>Tipo de contrato</td>

                    <td width="100"></td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->hojaVida as $content) { ?>
                    <?php $id =  $content->id; ?>
                    <tr>

                        <td><?= $content->nombres; ?></td>
                        <td><?= $content->apellidos; ?></td>
                        <td><?= $this->list_tipo_documento[$content->tipo_documento]; ?>
                        <td><?= $content->documento; ?></td>
                        <td><?= $this->list_empresa[$content->empresa]; ?></td>
                        <td><?= $this->list_tipo_contrato[$content->tipo_contrato]; ?></td>

                        <td class="text-right">
                            <div>
                                <a class="btn btn-azul btn-sm" href="/page/hojadevida/manage?id=<?= $id ?>" data-bs-toggle="tooltip" data-placement="top" title="Editar"><i class="fa-regular fa-pen-to-square"></i></a>
                                <span data-bs-toggle="tooltip" data-placement="top" title="Eliminar"><a class="btn btn-rojo btn-sm" data-bs-toggle="modal" data-bs-target="#modal<?= $id ?>"><i class="fas fa-trash-alt"></i></a></span>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade text-left" id="modal<?= $id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myModalLabel">Eliminar Registro</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="">¿Esta seguro de eliminar este registro?</div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <a class="btn btn-danger" href="/page/panel/delete?id=<?= $id ?>&csrf=<?= $this->csrf; ?><?php echo ''; ?>">Eliminar</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</div>