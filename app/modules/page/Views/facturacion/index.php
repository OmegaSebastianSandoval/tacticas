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

    <form action="<?php echo $this->route; ?>" method="post" id="form-facturacion">
        <div class="content-dashboard p-0">
            <div class="row d-flex justify-content-start">

                <div class="col-12  col-md-4 col-lg-1">
                    <label>Empresa</label>
                    <label class="input-group">
                      
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
                <div class="col-12  col-md-4 col-lg-2">
                    <label>Tipo</label>
                    <label class="input-group">
                      
                        <select class="form-control" name="tipo">

                            <?php foreach ($this->list_tipo as $key => $value) : ?>
                                <option value="<?= $key; ?>" <?php if ($this->getObjectVariable($this->filters, 'tipo') == $key) {
                                                                    echo "selected";
                                                                } ?>><?= $value; ?></option>
                            <?php endforeach ?>
                        </select>
                    </label>
                </div>
                <div class="col-12  col-md-4 col-lg-2">
                    <label>Localización</label>
                    <label class="input-group">
                      
                        <select class="form-control" name="localizacion">
                            <option value="">Todas</option>
                            <?php foreach ($this->list_localizacion as $key => $value) : ?>
                                <option value="<?= $key; ?>" <?php if ($this->getObjectVariable($this->filters, 'localizacion') == $key) {
                                                                    echo "selected";
                                                                } ?>><?= $value; ?></option>
                            <?php endforeach ?>
                        </select>
                    </label>
                </div>
                <div class="col-12  col-md-4 col-lg-2">
                    <label>Fecha de inicio</label>
                    <label class="input-group">
                        
                        <input type="date" class="form-control" name="fecha_inicio" value="<?php
                                                                                            if ($this->fecha_inicio) {
                                                                                                echo $this->fecha_inicio;
                                                                                            } else {
                                                                                                echo $this->getObjectVariable($this->filters, 'fecha_inicio');
                                                                                            } ?>" required></input>
                    </label>
                </div>
                <div class="col-12  col-md-4 col-lg-2">
                    <label>Fecha final</label>
                    <label class="input-group">
                        
                        <input type="date" class="form-control" name="fecha_fin" value="<?php
                                                                                        if ($this->fecha_fin) {
                                                                                            echo $this->fecha_fin;
                                                                                        } else {
                                                                                            echo $this->getObjectVariable($this->filters, 'fecha_fin');
                                                                                        }   ?>" required></input>
                    </label>
                </div>



                <div class="col-12   col-md-6 col-lg-1  d-grid align-items-end mb-2">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-block btn-azul"> </i> Filtrar</button>
                </div>
                <div class="col-12   col-md-6  col-lg-2  d-grid align-items-end mb-2">

                    <a class="btn btn-block btn-azul-claro " href="<?php echo $this->route; ?>?cleanfilter=1"> </i> Limpiar Filtro</a>
                </div>

            </div>
        </div>
    </form>
    <?php if(!$this->noContent){?>
    <div class="content-dashboard">
        <div class="franja-paginas">
            <div class="d-flex justify-content-between">
                <div class="">
                    <div class="titulo-registro">Se encontraron <?php echo $this->register_number; ?> Registros</div>
                </div>
                <div class="d-flex gap-2 align-items-ce3nter">
                    <!-- <div>


                        <span class="texto-paginas">Registros por pagina:</span>
                    </div>
                    <div>

                        <select class="form-control form-control-sm selectpagination">
                            <option value="50" <?php if ($this->pages == 50) {
                                                    echo 'selected';
                                                } ?>>50</option>
                            <option value="100" <?php if ($this->pages == 100) {
                                                    echo 'selected';
                                                } ?>>100</option>
                            <option value="150" <?php if ($this->pages == 150) {
                                                    echo 'selected';
                                                } ?>>150</option>
                            <option value="200" <?php if ($this->pages == 200) {
                                                    echo 'selected';
                                                } ?>>200</option>
                        </select>
                    </div> -->
                </div>

                <div class="d-flex gap-2">
                    <!-- <div class="text-right"><a class="btn btn-sm btn-success" href="<?php echo $this->route . "\manage"; ?>"> <i class="fas fa-plus-square"></i> Crear Nuevo</a></div> -->
                    <div class="text-right"><a class="btn btn-sm btn-success2" target="_blank" href="<?php echo $this->route . "/exportar"; ?>"> <i class="fa-regular fa-file-excel"></i> Exportar</a></div>

                </div>
            </div>
        </div>
        <div class="content-table table-responsive pb-5">
            <table class=" table table-striped  table-hover table-administrator text-center">
                <thead>
                    <tr class="text-center">
                        <td rowspan="2">Item</td>
                        <td rowspan="2">Cédula</td>
                        <td rowspan="2">Nombre</td>
                        <td>PEND.</td>
                        <?php for ($j = $this->fecha_inicio; $j <= $this->fecha_fin; $j = sumar_dias($j, 1)) { ?>
                            <td>
                                <div align="center">
                                    <?php
                                    $dia = $j;

                                    echo substr($j, 5, 5);

                                    ?>
                                </div>
                            </td>
                        <?php } ?>
                        <td rowspan="2">
                            <div align="center">INC.</div>
                        </td>
                        <td rowspan="2">
                            <div align="center">TOT</div>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <?php for ($j = $this->fecha_inicio; $j <= $this->fecha_fin; $j = sumar_dias($j, 1)) { ?>
                            <th>
                                <div align="center">
                                    <?php
                                    $dia = $j;
                                    echo dia_semana($dia);
                                    ?>
                                </div>
                            </th>
                        <?php } ?>
                    </tr>
                </thead>

                <tbody>
                    <?php echo $this->tabla; ?>
                    <?php echo $this->tabla2; ?>


                </tbody>
            </table>
        </div>
        <input type="hidden" id="page-route" value="<?php echo $this->route; ?>/changepage">
    </div>
    <?php } ?>

</div>
<?php

function formato_numero($n)
{
    return number_format($n, 2, ',', '');
}
function con_cero($mes)
{
    $mes1 = $mes;
    if ($mes1 < 10) {
        $mes1 = "0" . $mes;
    }
    return $mes1;
}
function dia_semana($f)
{
    $dia_semana = date("w", strtotime($f));

    if ($dia_semana == 0) {
        $dia_semana = 6; // Assign 6 instead of 7 for Sunday
    } else {
        $dia_semana -= 1; // Subtract 1 from the other days to match the array indexes
    }

    $dias = array('L', 'M', 'X', 'J', 'V', 'S', 'D');
    $letra = $dias[$dia_semana];

    return $letra;
}


function sumar_dias($fecha, $dias)
{
    $nuevafecha = strtotime('+' . $dias . ' day', strtotime($fecha));
    $nuevafecha = date('Y-m-d', $nuevafecha);
    return $nuevafecha;
}

function evaluar($x)
{
    if ($x == "0") {
        return "";
    } else {
        return $x;
    }
}
?>
<script>
    
    // Función que se ejecutará al enviar el formulario
    function onSubmitForm() {
      const contentloader = document.getElementById('content-loader')
      const loader = document.getElementById('loader')
      contentloader.style.display = 'flex'
      loader.style.display = 'block'
    }
    document.getElementById('form-facturacion').addEventListener('submit', onSubmitForm);
    
    
    </script>