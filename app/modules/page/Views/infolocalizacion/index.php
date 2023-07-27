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

                <div class="col-12 col-lg-4">
                    <label>Rangos guardados</label>
                    <label class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text input-icono fondo-rosado "><i class="far fa-list-alt"></i></span>
                        </div>
                        <select class="form-control" name="fecha_completa">
                            <option value=""></option>
                            <?php foreach ($this->list_facturadas as $key => $value) : ?>
                                <option value="<?= $value; ?>" <?php if ($this->getObjectVariable($this->filters, 'fecha_completa') ==  $value) {
                                                                    echo "selected";
                                                                } ?>><?= $value; ?></option>
                            <?php endforeach ?>
                        </select>
                    </label>
                </div>
                <div class="col-12 col-lg-2">
                    <label>Fecha de inicio</label>
                    <label class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text input-icono fondo-verde-claro "><i class="fas fa-calendar-alt"></i></span>
                        </div>
                        <input type="date" formart="yyyy-mm-dd" class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?php echo $this->getObjectVariable($this->filters, 'fecha_inicio') ?>"></input>
                    </label>
                </div>
                <div class="col-12 col-lg-2">
                    <label>Fecha final</label>
                    <label class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text input-icono fondo-verde-claro "><i class="fas fa-calendar-alt"></i></span>
                        </div>
                        <input type="date" formart="yyyy-mm-dd" class="form-control" name="fecha_fin" id="fecha_fin" value="<?php echo $this->getObjectVariable($this->filters, 'fecha_fin') ?>"></input>
                    </label>
                </div>



                <div class="col-12 col-lg-2  d-grid align-items-end mb-2">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-block btn-azul"> <i class="fas fa-filter"></i> Filtrar</button>
                </div>
                <div class="col-12 col-lg-2  d-grid align-items-end mb-2">

                    <a class="btn btn-block btn-azul-claro " href="<?php echo $this->route; ?>?cleanfilter=1"> <i class="fas fa-eraser"></i> Limpiar Filtro</a>
                </div>

            </div>
        </div>
    </form>

    <div class="container-fluid mb-5 overflow-auto">

        <div align="center">
            <ul class="pagination m-0 pagination-end justify-content-center">
                <?php
                $url = $this->route;
                $min = $this->page - 10;
                if ($min < 0) {
                    $min = 1;
                }
                $max = $this->page + 15;
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

        <div class="content-table table-responsive">
            <table class=" table table-striped  table-hover table-administrator text-center">
                <thead style="font-size: 0.8rem;">
                    <tr class="text-center">
                        <th>LOCALIZACIÓN</th>
                        <th>H NORMALES</th>
                        <th>H NORMALES FACTURADAS</th>
                        <th>H DIURNAS </th>
                        <th>H DIURNAS FACTURADAS</th>
                        <th>H NOCTURNAS </th>
                        <th>H NOCTURNAS FACTURADAS</th>
                        <th>FESTIVOS </th>
                        <th>FESTIVOS FACTURADAS</th>
                        <th>DOMINICALES </th>
                        <th>DOMINACALES FACTURADAS</th>


                    </tr>
                </thead>
                <tbody>
                    <!-- <?php echo $this->tabla2; ?> -->

                    <?php echo $this->tabla; ?>

                    <!--   <?php foreach ($this->localizaciones as $key => $content) { ?>
                       


                        <tr>
                            <td>
                                <?php echo $content->nombre; ?>
                            </td>
                            <td>
                                <?php echo $this->total['normal'] ?>
                            </td>




                        </tr> 
                    <?php  }  ?> -->


                </tbody>
            </table>
        </div>
        <input type="hidden" id="page-route" value="<?php echo $this->route; ?>/changepage">
    </div>

</div>
<style>

</style>
<script>
    Fancybox.bind("[data-fancybox]", {
        //
    })

    function guardar_facturadas(loc, campo, i) {
        var fecha1 = document.getElementById('fecha_inicio').value;
        var fecha2 = document.getElementById('fecha_fin').value;
        var valor = document.getElementById(campo + '_' + i).value;
        if (fecha1 != "" && fecha2 != "" && valor != "") {
            $.post("/page/infolocalizacion/guardarfacturadas", {
                "loc": loc,
                "fecha1": fecha1,
                "fecha2": fecha2,
                "campo": campo,
                "valor": valor
            }, function(res) {
                console.log(res);
            })

            /* $('#consulta_facturadas'+i).load('mod_nomina/consulta_facturadas.php', {loc:loc, fecha1:fecha1, fecha2:fecha2, campo:campo, valor:valor }); */
            /*   console.log(fecha1)
              console.log(fecha2)
              console.log(valor)
              console.log(loc)
              console.log(campo)
              console.log(i) */

        }
    }
</script>