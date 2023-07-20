<div class="container-fluid">
	<div class=" d-flex justify-content-start ">
		<h3 class="my-0"><i class="fas fa-user" title="Usuarios"></i> <?php echo $this->titlesection; ?></h3>
	</div>
	<form class="text-left" enctype="multipart/form-data" method="post" action="<?php echo $this->routeform; ?>" data-bs-toggle="validator">
		<div class="content-dashboard">
			<input type="hidden" name="csrf" id="csrf" value="<?php echo $this->csrf ?>">
			<input type="hidden" name="csrf_section" id="csrf_section" value="<?php echo $this->csrf_section ?>">
			<?php if ($this->content->id) { ?>
				<input type="hidden" name="id" id="id" value="<?= $this->content->id; ?>" />
			<?php } ?>

			<div class="row">
				<div class="col-12 col-md-6 col-lg-3 form-group">
					<label for="nombre" class="control-label">Nombre</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-rojo-claro "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->nombre; ?>" name="nombre" id="nombre" class="form-control" required>
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-md-6 col-lg-3 form-group">
					<label for="usuario" class="control-label">Usuario</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-azul "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" autocomplete="off" value="<?= $this->content->usuario; ?>" name="usuario" id="usuario" class="form-control" required>
					</label>
					<div class="help-block with-errors"></div>
				</div>


				<div class="col-12 col-md-6 col-lg-3 form-group">
					<label for="email" class="control-label">Email</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-verde-claro "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->email; ?>" name="email" id="email" class="form-control">
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-md-6 col-lg-2 form-group">
					<label class="control-label">Nivel</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-cafe "><i class="far fa-list-alt"></i></span>
						</div>
						<select class="form-control" onchange="selectorUsuario()" name="nivel" id="nivel" required>
							<option value="">Seleccione...</option>
							<?php foreach ($this->list_nivel as $key => $value) { ?>
								<option <?php if ($this->getObjectVariable($this->content, "nivel") == $key) {
											echo "selected";
										} ?> value="<?php echo $key; ?>" /> <?= $value; ?></option>
							<?php } ?>
						</select>
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-1 d-grid form-group">
					<label class="control-label">Activo</label>
					<input type="checkbox" name="activo" value="1" class="form-control switch-form " <?php if ($this->getObjectVariable($this->content, 'activo') == 1) {
																											echo "checked";
																										} ?>></input>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-md-6 col-lg-3 form-group contenedor-empresa">
					<label class="control-label">Empresa</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-azul-claro "><i class="far fa-list-alt"></i></span>
						</div>
						<select class="form-control" name="empresa">
							<option value="">Seleccione...</option>
							<?php foreach ($this->list_empresa as $key => $value) { ?>
								<option <?php if ($this->getObjectVariable($this->content, "empresa") == $key) {
											echo "selected";
										} ?> value="<?php echo $key; ?>" /> <?= $value; ?></option>
							<?php } ?>
						</select>
					</label>
					<div class="help-block with-errors"></div>
				</div>

				<?php if (!$this->content->id) { ?>

					<div class="col-12 col-md-6 col-lg-3 form-group">
						<label for="clave_principal" class="control-label">Contraseña</label>
						<label class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text input-icono  fondo-rosado "><i class="fas fa-pencil-alt"></i></span>
							</div>
							<input type="password" autocomplete="off" value="" name="clave_principal" id="clave_principal" class="form-control" required>
						</label>
						<div class="help-block with-errors">
							<p id="message"></p>
							<ul id="conditions"></ul>
						</div>
					</div>
					<div class="col-12 col-md-6 col-lg-3 form-group">
						<label for="clave_principal-r" class="control-label">Repetir contraseña</label>
						<label class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text input-icono  fondo-rosado "><i class="fas fa-pencil-alt"></i></span>
							</div>
							<input type="password" autocomplete="off" value="" name="clave_principal-r" id="clave_principal-r" class="form-control" required>
						</label>
						<div class="help-block with-errors"></div>
					</div>
				<?php } ?>
				<div class="col-12 form-group d-none">
					<label for="asignacion" class="control-label">Asignacion</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-verde "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->asignacion; ?>" name="asignacion" id="asignacion" class="form-control">
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 form-group contenedor-ocupacion mt-4">
					<div class="list-group">
						<div class="row">

							<?php foreach ($this->list_empresa as $key => $value) { ?>
								<!-- <option <?php if ($this->getObjectVariable($this->content, "empresa") == $key) {
													echo "selected";
												} ?> value="<?php echo $key; ?>" /> <?= $value; ?></option> -->
								<div class="col-md-4">
									<label class="list-group-item">
										<input class="form-check-input me-1 check-empresas" onclick="agregarEmpresas()" type="checkbox" <?php foreach ($this->asignacionArray as $keyAsignacion => $asignacion) {
																																			if ($asignacion == $key) {
																																				echo 'checked';
																																			}
																																		}  ?> value="<?php echo $key; ?>" id="c<?php echo $key; ?>">
										<?= $value; ?>
									</label>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>

			</div>
			<!-- <?php
					echo '<pre>';
					print_r($this->list_empresa);
					echo '</pre>'
					?> -->
		</div>
		<div id="botones-acciones" class="botones-acciones">
			<button class="btn btn-guardar" id="submitButton" type="submit">Guardar</button>
			<a href="<?php echo $this->route; ?>" class="btn btn-cancelar">Cancelar</a>
		</div>
	</form>
</div>

<script>
$(document).ready(function () {
selectorUsuario()
})
	// 
	//guardar asignacion, empresas
	// Array para almacenar los checkboxes marcados
	let asignacion = document.getElementById('asignacion')

	function agregarEmpresas() {
		let checkboxesMarcados = '';
		const checks = document.getElementsByClassName('check-empresas')
		// Recorre cada checkbox y verifica si está marcado
		for (var i = 0; i < checks.length; i++) {
			if (checks[i].checked === true) {

				checkboxesMarcados += checks[i].value + ','
			}

		}
		asignacion.value = checkboxesMarcados
		console.log(asignacion.value);

	}

	function leerAsignacion() {
		const asignacion = document.getElementById('asignacion')
		console.log(asignacion.value);
		let asignacionArray = asignacion.value.split(",");
		numerosArray = asignacionArray.map(function(numero) {
			return parseInt(numero);
		});
		console.log(numerosArray);
	}
	// selectorUsuario()
	leerAsignacion()
</script>