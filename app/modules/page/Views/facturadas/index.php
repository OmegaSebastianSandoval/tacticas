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
	<form action="<?php echo $this->route; ?>" method="post" id="form-facturadas">
		<div class="content-dashboard">
			<div class="row">

				<div class="col-12 col-md-4">
					<label>Fecha de inicio</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono fondo-verde-claro "><i class="fas fa-calendar-alt"></i></span>
						</div>
						<input type="date" formart="yyyy-mm-dd" class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?php echo $this->fecha1  ?>"></input>
					</label>
				</div>
				<div class="col-12 col-md-4">
					<label>Fecha final</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono fondo-verde-claro "><i class="fas fa-calendar-alt"></i></span>
						</div>
						<input type="date" formart="yyyy-mm-dd" class="form-control" name="fecha_fin" id="fecha_fin" value="<?php echo $this->fecha2 ?>"></input>
					</label>
				</div>
				<div class="col-12 col-md-2  d-grid align-items-end ">
					<label>&nbsp;</label>
					<button type="submit" class="btn btn-block btn-azul"> <i class="fas fa-filter"></i> Filtrar</button>
				</div>
				<div class="col-12 col-md-2  d-grid align-items-end ">
					<label>&nbsp;</label>
					<a class="btn btn-block btn-azul-claro " href="<?php echo $this->route; ?>?cleanfilter=1"> <i class="fas fa-eraser"></i> Limpiar Filtro</a>
				</div>
			</div>
		</div>
	</form>

	<div class="content-dashboard pt-0 pb-5">

		<div class="content-table table-responsive mt-0">
			<table class=" table table-striped  table-hover table-administrator text-left tabla-facturadas">

				<thead>
					<tr class="text-center">
						<td>
							Localizaci&oacute;n
						<td>
							<div align="center">H Normales</div>
						</td>
						<td>H Normales Facturadas</td>
						<td>
							<div align="center">H Diurnas</div>
						</td>
						<td>H Diurnas Facturadas</td>
						<td>
							<div align="center">H Nocturnas</div>
						</td>
						<td>H Nocturnas Facturadas</td>
						<td>
							<div align="center">Festivos</div>
						</td>
						<td>Festivos Facturados</td>
						<td>
							<div align="center">Dominicales</div>
						</td>
						<td>Dominacales Facturados</td>
					</tr>
				</thead>
				<tbody>
					<?php echo $this->tabla; ?>

				</tbody>
			</table>
		</div>
		<input type="hidden" id="csrf" value="<?php echo $this->csrf ?>"><input type="hidden" id="page-route" value="<?php echo $this->route; ?>/changepage">
	</div>

</div>

<style>
	.tabla-facturadas thead {
		font-size: 12px;
		font-weight: 400;
	}

	.content-table .table tbody tr td>a {
		color: var(--primary);
		text-decoration: none;
		font-weight: 500;
		transition: all 300ms;
	}
</style>
<script>
	Fancybox.bind("[data-fancybox]", {
		//
	})



	// Función que se ejecutará al enviar el formulario
	function onSubmitForm() {
		const contentloader = document.getElementById('content-loader')
		const loader = document.getElementById('loader')
		contentloader.style.display = 'flex'
		loader.style.display = 'block'
	}
	document.getElementById('form-facturadas').addEventListener('submit', onSubmitForm);
</script>