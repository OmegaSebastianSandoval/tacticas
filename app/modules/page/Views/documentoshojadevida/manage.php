<div class="container-fluid mb-3">
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
				<div class="col-3 form-group">
					<label for="nombre" class="control-label">Nombre</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-azul "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->nombre; ?>" name="nombre" id="nombre" class="form-control" required>
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-3 form-group">
					<label class="control-label">Tipo de documento</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-verde "><i class="far fa-list-alt"></i></span>
						</div>
						<select class="form-control" name="tipo">
							<option value="">Seleccione...</option>
							<?php foreach ($this->list_tipo as $key => $value) { ?>
								<option <?php if ($this->getObjectVariable($this->content, "tipo") == $key) {
											echo "selected";
										} ?> value="<?php echo $key; ?>" /> <?= $value; ?></option>
							<?php } ?>
						</select>
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-3 form-group">
					<label for="archivo">Documento</label>
					<input type="file" name="archivo" id="archivo" class="form-control  file-document" data-buttonName="btn-primary" onchange="validardocumento('archivo');" accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint, text/plain, application/pdf">
					<div class="help-block with-errors"></div>
					<?php if($this->content->archivo) { ?>
						<div id="archivo_archivo">
							<div><?php echo $this->content->archivo; ?></div>
							<div><button class="btn btn-danger btn-sm" type="button" onclick="eliminararchivo('archivo','<?php echo $this->route."/deletearchivo"; ?>')"><i class="glyphicon glyphicon-remove" ></i> Eliminar Archivo</button></div>
						</div>
					<?php } ?>
				</div>
			
				
				


				<input type="hidden" name="fecha" value="<?php if ($this->content->cedula) {
																echo $this->content->cedula;
															} else {
																echo date("Y-m-d H:i:s");
															} ?> ">

				<input type="hidden" name="cedula" value="<?php if ($this->content->cedula) {
																echo $this->content->cedula;
															} else {
																echo $this->cc;
															} ?>">

				<div class="col-3 form-group">
					<label for="fecha_vencimiento" class="control-label">Fecha vencimiento</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-verde-claro "><i class="fas fa-calendar-alt"></i></span>
						</div>
						<input type="date" value="<?php if ($this->content->fecha_vencimiento) {
														echo $this->content->fecha_vencimiento;
													} else {
														echo '';
													} ?>" name="fecha_vencimiento" id="fecha_vencimiento" class="form-control" data-date-format="yyyy-mm-dd" data-date-language="es">
					</label>
					<div class="help-block with-errors"></div>
				</div>
			</div>
		</div>
		<div class="botones-acciones">
			<button class="btn btn-guardar" type="submit">Guardar</button>
			<a href="/page/hojadevida/manage?cc=<?php echo $this->cc ?>#pills-documentos" class="btn btn-cancelar">Cancelar</a>
		</div>
	</form>
</div>