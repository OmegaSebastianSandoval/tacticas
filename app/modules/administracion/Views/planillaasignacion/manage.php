<h1 class="titulo-principal"><i class="fas fa-cogs"></i> <?php echo $this->titlesection; ?></h1>
<div class="container-fluid">
	<form class="text-left" enctype="multipart/form-data" method="post" action="<?php echo $this->routeform;?>"  data-bs-toggle="validator">
		<div class="content-dashboard">
			<input type="hidden" name="csrf" id="csrf" value="<?php echo $this->csrf ?>">
			<input type="hidden" name="csrf_section" id="csrf_section" value="<?php echo $this->csrf_section ?>">
			<?php if ($this->content->id) { ?>
				<input type="hidden" name="id" id="id" value="<?= $this->content->id; ?>" />
			<?php }?>
			<div class="row">
				<input type="hidden" name="planilla"  value="<?php if($this->content->planilla){ echo $this->content->planilla; } else { echo $this->planilla; } ?>">
				<div class="col-3 form-group">
					<label for="cedula"  class="control-label">C&eacute;dula</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-rojo-claro " ><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->cedula; ?>" name="cedula" id="cedula" class="form-control"   >
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-3 form-group">
					<label for="valor_hora"  class="control-label">Valor hora</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-verde " ><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->valor_hora; ?>" name="valor_hora" id="valor_hora" class="form-control"   >
					</label>
					<div class="help-block with-errors"></div>
				</div>
		<div class="col-3 form-group">
			<label   class="control-label">&iquest;Sin seguridad social?</label>
				<input type="checkbox" name="sin_seguridad" value="1" class="form-control switch-form " <?php if ($this->getObjectVariable($this->content, 'sin_seguridad') == 1) { echo "checked";} ?>   ></input>
				<div class="help-block with-errors"></div>
		</div>
			</div>
		</div>
		<div class="botones-acciones">
			<button class="btn btn-guardar" type="submit">Guardar</button>
			<a href="<?php echo $this->route; ?>?planilla=<?php if($this->content->planilla){ echo $this->content->planilla; } else { echo $this->planilla; } ?>" class="btn btn-cancelar">Cancelar</a>
		</div>
	</form>
</div>