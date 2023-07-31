<div class="container-fluid">
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
				<div class="col-2 form-group d-grid">
					<label class="control-label">&iquest;Cerrar n&oacute;mina?</label>
					<input type="checkbox" name="cerrada" value="1" class="form-control switch-form " <?php if ($this->getObjectVariable($this->content, 'cerrada') == 1) {
																											echo "checked";
																										} ?>></input>
					<div class="help-block with-errors"></div>
				</div>
			</div>

			<div class="row">

				<div class="col-12 col-md-4 form-group">
					<label for="fecha1" class="control-label">Fecha inicio</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-rojo-claro "><i class="fas fa-calendar-alt"></i></span>
						</div>
						<input type="text" onchange="set_fecha2();" onkeyup="set_fecha2();" value="<?php if ($this->content->fecha1) {
														echo $this->content->fecha1;
													} else {
														echo date('Y-m-d');
													} ?>" name="fecha1" id="fecha1" class="form-control" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-language="es">
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-md-4 form-group">
					<label for="fecha2" class="control-label">Fecha fin</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-azul-claro "><i class="fas fa-calendar-alt"></i></span>
						</div>
						<input type="text" value="<?php if ($this->content->fecha2) {
														echo $this->content->fecha2;
													} ?>" name="fecha2" id="fecha2" class="form-control" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-language="es">
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-md-4 form-group">
					<label class="control-label">Empresa</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-rosado "><i class="far fa-list-alt"></i></span>
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
			</div>

			<div class="row">


				<div class="col-12 col-md-4 form-group">
					<label for="fecha_cerrada" class="control-label">Fecha de cierre</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-azul "><i class="fas fa-calendar-alt"></i></span>
						</div>
						<input type="text" value="<?php if ($this->content->fecha_cerrada) {
														echo $this->content->fecha_cerrada;
													} ?>" name="fecha_cerrada" id="fecha_cerrada" class="form-control" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-language="es">
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-md-4 form-group">
					<label for="limite_horas" class="control-label">L&iacute;mite de horas normales</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-verde-claro "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->limite_horas; ?>" name="limite_horas" id="limite_horas" class="form-control">
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-md-4 form-group">
					<label for="limite_dominicales" class="control-label">L&iacute;mite de horas dominicales</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  fondo-morado "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->limite_dominicales; ?>" name="limite_dominicales" id="limite_dominicales" class="form-control">
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

<script>
	set_fecha2()
	function set_fecha2(){
	var fecha1 = document.getElementById('fecha1').value;
	var days = 1;
    fecha=new Date(fecha1);
    tiempo=fecha.getTime();
    milisegundos=parseInt(days*24*60*60*1000);
    total=fecha.setTime(tiempo+milisegundos);
    day=fecha.getDate();
    month=fecha.getMonth()+1;
    year=fecha.getFullYear();
	

	if(day>=15){
		day = ultimo_dia(fecha1);
	}
	if(day<15){
		day = 15;
	}

	month = con_cero(month);
	day = con_cero(day);
 
    fecha2 = year+"-"+month+"-"+day;	
	document.getElementById('fecha2').value=fecha2;	
}
function  ultimo_dia(fecha1){
	var date = new Date(fecha1);
	var fecha = new Date(date.getFullYear(), date.getMonth() + 1, 0);

    day=fecha.getDate();
    month=fecha.getMonth()+1;
    year=fecha.getFullYear();
	
	//month = con_cero(month);
	//day = con_cero(day);
	
	return day;	
}
function con_cero(x){
	if(x<10){
		x = '0'+x;
	}
	return x;
}
</script>