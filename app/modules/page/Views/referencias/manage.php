<div class="container-fluid mb-4">
<div class=" d-flex justify-content-start ">
		<h3 class="my-0"><i class="fa-regular fa-newspaper" title="Hoja de vida"></i> <?php echo $this->titlesection; ?></h3>
	</div>
	<form class="text-left" enctype="multipart/form-data" method="post" action="<?php echo $this->routeform; ?>" data-bs-toggle="validator">
		<div class="content-dashboard">
			<input type="hidden" name="csrf" id="csrf" value="<?php echo $this->csrf ?>">
			<input type="hidden" name="csrf_section" id="csrf_section" value="<?php echo $this->csrf_section ?>">
			<?php if ($this->content->id) { ?>
				<input type="hidden" name="id" id="id" value="<?= $this->content->id; ?>" />
			<?php } ?>
			<div class="row">
				<div class="col-4 form-group">
					<label class="control-label">Tipo</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-azul "><i class="far fa-list-alt"></i></span>
						</div>
						<select class="form-control" name="tipo" required>
							<option value="">Seleccione...</option>
							<?php foreach ($this->list_tipo as $key => $value) { ?>
								<option <?php if ($this->getObjectVariable($this->content, "tipo") == $key) {
											echo "selected";
										} ?> value="<?php echo $key; ?>" > <?= $value; ?></option>
							<?php } ?>
						</select>
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-4 form-group">
					<label for="nombre" class="control-label">Nombre</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-rojo-claro "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->nombre; ?>" name="nombre" id="nombre" class="form-control" required>
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-4 form-group">
					<label for="cargo" class="control-label">Cargo</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-verde "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->cargo; ?>" name="cargo" id="cargo" class="form-control">
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-4 form-group">
					<label for="empresa" class="control-label">Empresa</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-cafe "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->empresa; ?>" name="empresa" id="empresa" class="form-control">
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-4 form-group">
					<label for="telefono" class="control-label">Tel&eacute;fono</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-verde-claro "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->telefono; ?>" name="telefono" id="telefono" class="form-control" required>
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<input type="hidden" name="cedula" value="<?php if ($this->content->cedula) {
																echo $this->content->cedula;
															} else {
																echo $this->cc;
															} ?>">
				<div class="col-2 form-group d-grid">
					<label class="control-label">&iquest;Se llam&oacute;?</label>
					<input type="checkbox" name="se_llamo" value="1" class="form-control switch-form " <?php if ($this->getObjectVariable($this->content, 'se_llamo') == 1) {
																											echo "checked";
																										} ?>></input>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-2 form-group d-grid">
					<label class="control-label">&iquest;Se confirm&oacute;?</label>
					<input type="checkbox" name="se_confirmo" value="1" class="form-control switch-form " <?php if ($this->getObjectVariable($this->content, 'se_confirmo') == 1) {
																												echo "checked";
																											} ?>></input>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 form-group">
					<label for="descripcion" class="form-label">Descripci&oacute;n</label>
					<textarea name="descripcion" id="descripcion" class="form-control tinyeditor" rows="10"><?= $this->content->descripcion; ?></textarea>
					<div class="help-block with-errors"></div>
				</div>
			</div>
		</div>
		<div class="botones-acciones">
			<button class="btn btn-guardar" type="submit">Guardar</button>
			<a href="/page/hojadevida/manage?cc=<?php echo $this->cc ?>#pills-referencias" class="btn btn-cancelar">Cancelar</a>

		</div>
	</form>
</div>