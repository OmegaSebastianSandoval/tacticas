<div class="container-fluid">
  
    <div class=" d-flex justify-content-between ">
        <h3 class="my-0"><i class="fa-regular fa-newspaper" title="Hoja de vida"></i>
            <?php echo $this->titlesection; ?>
        </h3>
        <a href="/page/nomina">
            <button class="btn-primary-home btn-primary-volver  mt-2" type="submit">
                <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd">
                    <path d="M2.117 12l7.527 6.235-.644.765-9-7.521 9-7.479.645.764-7.529 6.236h21.884v1h-21.883z" />
                </svg>
                <span>Regresar</span>
            </button>
        </a>
    </div>

	<form class="text-left" enctype="multipart/form-data" method="post" action="<?php echo $this->routeform; ?>" data-bs-toggle="validator">
		<div class="content-dashboard">
			<input type="hidden" name="csrf" id="csrf" value="<?php echo $this->csrf ?>">
			<input type="hidden" name="csrf_section" id="csrf_section" value="<?php echo $this->csrf_section ?>">
			<?php if ($this->content->id) { ?>
				<input type="hidden" name="id" id="id" value="<?= $this->content->id; ?>" />
			<?php } ?>
			<div class="row">
				<div class="col-12 col-md-4 form-group">
					<label for="horas_extra" class="control-label">% horas extra</label>
					<label class="input-group">
						
						<input type="text" value="<?= $this->content->horas_extra; ?>" name="horas_extra" id="horas_extra" class="form-control">
												<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-rosado "><i class="fa-solid fa-percent"></i></span>
						</div>
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-md-4 form-group">
					<label for="horas_dominicales" class="control-label">% horas dominicales</label>
					<label class="input-group">
						
						<input type="text" value="<?= $this->content->horas_dominicales; ?>" name="horas_dominicales" id="horas_dominicales" class="form-control">
												<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-rosado "><i class="fa-solid fa-percent"></i></span>
						</div>
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-md-4 form-group">
					<label for="horas_nocturnas" class="control-label">% horas nocturnas</label>
					<label class="input-group">
					
						<input type="text" value="<?= $this->content->horas_nocturnas; ?>" name="horas_nocturnas" id="horas_nocturnas" class="form-control">
												<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-rosado "><i class="fa-solid fa-percent"></i></span>
						</div>
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-md-4 form-group">
					<label for="festivos" class="control-label">% festivos</label>
					<label class="input-group">
						
						<input type="text" value="<?= $this->content->festivos; ?>" name="festivos" id="festivos" class="form-control">
												<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-rosado "><i class="fa-solid fa-percent"></i></span>
						</div>
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-md-4 form-group">
					<label for="decimo" class="control-label">% decimo</label>
					<label class="input-group">
						
						<input type="text" value="<?= $this->content->decimo; ?>" name="decimo" id="decimo" class="form-control">
												<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-rosado "><i class="fa-solid fa-percent"></i></span>
						</div>
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-md-4 form-group">
					<label for="vacaciones" class="control-label">% vacaciones</label>
					<label class="input-group">
						
						<input type="text" value="<?= $this->content->vacaciones; ?>" name="vacaciones" id="vacaciones" class="form-control">
												<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-rosado "><i class="fa-solid fa-percent"></i></span>
						</div>
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-md-4 form-group">
					<label for="antiguedad" class="control-label">% antiguedad</label>
					<label class="input-group">
						
						<input type="text" value="<?= $this->content->antiguedad; ?>" name="antiguedad" id="antiguedad" class="form-control">
												<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-rosado "><i class="fa-solid fa-percent"></i></span>
						</div>
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-md-4 form-group">
					<label for="seguridad_social" class="control-label">% seguridad social empleado</label>
					<label class="input-group">
						
						<input type="text" value="<?= $this->content->seguridad_social; ?>" name="seguridad_social" id="seguridad_social" class="form-control">
												<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-rosado "><i class="fa-solid fa-percent"></i></span>
						</div>
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-md-4 form-group">
					<label for="seguro_educativo" class="control-label">% seguro educativo empleado</label>
					<label class="input-group">
						
						<input type="text" value="<?= $this->content->seguro_educativo; ?>" name="seguro_educativo" id="seguro_educativo" class="form-control">
												<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-rosado "><i class="fa-solid fa-percent"></i></span>
						</div>
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-md-4 form-group">
					<label for="seguridad_social2" class="control-label">% seguridad social empleador</label>
					<label class="input-group">
						
						<input type="text" value="<?= $this->content->seguridad_social2; ?>" name="seguridad_social2" id="seguridad_social2" class="form-control">
												<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-rosado "><i class="fa-solid fa-percent"></i></span>
						</div>
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-md-4 form-group">
					<label for="seguro_educativo2" class="control-label">% seguro educativo empleador</label>
					<label class="input-group">
						
						<input type="text" value="<?= $this->content->seguro_educativo2; ?>" name="seguro_educativo2" id="seguro_educativo2" class="form-control">
												<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-rosado "><i class="fa-solid fa-percent"></i></span>
						</div>
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-md-4 form-group">
					<label for="riesgos_profesionales" class="control-label">% riesgos profesionales</label>
					<label class="input-group">
						
						<input type="text" value="<?= $this->content->riesgos_profesionales; ?>" name="riesgos_profesionales" id="riesgos_profesionales" class="form-control">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-rosado "><i class="fa-solid fa-percent"></i></span>
						</div>
					</label>
					<div class="help-block with-errors"></div>
				</div>
			</div>
		</div>
		<div class="botones-acciones">
			<button class="btn btn-guardar" type="submit">Guardar</button>
			<a href="/page/nomina" class="btn btn-cancelar">Cancelar</a>
		</div>
	</form>
</div>

<style>
	.input-group>.input-group-prepend>.input-group-text {
    border-top-right-radius: 0.375rem;
    border-bottom-right-radius: 0.375rem;
	border-top-left-radius: 0;
    border-bottom-left-radius: 0;
	font-size: 1.2rem;
}
</style>