<div class="container-fluid mb-5">
	<style>
		h1 {
			font-size: 1.5em;
			font-weight: normal;
			margin: 0;
		}

		h2 {
			font-size: 1.3em;
			font-weight: normal;
			margin: 2em 0 0 0;
		}

		p {
			margin: 0.6em 0;
		}

		p.tabnav {
			font-size: 1.1em;
			text-transform: uppercase;
			text-align: right;
		}

		p.tabnav a {
			text-decoration: none;
			color: #999;
		}

		article.tabs {
			position: relative;
			display: block;
			width: 40em;
			height: 15em;
			margin: 2em auto;
		}

		article.tabs section {
			position: absolute;
			display: block;
			top: 1.8em;
			left: 0;
			height: 12em;
			padding: 10px 20px;
			background-color: #ddd;
			border-radius: 5px;
			box-shadow: 0 3px 3px rgba(0, 0, 0, 0.1);
			z-index: 0;
		}

		article.tabs section:first-child {
			z-index: 1;
		}

		article.tabs section h2 {
			position: absolute;
			font-size: 1em;
			font-weight: normal;
			width: 120px;
			height: 1.8em;
			top: -1.8em;
			left: 10px;
			padding: 0;
			margin: 0;
			color: #999;
			background-color: #ddd;
			border-radius: 5px 5px 0 0;
		}

		article.tabs section:nth-child(2) h2 {
			left: 132px;
		}

		article.tabs section:nth-child(3) h2 {
			left: 254px;
		}

		article.tabs section h2 a {
			display: block;
			width: 100%;
			line-height: 1.8em;
			text-align: center;
			text-decoration: none;
			color: inherit;
			outline: 0 none;
		}

		article.tabs section,
		article.tabs section h2 {
			-webkit-transition: all 500ms ease;
			-moz-transition: all 500ms ease;
			-ms-transition: all 500ms ease;
			-o-transition: all 500ms ease;
			transition: all 500ms ease;
		}

		article.tabs section:target,
		article.tabs section:target h2 {
			color: #333;
			background-color: #fff;
			z-index: 2;
		}
	</style>




	<article class="tabs">

		<section id="tab1">
			<h2><a href="#tab1">Tab 1</a></h2>
			<p>This content appears on tab 1.</p>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum lacinia elit nec mi ornare et viverra massa pharetra. Phasellus mollis, massa sed suscipit pharetra, nunc tellus sagittis nunc, et tempus dui lorem a ipsum.</p>
			<p class="tabnav"><a href="#tab2">next &#10151;</a></p>
		</section>

		<section id="tab2">
			<h2><a href="#tab2">Tab 2</a></h2>
			<p>This content appears on tab 2.</p>
			<p>Fusce ullamcorper orci vel turpis vestibulum eu congue nisl euismod. Maecenas euismod, orci non tempus fermentum, leo metus lacinia lacus, nec ultrices quam ligula ac leo. Quisque tortor neque, vulputate quis ultricies ut, rhoncus mollis metus.</p>
			<p class="tabnav"><a href="#tab3">next &#10151;</a></p>
		</section>

		<section id="tab3">
			<h2><a href="#tab3">Tab 3</a></h2>
			<p>This content appears on tab 3.</p>
			<p>Sed et diam eu ipsum scelerisque laoreet quis in nibh. Proin sodales augue lectus. Maecenas a lorem a mi congue pharetra. Sed sed risus in nisi venenatis condimentum. Donec ac consectetur arcu. Integer urna neque, rutrum at pretium eu.</p>
			<p class="tabnav"><a href="#tab1">next &#10151;</a></p>
		</section>

	</article>
	<ul class="nav nav-pills mb-3 gap-3" id="pills-tab" role="tablist">
		<li class="nav-item" role="presentation">
			<button class=" btn-tab active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Home
				<span></span>
			</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class=" btn-tab" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Contactos de emergencia
				<span></span>
			</button>
		</li>
		<li class="nav-item" role="presentation">
			<a class="btn-tab" href="#pills-contact" id="pills-contact-tab" data-bs-toggle="pill">Contactos de emergencia
				<span></span>
			</a>
		</li>
		<!--	<li class="nav-item" role="presentation">
			<button class="nav-link" id="pills-disabled-tab" data-bs-toggle="pill" data-bs-target="#pills-disabled" type="button" role="tab" aria-controls="pills-disabled" aria-selected="false" disabled>Disabled</button>
		</li> -->
	</ul>

	<div class="tab-content" id="pills-tabContent">
		<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
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

						<div class="col-12 col-md-4 col-lg-3 form-group">
							<label for="nombres" class="control-label">Nombres</label>
							<label class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text input-icono  fondo-verde "><i class="fas fa-pencil-alt"></i></span>
								</div>
								<input type="text" value="<?= $this->content->nombres; ?>" name="nombres" id="nombres" class="form-control" required>
							</label>
							<div class="help-block with-errors"></div>
						</div>
						<div class="col-12 col-md-4 col-lg-3 form-group">
							<label for="apellidos" class="control-label">Apellidos</label>
							<label class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text input-icono  fondo-cafe "><i class="fas fa-pencil-alt"></i></span>
								</div>
								<input type="text" value="<?= $this->content->apellidos; ?>" name="apellidos" id="apellidos" class="form-control" required>
							</label>
							<div class="help-block with-errors"></div>
						</div>
						<div class="col-12 col-md-4 col-lg-3 form-group">
							<label class="control-label">Tipo documento</label>
							<label class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text input-icono  fondo-verde-claro "><i class="far fa-list-alt"></i></span>
								</div>
								<select class="form-control" name="tipo_documento" required>
									<option value="">Seleccione...</option>
									<?php foreach ($this->list_tipo_documento as $key => $value) { ?>
										<option <?php if ($this->getObjectVariable($this->content, "tipo_documento") == $key) {
													echo "selected";
												} ?> value="<?php echo $key; ?>" /> <?= $value; ?></option>
									<?php } ?>
								</select>
							</label>
							<div class="help-block with-errors"></div>
						</div>
						<div class="col-12 col-md-4 col-lg-3 form-group">
							<label for="documento" class="control-label">N&uacute;mero de documento</label>
							<label class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text input-icono  fondo-rosado "><i class="fas fa-pencil-alt"></i></span>
								</div>
								<input type="text" value="<?= $this->content->documento; ?>" name="documento" id="documento" class="form-control" required>
							</label>
							<div class="help-block with-errors"></div>
						</div>
						<div class="col-12 col-md-4 col-lg-3 form-group">
							<label for="fecha_nacimiento" class="control-label">Fecha de nacimiento</label>
							<label class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text input-icono  fondo-azul "><i class="fas fa-calendar-alt"></i></span>
								</div>
								<input type="text" value="<?php if ($this->content->fecha_nacimiento) {
																echo $this->content->fecha_nacimiento;
															} else {
																echo date('Y-m-d');
															} ?>" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control" required data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-language="es">
							</label>
							<div class="help-block with-errors"></div>
						</div>
						<div class="col-12 col-md-4 col-lg-3 form-group">
							<label class="control-label">Ciudad de nacimiento</label>
							<label class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text input-icono  fondo-rojo-claro "><i class="far fa-list-alt"></i></span>
								</div>
								<select class="form-control" name="ciudad_nacimiento">
									<option value="">Seleccione...</option>
									<?php foreach ($this->list_ciudad_nacimiento as $key => $value) { ?>
										<option <?php if ($this->getObjectVariable($this->content, "ciudad_nacimiento") == $key) {
													echo "selected";
												} ?> value="<?php echo $key; ?>" /> <?= $value; ?></option>
									<?php } ?>
								</select>
							</label>
							<div class="help-block with-errors"></div>
						</div>
						<div class="col-12 col-md-4 col-lg-3 form-group">
							<label for="email" class="control-label">Email</label>
							<label class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text input-icono  fondo-morado "><i class="fas fa-pencil-alt"></i></span>
								</div>
								<input type="text" value="<?= $this->content->email; ?>" name="email" id="email" class="form-control">
							</label>
							<div class="help-block with-errors"></div>
						</div>
						<div class="col-12 col-md-4 col-lg-3 form-group">
							<label for="direccion" class="control-label">Direcci&oacute;n</label>
							<label class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text input-icono  fondo-azul-claro "><i class="fas fa-pencil-alt"></i></span>
								</div>
								<input type="text" value="<?= $this->content->direccion; ?>" name="direccion" id="direccion" class="form-control">
							</label>
							<div class="help-block with-errors"></div>
						</div>
						<div class="col-12 col-md-4 col-lg-3 form-group">
							<label for="telefono" class="control-label">Tel&eacute;fono</label>
							<label class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text input-icono  fondo-azul-claro "><i class="fas fa-pencil-alt"></i></span>
								</div>
								<input type="text" value="<?= $this->content->telefono; ?>" name="telefono" id="telefono" class="form-control">
							</label>
							<div class="help-block with-errors"></div>
						</div>
						<div class="col-12 col-md-4 col-lg-3 form-group">
							<label for="celular" class="control-label">Celular</label>
							<label class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text input-icono  fondo-azul "><i class="fas fa-pencil-alt"></i></span>
								</div>
								<input type="text" value="<?= $this->content->celular; ?>" name="celular" id="celular" class="form-control" required>
							</label>
							<div class="help-block with-errors"></div>
						</div>
						<div class="col-12 col-md-4 col-lg-3 form-group">
							<label class="control-label">Ciudad de residencia</label>
							<label class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text input-icono  fondo-rojo-claro "><i class="far fa-list-alt"></i></span>
								</div>
								<select class="form-control" name="ciudad">
									<option value="">Seleccione...</option>
									<?php foreach ($this->list_ciudad as $key => $value) { ?>
										<option <?php if ($this->getObjectVariable($this->content, "ciudad") == $key) {
													echo "selected";
												} ?> value="<?php echo $key; ?>" /> <?= $value; ?></option>
									<?php } ?>
								</select>
							</label>
							<div class="help-block with-errors"></div>
						</div>
						<div class="col-12 col-md-4 col-lg-3 form-group">
							<label class="control-label">Estado civil</label>
							<label class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text input-icono  fondo-verde "><i class="far fa-list-alt"></i></span>
								</div>
								<select class="form-control" name="estado_civil">
									<option value="">Seleccione...</option>
									<?php foreach ($this->list_estado_civil as $key => $value) { ?>
										<option <?php if ($this->getObjectVariable($this->content, "estado_civil") == $key) {
													echo "selected";
												} ?> value="<?php echo $key; ?>" /> <?= $value; ?></option>
									<?php } ?>
								</select>
							</label>
							<div class="help-block with-errors"></div>
						</div>
						<div class="col-12 col-md-4 col-lg-3 form-group">
							<label for="fecha_ingreso" class="control-label">Fecha ingreso</label>
							<label class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text input-icono  fondo-rosado "><i class="fas fa-calendar-alt"></i></span>
								</div>
								<input type="text" value="<?php if ($this->content->fecha_ingreso) {
																echo $this->content->fecha_ingreso;
															} else {
																echo date('Y-m-d');
															} ?>" name="fecha_ingreso" id="fecha_ingreso" class="form-control" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-language="es">
							</label>
							<div class="help-block with-errors"></div>
						</div>
						<div class="col-12 col-md-4 col-lg-3 form-group">
							<label for="numero_seguro" class="control-label">N&uacute;mero de seguro</label>
							<label class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text input-icono  fondo-morado "><i class="fas fa-pencil-alt"></i></span>
								</div>
								<input type="text" value="<?= $this->content->numero_seguro; ?>" name="numero_seguro" id="numero_seguro" class="form-control">
							</label>
							<div class="help-block with-errors"></div>
						</div>

						<div class="col-12 col-md-4 col-lg-3 form-group">
							<label class="control-label">Tipo de contrato</label>
							<label class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text input-icono  fondo-verde-claro "><i class="far fa-list-alt"></i></span>
								</div>
								<select class="form-control" name="tipo_contrato">
									<option value="">Seleccione...</option>
									<?php foreach ($this->list_tipo_contrato as $key => $value) { ?>
										<option <?php if ($this->getObjectVariable($this->content, "tipo_contrato") == $key) {
													echo "selected";
												} ?> value="<?php echo $key; ?>" /> <?= $value; ?></option>
									<?php } ?>
								</select>
							</label>
							<div class="help-block with-errors"></div>
						</div>

						<div class="col-12 col-md-4 col-lg-3 form-group">
							<label class="control-label">Método de pago</label>
							<label class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text input-icono  fondo-verde-claro "><i class="far fa-list-alt"></i></span>
								</div>
								<select class="form-control" onchange="mostrarMetodoPago() " id="metodo_pago" name="metodo_pago">
									<option value="">Seleccione...</option>
									<?php foreach ($this->list_metodo_pago as $key => $value) { ?>
										<option <?php if ($this->getObjectVariable($this->content, "metodo_pago") == $key) {
													echo "selected";
												} ?> value="<?php echo $key; ?>" /> <?= $value; ?></option>
									<?php } ?>
								</select>
							</label>
							<div class="help-block with-errors"></div>
						</div>
						<div class="col-12 col-md-4 col-lg-3 form-group no-numero">
							<label for="numero_cuenta" class="control-label">N&uacute;mero de cuenta</label>
							<label class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text input-icono  fondo-morado "><i class="fas fa-pencil-alt"></i></span>
								</div>
								<input type="text" value="<?= $this->content->numero_cuenta; ?>" name="numero_cuenta" id="numero_cuenta" class="form-control">
							</label>
							<div class="help-block with-errors"></div>
						</div>
						<div class="col-12 col-md-4 col-lg-3 form-group">
							<label for="fecha_salida" class="control-label">Fecha de salida</label>
							<label class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text input-icono  fondo-cafe "><i class="fas fa-calendar-alt"></i></span>
								</div>
								<input type="text" value="<?php if ($this->content->fecha_salida) {
																echo $this->content->fecha_salida;
															} else {
																echo date('Y-m-d');
															} ?>" name="fecha_salida" id="fecha_salida" class="form-control" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-language="es">
							</label>
							<div class="help-block with-errors"></div>
						</div>
						<div class="col-12 col-md-4 col-lg-3 form-group d-none">
							<label for="inicio" class="control-label">Inicio</label>
							<label class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text input-icono  fondo-azul-claro "><i class="fas fa-calendar-alt"></i></span>
								</div>
								<input type="text" value="<?php if ($this->content->inicio) {
																echo $this->content->inicio;
															} else {
																echo date('Y-m-d');
															} ?>" name="inicio" id="inicio" class="form-control" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-language="es">
							</label>
							<div class="help-block with-errors"></div>
						</div>
						<div class="col-12 col-md-4 col-lg-3 form-group d-none">
							<label for="fin" class="control-label">Fin</label>
							<label class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text input-icono  fondo-rojo-claro "><i class="fas fa-calendar-alt"></i></span>
								</div>
								<input type="text" value="<?php if ($this->content->fin) {
																echo $this->content->fin;
															} else {
																echo date('Y-m-d');
															} ?>" name="fin" id="fin" class="form-control" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-language="es">
							</label>
							<div class="help-block with-errors"></div>
						</div>
						<div class="col-12 col-md-4 col-lg-3 form-group">
							<label class="control-label">Empresa</label>
							<label class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text input-icono  fondo-verde-claro "><i class="far fa-list-alt"></i></span>
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
						<div class="col-12 col-md-4 col-lg-3 form-group">
							<label class="control-label">Cargo</label>
							<label class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text input-icono  fondo-verde-claro "><i class="far fa-list-alt"></i></span>
								</div>
								<select class="form-control" name="cargo">
									<option value="">Seleccione...</option>
									<?php foreach ($this->list_cargo as $key => $value) { ?>
										<option <?php if ($this->getObjectVariable($this->content, "cargo") == $value) {
													echo "selected";
												} ?> value="<?php echo $value; ?>" /> <?= $value; ?></option>
									<?php } ?>
								</select>
							</label>
							<div class="help-block with-errors"></div>
						</div>

						<div class="col-12 col-md-4 col-lg-3 form-group">
							<label for="edad" class="control-label">Edad</label>
							<label class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text input-icono  fondo-azul "><i class="fas fa-pencil-alt"></i></span>
								</div>
								<input type="text" value="<?php echo $this->edad ?>" name="edad" id="edad" class="form-control" readonly>
							</label>
							<div class="help-block with-errors"></div>
						</div>
						<div class="col-12 col-md-4 col-lg-3 form-group ">

							<label class="control-label">Retirado</label>
							<br>
							<input type="checkbox" name="retirado" value="1" class="form-control switch-form " <?php if ($this->getObjectVariable($this->content, 'retirado') == 1) {
																													echo "checked";
																												} ?>></input>
							<div class="help-block with-errors"></div>
						</div>


						<div class="col-12 col-md-4 col-lg-3 form-group">
							<label for="foto">Foto</label>
							<input type="file" name="foto" id="foto" class="form-control  file-image" data-buttonName="btn-primary" accept="image/gif, image/jpg, image/jpeg, image/png">
							<div class="help-block with-errors"></div>
							<?php if ($this->content->foto) { ?>
								<div id="imagen_foto">
									<img src="/images/<?= $this->content->foto; ?>" class="img-thumbnail thumbnail-administrator" />
									<div><button class="btn btn-danger btn-sm" type="button" onclick="eliminarImagen('foto','<?php echo $this->route . "/deleteimage"; ?>')"><i class="glyphicon glyphicon-remove"></i> Eliminar Imagen</button></div>
								</div>
							<?php } ?>
						</div>
						<?php $hoy = date('Y-m-d') ?>
						<?php if ($this->content->id) { ?>
							<input type="hidden" name="fecha_m" value="<?php echo $hoy ?>">
						<?php } ?>

						<input type="hidden" name="fecha_c" value="<?php echo $hoy ?>">
						<div class="col-12 form-group">
							<label for="perfil_profesional" class="form-label">Perfil profesional</label>
							<textarea name="perfil_profesional" id="perfil_profesional" class="form-control tinyeditor" rows="10"><?= $this->content->perfil_profesional; ?></textarea>
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
		<div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
			<?php if ($this->cantidadContactosEmergencia >= 1) { ?>
				<div class="d-flex justify-content-end">
					<a href="/page/contactosemergencia/manage?empleado=<?php echo $this->content->id ?>" class="btn btn-primary">Agregar contacto de emergencia</a>
				</div>
				<!-- <?php print_r($this->listaContactos) ?> -->
				<div class="content-table table-responsive">
					<table class=" table table-striped  table-hover table-administrator text-center">
						<thead>
							<tr class="text-center">
								<td>Nombre</td>
								<td>Teléfono</td>
								<td>Parentesco</td>


								<td width="100"></td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($this->listaContactos as $content) { ?>
								<?php $idlistaContactos = $content->contacto_emergencia_id; ?>
								<tr>

									<td><?= $content->contacto_emergencia_nombre; ?></td>
									<td><?= $content->contacto_emergencia_telefono; ?></td>
									<td><?= $content->contacto_emergencia_parentesco; ?>

									<td class="text-right">
										<div>
											<a class="btn btn-azul btn-sm" href="/page/contactosemergencia/manage?id=<?= $idlistaContactos ?>" data-bs-toggle="tooltip" data-placement="top" title="Editar"><i class="fas fa-pen-alt"></i></a>
											<span data-bs-toggle="tooltip" data-placement="top" title="Eliminar"><a class="btn btn-rojo btn-sm" data-bs-toggle="modal" data-bs-target="#modal<?= $id ?>"><i class="fas fa-trash-alt"></i></a></span>
										</div>

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
			<?php } else { ?>
				<div class="d-flex justify-content-end">
					<a href="/page/contactosemergencia/manage?empleado=<?php echo $this->content->id ?>" class="btn btn-primary">Agregar contacto de emergencia</a>
				</div>
				<div class="alert alert-info text-center" role="alert">
					El usuario aún no cuenta con registros
				</div>

			<?php } ?>


		</div>
		<div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab" tabindex="0">
			<?php
			include '../app/modules/page/Views/contactosemergencia/index.php';
			?>
		</div>
		<!--<div class="tab-pane fade" id="pills-disabled" role="tabpanel" aria-labelledby="pills-disabled-tab" tabindex="0">...</div> -->
	</div>
</div>