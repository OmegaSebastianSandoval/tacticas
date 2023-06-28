<div class="container-fluid">
<div class=" d-flex justify-content-start ">
		<h3 class="my-0"><i class="fa-regular fa-newspaper" title="Hoja de vida"></i> <?php echo $this->titlesection; ?></h3>
	</div>
	<form class="text-left" enctype="multipart/form-data" method="post" action="<?php echo $this->routeform; ?>" data-bs-toggle="validator">
		<div class="content-dashboard">
			<input type="hidden" name="csrf" id="csrf" value="<?php echo $this->csrf ?>">
			<input type="hidden" name="csrf_section" id="csrf_section" value="<?php echo $this->csrf_section ?>">
			<?php if ($this->content->cargo_id) { ?>
				<input type="hidden" name="id" id="id" value="<?= $this->content->cargo_id; ?>" />
			<?php } ?>
			<div class="row">
				<div class="col-9 form-group">
					<label for="cargo_nombre" class="control-label">Nombre</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-rosado "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->cargo_nombre; ?>" name="cargo_nombre" id="cargo_nombre" class="form-control" required>
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-3 form-group d-grid">
					<label class="control-label">Activo</label>
					<input type="checkbox" name="cargo_estado" value="1" class="form-control switch-form " <?php if ($this->getObjectVariable($this->content, 'cargo_estado') == 1) {
																												echo "checked";
																											} ?>></input>
					<div class="help-block with-errors"></div>
				</div>
			</div>
		</div>
		<div class="botones-acciones">
			<button class="btn btn-guardar" type="submit">Guardar</button>
			<a href="<?php echo $this->route; ?>" class="btn btn-cancelar">Cancelar</a>
		</div>
	</form>
</div>