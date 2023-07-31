<div class="container-fluid mb-5">
    <div class=" d-flex justify-content-start ">
        <h3 class="my-0"><i class="fa-regular fa-newspaper" title="Hoja de vida"></i> <?php echo $this->titlesection; ?></h3>
    </div>
    <form class="text-left" enctype="multipart/form-data" method="post" action="/page/planillaasignacion/importar" data-bs-toggle="validator">
        <div class="content-dashboard">
            <input type="hidden" name="csrf" id="csrf" value="<?php echo $this->csrf ?>">
            <input type="hidden" name="csrf_section" id="csrf_section" value="<?php echo $this->csrf_section ?>">
            <?php if ($this->content->id) { ?>
                <input type="hidden" name="id" id="id" value="<?= $this->content->id; ?>" />
            <?php } ?>
            <div class="row">
                <input type="hidden" name="planilla" value="<?php if ($this->content->planilla) {
                                                                echo $this->content->planilla;
                                                            } else {
                                                                echo $this->planilla;
                                                            } ?>">


                <div class="col-12 col-md-8 form-group ">
                    <label for="colaboradores"> <a class="text-primary " href="/skins/page/files/ejemplo.xls" download>Ejemplo de cargue</a></label>
                    <input type="file" name="colaboradores" required id="colaboradores" class="form-control  file-document" data-buttonName="btn-primary" onchange="validardocumento('colaboradores');" accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint, text/plain, application/pdf">

                </div>
              
                <div class="col-12 col-md-4 form-group d-flex align-items-end justify-content-end gap-3 ">
                    <button class="btn btn-guardar" type="submit">Importar</button>
                    <a href="<?php echo $this->route; ?>?planilla=<?php if ($this->content->planilla) {
                                                                        echo $this->content->planilla;
                                                                    } else {
                                                                        echo $this->planilla;
                                                                    } ?>" class="btn btn-cancelar">Cancelar</a>
                </div>

            </div>

    </form>
</div>




<script>
    Fancybox.bind("[data-fancybox]", {
        //
    })

    function set_cedula(cedula, nombre) {
        document.getElementById('cedula').value = cedula;
        document.getElementById('nombre').value = nombre;
        var instance = Fancybox.getInstance();
        if (instance) {
            instance.close();
        }
    }
</script>