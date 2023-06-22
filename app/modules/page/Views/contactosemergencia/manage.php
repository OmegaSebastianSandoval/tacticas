<div class="container-fluid">
	<div class=" d-flex justify-content-start ">
		<h3 class="my-0"><i class="fa-regular fa-newspaper" title="Hoja de vida"></i> <?php echo $this->titlesection; ?></h3>
	</div>
	<form class="text-left" enctype="multipart/form-data" method="post" action="<?php echo $this->routeform; ?>" data-bs-toggle="validator">
		<div class="content-dashboard">
			<input type="hidden" name="csrf" id="csrf" value="<?php echo $this->csrf ?>">
			<input type="hidden" name="csrf_section" id="csrf_section" value="<?php echo $this->csrf_section ?>">
			<?php if ($this->content->contacto_emergencia_id) { ?>
				<input type="hidden" name="id" id="id" value="<?= $this->content->contacto_emergencia_id; ?>" />
			<?php } ?>
			<div class="row">
				<div class="col-4 form-group">
					<label for="contacto_emergencia_nombre" class="control-label">Nombre</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-verde "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->contacto_emergencia_nombre; ?>" name="contacto_emergencia_nombre" id="contacto_emergencia_nombre" class="form-control" required>
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-4 form-group">
					<label for="contacto_emergencia_telefono" class="control-label">Tel&eacute;fono</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-azul-claro "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->contacto_emergencia_telefono; ?>" name="contacto_emergencia_telefono" id="contacto_emergencia_telefono" class="form-control" required>
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-4 form-group">
					<label for="contacto_emergencia_parentesco" class="control-label">Parentesco</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-rojo-claro "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->contacto_emergencia_parentesco; ?>" name="contacto_emergencia_parentesco" id="contacto_emergencia_parentesco" class="form-control">

					</label>
					<div class="help-block with-errors"></div>
				</div>
				<!-- <input type="hidden" name="contacto_emergencia_empleado"  value="<?php echo $this->content->contacto_emergencia_empleado ?>"> -->
				<input type="hidden" name="contacto_emergencia_empleado" value="<?php echo $this->empleado  ?>">

			</div>
		</div>
		<div class="botones-acciones">
			<button class="btn btn-guardar" type="submit">Guardar</button>
			<a href="/page/hojadevida/manage?id=<?php echo $this->empleado ?>#pills-profile" class="btn btn-cancelar">Cancelar</a>
		</div>
	</form>
</div>