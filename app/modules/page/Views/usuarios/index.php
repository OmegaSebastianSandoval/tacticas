<div class="container-fluid">
	<div class=" d-flex justify-content-start ">
		<h3 class="my-0"><i class="fas fa-user" title="Usuarios"></i> <?php echo $this->titlesection; ?></h3>
	</div>
	<form action="<?php echo $this->route; ?>" method="post">
		<div class="content-dashboard">
			<div class="row">
				<div class="col-12 col-lg-2">
					<label>Nombre</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono fondo-morado "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" class="form-control" name="nombre" value="<?php echo $this->getObjectVariable($this->filters, 'nombre') ?>"></input>
					</label>
				</div>
				<!-- 				<div class="col-12 col-lg-2">
					<label>Usuario</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono fondo-verde-claro "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" class="form-control" name="usuario" value="<?php echo $this->getObjectVariable($this->filters, 'usuario') ?>"></input>
					</label>
				</div> -->
				<!-- 
				<div class="col-12 col-lg-2">
					<label>Email</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono fondo-azul "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" class="form-control" name="email" value="<?php echo $this->getObjectVariable($this->filters, 'email') ?>"></input>
					</label>
				</div> -->
				<div class="col-12 col-lg-2">
					<label>Nivel</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono fondo-azul-claro "><i class="far fa-list-alt"></i></span>
						</div>
						<select class="form-control" name="nivel">
							<option value="">Todas</option>
							<?php foreach ($this->list_nivel as $key => $value) : ?>
								<option value="<?= $key; ?>" <?php if ($this->getObjectVariable($this->filters, 'nivel') ==  $key) {
																	echo "selected";
																} ?>><?= $value; ?></option>
							<?php endforeach ?>
						</select>
					</label>
				</div>
				<div class="col-12 col-lg-2">
					<label>Estado</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono fondo-verde "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<select class="form-control" name="activo">
							<option value="">Todos</option>

							<?php foreach ($this->list_estado as $key => $value) : ?>
								<option value="<?= $key; ?>" <?php if ($this->getObjectVariable($this->filters, 'activo') ==  $key) {
																	echo "selected";
																} ?>><?= $value; ?></option>
							<?php endforeach ?>
						</select>
						<!-- <input type="text" class="form-control" name="activo" value="<?php echo $this->getObjectVariable($this->filters, 'activo') ?>"></input> -->
					</label>
				</div>
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
				<div class="col-12 col-lg-2  d-grid align-items-end ">
					<label>&nbsp;</label>
					<button type="submit" class="btn btn-block btn-azul"> <i class="fas fa-filter"></i> Filtrar</button>
				</div>
				<div class="col-12 col-lg-2  d-grid align-items-end ">

					<a class="btn btn-block btn-azul-claro " href="<?php echo $this->route; ?>?cleanfilter=1"> <i class="fas fa-eraser"></i> Limpiar Filtro</a>
				</div>

			</div>
		</div>
	</form>
	<?php if ($this->totalpages > 1) { ?>


		<div align="center">
			<ul class="pagination py-0 my-0 justify-content-center">
				<?php
				$url = $this->route;
				if ($this->totalpages > 1) {
					if ($this->page != 1)
						echo '<li class="page-item" ><a class="page-link"  href="' . $url . '?page=' . ($this->page - 1) . '"> &laquo; Anterior </a></li>';
					for ($i = 1; $i <= $this->totalpages; $i++) {
						if ($this->page == $i)
							echo '<li class="active page-item"><a class="page-link">' . $this->page . '</a></li>';
						else
							echo '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . $i . '">' . $i . '</a></li>  ';
					}
					if ($this->page != $this->totalpages)
						echo '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . ($this->page + 1) . '">Siguiente &raquo;</a></li>';
				}
				?>
			</ul>
		</div>
	<?php } ?>

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
							<option value="20" <?php if ($this->pages == 20) {
													echo 'selected';
												} ?>>20</option>
							<option value="30" <?php if ($this->pages == 30) {
													echo 'selected';
												} ?>>30</option>
							<option value="50" <?php if ($this->pages == 50) {
													echo 'selected';
												} ?>>50</option>
							<option value="100" <?php if ($this->pages == 100) {
													echo 'selected';
												} ?>>100</option>
						</select>
					</div>
				</div>

				<div class="">
					<div class="text-right"><a class="btn btn-sm btn-success" href="<?php echo $this->route . "\manage"; ?>"> <i class="fas fa-plus-square"></i> Crear Nuevo</a></div>
				</div>
			</div>
		</div>
		<div class="content-table table-responsive">
			<table class=" table table-striped  table-hover table-administrator text-center">
				<thead>
					<tr class="text-center">
						<td>Nombre</td>
						<td>Usuario</td>
					
						<td>Email</td>
						<td>Nivel</td>
						<td>Activo</td>
						<td>Empresa</td>
						<td width="100"></td>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($this->lists as $content) { ?>
						<?php $id =  $content->id; ?>
						<tr>
							<td><?= $content->nombre; ?></td>
							<td><?= $content->usuario; ?></td>
							
							<td><?= $content->email; ?></td>
							<td><?= $this->list_nivel[$content->nivel]; ?>
							<td><?= $content->activo; ?></td>
							<td><?= $this->list_empresa[$content->empresa]; ?>
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
	<div align="center">
		<ul class="pagination pagination-end justify-content-center">
			<?php
			$url = $this->route;
			if ($this->totalpages > 1) {
				if ($this->page != 1)
					echo '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . ($this->page - 1) . '"> &laquo; Anterior </a></li>';
				for ($i = 1; $i <= $this->totalpages; $i++) {
					if ($this->page == $i)
						echo '<li class="active page-item"><a class="page-link">' . $this->page . '</a></li>';
					else
						echo '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . $i . '">' . $i . '</a></li>  ';
				}
				if ($this->page != $this->totalpages)
					echo '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . ($this->page + 1) . '">Siguiente &raquo;</a></li>';
			}
			?>
		</ul>
	</div>
</div>