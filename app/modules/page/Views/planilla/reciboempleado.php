<style>
    .table>:not(caption)>*>* {
        padding: 3px;
        vertical-align: middle;
        display: table-cell;
        text-align: center;
    }

    .content-table {
        margin-top: 10px;
    }

    .title {
        color: var(--primary);
    
        font-weight: 600;
        font-size: 1rem;
        margin-top: 2rem;
        text-transform: uppercase;
    }
</style>
<div class="container-fluid">
    <div class=" d-flex justify-content-between align-items-center">
        <h3 class="my-0 d-flex text-start gap-2 align-items-center"><i class="fa-regular fa-newspaper" title="Planilla"></i> <?php echo $this->titlesection; ?></h3>
       
    </div>
    <div class="container-fluid  ">

       
        <div class=" d-flex justify-content-start mt-2 gap-2 align-items-center">
        
        </div>
    </div>
    
    <div class="content-dashboard mb-5 pt-0 pb-4">

        <div class="franja-paginas mb-2">
            <div class="d-flex justify-content-between">
                <div class="">
                    <!-- <div class="titulo-registro">Se encontraron <?php echo $this->register_number; ?> Registros</div> -->
                </div>

                <div class="d-flex gap-2">

                    <div class="text-right"><a class="btn btn-sm btn-success2" href="<?php echo $this->route . "/exportarreciboEmpleado" ?>"> <i class="fa-regular fa-file-excel"></i> Exportar</a></div>
                    <div class="text-right"><a target="_blank"  class="btn btn-sm d-flex align-items-center gap-2 btn-secondary" href="<?php echo $this->route . "/imprimirreciboempleado" ?>">  <i class="fa-solid fa-print"></i></i>Imprimir</a></div>
                
                </div>
            </div>
        </div>



        <?php
        echo $this->tabla;
        ?>





        <input type="hidden" id="page-route" value="<?php echo $this->route; ?>/changepage">
    </div>
</div>
