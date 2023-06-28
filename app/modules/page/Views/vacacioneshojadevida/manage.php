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
				<div class="col-6 form-group">
					<label for="fecha1" class="control-label">Fecha inicio</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-cafe "><i class="fas fa-calendar-alt"></i></span>
						</div>
						<input type="date" value="<?php if ($this->content->fecha1) {
														echo $this->content->fecha1;
													}  ?>" name="fecha1" id="fecha1" class="form-control" required data-date-format="yyyy-mm-dd" data-date-language="es">
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-6 form-group">
					<label for="fecha2" class="control-label">Fecha fin</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-morado "><i class="fas fa-calendar-alt"></i></span>
						</div>
						<input type="date" value="<?php if ($this->content->fecha2) {
														echo $this->content->fecha2;
													}  ?>" name="fecha2" id="fecha2" class="form-control" data-date-format="yyyy-mm-dd" data-date-language="es">
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<input type="hidden" name="cedula" value="<?php if ($this->content->cedula) {
																echo $this->content->cedula;
															} else {
																echo $this->cc;
															} ?>">
			</div>
		</div>
		<div class="botones-acciones">
			<button class="btn btn-guardar" type="submit">Guardar</button>
			<?php if ($this->seccion) { ?>
				<a href="/page/vencimientovacaciones" class="btn btn-cancelar">Cancelar</a>

			<?php } else { ?>
				<a href="/page/hojadevida/manage?cc=<?php echo $this->cc ?>#pills-vacaciones" class="btn btn-cancelar">Cancelar</a>

			<?php } ?>
		</div>
	</form>
</div>