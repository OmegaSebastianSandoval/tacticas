
<div class="container-fluid">

    <div class=" d-flex justify-content-between ">
        <h3 class="my-0"><i class="fa-regular fa-newspaper" title="Hoja de vida"></i>
            <?php

            use PHP_CodeSniffer\Standards\Squiz\Sniffs\Strings\EchoedStringsSniff;

            echo $this->titlesection; ?>
        </h3>
        <a href="/page/nomina">
            <button class="btn-primary-home btn-primary-volver  mt-2">
                <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd">
                    <path d="M2.117 12l7.527 6.235-.644.765-9-7.521 9-7.479.645.764-7.529 6.236h21.884v1h-21.883z" />
                </svg>
                <span>Regresar</span>
            </button>
        </a>
    </div>
    <form action="<?php echo $this->route; ?>" method="post" id="form-viaticos">
        <div class="content-dashboard p-0">
            <div class="row">

                <div class="col-12  col-md-4 col-lg-2">
                    <label>Empresa</label>
                    <label class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text input-icono fondo-rosado "><i class="far fa-list-alt"></i></span>
                        </div>
                        <select class="form-control" name="empresa">
                            <option value="">Todas</option>
                            <?php foreach ($this->list_empresa as $key => $value) : ?>
                                <option value="<?= $key; ?>" <?php if ($this->getObjectVariable($this->filters, 'empresa') == $key) {
                                                                    echo "selected";
                                                                } ?>><?= $value; ?></option>
                            <?php endforeach ?>
                        </select>
                    </label>
                </div>
                <div class="col-12  col-md-4 col-lg-3">
                    <label>Fecha de inicio</label>
                    <label class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text input-icono fondo-verde-claro "><i class="fas fa-calendar-alt"></i></span>
                        </div>
                        <input type="date" class="form-control" name="fecha_inicio" value="<?php
                                                                                            if ($this->fecha_inicio) {
                                                                                                echo $this->fecha_inicio;
                                                                                            } else {
                                                                                                echo $this->getObjectVariable($this->filters, 'fecha_inicio');
                                                                                            } ?>" required></input>
                    </label>
                </div>
                <div class="col-12  col-md-4 col-lg-3">
                    <label>Fecha final</label>
                    <label class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text input-icono fondo-verde-claro "><i class="fas fa-calendar-alt"></i></span>
                        </div>
                        <input type="date" class="form-control" name="fecha_fin" value="<?php
                                                                                        if ($this->fecha_fin) {
                                                                                            echo $this->fecha_fin;
                                                                                        } else {
                                                                                            echo $this->getObjectVariable($this->filters, 'fecha_fin');
                                                                                        } ?>" required></input>
                    </label>
                </div>



                <div class="col-12   col-md-6 col-lg-2  d-grid align-items-end mb-2">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-block btn-azul"> <i class="fas fa-filter"></i> Filtrar</button>
                </div>
                <div class="col-12   col-md-6  col-lg-2  d-grid align-items-end mb-2">

                    <a class="btn btn-block btn-azul-claro " href="<?php echo $this->route; ?>?cleanfilter=1"> <i class="fas fa-eraser"></i> Limpiar Filtro</a>
                </div>

            </div>
        </div>
    </form>
    <?php if (!$this->noContent) { ?>

        <div class="content-dashboard m-0 p-0">
            <div class="franja-paginas">
                <div class="d-flex justify-content-between">
                    <div class="">
                        <div class="titulo-registro">Se encontraron <?php echo count($this->cedulas); ?> Registros</div>
                    </div>
                    <!--    <div class="d-flex gap-2 align-items-center">
                    <div>


                        <span class="texto-paginas">Registros por pagina:</span>
                    </div>
                    <div>

                        <select class="form-control form-control-sm selectpagination">

                            <option value="100" <?php if ($this->pages == 100) {
                                                    echo 'selected';
                                                } ?>>100</option>

                            <option value="200" <?php if ($this->pages == 200) {
                                                    echo 'selected';
                                                } ?>>200</option>
                            <option value="300" <?php if ($this->pages == 300) {
                                                    echo 'selected';
                                                } ?>>300</option>
                            <option value="Todos" <?php if ($this->pages == 'Todos') {
                                                        echo 'selected';
                                                    } ?>>Todos</option>
                        </select>
                    </div>
                </div> -->

                    <div class="d-flex gap-2">

                        <div class="text-right"><a class="btn btn-sm btn-success2"  target="_blank" href="<?php echo $this->route . "/exportar"; ?>"> <i class="fa-regular fa-file-excel"></i> Exportar</a></div>

                    </div>
                </div>
            </div>

            <div class="content-table table-responsive mb-5 pb-5">
                <table class=" table table-striped  table-hover table-administrator text-center">
                    <thead>
                        <tr class="text-center">
                            <td>Item</td>
                            <td>Documento</td>
                            <td>Nombre</td>
                            <td>Viáticos asignados</td>
                            <td>Viáticos gastados</td>
                            <td>Diferencia</td>


                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        /*  echo '<pre>';
                    print_r($this->cedulas);
                    echo '</pre>'; */
                        $key = 1;

                        foreach ($this->cedulas as $key => $content) {

                            $key++;
                        ?>

                            <tr>
                                <td>
                                    <?php echo $key ?>
                                </td>
                                <td>
                                    <?php echo $content->cedula ?>
                                </td>
                                <td>
                                    <?php echo $content->nombre1 ?>
                                </td>
                                <td>
                                    <?php echo formato_numero($content->viaticos) ?>
                                </td>
                                <td>
                                    <?php echo formato_numero($this->viaticos[$content->cedula]) ?>
                                    <?php $TOTAL += $this->viaticos[$content->cedula];   ?>

                                </td>
                                <td class="<?php if ($content->viaticos - $this->viaticos[$content->cedula] >= 0) {
                                                echo 'verde';
                                            } else {
                                                echo 'rojo';
                                            } ?>">
                                    <?php echo formato_numero($content->viaticos - $this->viaticos[$content->cedula]) ?>
                                </td>

                            <?php } ?>
                            </tr>
                            <tr>

                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>

                                <td>
                                    <div align="right"><strong>TOTAL</strong></div>
                                </td>
                                <td><strong><?php echo formato_numero2($TOTAL) ?></strong></td>
                            </tr>


                    </tbody>
                </table>
            </div>
        </div>
    <?php } ?>
</div>

<?php

function formato_numero($n)
{
    return number_format($n, 2, ',', '');
}


function formato_numero2($n)
{
    return number_format($n, 2, '.', ',');
}


?>

<?php
include '../public/skins/page/js/informes.php';
?>