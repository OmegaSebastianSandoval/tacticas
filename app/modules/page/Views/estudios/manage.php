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
				<div class="col-12 col-lg-3 form-group">
					<label for="institucion" class="control-label">Instituci&oacute;n</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-rojo-claro "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->institucion; ?>" name="institucion" id="institucion" class="form-control" required>
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-lg-3 form-group">
					<label for="titulo" class="control-label">Titulo</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-verde-claro "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->titulo; ?>" name="titulo" id="titulo" class="form-control" required>
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-lg-3 form-group">
					<label for="fecha1" class="control-label">Fecha de inicio</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-morado "><i class="fas fa-calendar-alt"></i></span>
						</div>
						<input type="text" value="<?php if ($this->content->fecha1) {
														echo $this->content->fecha1;
													} else {
														echo date('Y-m-d');
													} ?>" name="fecha1" id="fecha1" class="form-control" required data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-language="es">
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-lg-3 form-group">
					<label for="fecha2" class="control-label">Fecha de finalizaci&oacute;n</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-azul "><i class="fas fa-calendar-alt"></i></span>
						</div>
						<input type="text" value="<?php if ($this->content->fecha2) {
														echo $this->content->fecha2;
													} else {
														echo date('Y-m-d');
													} ?>" name="fecha2" id="fecha2" class="form-control" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-language="es">
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<!-- <input type="hidden" name="cedula" value="<?php echo $this->content->cedula ?>"> -->
				<input type="hidden" name="cedula" value="<?php echo $this->cedula ?>">
			
				<div class="col-12 form-group">
					<label for="descripcion" class="form-label">Descripci&oacute;n</label>
					<textarea name="descripcion" id="descripcion" class="form-control tinyeditor" rows="10" ><?= $this->content->descripcion; ?></textarea>
					<div class="help-block with-errors"></div>
				</div>
			</div>
		</div>
		<div class="botones-acciones">
			<button class="btn btn-guardar" type="submit">Guardar</button>
			<a href="/page/hojadevida/manage?cc=<?php echo $this->cedula ?>#pills-contact" class="btn btn-cancelar">Cancelar</a>
		</div>
	</form>
</div>