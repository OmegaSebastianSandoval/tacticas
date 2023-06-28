<div class="container-fluid mb-5">




	<ul class=" autoplay nav nav-pills mb-3 gap-3 " id="pills-tab" role="tablist">
		<li class="nav-item" role="presentation">

			<a class=" btn-tab " id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" href="#pills-home" onclick="event.preventDefault()" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Home
				<span></span>
			</a>
		</li>
		<?php if ($this->content->id) { ?>
			<li class="nav-item" role="presentation">
				<a class=" btn-tab" id="pills-profile-tab" href="#pills-profile" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Contactos de emergencia
					<span></span>
				</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class=" btn-tab" id="pills-contact-tab" href="#pills-contact" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Educación y formación
					<span></span>
				</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class=" btn-tab" id="pills-experiencia-tab" href="#pills-experiencia" data-bs-toggle="pill" data-bs-target="#pills-experiencia" type="button" role="tab" aria-controls="pills-experiencia" aria-selected="false">Experiencia laboral
					<span></span>
				</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class=" btn-tab" id="pills-referencias-tab" href="#pills-referencias" data-bs-toggle="pill" data-bs-target="#pills-referencias" type="button" role="tab" aria-controls="pills-referencias" aria-selected="false">Referencias
					<span></span>
				</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class=" btn-tab" id="pills-otros-tab" href="#pills-otros" data-bs-toggle="pill" data-bs-target="#pills-otros" type="button" role="tab" aria-controls="pills-otros" aria-selected="false">Otros datos
					<span></span>
				</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class=" btn-tab" id="pills-vacaciones-tab" href="#pills-vacaciones" data-bs-toggle="pill" data-bs-target="#pills-vacaciones" type="button" role="tab" aria-controls="pills-vacaciones" aria-selected="false">Vacaciones
					<span></span>
				</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class=" btn-tab" id="pills-dotaciones-tab" href="#pills-dotaciones" data-bs-toggle="pill" data-bs-target="#pills-dotaciones" type="button" role="tab" aria-controls="pills-dotaciones" aria-selected="false">Dotaciones
					<span></span>
				</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class=" btn-tab" id="pills-documentos-tab" href="#pills-documentos" data-bs-toggle="pill" data-bs-target="#pills-documentos" type="button" role="tab" aria-controls="pills-documentos" aria-selected="false">Documentos
					<span></span>
				</a>
			</li>
		<?php } ?>
	</ul>


	<div class="tab-content">
		<div class="tab-pane fade active " id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
			<div class=" d-flex justify-content-start ">
				<h3 class="my-0"><i class="fa-regular fa-newspaper" title="Hoja de vida"></i> <?php echo $this->titlesection; ?></h3>
			</div>
			<form class="text-left" enctype="multipart/form-data" method="post" action="<?php echo $this->routeform; ?>?debug=1" data-bs-toggle="validator">
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
								<input type="text" value="<?= $this->content->id; ?>" name="documento" id="documento" class="form-control" required>
							</label>
							<div class="help-block with-errors"></div>
						</div>
						<div class="col-12 col-md-4 col-lg-3 form-group">
							<label for="fecha_nacimiento" class="control-label">Fecha de nacimiento</label>
							<label class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text input-icono  fondo-azul "><i class="fas fa-calendar-alt"></i></span>
								</div>
								<input type="date" value="<?php if ($this->content->fecha_nacimiento) {
																echo $this->content->fecha_nacimiento;
															} else {
																/* echo date('Y-m-d'); */
															} ?>" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control" required data-date-format="yyyy-mm-dd" data-date-language="es">
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
							<label for="fecha_ingreso" class="control-label">Fecha ingreso</label>
							<label class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text input-icono  fondo-rosado "><i class="fas fa-calendar-alt"></i></span>
								</div>
								<input type="date" value="<?php if ($this->content->fecha_ingreso) {
																echo $this->content->fecha_ingreso;
															} else {
																/* echo date('Y-m-d'); */
															} ?>" name="fecha_ingreso" id="fecha_ingreso" class="form-control" data-date-format="yyyy-mm-dd" data-date-language="es">
							</label>
							<div class="help-block with-errors"></div>
						</div>
						<div class="col-12 col-md-4 col-lg-3 form-group">
							<label for="fecha_salida" class="control-label">Fecha de salida</label>
							<label class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text input-icono  fondo-cafe "><i class="fas fa-calendar-alt"></i></span>
								</div>
								<input type="date" value="<?php if ($this->content->fecha_salida) {
																echo $this->content->fecha_salida;
															} else {
																/* echo date('Y-m-d'); */
															} ?>" name="fecha_salida" id="fecha_salida" class="form-control" data-date-format="yyyy-mm-dd" data-date-language="es">
							</label>
							<div class="help-block with-errors"></div>
						</div>
						<?php if ($this->content->id && $this->content->id > 0) { ?>
							<div class="col-12 col-md-4 col-lg-3 form-group">
								<label for="inicio" class="control-label">Fecha inicio de contrato</label>
								<label class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text input-icono  fondo-azul-claro "><i class="fas fa-calendar-alt"></i></span>
									</div>
									<input type="date" value="<?php if ($this->content->inicio) {
																	echo $this->content->inicio;
																} else {
																	/* echo date('Y-m-d'); */
																} ?>" name="inicio" id="inicio" class="form-control" data-date-format="yyyy-mm-dd" data-date-language="es">
								</label>
								<div class="help-block with-errors"></div>
							</div>
							<div class="col-12 col-md-4 col-lg-3 form-group">
								<label for="fin" class="control-label">Fecha fin de contrato</label>
								<label class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text input-icono  fondo-rojo-claro "><i class="fas fa-calendar-alt"></i></span>
									</div>
									<input type="date" value="<?php if ($this->content->fin) {
																	echo $this->content->fin;
																} else {
																	/* echo date('Y-m-d'); */
																} ?>" name="fin" id="fin" class="form-control" data-date-format="yyyy-mm-dd" data-date-language="es">
								</label>
								<div class="help-block with-errors"></div>
							</div>
						<?php } ?>

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
		<div class="tab-pane fade " id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
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
											<a class="btn btn-azul btn-sm" href="/page/contactosemergencia/manage?id=<?= $idlistaContactos ?>&empleado=<?php echo $this->content->id ?>" data-bs-toggle="tooltip" data-placement="top" title="Editar"><i class="fas fa-pen-alt"></i></a>
											<span data-bs-toggle="tooltip" data-placement="top" title="Eliminar"><a class="btn btn-rojo btn-sm" data-bs-toggle="modal" data-bs-target="#modal<?= $idlistaContactos ?>"><i class="fas fa-trash-alt"></i></a></span>
										</div>

										<div class="modal fade text-left" id="modal<?= $idlistaContactos ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
														<a class="btn btn-danger" href="/page/contactosemergencia/delete?id=<?= $idlistaContactos ?>&csrf=<?= $this->csrf; ?>&csrf_section=<?= $this->csrf_section; ?><?php echo ''; ?>&empleado=<?php echo $this->content->id ?>">Eliminar</a>
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
				<div class="d-flex justify-content-end mb-2">
					<a href="/page/contactosemergencia/manage?empleado=<?php echo $this->content->id ?>" class="btn btn-primary">Agregar contacto de emergencia</a>
				</div>

				<div class="alert alert-info text-center" role="alert">
					El usuario aún no cuenta con registros
				</div>

			<?php } ?>


		</div>
		<div class="tab-pane fade " id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab" tabindex="0">
			<?php if ($this->cantidadEstudios >= 1) { ?>
				<div class="d-flex justify-content-end mb-2">
					<a href="/page/estudios/manage?cc=<?php echo $this->content->id ?>" class="btn btn-primary">Agregar estudio o formación</a>
				</div>
				<!-- <?php print_r($this->listaContactos) ?> -->
				<div class="content-table table-responsive">
					<table class=" table table-striped  table-hover table-administrator text-left">
						<thead>
							<tr>
								<td>Instituci&oacute;n</td>
								<td>Titulo</td>
								<td>Fecha de inicio</td>
								<td>Fecha de finalizaci&oacute;n</td>
								<td>Descripci&oacute;n</td>

								<td width="100"></td>
							</tr>
						</thead>
						<tbody>

							<?php foreach ($this->listaEstudios as $content) { ?>
								<?php $idEstudios =  $content->id; ?>
								<tr>
									<td><?= $content->institucion; ?></td>
									<td><?= $content->titulo; ?></td>
									<td><?= $content->fecha1; ?></td>
									<td><?= $content->fecha2; ?></td>
									<td><?= $content->descripcion; ?></td>

									<td class="text-right">
										<div>
											<a class="btn btn-azul btn-sm" href="/page/estudios/manage?id=<?= $idEstudios ?>&cc=<?php echo $this->content->id ?>" data-bs-toggle="tooltip" data-placement="top" title="Editar"><i class="fas fa-pen-alt"></i></a>
											<span data-bs-toggle="tooltip" data-placement="top" title="Eliminar"><a class="btn btn-rojo btn-sm" data-bs-toggle="modal" data-bs-target="#modaledu<?= $idEstudios ?>"><i class="fas fa-trash-alt"></i></a></span>
										</div>
										<!-- Modal -->
										<div class="modal fade text-left" id="modaledu<?= $idEstudios ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
														<a class="btn btn-danger" href="/page/estudios/delete?id=<?= $idEstudios ?>&csrf=<?= $this->csrf; ?>&csrf_section=<?= $this->csrf_section; ?><?php echo ''; ?>&empleado=<?php echo $this->content->id ?>">Eliminar</a>
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
				<div class="d-flex justify-content-end mb-2">
					<a href="/page/estudios/manage?cc=<?php echo $this->content->id ?>" class="btn btn-primary">Agregar estudio o formación</a>
				</div>

				<div class="alert alert-info text-center" role="alert">
					El usuario aún no cuenta con registros
				</div>

			<?php } ?>

		</div>
		<div class="tab-pane fade" id="pills-experiencia" role="tabpanel" aria-labelledby="pills-experiencia-tab" tabindex="0">
			<?php if ($this->cantidadExperiencia >= 1) { ?>
				<div class="d-flex justify-content-end mb-2">
					<a href="/page/experiencia/manage?cc=<?php echo $this->content->id ?>" class="btn btn-primary">Agregar experiencia laboral</a>
				</div>
				<div class="content-table table-responsive">
					<table class=" table table-striped  table-hover table-administrator text-left">
						<thead>
							<tr>
								<td>Empresa</td>
								<td>Cargo</td>
								<td>Fecha de ingreso</td>
								<td>Fecha de finalizaci&oacute;n</td>
								<td>Detalles</td>
								<td width="100"></td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($this->listaExperiencia as $content) { ?>
								<?php $idExperiencia =  $content->id; ?>
								<tr>
									<td><?= $content->empresa; ?></td>
									<td><?= $content->cargo; ?></td>
									<td><?= $content->fecha1; ?></td>
									<td><?= $content->fecha2; ?></td>
									<td><?= $content->detalles; ?></td>
									<td class="text-right">
										<div>
											<a class="btn btn-azul btn-sm" href="/page/experiencia/manage?id=<?= $idExperiencia ?>&cc=<?php echo $this->content->id ?>" data-bs-toggle="tooltip" data-placement="top" title="Editar"><i class="fas fa-pen-alt"></i></a>
											<span data-bs-toggle="tooltip" data-placement="top" title="Eliminar"><a class="btn btn-rojo btn-sm" data-bs-toggle="modal" data-bs-target="#modalexp<?= $idExperiencia ?>"><i class="fas fa-trash-alt"></i></a></span>
										</div>
										<!-- Modal -->
										<div class="modal fade text-left" id="modalexp<?= $idExperiencia ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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

														<a class="btn btn-danger" href="/page/experiencia/delete?id=<?= $idExperiencia ?>&csrf=<?= $this->csrf; ?>&csrf_section=<?= $this->csrf_section; ?><?php echo ''; ?>&cc=<?php echo $this->content->id ?>">Eliminar</a>
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
				<div class="d-flex justify-content-end mb-2">
					<a href="/page/experiencia/manage?cc=<?php echo $this->content->id ?>" class="btn btn-primary">Agregar experiencia laboral</a>
				</div>

				<div class="alert alert-info text-center" role="alert">
					El usuario aún no cuenta con registros
				</div>
			<?php } ?>


		</div>
		<div class="tab-pane fade" id="pills-referencias" role="tabpanel" aria-labelledby="pills-referencias-tab" tabindex="0">
			<?php if ($this->cantidadReferencia >= 1) { ?>
				<div class="d-flex justify-content-end mb-2">
					<a href="/page/referencias/manage?cc=<?php echo $this->content->id ?>" class="btn btn-primary">Agregar referencias </a>
				</div>
				<div class="content-table table-responsive">
					<table class=" table table-striped  table-hover table-administrator text-left">
						<thead>
							<tr>
								<td>Tipo</td>
								<td>Nombre</td>
								<td>Cargo</td>
								<td>Empresa</td>
								<td>Tel&eacute;fono</td>
								<td>&iquest;Se llam&oacute;?</td>
								<td>&iquest;Se confirm&oacute;?</td>
								<td>Descripci&oacute;n</td>
								<td width="100"></td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($this->listaReferencia as $content) { ?>
								<?php $idReferencia =  $content->id; ?>
								<tr>
									<td><?= $this->list_tipo[$content->tipo]; ?>
									<td><?= $content->nombre; ?></td>
									<td><?= $content->cargo; ?></td>
									<td><?= $content->empresa; ?></td>
									<td><?= $content->telefono; ?></td>
									<td><?= $content->se_llamo; ?></td>
									<td><?= $content->se_confirmo; ?></td>
									<td><?= $content->descripcion; ?></td>
									<td class="text-right">
										<div>
											<a class="btn btn-azul btn-sm" href="/page/referencias/manage?id=<?= $idReferencia ?>&cc=<?php echo $this->content->id ?>" data-bs-toggle="tooltip" data-placement="top" title="Editar"><i class="fas fa-pen-alt"></i></a>
											<span data-bs-toggle="tooltip" data-placement="top" title="Eliminar"><a class="btn btn-rojo btn-sm" data-bs-toggle="modal" data-bs-target="#modalref<?= $idReferencia ?>"><i class="fas fa-trash-alt"></i></a></span>
										</div>
										<!-- Modal -->
										<div class="modal fade text-left" id="modalref<?= $idReferencia ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
														<a class="btn btn-danger" href="/page/referencias/delete?id=<?= $idReferencia ?>&csrf=<?= $this->csrf; ?>&csrf_section=<?= $this->csrf_section; ?><?php echo ''; ?>&cc=<?php echo $this->content->id ?>">Eliminar</a>
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
				<div class="d-flex justify-content-end mb-2">
					<a href="/page/referencias/manage?cc=<?php echo $this->content->id ?>" class="btn btn-primary">Agregar referencias </a>
				</div>

				<div class="alert alert-info text-center" role="alert">
					El usuario aún no cuenta con registros
				</div>
			<?php } ?>
		</div>
		<div class="tab-pane fade" id="pills-otros" role="tabpanel" aria-labelledby="pills-otros-tab" tabindex="0">
			<?php if ($this->cantidadOtros >= 1) { ?>
				<div class="d-flex justify-content-end mb-2">
					<a href="/page/otros/manage?cc=<?php echo $this->content->id ?>" class="btn btn-primary">Agregar otros datos </a>
				</div>
				<div class="content-table table-responsive">
					<table class=" table table-striped  table-hover table-administrator text-left">
						<thead>
							<tr>
								<td>Nombre</td>
								<td>Descripci&oacute;n</td>
								<td width="100"></td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($this->listaOtros as $content) { ?>
								<?php $idOtros =  $content->id; ?>
								<tr>
									<td><?= $content->nombre; ?></td>
									<td><?= $content->descripcion; ?></td>
									<td class="text-right">
										<div>
											<a class="btn btn-azul btn-sm" href="/page/otros/manage?id=<?= $idOtros ?>&cc=<?php echo $this->content->id ?>" data-bs-toggle="tooltip" data-placement="top" title="Editar"><i class="fas fa-pen-alt"></i></a>
											<span data-bs-toggle="tooltip" data-placement="top" title="Eliminar"><a class="btn btn-rojo btn-sm" data-bs-toggle="modal" data-bs-target="#modalotros<?= $idOtros ?>"><i class="fas fa-trash-alt"></i></a></span>
										</div>
										<!-- Modal -->
										<div class="modal fade text-left" id="modalotros<?= $idOtros ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
														<a class="btn btn-danger" href="/page/otros/delete?id=<?= $idOtros ?>&csrf=<?= $this->csrf; ?>&csrf_section=<?= $this->csrf_section; ?><?php echo ''; ?>&cc=<?php echo $this->content->id ?>">Eliminar</a>
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
				<div class="d-flex justify-content-end mb-2">
					<a href="/page/otros/manage?cc=<?php echo $this->content->id ?>" class="btn btn-primary">Agregar otros datos </a>
				</div>

				<div class="alert alert-info text-center" role="alert">
					El usuario aún no cuenta con registros
				</div>
			<?php } ?>
		</div>
		<div class="tab-pane fade" id="pills-vacaciones" role="tabpanel" aria-labelledby="pills-vacaciones-tab" tabindex="0">
			<?php if ($this->cantidadVacaciones >= 1) { ?>
				<div class="d-flex justify-content-end mb-2">
					<a href="/page/vacacioneshojadevida/manage?cc=<?php echo $this->content->id ?>" class="btn btn-primary">Agregar vacaciones </a>
				</div>
				<div class="content-table table-responsive">
					<table class=" table table-striped  table-hover table-administrator text-left">
						<thead>
							<tr>
								<td>Fecha inicio</td>
								<td>Fecha fin</td>
								<td>cedula</td>
								<td width="100"></td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($this->listaVacaciones as $content) { ?>
								<?php $idVacaciones =  $content->id; ?>
								<tr>
									<td><?= $content->fecha1; ?></td>
									<td><?= $content->fecha2; ?></td>
									<td><?= $content->cedula; ?></td>
									<td class="text-right">
										<div>
											<a class="btn btn-azul btn-sm" href="/page/vacacioneshojadevida/manage?id=<?= $idVacaciones ?>&cc=<?php echo $this->content->id ?>" data-bs-toggle="tooltip" data-placement="top" title="Editar"><i class="fas fa-pen-alt"></i></a>
											<span data-bs-toggle="tooltip" data-placement="top" title="Eliminar"><a class="btn btn-rojo btn-sm" data-bs-toggle="modal" data-bs-target="#modalvac<?= $idVacaciones ?>"><i class="fas fa-trash-alt"></i></a></span>
										</div>
										<!-- Modal -->
										<div class="modal fade text-left" id="modalvac<?= $idVacaciones ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
														<a class="btn btn-danger" href="/page/vacacioneshojadevida/delete?id=<?= $idVacaciones ?>&csrf=<?= $this->csrf; ?>&csrf_section=<?= $this->csrf_section; ?><?php echo ''; ?>&cc=<?php echo $this->content->id ?>">Eliminar</a>
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
				<div class="d-flex justify-content-end mb-2">
					<a href="/page/vacacioneshojadevida/manage?cc=<?php echo $this->content->id ?>" class="btn btn-primary">Agregar vacaciones </a>
				</div>

				<div class="alert alert-info text-center" role="alert">
					El usuario aún no cuenta con registros
				</div>
			<?php } ?>
		</div>
		<div class="tab-pane fade" id="pills-dotaciones" role="tabpanel" aria-labelledby="pills-dotaciones-tab" tabindex="0">
			<?php if ($this->cantidadDotacion >= 1) { ?>
				<div class="d-flex justify-content-end mb-2">
					<a href="/page/dotacioneshojadevida/manage?cc=<?php echo $this->content->id ?>" class="btn btn-primary">Agregar dotaciones </a>
				</div>
				<div class="content-table table-responsive">
					<table class=" table table-striped  table-hover table-administrator text-left">
						<thead>
							<tr>
								<td>Fecha de entrega</td>
								<td>Tipo</td>
								<td>Cantidad</td>
								<td>Observación</td>

								<td width="100"></td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($this->listaDotacion as $content) { ?>
								<?php $idDotacion =  $content->id; ?>
								<tr>
									<td><?= $content->fecha1; ?></td>
									<td><?= $this->list_tipoDotacion[$content->tipo]; ?>
									<td><?= $content->cantidad; ?></td>
									<td><?= $content->observacion; ?></td>

									<td class="text-right">
										<div>
											<a class="btn btn-azul btn-sm" href="/page/dotacioneshojadevida/manage?id=<?= $idDotacion ?>&cc=<?php echo $this->content->id ?>" data-bs-toggle="tooltip" data-placement="top" title="Editar"><i class="fas fa-pen-alt"></i></a>
											<span data-bs-toggle="tooltip" data-placement="top" title="Eliminar"><a class="btn btn-rojo btn-sm" data-bs-toggle="modal" data-bs-target="#modaldot<?= $idDotacion ?>"><i class="fas fa-trash-alt"></i></a></span>
										</div>
										<!-- Modal -->
										<div class="modal fade text-left" id="modaldot<?= $idDotacion ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
														<a class="btn btn-danger" href="/page/dotacioneshojadevida/delete?id=<?= $idDotacion ?>&csrf=<?= $this->csrf; ?>&csrf_section=<?= $this->csrf_section; ?><?php echo ''; ?>&cc=<?php echo $this->content->id ?>">Eliminar</a>
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
				<div class="d-flex justify-content-end mb-2">
					<a href="/page/dotacioneshojadevida/manage?cc=<?php echo $this->content->id ?>" class="btn btn-primary">Agregar dotaciones </a>
				</div>

				<div class="alert alert-info text-center" role="alert">
					El usuario aún no cuenta con registros
				</div>
			<?php } ?>
		</div>
		<div class="tab-pane fade" id="pills-documentos" role="tabpanel" aria-labelledby="pills-documentos-tab" tabindex="0">
			<?php if ($this->cantidadDocumentos >= 1) { ?>
				<div class="d-flex justify-content-end mb-2">
					<a href="/page/documentoshojadevida/manage?cc=<?php echo $this->content->id ?>" class="btn btn-primary">Agregar documentos </a>
				</div>
				<div class="content-table table-responsive">
					<table class=" table table-striped  table-hover table-administrator text-left">
						<thead>
							<tr>

								<td>Nombre</td>

								<td>fecha de creación</td>

								<td>Tipo de documento</td>

								<td>Fecha vencimiento</td>

								<td width="100"></td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($this->listaDocumentos as $content) { ?>
								<?php $idDocumento =  $content->id; ?>
								<tr>

									<td><a href="/files/<?php echo $content->archivo; ?>" target="_blank"><?php $content->archivo; ?><?= $content->nombre; ?></a></td>

									<td><?= $content->fecha; ?></td>
									<td><?= $this->list_tipodocumentohojadevida[$content->tipo]; ?>
									<td><?= $content->fecha_vencimiento; ?></td>
									<td class="text-right">
										<div>
											<a class="btn btn-azul btn-sm" href="/page/documentoshojadevida/manage?id=<?= $idDocumento ?>&cc=<?php echo $this->content->id ?>" data-bs-toggle="tooltip" data-placement="top" title="Editar"><i class="fas fa-pen-alt"></i></a>
											<span data-bs-toggle="tooltip" data-placement="top" title="Eliminar"><a class="btn btn-rojo btn-sm" data-bs-toggle="modal" data-bs-target="#modaldocum<?= $idDocumento ?>"><i class="fas fa-trash-alt"></i></a></span>
										</div>
										<!-- Modal -->
										<div class="modal fade text-left" id="modaldocum<?= $idDocumento ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
														<a class="btn btn-danger" href="/page/documentoshojadevida/delete?id=<?= $idDocumento ?>&csrf=<?= $this->csrf; ?>&csrf_section=<?= $this->csrf_section; ?><?php echo ''; ?>&cc=<?php echo $this->content->id ?>">Eliminar</a>
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
				<div class="d-flex justify-content-end mb-2">
					<a href="/page/documentoshojadevida/manage?cc=<?php echo $this->content->id ?>" class="btn btn-primary">Agregar documentos </a>
				</div>

				<div class="alert alert-info text-center" role="alert">
					El usuario aún no cuenta con registros
				</div>
			<?php } ?>
		</div>
	</div>
</div>
<style>
	.tab-pane:focus-visible {
		outline: none;
	}
</style>


<script>
	document.addEventListener("DOMContentLoaded", function() {
		// Leer el ID de la pestaña del hash de la URL
		var hash = window.location.hash.substr(1);

		// Variable para realizar el seguimiento de si se ha activado alguna pestaña
		var tabActivated = false;

		// Función para activar la pestaña correspondiente
		function activateTab(tabId) {
			var tabElement = document.querySelector('a[href="#' + tabId + '"]');
			var tabContentElement = document.getElementById(tabId);

			if (tabElement && tabContentElement) {
				// Eliminar la clase "active" de todas las pestañas y contenidos
				var allTabElements = document.querySelectorAll('.btn-tab');
				var allTabContentElements = document.querySelectorAll('.tab-pane');
				allTabElements.forEach(function(element) {
					element.classList.remove("active");
				});
				allTabContentElements.forEach(function(element) {
					element.classList.remove("active");
					element.classList.remove("show");

				});

				// Agregar la clase "active" a la nueva pestaña y contenido
				tabElement.classList.add("active");
				tabContentElement.classList.add("active");
				tabContentElement.classList.add("show");


				// Hacer scroll al elemento de la pestaña
				/* tabElement.scrollIntoView({
				  behavior: 'smooth',
				  block: 'start'
				}); */
			}
		}

		// Activar la pestaña correspondiente si se proporciona un ID válido en la URL
		if (hash !== "") {
			activateTab(hash);
			tabActivated = true;
		}

		// Si no se ha activado ninguna pestaña, activar la primera pestaña por defecto
		if (!tabActivated) {
			var firstTab = document.querySelector('.btn-tab');
			if (firstTab) {
				var firstTabId = firstTab.getAttribute('href').substring(1);
				activateTab(firstTabId);
				window.history.replaceState(null, null, '#' + firstTabId); // Actualizar la URL sin recargar la página
			}
		}

		// Escuchar el evento de clic en las pestañas para actualizar la URL y mostrar el contenido correspondiente
		var tabLinks = document.querySelectorAll('.btn-tab');
		tabLinks.forEach(function(link) {
			link.addEventListener('click', function(event) {
				event.preventDefault(); // Evitar el comportamiento de enlace predeterminado
				var href = this.getAttribute('href');
				var tabId = href.substring(1);

				// Activar la pestaña correspondiente
				activateTab(tabId);

				// Actualizar la URL sin recargar la página
				window.history.replaceState(null, null, '#' + tabId);
			});
		});
	});
	/* 	$(document).ready(function() {
			// Obtener el identificador de la pestaña activa desde la URL
			var url = window.location.href;
			var activeTabId = url.substring(url.indexOf("#") + 1);

			// Mover el carrusel hasta la pestaña activa después de que se haya inicializado
			$('.autoplay').on('init', function() {
				var activeTab = $('#' + activeTabId);

				if (activeTab.length > 0) {
					var activeTabIndex = activeTab.closest('li').index();
					$('.autoplay').slick('slickGoTo', activeTabIndex);
				}
			});

			// Inicializar el carrusel
			$('.autoplay').slick({
				infinite: false,
				slidesToShow: 6,
				slidesToScroll: 1
			});
		}); */
</script>