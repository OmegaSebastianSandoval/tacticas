<div class="container-fluid">
    <div class=" d-flex justify-content-start ">
        <h3 class="my-0"><i class="fa-regular fa-newspaper" title="Hoja de vida"></i> <?php echo $this->titlesection; ?></h3>
    </div>
    <div class="content-dashboard mb-5">
        <div class="franja-paginas">
            <div class="d-flex justify-content-between">
                <div class="">
                    <div class="titulo-registro">Se encontraron <?php echo $this->register_number; ?> Registros</div>
                </div>

                <div class="d-flex gap-2">

                    <div class="text-right"><a class="btn btn-sm btn-success2" href="<?php echo $this->route . "/exportarconsolidado?planilla=" . $this->planilaId; ?>"> <i class="fa-regular fa-file-excel"></i> Exportar</a></div>
                </div>
            </div>
        </div>

        <div class="content-table table-responsive">
            <table class=" table table-striped table-bordered table-hover table-administrator text-center" style="font-size: 11px">
                <thead>
                    <tr class="text-center">
                       
                        
                        <th valign="">DECIMO</th>
                        <th valign="">VACACIONES</th>
                        <th valign="">P. ANTIGUEDAD</th>
                        <th valign="">CUOTA EMPLEADO SEGURO SOCIAL</th>
                        <th valign="">CUOTA EMPLEADO SEGURO EDUCATIVO</th>
                        <th valign="">CUOTA EMPLEADOR SEGURO SOCIAL</th>
                        <th valign="">CUOTA EMPLEADOR SEGURO EDUCATIVO</th>
                        <th valign="">RIESGOS PROFESIONALES</th>
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