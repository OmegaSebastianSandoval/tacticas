<h1 class="titulo-principal"><i class="fas fa-cogs"></i> <?php echo $this->titlesection; ?></h1>
<div class="container-fluid">
	<form class="text-left" enctype="multipart/form-data" method="post" action="<?php echo $this->routeform;?>"  data-bs-toggle="validator">
		<div class="content-dashboard">
			<input type="hidden" name="csrf" id="csrf" value="<?php echo $this->csrf ?>">
			<input type="hidden" name="csrf_section" id="csrf_section" value="<?php echo $this->csrf_section ?>">
			<?php if ($this->content->historial_contratos_id) { ?>
				<input type="hidden" name="id" id="id" value="<?= $this->content->historial_contratos_id; ?>" />
			<?php }?>
			<div class="row">
				<div class="col-12 form-group">
					<label for="historial_contratos_fecha_inicio"  class="control-label">historial_contratos_fecha_inicio</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-verde-claro " ><i class="fas fa-calendar-alt"></i></span>
						</div>
					<input type="text" value="<?php if($this->content->historial_contratos_fecha_inicio){ echo $this->content->historial_contratos_fecha_inicio; } else { echo date('Y-m-d'); } ?>" name="historial_contratos_fecha_inicio" id="historial_contratos_fecha_inicio" class="form-control"   data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-language="es"  >
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 form-group">
					<label for="historial_contratos_fecha_fin"  class="control-label">historial_contratos_fecha_fin</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-verde " ><i class="fas fa-calendar-alt"></i></span>
						</div>
					<input type="text" value="<?php if($this->content->historial_contratos_fecha_fin){ echo $this->content->historial_contratos_fecha_fin; } else { echo date('Y-m-d'); } ?>" name="historial_contratos_fecha_fin" id="historial_contratos_fecha_fin" class="form-control"   data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-language="es"  >
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 form-group">
					<label for="historial_contratos_cedula"  class="control-label">historial_contratos_cedula</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-rojo-claro " ><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->historial_contratos_cedula; ?>" name="historial_contratos_cedula" id="historial_contratos_cedula" class="form-control"   >
					</label>
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