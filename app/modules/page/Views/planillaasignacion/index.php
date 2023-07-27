<div class="container-fluid mb-5">
	<div class=" d-flex justify-content-between align-items-center">
		<h3 class="my-0"><i class="fa-regular fa-newspaper" title="Hoja de vida"></i> <?php echo $this->titlesection; ?></h3>
		<a href="/page/planilla">
            <button class="btn-primary-home btn-primary-volver  mt-2" type="submit">
                <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd">
                    <path d="M2.117 12l7.527 6.235-.644.765-9-7.521 9-7.479.645.764-7.529 6.236h21.884v1h-21.883z" />
                </svg>
                <span>Regresar</span>
            </button>
        </a>
	</div>
	<!-- 	<form action="<?php echo $this->route . "?planilla=" . $this->planilla . ""; ?>" method="post">
        <div class="content-dashboard">
            <div class="row">
				<div class="col-3">
		            <label>C&eacute;dula</label>
		            <label class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text input-icono fondo-rojo-claro " ><i class="fas fa-pencil-alt"></i></span>
							</div>
		            <input type="text" class="form-control" name="cedula" value="<?php echo $this->getObjectVariable($this->filters, 'cedula') ?>"></input>
		            </label>
		        </div>
				<div class="col-3">
		            <label>Valor hora</label>
		            <label class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text input-icono fondo-azul-claro " ><i class="fas fa-pencil-alt"></i></span>
							</div>
		            <input type="text" class="form-control" name="valor_hora" value="<?php echo $this->getObjectVariable($this->filters, 'valor_hora') ?>"></input>
		            </label>
		        </div>
				<div class="col-3">
		            <label>&iquest;Sin seguridad social?</label>
		            <label class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text input-icono fondo-azul " ><i class="fas fa-pencil-alt"></i></span>
							</div>
		            <input type="text" class="form-control" name="sin_seguridad" value="<?php echo $this->getObjectVariable($this->filters, 'sin_seguridad') ?>"></input>
		            </label>
		        </div>
                <div class="col-3  d-flex align-items-end ">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-block btn-azul"> <i class="fas fa-filter"></i> Filtrar</button>
                </div>
                <div class="col-3  d-flex align-items-end ">
                    <label>&nbsp;</label>
                    <a class="btn btn-block btn-azul-claro " href="<?php echo $this->route; ?>?cleanfilter=1" > <i class="fas fa-eraser"></i> Limpiar Filtro</a>
                </div>
            </div>
        </div>
    </form> -->
	<div class="container-fluid overflow-auto">

		<div align="center">
			<ul class="pagination py-3 my-0 justify-content-center">
				
				<?php

				$url = $this->route;
				$min = $this->page - 10;
				if ($min < 0) {
					$min = 1;
				}
				$max = $this->page + 10;
				if ($this->totalpages > 1) {
					if ($this->page != 1)
						echo '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . ($this->page - 1) . '&planilla=' . ($this->planilla ) . '&planilla=' . ($this->planilla ) . '""> &laquo; Anterior </a></li>';
					for ($i = 1; $i <= $this->totalpages; $i++) {
						if ($this->page == $i)
							echo '<li class="active page-item"><a class="page-link">' . $this->page . '</a></li>';
						else {
							if ($i <= $max and $i >= $min) {
								echo '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . $i . '&planilla=' . ($this->planilla ) . '"">' . $i . '</a></li>  ';
							}
						}
					}
					if ($this->page != $this->totalpages)
						echo '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . ($this->page + 1) . '&planilla=' . ($this->planilla) . '"">Siguiente &raquo;</a></li>';
				}

				?>
			</ul>
		</div>

		<div class="content-dashboard mb-0 p-0">
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
						<div class="text-right"><a class="btn btn-sm btn-success" href="<?php echo $this->route . "\manage?planilla=" .	$this->planilla; ?>"> <i class="fas fa-plus-square"></i> Crear Nuevo</a></div>
						<div class="text-right"><a class="btn btn-sm btn-success2" href="<?php echo $this->route . "/cargar?planilla=" .	$this->planilla;  ?>"> <i class="fa-regular fa-file-excel"></i> Importar</a></div>

					</div>
				</div>
			</div>
		</div>
		<div class="content-table table-responsive mt-2">
			<table class=" table table-striped  table-hover table-administrator text-left">
				<thead>
					<tr>
					

						<td>C&eacute;dula</td>
						<td>Nombre</td>

						<td>Valor hora</td>
						<td>&iquest;Sin seguridad social?</td>
						<td width="100"></td>
					</tr>
				</thead>
				<tbody>

					<?php
				
					foreach ($this->lists as $content) { ?>
						<?php $id =  $content->id;
					

						?>
						<tr>
							

							<td><?= $content->cedula; ?></td>
							<td><?= $content->nombre1; ?></td>

							<td><?= $content->valor_hora; ?></td>
							<td><?= $content->sin_seguridad == 1 ? 'Si' : 'No' ?></td>
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
												<button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancelar</button>
												<a class="btn btn-danger" href="<?php echo $this->route; ?>/delete?id=<?= $id ?>&csrf=<?= $this->csrf; ?><?php echo '' . '&planilla=' . $this->planilla; ?>">Eliminar</a>
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
	<div class="container-fluid mt-2 overflow-auto">
		<div align="center">
			<ul class="pagination justify-content-center">
				<?php
				$url = $this->route;
				if ($this->totalpages > 1) {
					if ($this->page != 1)
						echo '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . ($this->page - 1) . '&planilla=' . $this->planilla . '"> &laquo; Anterior </a></li>';
					for ($i = 1; $i <= $this->totalpages; $i++) {
						if ($this->page == $i)
							echo '<li class="active page-item"><a class="page-link">' . $this->page . '</a></li>';
						else
							echo '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . $i . '&planilla=' . $this->planilla . '">' . $i . '</a></li>  ';
					}
					if ($this->page != $this->totalpages)
						echo '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . ($this->page + 1) . '&planilla=' . $this->planilla . '">Siguiente &raquo;</a></li>';
				}
				?>
			</ul>
		</div>
	</div>

</div>