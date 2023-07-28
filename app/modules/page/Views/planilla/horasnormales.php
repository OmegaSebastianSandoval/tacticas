<style>
    .table>:not(caption)>*>* {
        padding: 3px;
        vertical-align: middle;
        display: table-cell;
        text-align: center;
        font-size: 11px;
    }
</style>
<!-- <script src="/skins/page/js/horas.js"></script> -->
<?php
include '../public/skins/page/js/horas.php';
?>


<div class="container-fluid">
    <div class=" d-flex justify-content-between align-items-center">


        <h3 class="my-0  d-flex text-start gap-2 align-items-center"><i class="fa-regular fa-newspaper" title="Hoja de vida"></i> <?php echo $this->titlesection; ?></h3>

        <a href="/page/planilla">
            <button class="btn-primary-home  btn-primary-volver  mt-2" type="submit">
                <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd">
                    <path d="M2.117 12l7.527 6.235-.644.765-9-7.521 9-7.479.645.764-7.529 6.236h21.884v1h-21.883z" />
                </svg>
                <span>Regresar</span>
            </button>
        </a>
    </div>
    <div class="container-fluid  ">
        <div class=" d-flex justify-content-start mt-2 gap-2 align-items-center">
            <a class="btn-tab btn-consolidado <?php echo $this->tipo == 1 ? 'active' : '' ?> m-0  " href="/page/planilla/horasnormales?planilla=<?php echo $this->planila ?>&tipo=1">Horas normales </a>
            <a class="btn-tab btn-consolidado <?php echo $this->tipo == 2 ? 'active' : '' ?> m-0  " href="/page/planilla/horasnormales?planilla=<?php echo $this->planila ?>&tipo=2">Horas adicionales diurnas</a>
            <a class="btn-tab btn-consolidado <?php echo $this->tipo == 3 ? 'active' : '' ?> m-0  " href="/page/planilla/horasnormales?planilla=<?php echo $this->planila ?>&tipo=3">Horas adicionales nocturnas</a>
            <a class="btn-tab btn-consolidado <?php echo $this->tipo == 4 ? 'active' : '' ?> m-0  " href="/page/planilla/horasnormales?planilla=<?php echo $this->planila ?>&tipo=4">Horas recargo festivo</a>
            <a class="btn-tab btn-consolidado <?php echo $this->tipo == 5 ? 'active' : '' ?> m-0  " href="/page/planilla/horasnormales?planilla=<?php echo $this->planila ?>&tipo=5">Horas dominicales</a>
        </div>
        <div class=" d-flex justify-content-start mt-2 gap-2 align-items-center">
            <a class="btn-tab btn-consolidado  m-0  " href="/page/planilla/totalnomina?planilla=<?php echo $this->planila ?>">Total nómina

            </a>
            <a class="btn-tab btn-consolidado  m-0  " href="/page/planilla/consolidado?planilla=<?php echo $this->planila ?>">Consolidado nómina

            </a>
            <a class="btn-tab btn-consolidado  m-0  " href="">Recibos nómina

            </a>
            <a class="btn-tab btn-consolidado  m-0  " href="/page/planilla/limite?planilla=<?php echo $this->planila ?>">Reporte límite horas </a>
        </div>
    </div>
    <div class="content-dashboard mb-5">
        <div class="franja-paginas">
            <div class="d-flex justify-content-between">
                <div class="">
                    <div class="titulo-registro">Se encontraron <?php echo $this->register_number; ?> Registros</div>
                </div>

                <div class="d-flex gap-2">
                <div class="text-right"><button class="btn btn-sm btn-success2" onclick="verificar_planilla();">
                <i class="fa-solid fa-check-to-slot"></i> Verificar planilla</button></div>

                </div>
            </div>
        </div>

        <div class="content-table table-responsive" id="parent">
            <table id="fixTable" class=" table table-striped table-hover table-administrator text-center" style="font-size: 11px">
                <thead>

                    <tr id="cabecera2">
                        <th class="ancho1 cabeceraitem">
                            <div align="left" class="ancho1">ITEM</div>
                        </th>
                        <th class="ancho2">
                            <div align="left" class="ancho2">CÉDULA</div>
                        </th>
                        <th class="ancho3">
                            <div align="left" class="ancho3">NOMBRE</div>
                        </th>
                        <th class="ancho4">
                            <div align="left" class="ancho4">VALOR</div>
                        </th>
                        <th class="ancho5">LOC GENERAL</th>
                        <th class="ancho5">Pendientes</th>
                        <?php for ($j = $this->dia1 * 1; $j <= $this->dia2 * 1; $j++) { ?>
                            <th class="ancho5">
                                <div class="ancho5">
                                    <?php
                                    $dia = $this->anio . "-" . con_cero($this->mes) . "-" . con_cero($j);
                                    echo $j . " - ";
                                    echo $w = $this->dias[dia_semana($dia)];
                                    ?></div><input id="w_<?php echo $j; ?>" name="w_<?php echo $j; ?>" type="hidden" value="<?php echo $w; ?>" />
                            </th>
                        <?php } ?>
                        <th class="ancho2">Incap</th>
                        <th class="ancho2">Total horas</th>
                        <th class="ancho2 cabeceratotal">Total</th>
                    </tr>
                    <tr>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>
                            <div align="center"><span class="enlinea">
                                    <select name="filtro_0" id="filtro_0" class="form-select v2" onchange="filtrar_0();" multiple="multiple" style="width:100px;">
                                        <option value="" <?php if (!(strcmp("", $row_rsHoras['loc']))) {
                                                                echo "selected=\"selected\"";
                                                            } ?>></option>
                                    </select>
                                </span>
                            </div>
                        </th>
                        <th>&nbsp;</th>
                        <?php for ($j = $this->dia1 * 1; $j <= $this->dia2 * 1; $j++) { ?>


                            <th>
                                <div align="center"><span class="enlinea">
                                        <select name="filtro_<?php echo $j; ?>" id="filtro_<?php echo $j; ?>" class="v form-select w-100" onchange="filtrar('<?php echo $j; ?>');">
                                            <option value=""></option>

                                            <?php foreach ($this->list_locaciones as $key => $value) : ?>
                                                <option value="<?= $key; ?>" <?php if ($this->getObjectVariable($this->filters, 'localizacion') == $key) {
                                                                                    echo "selected";
                                                                                } ?>><?= $value; ?></option>
                                            <?php endforeach ?>
                            </th>
                        <?php } ?>


                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    echo $this->tabla;
                    ?>


                </tbody>
            </table>
        </div>
        <input type="hidden" id="page-route" value="<?php echo $this->route; ?>/changepage">
        <?php echo $this->resto ?>
    </div>
</div>
<?php if ($this->planillaAct->cerrada == 1 and ($this->planillaAct->fecha_cerrada == "" or $this->planillaAct->fecha_cerrada == "0000-00-00")) { ?>
    <script type="text/javascript">
        // Deshabilitar todos los elementos <input>
        const inputs = document.getElementsByTagName("input");
        for (let i = 0; i < inputs.length; i++) {
            inputs[i].disabled = true;
        }

        // Deshabilitar todos los elementos <select>
        const selects = document.getElementsByTagName("select");
        for (let i = 0; i < selects.length; i++) {
            selects[i].disabled = true;
        }
    </script>
<?php } ?>
<?php
$i = 0;
foreach ($this->cedulas  as  $cedula) {
    $i++;
?>
    <script type="text/javascript">
        total_horas(<?php echo $i; ?>);
    </script>

<?php } ?>



<?php
$aux = explode("-", $this->planillaAct->fecha_cerrada);
$dia_aux = $aux[2] * 1;
if ($this->planillaAct->cerrada == 1 and ($this->planillaAct->fecha_cerrada != "" or $this->planillaAct->fecha_cerrada != "0000-00-00")) { ?>
    <script type="text/javascript">
        cerrar_nomina('<?php echo $dia_aux; ?>');
    </script>
<?php } ?>

<script type="text/javascript">
    $(document).ready(function() {
        $("#fixTable").tableHeadFixer({
            left: 3
        });
        actualizar_filtro();
    });
</script>

<?php
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
        $dia_semana = 7;
    }
    return $dia_semana;
}

?>