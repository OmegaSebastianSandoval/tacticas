<div class="container-fluid">
	<div class="d-flex justify-content-start">
		<h3 class="my-0"><i class="fa-solid fa-building" title="Empresas"></i> <?php echo $this->titlesection; ?></h3>
	</div>
	<form class="text-left" enctype="multipart/form-data" method="post" action="<?php echo $this->routeform; ?>" data-bs-toggle="validator">
		<div class="content-dashboard">
			<input type="hidden" name="csrf" id="csrf" value="<?php echo $this->csrf ?>">
			<input type="hidden" name="csrf_section" id="csrf_section" value="<?php echo $this->csrf_section ?>">
			<?php if ($this->content->documento_empresa_id) { ?>
				<input type="hidden" name="id" id="id" value="<?= $this->content->documento_empresa_id; ?>" />
			<?php } ?>
			<div class="row">
				<div class="col-6 form-group">
					<label for="documento_empresa_nombre" class="control-label">Nombre del documento</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-rosado "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->documento_empresa_nombre; ?>" name="documento_empresa_nombre" id="documento_empresa_nombre" class="form-control" required>
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-6 form-group">
					<label for="documento_empresa_archivo">Archivo</label>
					<input type="file" name="documento_empresa_archivo" id="documento_empresa_archivo" class="form-control  file-document" data-buttonName="btn-primary" onchange="validardocumento('documento_empresa_archivo');" accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint, text/plain, application/pdf" <?php if (!$this->content->documento_empresa_id) {
																																																																																							echo 'required';
																																																																																						} ?>>
					<div class="help-block with-errors"></div>
					<?php if ($this->content->documento_empresa_archivo) { ?>
						<div id="archivo_documento_empresa_archivo">
							<div><?php echo $this->content->documento_empresa_archivo; ?></div>
							<div><button class="btn btn-danger btn-sm" type="button" onclick="eliminararchivo('documento_empresa_archivo','<?php echo $this->route . "/deletearchivo"; ?>')"><i class="glyphicon glyphicon-remove"></i> Eliminar Archivo</button></div>
						</div>
					<?php } ?>
				</div>
				<input type="hidden" name="documento_empresa_fecha_creacion" value="<?php if ($this->content->documento_empresa_fecha_creacion) {
																						echo $this->content->documento_empresa_fecha_creacion;
																					} else {
																						echo date("Y-m-d H:i:s");
																					} ?>">
				<input type="hidden" name="documento_empresa_empresa_id" value="<?php if ($this->content->documento_empresa_empresa_id) {
																					echo $this->content->documento_empresa_empresa_id;
																				} else {
																					echo $this->emp;
																				} ?>">
			</div>
		</div>
		<div class="botones-acciones">
			<button class="btn btn-guardar" type="submit">Guardar</button>
			<a href="/page/empresas/manage?id=<?php echo $this->emp ?>#pills-documentosEmpresa" class="btn btn-cancelar">Cancelar</a>
		</div>
	</form>
</div>