<div class="container-fluid mb-4">
<div class=" d-flex justify-content-start ">
		<h3 class="my-0"><i class="fa-regular fa-newspaper" title="Hoja de vida"></i> <?php echo $this->titlesection; ?></h3>
	</div>
	<form class="text-left" enctype="multipart/form-data" method="post" action="<?php echo $this->routeform;?>"  data-bs-toggle="validator">
		<div class="content-dashboard">
			<input type="hidden" name="csrf" id="csrf" value="<?php echo $this->csrf ?>">
			<input type="hidden" name="csrf_section" id="csrf_section" value="<?php echo $this->csrf_section ?>">
			<?php if ($this->content->id) { ?>
				<input type="hidden" name="id" id="id" value="<?= $this->content->id; ?>" />
			<?php }?>
			<div class="row">
				<div class="col-3 form-group">
					<label for="empresa"  class="control-label">Empresa</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-azul-claro " ><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->empresa; ?>" name="empresa" id="empresa" class="form-control"  required >
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-3 form-group">
					<label for="cargo"  class="control-label">Cargo</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-azul " ><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->cargo; ?>" name="cargo" id="cargo" class="form-control"  required >
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-3 form-group">
					<label for="fecha1"  class="control-label">Fecha de ingreso</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-cafe " ><i class="fas fa-calendar-alt"></i></span>
						</div>
					<input type="text" value="<?php if($this->content->fecha1){ echo $this->content->fecha1; } else { echo date('Y-m-d'); } ?>" name="fecha1" id="fecha1" class="form-control"  required data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-language="es"  >
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-3 form-group">
					<label for="fecha2"  class="control-label">Fecha de finalizaci&oacute;n</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-rosado " ><i class="fas fa-calendar-alt"></i></span>
						</div>
					<input type="text" value="<?php if($this->content->fecha2){ echo $this->content->fecha2; } else { echo date('Y-m-d'); } ?>" name="fecha2" id="fecha2" class="form-control"  required data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-language="es"  >
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 form-group">
					<label for="detalles" class="form-label" >Detalles</label>
					<textarea name="detalles" id="detalles"   class="form-control tinyeditor" rows="10"   ><?= $this->content->detalles; ?></textarea>
					<div class="help-block with-errors"></div>
				</div>
				<input type="hidden" name="cedula"  value="<?php if($this->content->cedula){ echo $this->content->cedula; } else { echo $this->cc; } ?>">
			</div>
		</div>
		<div class="botones-acciones">
			<button class="btn btn-guardar" type="submit">Guardar</button>
			<a href="/page/hojadevida/manage?cc=<?php echo $this->cc ?>#pills-experiencia" class="btn btn-cancelar">Cancelar</a>
		
		</div>
	</form>
</div>