<div class="container-fluid">
	<div class=" d-flex justify-content-start ">
		<h3 class="my-0"><i class="fa-regular fa-newspaper" title="Hoja de vida"></i> <?php echo $this->titlesection; ?></h3>
	</div>
	<form action="<?php echo $this->route; ?>" method="post">
		<div class="content-dashboard">
			<div class="row">
				<!-- 				<div class="col-3">
					<label>Fecha inicio</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-cafe "><i class="fas fa-calendar-alt"></i></span>
						</div>
						<input type="text" class="form-control" name="fecha1" value="<?php echo $this->getObjectVariable($this->filters, 'fecha1') ?>"></input>
					</label>
				</div>
				<div class="col-3">
					<label>Fecha fin</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-azul-claro "><i class="fas fa-calendar-alt"></i></span>
						</div>
						<input type="text" class="form-control" name="fecha2" value="<?php echo $this->getObjectVariable($this->filters, 'fecha2') ?>"></input>
					</label>
				</div> -->
				<div class="col-3">
					<label>Empresa</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono fondo-morado "><i class="far fa-list-alt"></i></span>
						</div>
						<select class="form-control" name="empresa" id="empresa">
							<option value="">Todas</option>
							<?php foreach ($this->list_empresa as $key => $value) : ?>
								<option value="<?= $key; ?>" <?php if ($this->getObjectVariable($this->filters, 'empresa') ==  $key) {
																	echo "selected";
																} ?>><?= $value; ?></option>
							<?php endforeach ?>
						</select>
					</label>
				</div>
				<div class="col-3">
					<label>Meses</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono fondo-morado "><i class="far fa-list-alt"></i></span>
						</div>
						<select class="form-control" name="meses" id="meses">
							<option value=""></option>
							<?php foreach ($this->list_meses as $key => $value) : ?>
								<option value="<?= $key; ?>" <?php if ($this->getObjectVariable($this->filters, 'meses') ==  $key) {
																	echo "selected";
																} ?>><?= $value; ?></option>
							<?php endforeach ?>
						</select>
					</label>
				</div>
				<div class="col-2">
					<label>Quioncena</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono fondo-morado "><i class="far fa-list-alt"></i></span>
						</div>
						<select class="form-control" name="quincena" id="quincena">
							<option value=""></option>
							<?php foreach ($this->list_quincena as $key => $value) : ?>
								<option value="<?= $key; ?>" <?php if ($this->getObjectVariable($this->filters, 'quincena') ==  $key) {
																	echo "selected";
																} ?>><?= $value; ?></option>
							<?php endforeach ?>
						</select>
					</label>
				</div>
				<!-- 				<div class="col-3">
					<label>&iquest;Cerrar n&oacute;mina?</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono fondo-azul "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" class="form-control" name="cerrada" value="<?php echo $this->getObjectVariable($this->filters, 'cerrada') ?>"></input>
					</label>
				</div> -->
				<!-- 				<div class="col-3">
					<label>Fecha de cierre</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-verde "><i class="fas fa-calendar-alt"></i></span>
						</div>
						<input type="text" class="form-control" name="fecha_cerrada" value="<?php echo $this->getObjectVariable($this->filters, 'fecha_cerrada') ?>"></input>
					</label>
				</div> -->
				<!-- 	<div class="col-3">
					<label>L&iacute;mite de horas normales</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono fondo-rosado "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" class="form-control" name="limite_horas" value="<?php echo $this->getObjectVariable($this->filters, 'limite_horas') ?>"></input>
					</label>
				</div> -->
				<div class="col-2  d-grid align-items-end ">
					<label>&nbsp;</label>
					<button type="submit" class="btn btn-block btn-azul"> <i class="fas fa-filter"></i> Filtrar</button>
				</div>
				<div class="col-2  d-grid align-items-end ">
					<label>&nbsp;</label>
					<a class="btn btn-block btn-azul-claro " href="<?php echo $this->route; ?>?cleanfilter=1"> <i class="fas fa-eraser"></i> Limpiar Filtro</a>
				</div>
			</div>
		</div>
	</form>
	<div class="container-fluid overflow-auto">

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
	<div class="content-dashboard">
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

				<div class="d-flex gap-2">
					<div class="text-right"><a class="btn btn-sm btn-success" href="<?php echo $this->route . "\manage"; ?>"> <i class="fas fa-plus-square"></i> Crear Nuevo</a></div>


				</div>
			</div>
		</div>
		<div class="content-table table-responsive mt-1">



			<table class=" table table-striped  table-hover table-administrator text-left">
				<thead>
					<tr>
						<td>Empresa</td>

						<td>Fecha inicio</td>
						<td>Fecha fin</td>
						<td>&iquest;N&oacute;mina cerrada?</td>
						<!-- <td>Fecha de cierre</td> -->
						<!-- <td>L&iacute;mite de horas normales</td> -->
						<td></td>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($this->lists as $content) { ?>
						<?php $id =  $content->id; ?>
						<tr>
							<td><?= $content->empresa; ?>

							<td><?= $content->fecha1; ?></td>
							<td><?= $content->fecha2; ?></td>
							<td><?= $content->cerrada  == 1 ?  'Si' : 'No'; ?></td>
							<!-- <td><?= $content->fecha_cerrada; ?></td>
							<td><?= $content->limite_horas; ?></td> -->
							<td class="text-right">
								<div class="d-flex gap-1 justify-content-center align-items-center pt-1 pb-1"> <?php
																												$enlace_horas = "/page/planilla/horas?planilla=$id&tipo=1";
																												if ($content->cerrada == 1) {
																													$enlace_horas = "/page/planilla/consolidado?planilla=$id";
																												}
																												?>

									<?php if ($_SESSION['kt_login_level'] == 1) { ?>
										<a class="btn btn-azul btn-sm" href="<?php echo $this->route; ?>/manage?id=<?= $id ?>" data-bs-toggle="tooltip" data-placement="top" title="Editar"><i class="fas fa-pen-alt"></i></a>


										<?php if ($content->cerrada  != 1) { ?>

											<a class="btn btn-azul-claro btn-sm" href="<?php echo $this->route; ?>/planilla/asignarcolaboradores?planilla=<?= $id ?>" data-bs-toggle="tooltip" data-placement="top" title="Asignar colaboradores"><i class="fa-solid fa-user-plus"></i></a>
										<?php } ?>
										<a class="btn btn-azul-claro  btn-sm" href="<?php echo $enlace_horas ?>" data-bs-toggle="tooltip" data-placement="top" title="Horas"><i class="fa-solid fa-clock"></i></a>

										<span data-bs-toggle="tooltip" data-placement="top" title="Eliminar"><a class="btn btn-rojo btn-sm" data-bs-toggle="modal" data-bs-target="#modal<?= $id ?>"><i class="fas fa-trash-alt"></i></a></span>

									<?php } ?>
								</div>
								<!-- Modal -->
								<div class="modal fade text-left" id="modal<?= $id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
									<div class="modal-dialog modal-dialog-centered" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="myModalLabel">Eliminar Registro</h4>
												<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
											</div>
											<div class="modal-body">
												<div class="">¿Esta seguro de eliminar este registro?</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancelar</button>
												<a class="btn btn-danger" href="<?php echo $this->route; ?>/delete?id=<?= $id ?>&csrf=<?= $this->csrf; ?><?php echo ''; ?>">Eliminar</a>
											</div>
										</div>
									</div>
								</div>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<input type="hidden" id="csrf" value="<?php echo $this->csrf ?>"><input type="hidden" id="page-route" value="<?php echo $this->route; ?>/changepage">
	</div>
	<div class="container-fluid overflow-auto">

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