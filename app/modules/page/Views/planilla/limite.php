<style>
    .table>:not(caption)>*>* {
        padding: 3px;
        vertical-align: middle;
        display: table-cell;
        text-align: center;
    }
</style>
<div class="container-fluid">
    <div class=" d-flex justify-content-between align-items-center">
        <h3 class="my-0 d-flex text-start gap-2 align-items-center"><i class="fa-regular fa-newspaper" title="Hoja de vida"></i> <?php echo $this->titlesection; ?></h3>
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
            <a class="btn-tab btn-consolidado  m-0  " href="/page/planilla/recibos?planilla=<?php echo $this->planila?>">Recibos nómina

            </a>
            <a class="btn-tab btn-consolidado active m-0  " href="/page/planilla/limite?planilla=<?php echo $this->planila ?>">Reporte límite horas </a>
        </div>
    </div>

    <div class="content-dashboard mb-5">
        <div class="franja-paginas">
            <div class="d-flex justify-content-between">
                <div class="">
                    <div class="titulo-registro">Se encontraron <?php echo $this->register_number; ?> Registros</div>
                </div>

                <div class="d-flex gap-2">

                    <div class="text-right"><a class="btn btn-sm btn-success2" href="<?php echo $this->route . "/exportarlimite?planilla=" . $this->planila; ?>"> <i class="fa-regular fa-file-excel"></i> Exportar XLS</a></div>

                    <div class="text-right"><a class="btn btn-sm btn-success2" href="<?php echo $this->route . "/exportarlimitexlsx?planilla=" . $this->planila; ?>"> <i class="fa-regular fa-file-excel"></i> Exportar XLSX</a></div>
                </div>
            </div>
        </div>

        <div class="content-table table-responsive">
            <table class=" table table-striped table-bordered table-hover table-administrator text-center" style="font-size: 11px">
                <thead>
                    <tr class="text-center">

                        <th>ITEM</th>
                        <th>CÉDULA</th>
                        <th> NOMBRE</th>
                        <th>LIMITE HORAS NORMALES</th>
                        <th> HORAS NORMALES</th>
                        <th> HORAS EXTRA</th>
                        <th>HORAS NOCTURNAS</th>
                        <th>FESTIVOS</th>
                        <th>LIMITE HORA DOMINICAL</th>
                        <th> HORAS DOMINICALES</th>
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
    </div>
</div>