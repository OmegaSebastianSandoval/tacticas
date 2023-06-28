<div class="container-fluid">
<div class=" d-flex justify-content-start mb-4 ">
        <a href="/page/reportes">
            <button class="btn-primary mt-2" type="submit">
                <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd">
                    <path d="M2.117 12l7.527 6.235-.644.765-9-7.521 9-7.479.645.764-7.529 6.236h21.884v1h-21.883z" />
                </svg>
                <span>Regresar</span>
            </button>
        </a>
    </div>
    <div class=" d-flex justify-content-start ">
        <h3 class="my-0"><i class="fa-solid fa-rotate"></i> <?php echo $this->titlesection; ?></h3>
    </div>
    <!-- <div class="row d-flex justify-content-center"> -->
    <?php if (Session::getInstance()->get("kt_login_level") && Session::getInstance()->get("kt_login_level") != 2) {  ?>

    <form action="<?php echo $this->route; ?>" method="post">
            <div class="content-dashboard p-0">
                <div class="row">

                    <div class="col-12 col-lg-6">
                        <label>Empresa</label>
                        <label class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text input-icono fondo-rosado "><i class="far fa-list-alt"></i></span>
                            </div>
                            <select class="form-control" name="empresa">
                                <option value="">Todas</option>
                                <?php foreach ($this->list_empresa as $key => $value) : ?>
                                    <option value="<?= $key; ?>" <?php if ($this->getObjectVariable($this->filters, 'empresa') ==  $key) {
                                                                        echo "selected";
                                                                    } ?>><?= $value; ?></option>
                                <?php endforeach ?>
                            </select>
                        </label>
                    </div>
                  
                    <div class="col-12 col-lg-2">
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
    <?php      }  ?>



    <div class="content-dashboard">
	<div class="franja-paginas">
			<div class="d-flex justify-content-between">
				<div class="">
					<div class="titulo-registro">Se encontraron <?php echo $this->register_number; ?> Registros</div>
				</div>
				
			</div>
		</div>
        <div class="content-table table-responsive">
            <table class=" table table-striped  table-hover table-administrator text-center">
                <thead>
                    <tr class="text-center">
                        <td>Mes</td>
                        <td>Ingresos</td>
                        <td>Retiros</td>
                        <td>Indicador rotación</td>



                    </tr>
                </thead>
                <tbody>
                    <?php
                    echo $this->tabla;
                    echo $this->tabla2

                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>