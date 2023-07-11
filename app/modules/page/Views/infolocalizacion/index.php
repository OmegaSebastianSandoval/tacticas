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
                            <input type="date" formart="yyyy-mm-dd" class="form-control" name="fecha_inicio" value="<?php echo $this->getObjectVariable($this->filters, 'fecha_inicio') ?>" ></input>
                        </label>
                    </div>
                    <div class="col-12 col-lg-2">
                        <label>Fecha final</label>
                        <label class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text input-icono fondo-verde-claro "><i class="fas fa-calendar-alt"></i></span>
                            </div>
                            <input type="date" formart="yyyy-mm-dd" class="form-control" name="fecha_fin" value="<?php echo $this->getObjectVariable($this->filters, 'fecha_fin') ?>" ></input>
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
        
</div>