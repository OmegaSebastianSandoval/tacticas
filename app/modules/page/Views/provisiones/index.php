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

    <?php if (!$this->noContent) { ?>
        <?php if ($this->amount != 'Todos') { ?>

            <div class="container-fluid overflow-auto">


                <div align="center">
                    <ul class="pagination py-0 my-0 justify-content-center">
                        <?php

                        $url = $this->route;
                        if ($this->amount == 'Todos') {
                        }
                        $min = $this->page - 10;

                        if ($min < 0) {
                            $min = 1;
                        }
                        $max = $this->page + 10;
                        if ($this->totalpages > 1) {
                            if ($this->page != 1)
                                echo '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . ($this->page - 1) . '"> &laquo; Anterior </a></li>';
                            for ($i = 1; $i <= $this->totalpages; $i++) {
                                if ($this->page == $i)
                                    echo '<li class="active page-item"><a class="page-link">' . $this->page . '</a></li>';
                                else {
                                    if ($i <= $max and $i >= $min) {
                                        echo '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . $i . '">' . $i . '</a></li>  ';
                                    }
                                }
                            }
                            if ($this->page != $this->totalpages)
                                echo '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . ($this->page + 1) . '">Siguiente &raquo;</a></li>';
                        }

                        ?>
                    </ul>
                </div>
            </div>

        <?php } ?>

        <div class="content-dashboard mb-5">
            <div class="franja-paginas">
                <div class="d-flex justify-content-between">
                    <div class="">
                        <div class="titulo-registro">Se encontraron <?php echo $this->register_number; ?> Registros</div>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
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
                    </div>

                    <div class="d-flex gap-2">

                        <div class="text-right"><a class="btn btn-sm btn-success2" href="<?php echo $this->route . "/exportar"; ?>"> <i class="fa-regular fa-file-excel"></i> Exportar XLS</a></div>
                        <div class="text-right"><a class="btn btn-sm btn-success2" href="<?php echo $this->route . "/exportarxlsx"; ?>"> <i class="fa-regular fa-file-excel"></i> Exportar XLSX</a></div>
                    </div>
                </div>
            </div>



            <div class="content-table table-responsive">
                <table class=" table table-striped  table-hover table-administrator text-center">
                    <thead>
                        <tr class="text-center">
                            <th>Item</th>
                            <th>Cédula</th>
                            <th>Nombre</th>
                            <th>DÉCIMO</th>
                            <th>VACACIONES</th>
                            <th>P. ANTIGUEDAD</th>
                            <th>TOTAL PROVISIONES</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php

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
                                    <?php echo formato_numero($this->decimo[$content->cedula]);
                                    $total_decimo += $this->decimo[$content->cedula]; ?>
                                </td>
                                <td>
                                    <?php echo formato_numero($this->vacaciones[$content->cedula]);
                                    $total_vacaciones += $this->vacaciones[$content->cedula]; ?>
                                </td>
                                <td>
                                    <?php if ($content->tipo_contrato == 1) {
                                        echo formato_numero($this->antiguedad[$content->cedula]);
                                    }
                                    $total_antiguedad += $this->antiguedad[$content->cedula]; ?>
                                </td>
                                <td>
                                    <?php echo formato_numero($this->total_provisiones[$content->cedula]);
                                    $total_provisiones += $this->total_provisiones[$content->cedula]; ?>
                                </td>
                            </tr>
                        <?php } ?>
                        <?php if ($this->amount == 'Todos'  || $this->empresa) { ?>

                            <tr>
                                <td></td>
                                <td></td>
                                <td class="text-end"><strong>TOTAL</strong></td>
                                <td><?php echo $total_decimo ?></td>
                                <td><?php echo $total_vacaciones ?></td>
                                <td><?php echo $total_antiguedad ?></td>
                                <td><?php echo $total_provisiones ?></td>
                            </tr>
                        <?php } ?>

                    </tbody>
                </table>
            </div>
            <input type="hidden" id="page-route" value="<?php echo $this->route; ?>/changepage">
        </div>
        <?php if ($this->amount != 'Todos') { ?>

            <div class="container-fluid overflow-auto">

                <div align="center">
                    <ul class="pagination py-0 my-0 justify-content-center">
                        <?php

                        $url = $this->route;
                        $min = $this->page - 10;
                        if ($min < 0) {
                            $min = 1;
                        }
                        $max = $this->page + 10;
                        if ($this->totalpages > 1) {
                            if ($this->page != 1)
                                echo '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . ($this->page - 1) . '"> &laquo; Anterior </a></li>';
                            for ($i = 1; $i <= $this->totalpages; $i++) {
                                if ($this->page == $i)
                                    echo '<li class="active page-item"><a class="page-link">' . $this->page . '</a></li>';
                                else {
                                    if ($i <= $max and $i >= $min) {
                                        echo '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . $i . '">' . $i . '</a></li>  ';
                                    }
                                }
                            }
                            if ($this->page != $this->totalpages)
                                echo '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . ($this->page + 1) . '">Siguiente &raquo;</a></li>';
                        }

                        ?>
                    </ul>
                </div>
            </div>
        <?php } ?>
    <?php } ?>


</div>

<?php
function formato_numero($n)
{
    return number_format($n, 2, ',', '');
}
?>