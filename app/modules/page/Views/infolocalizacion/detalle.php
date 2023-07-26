<div class="container-fluid">
    <div class="content-table table-responsive">
        <table class=" table table-striped  table-hover table-administrator text-center">
            <thead style="font-size: 0.8rem;">
                <tr class="text-center">
                    <th>LOCALIZACIÓN</th>
                    <th>TOTAL HORAS</th>
                    <th>EMPRESA</th>
                    <th>FECHA1 </th>
                    <th>FECHA2</th>
                </tr>
            <tbody>
                <?php echo $this->tabla ?>
            </tbody>
            </thead>
        </table>
    </div>
</div>

<style>
    footer {
        display: none;
    }

    .contenedor-general {
      
        margin-top: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
         padding: 20px ; 
         margin-left: 0px; 
    }
    .content-table{
        margin-top: 0px;
    }
</style>