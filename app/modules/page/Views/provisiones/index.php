<div class="container-fluid">

    <div class=" d-flex justify-content-between ">
        <h3 class="my-0"><i class="fa-regular fa-newspaper" title="Hoja de vida"></i>
            <?php echo $this->titlesection; ?>
        </h3>
        <a href="/page/nomina">
            <button class="btn-primary-home btn-primary-volver  mt-2" type="submit">
                <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd">
                    <path d="M2.117 12l7.527 6.235-.644.765-9-7.521 9-7.479.645.764-7.529 6.236h21.884v1h-21.883z" />
                </svg>
                <span>Regresar</span>
            </button>
        </a>
    </div>
    <form action="<?php echo $this->route; ?>" method="post">
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
                                                                                        }   ?>" required></input>
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

    <div class="content-dashboard mt-5 mb-5">
        <div class="franja-paginas">
            <div class="d-flex justify-content-end">


                <div class="d-flex gap-2">

                    <div class="text-right"><a class="btn btn-sm btn-success2" href="<?php echo $this->route . "/exportar"; ?>"> <i class="fa-regular fa-file-excel"></i> Exportar</a></div>

                </div>
            </div>
        </div>



        <div class="content-table table-responsive">
            <table class=" table table-striped  table-hover table-administrator text-center">
                <thead>
                    <tr class="text-center">
                        <td>Item</td>
                        <td>Cédula</td>
                        <td>Nombre</td>
                        <td>DÉCIMO</td>
                        <td>VACACIONES</td>
                        <td>P. ANTIGUEDAD</td>
                        <td>TOTAL PROVISIONES</td>

                    </tr>
                </thead>
                <tbody>
                   <!--  <?php

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
                                <?php echo formato_numero($this->decimo[$content->cedula]) ?>                   
                            </td>
                            <td>
                                <?php echo formato_numero($this->vacaciones[$content->cedula]) ?>                   
                            </td>
                            <td>
                                <?php echo formato_numero($this->antiguedad[$content->cedula]) ?>                   
                            </td>
                            <td>
                                <?php echo formato_numero($this->total_provisiones[$content->cedula]) ?>                   
                            </td>

                        <?php } ?> -->
                        <?php
                        echo $this->tabla;
                        echo $this->tabla2;

                        ?>

                </tbody>
            </table>
        </div>
        <input type="hidden" id="page-route" value="<?php echo $this->route; ?>/changepage">
    </div>
</div>

<?php
 function formato_numero($n)
{
    return number_format($n, 2, ',', '');
}
?>