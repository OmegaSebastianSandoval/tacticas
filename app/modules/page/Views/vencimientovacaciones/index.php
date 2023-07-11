<div class="container-fluid">
  
    <div class=" d-flex justify-content-between ">
        <h3 class="my-0"><i class="fa-solid fa-calendar-xmark" title="Vencimientos"></i>
            <?php echo $this->titlesection; ?>
        </h3>
        <a href="/page/vencimientos">
            <button class="btn-primary-home btn-primary-volver  mt-2" type="submit">
                <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd">
                    <path d="M2.117 12l7.527 6.235-.644.765-9-7.521 9-7.479.645.764-7.529 6.236h21.884v1h-21.883z" />
                </svg>
                <span>Regresar</span>
            </button>
        </a>
    </div>
	<?php if (Session::getInstance()->get("kt_login_level") && Session::getInstance()->get("kt_login_level") != 2) {  ?>
		<form action="<?php echo $this->route; ?>" method="post">
			<div class="content-dashboard p-0">
				<div class="row">

					<div class="col-12 col-lg-2">
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
						<label>N° de documento</label>
						<label class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text input-icono fondo-verde-claro "><i class="fas fa-pencil-alt"></i></span>
							</div>
							<input type="text" class="form-control" name="documento" value="<?php echo $this->getObjectVariable($this->filters, 'documento') ?>"></input>
						</label>
					</div>
					<div class="col-12 col-lg-4">
						<label>Nombre</label>
						<label class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text input-icono fondo-verde-claro "><i class="fas fa-pencil-alt"></i></span>
							</div>
							<input type="text" class="form-control" name="nombre" value="<?php echo $this->getObjectVariable($this->filters, 'nombre') ?>"></input>
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
	<?php 	 }  ?>


	<?php if ($this->totalpages > 1) { ?>


		<div align="center">
			<ul class="pagination py-0 my-0 justify-content-center">
				<?php
				$url = $this->route;
				$min = $this->page - 10;
				if ($min < 0) {
					$min = 1;
				}
				$max = $this->page + 10;
				if ($this->totalpages > 1) {
					if ($this->page != 1)
						echo '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . ($this->page - 1) . '"> &laquo; Anterior </a></li>';
					for ($i = 1; $i <= $this->totalpages; $i++) {
						if ($this->page == $i)
							echo '<li class="active page-item"><a class="page-link">' . $this->page . '</a></li>';
						else {
							if ($i <= $max and $i >= $min) {
								echo '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . $i . '">' . $i . '</a></li>  ';
							}
						}
					}
					if ($this->page != $this->totalpages)
						echo '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . ($this->page + 1) . '">Siguiente &raquo;</a></li>';
				}
				?>
			</ul>
		</div>
	<?php } ?>

	<div class="content-dashboard p-0">
		<div class="franja-paginas">
			<div class="d-flex justify-content-between">
				<div class="">
					<div class="titulo-registro">Se encontraron <?php echo $this->register_number; ?> Registros</div>
				</div>
				<div class="d-flex gap-2 align-items-ce3nter">
					<div>
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
					</div>
				</div>

			</div>
		</div>
		<div class="content-table table-responsive">
			<table class=" table table-striped  table-hover table-administrator text-center">
				<thead>
					<tr class="text-center">
						<td>Nombre</td>
						<td>Apellidos</td>
						<td>Documento</td>
						<td>Empresa</td>


						<td>Fecha Final</td>
						<td>Total días</td>
						<td>Días desde últimas vacaciones</td>

						<td width="100"></td>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($this->lists as $content) { ?>
						<?php $id =  $content->id; ?>
						<tr>
							<td><?= $content->nombres; ?></td>
							<td><?= $content->apellidos; ?></td>
							<td><?= $content->documento; ?></td>
							<td><?= $this->list_empresa[$content->empresa]; ?>
							<td><?= $content->fecha2; ?></td>
							<td><?php if ($content->fecha1 != "") {
									echo diferencia($content->fecha2, $content->fecha1);
								} ?></td>

							<td class="<?php echo vencimiento2($content->fecha2); ?>">
								<?php echo diferencia(date("Y-m-d"), $content->fecha2); ?> </td>



							<td class="text-right">
								<div>
									<a class="btn btn-azul btn-sm" href="/page/vacacioneshojadevida/manage?id=<?= $id ?>&seccion=1" data-bs-toggle="tooltip" data-placement="top" title="Editar"><i class="fas fa-pen-alt"></i></a>

								</div>


							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<input type="hidden" id="csrf" value="<?php echo $this->csrf ?>"><input type="hidden" id="order-route" value="<?php echo $this->route; ?>/order"><input type="hidden" id="page-route" value="<?php echo $this->route; ?>/changepage">

	</div>
	<div class="container mb-5 overflow-auto">

		<div align="center">
			<ul class="pagination py-0 my-0 justify-content-center">
				<?php
				$url = $this->route;
				$min = $this->page - 10;
				if ($min < 0) {
					$min = 1;
				}
				$max = $this->page + 10;
				if ($this->totalpages > 1) {
					if ($this->page != 1)
						echo '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . ($this->page - 1) . '"> &laquo; Anterior </a></li>';
					for ($i = 1; $i <= $this->totalpages; $i++) {
						if ($this->page == $i)
							echo '<li class="active page-item"><a class="page-link">' . $this->page . '</a></li>';
						else {
							if ($i <= $max and $i >= $min) {
								echo '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . $i . '">' . $i . '</a></li>  ';
							}
						}
					}
					if ($this->page != $this->totalpages)
						echo '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . ($this->page + 1) . '">Siguiente &raquo;</a></li>';
				}
				?>
			</ul>
		</div>
	</div>

</div>
<?php
function diferencia($fecha_i, $fecha_f)
{
	$date_i = new DateTime($fecha_i);
	$date_f = new DateTime($fecha_f);
	$interval = $date_i->diff($date_f);
	$dias = $interval->format('%a');
	return $dias;
}
function vencimiento2($fecha)
{
	$hoy = date("Y-m-d");
	$clase = 'verde';
	$dias = diferencia($hoy, $fecha);
	if ($dias >= 3 * 30) {
		$clase = 'verde';
	}
	if ($dias >= 6 * 30) {
		$clase = 'azul';
	}
	if ($dias >= 8 * 30) {
		$clase = 'amarillo';
	}
	if ($dias >= 10 * 30) {
		$clase = 'naranja';
	}
	if ($dias >= 11 * 30) {
		$clase = 'rojo';
	}
	return $clase;
}


?>