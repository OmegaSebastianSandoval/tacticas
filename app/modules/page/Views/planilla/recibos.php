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
            <a class="btn-tab btn-consolidado m-0  " href="/page/planilla/horasnormales?planilla=<?php echo $this->planilla ?>&tipo=1">Horas normales </a>
            <a class="btn-tab btn-consolidado m-0  " href="/page/planilla/horasnormales?planilla=<?php echo $this->planilla ?>&tipo=2">Horas adicionales diurnas</a>
            <a class="btn-tab btn-consolidado m-0  " href="/page/planilla/horasnormales?planilla=<?php echo $this->planilla ?>&tipo=3">Horas adicionales nocturnas</a>
            <a class="btn-tab btn-consolidado m-0  " href="/page/planilla/horasnormales?planilla=<?php echo $this->planilla ?>&tipo=4">Horas recargo festivo</a>
            <a class="btn-tab btn-consolidado m-0  " href="/page/planilla/horasnormales?planilla=<?php echo $this->planilla ?>&tipo=5">Horas dominicales</a>
        </div>
        <div class=" d-flex justify-content-start mt-2 gap-2 align-items-center">
            <a class="btn-tab btn-consolidado  m-0  " href="/page/planilla/totalnomina?planilla=<?php echo $this->planilla ?>">Total nómina

            </a>
            <a class="btn-tab btn-consolidado  m-0  " href="/page/planilla/consolidado?planilla=<?php echo $this->planilla ?>">Consolidado nómina

            </a>
            <a class="btn-tab btn-consolidado active m-0  " href="/page/planilla/recibos?planilla=<?php echo $this->planilla ?>">Recibos nómina

            </a>
            <a class="btn-tab btn-consolidado  m-0  " href="/page/planilla/limite?planilla=<?php echo $this->planilla ?>">Reporte límite horas </a>
        </div>
    </div>
    <form action="/page/planilla/recibos?planilla=<?php echo $this->planilla ?>" class="form-dashboard" method="post">
        <div class="content-dashboard pb-0 mb-0">
            <div class="row">
                <div class="col-3">
                    <label>Nombre</label>
                    <label class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text input-icono  fondo-cafe "><i class="fas fa-calendar-alt"></i></span>
                        </div>
                        <input type="text" class="form-control" name="nombre" value="<?php if ($this->nombre) {
                                                                                            echo $this->nombre;
                                                                                        } ?>"></input>
                    </label>
                </div>
                <div class="col-3">
                    <label>Cédula</label>
                    <label class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text input-icono  fondo-azul-claro "><i class="fas fa-calendar-alt"></i></span>
                        </div>
                        <input type="text" class="form-control" name="cedula" value="<?php if ($this->cedula) {
                                                                                            echo $this->cedula;
                                                                                        }  ?>"></input>
                    </label>
                </div>
                <div class="col-2">
                    <label>Método de pago</label>
                    <label class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text input-icono  fondo-azul-claro "><i class="fas fa-calendar-alt"></i></span>
                        </div>
                        <select class="form-control"  id="metodo_pago" name="metodo_pago">
									<option value="">Seleccione...</option>
									<?php foreach ($this->list_metodo_pago as $key => $value) { ?>
										<option <?php if ($this->metodo_pago == $key) {
													echo "selected";
												} ?> value="<?php echo $key; ?>" /> <?= $value; ?></option>
									<?php } ?>
								</select>
                    </label>
                </div>


                <div class="col-2  d-grid align-items-end ">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-block btn-azul"> <i class="fas fa-filter"></i> Filtrar</button>
                </div>
                <div class="col-2  d-grid align-items-end ">
                    <label>&nbsp;</label>
                    <a class="btn btn-block btn-azul-claro " href="/page/planilla/recibos?cleanfilter=1&planilla=<?php echo $this->planilla ?>"> <i class="fas fa-eraser"></i> Limpiar Filtro</a>
                </div>
            </div>
        </div>
    </form>
    <div class="content-dashboard mb-5 pb-4">

        <div class="franja-paginas mb-2">
            <div class="d-flex justify-content-between">
                <div class="">
                    <div class="titulo-registro">Se encontraron <?php echo $this->register_number; ?> Registros</div>
                </div>

                <div class="d-flex gap-2">

                    <div class="text-right"><a class="btn btn-sm btn-success2" href="<?php echo $this->route . "/exportarrecibo?planilla=" . $this->planilla; ?>&nombre=<?php echo $this->nombre; ?>&cedula=<?php echo $this->cedula; ?>&metodo_pago=<?php echo $this->metodo_pago; ?>"> <i class="fa-regular fa-file-excel"></i> Exportar</a></div>
                    <div class="text-right"><a target="_blank"  class="btn btn-sm d-flex align-items-center gap-2 btn-secondary" href="<?php echo $this->route . "/imprimirRecibo?planilla=" . $this->planilla; ?>&nombre=<?php echo $this->nombre; ?>&cedula=<?php echo $this->cedula; ?>&metodo_pago=<?php echo $this->metodo_pago; ?>">  <i class="fa-solid fa-print"></i></i>Imprimir</a></div>
                
                </div>
            </div>
        </div>



        <?php
        echo $this->tabla;
        ?>





        <input type="hidden" id="page-route" value="<?php echo $this->route; ?>/changepage">
    </div>
</div>
