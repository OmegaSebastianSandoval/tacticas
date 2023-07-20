<div class="container-fluid">
	<div class=" d-flex justify-content-start ">
		<h3 class="my-0"><i class="fa-regular fa-newspaper" title="Hoja de vida"></i> <?php echo $this->titlesection; ?></h3>
	</div>
	<div class="content-table table-responsive">
		<table class=" table table-striped  table-hover table-administrator text-center">
			<thead>
				<tr class="text-center">
					<td>Total personas</td>
					<td>Personas activas</td>
					<td>Personas retiradas</td>
					<td>Personas con contrato indefinido</td>
					<td>Personas con contrato definido</td>
					<td>Personas con contrato de servicios</td>

				</tr>
			</thead>
			<tbody>
				<td><?php print_r($this->totalPersonas); ?></td>
				<td><?php print_r($this->activas); ?></td>
				<td><?php print_r($this->retiradas); ?></td>
				<td><?php print_r($this->contratoIndefinido); ?></td>
				<td><?php print_r($this->contratoDefinido); ?></td>
				<td><?php print_r($this->contratoServicios); ?></td>

		</table>
	</div>


	<form action="<?php echo $this->route; ?>" method="post">
		<div class="content-dashboard">
			<div class="row">

				<!-- <div class="col-12 col-md-4 col-lg-3">
					<label>Foto</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono fondo-rojo-claro "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" class="form-control" name="foto" value="<?php echo $this->getObjectVariable($this->filters, 'foto') ?>"></input>
					</label>
				</div> -->
				<div class="col-12 col-md-4 col-lg-2">
					<label>Nombres</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono fondo-morado "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" class="form-control" name="nombres" value="<?php echo $this->getObjectVariable($this->filters, 'nombres') ?>"></input>
					</label>
				</div>
				<!-- 				<div class="col-12 col-md-4 col-lg-2">
					<label>Apellidos</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono fondo-verde "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" class="form-control" name="apellidos" value="<?php echo $this->getObjectVariable($this->filters, 'apellidos') ?>"></input>
					</label>
				</div> -->
				<div class="col-12 col-md-4 col-lg-2">
					<label>Tipo documento</label>
					<label class="input-group">

						<select class="form-control" name="tipo_documento">
							<option value="">Todas</option>
							<?php foreach ($this->list_tipo_documento as $key => $value) : ?>
								<option value="<?= $key; ?>" <?php if ($this->getObjectVariable($this->filters, 'tipo_documento') ==  $key) {
																	echo "selected";
																} ?>><?= $value; ?></option>
							<?php endforeach ?>
						</select>
					</label>
				</div>
				<div class="col-12 col-md-4 col-lg-2">
					<label>Empresa</label>
					<label class="input-group">

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
				<div class="col-12 col-md-4 col-lg-2">
					<label>Tipo de contrato</label>
					<label class="input-group">

						<select class="form-control" name="tipo_contrato">
							<option value="">Todas</option>
							<?php foreach ($this->list_tipo_contrato as $key => $value) : ?>
								<option value="<?= $key; ?>" <?php if ($this->getObjectVariable($this->filters, 'tipo_contrato') ==  $key) {
																	echo "selected";
																} ?>><?= $value; ?></option>
							<?php endforeach ?>
						</select>
					</label>
				</div>
				<div class="col-12 col-md-4 col-lg-2">
					<label>N° de documento</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono fondo-verde-claro "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" class="form-control" name="documento" value="<?php echo $this->getObjectVariable($this->filters, 'documento') ?>"></input>
					</label>
				</div>
				<div class="col-12 col-lg-1 d-flex align-items-end   px-0 mx-0">

					<button type="submit" class="btn  w-100 btn-azul" style="font-size: 14px;"> <i class="fas fa-filter"></i> Filtrar</button>
				</div>

				<div class="col-12 col-lg-1 d-flex align-items-end     px-0 mx-0">
					<label>&nbsp;</label>
					<a class="btn  btn-azul-claro w-100" href="<?php echo $this->route; ?>?cleanfilter=1" style="font-size: 14px;"> <i class="fas fa-eraser"></i> Limpiar</a>
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
					<div class="text-right"><a class="btn btn-sm btn-success2" href="<?php echo $this->route . "/exportar"; ?>"> <i class="fa-regular fa-file-excel"></i> Exportar</a></div>

				</div>
			</div>
		</div>
		<div class="content-table table-responsive">
			<table class=" table table-striped  table-hover table-administrator text-center">
				<thead>
					<tr class="text-center">
						<td>Foto</td>
						<td>Nombres</td>
						<td>Apellidos</td>
						<td>Tipo documento</td>
						<td>N&uacute;mero de documento</td>
						<td>Empresa</td>
						<td>Tipo de contrato</td>

						<td width="100"></td>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($this->lists as $content) { ?>
						<?php $id =  $content->id; ?>
						<tr>
							<td>
								<?php if ($content->foto) { ?>
									<img src="/images/<?= $content->foto; ?>" class="img-thumbnail thumbnail-administrator" />
								<?php } ?>

							</td>
							<td><?= $content->nombres; ?></td>
							<td><?= $content->apellidos; ?></td>
							<td><?= $this->list_tipo_documento[$content->tipo_documento]; ?>
							<td><?= $content->documento; ?></td>
							<td><?= $this->list_empresa[$content->empresa]; ?></td>
							<td><?= $this->list_tipo_contrato[$content->tipo_contrato]; ?></td>

							<td class="text-right">
								<div>
									<a class="btn btn-azul btn-sm" href="<?php echo $this->route; ?>/manage?id=<?= $id ?>" data-bs-toggle="tooltip" data-placement="top" title="Editar"><i class="fas fa-pen-alt"></i></a>
									<span data-bs-toggle="tooltip" data-placement="top" title="Eliminar"><a class="btn btn-rojo btn-sm" data-bs-toggle="modal" data-bs-target="#modal<?= $id ?>"><i class="fas fa-trash-alt"></i></a></span>
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
												<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
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
	<div class="container mb-5 overflow-auto">

		<div align="center">
			<ul class="pagination pagination-end justify-content-center">
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