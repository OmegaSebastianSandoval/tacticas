<div class="container-fluid">
	<ul class="  nav nav-pills mb-3 gap-3 " id="pills-tab" role="tablist">
		<li class="nav-item" role="presentation">

			<a class=" btn-tab " id="pills-homeEmpresa-tab" data-bs-toggle="pill" data-bs-target="#pills-homeEmpresa" href="#pills-homeEmpresa" type="button" role="tab" aria-controls="pills-homeEmpresa" aria-selected="true">Home
				<span></span>
			</a>
		</li>
		<?php if ($this->content->id) { ?>
			<li class="nav-item" role="presentation">
				<a class=" btn-tab" id="pills-documentosEmpresa-tab" href="#pills-documentosEmpresa" data-bs-toggle="pill" data-bs-target="#pills-documentosEmpresa" type="button" role="tab" aria-controls="pills-documentosEmpresa" aria-selected="false">Documentos
					<span></span>
				</a>
			</li>
		<?php } ?>

	</ul>
	<div class="tab-content">
		<div class="tab-pane fade active " id="pills-homeEmpresa" role="tabpanel" aria-labelledby="pills-homeEmpresa-tab" tabindex="0">

			<div class="d-flex justify-content-start">
				<h3 class="my-0"><i class="fa-solid fa-building" title="Empresas"></i> <?php echo $this->titlesection; ?></h3>
			</div>
			<form class="text-left" enctype="multipart/form-data" method="post" action="<?php echo $this->routeform; ?>" data-bs-toggle="validator">
				<div class="content-dashboard">
					<input type="hidden" name="csrf" id="csrf" value="<?php echo $this->csrf ?>">
					<input type="hidden" name="csrf_section" id="csrf_section" value="<?php echo $this->csrf_section ?>">
					<?php if ($this->content->id) { ?>
						<input type="hidden" name="id" id="id" value="<?= $this->content->id; ?>" />
					<?php } ?>
					<div class="row">
						<div class="col-12 col-lg-3 form-group">
							<label for="nombre" class="control-label">Nombre</label>
							<label class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text input-icono  fondo-rojo-claro "><i class="fas fa-pencil-alt"></i></span>
								</div>
								<input type="text" value="<?= $this->content->nombre; ?>" name="nombre" id="nombre" class="form-control" required>
							</label>
							<div class="help-block with-errors"></div>
						</div>

						<div class="col-12 col-lg-3 form-group">
							<label for="direccion" class="control-label">Direcci&oacute;n</label>
							<label class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text input-icono  fondo-azul-claro "><i class="fas fa-pencil-alt"></i></span>
								</div>
								<input type="text" value="<?= $this->content->direccion; ?>" name="direccion" id="direccion" class="form-control">
							</label>
							<div class="help-block with-errors"></div>
						</div>
						<div class="col-12 col-lg-3 form-group">
							<label for="telefono" class="control-label">Tel&eacute;fono</label>
							<label class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text input-icono  fondo-azul "><i class="fas fa-pencil-alt"></i></span>
								</div>
								<input type="text" value="<?= $this->content->telefono; ?>" name="telefono" id="telefono" class="form-control">
							</label>
							<div class="help-block with-errors"></div>
						</div>
						<div class="col-12 col-lg-3 form-group">
							<label for="email" class="control-label">Email</label>
							<label class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text input-icono  fondo-verde "><i class="fas fa-pencil-alt"></i></span>
								</div>
								<input type="text" value="<?= $this->content->email; ?>" name="email" id="email" class="form-control">
							</label>
							<div class="help-block with-errors"></div>
						</div>
						<div class="col-12 col-lg-3 form-group">
							<label for="logo">Logo</label>
							<input type="file" name="logo" id="logo" class="form-control  file-image" data-buttonName="btn-primary" accept="image/gif, image/jpg, image/jpeg, image/png">
							<div class="help-block with-errors"></div>
							<?php if ($this->content->logo) { ?>
								<div id="imagen_logo">
									<img src="/images/<?= $this->content->logo; ?>" class="img-thumbnail thumbnail-administrator" />
									<div><button class="btn btn-danger btn-sm" type="button" onclick="eliminarImagen('logo','<?php echo $this->route . "/deleteimage"; ?>')"><i class="glyphicon glyphicon-remove"></i> Eliminar Imagen</button></div>
								</div>
							<?php } ?>
						</div>

						<div class="col-12 col-lg-3 form-group">
							<label for="web" class="control-label">P&aacute;gina web</label>
							<label class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text input-icono  fondo-verde-claro "><i class="fas fa-pencil-alt"></i></span>
								</div>
								<input type="text" value="<?= $this->content->web; ?>" name="web" id="web" class="form-control">
							</label>
							<div class="help-block with-errors"></div>
						</div>
						<div class="col-12 col-lg-3 form-group">
							<label for="fecha_c" class="control-label">Fecha creaci&oacute;n</label>
							<label class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text input-icono  fondo-morado "><i class="fas fa-calendar-alt"></i></span>
								</div>
								<input type="text" value="<?php if ($this->content->fecha_c) {
																echo $this->content->fecha_c;
															} else {
																echo date('Y-m-d');
															} ?>" name="fecha_c" id="fecha_c" class="form-control" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-language="es">
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
	</div>
	<div class="tab-pane fade active " id="pills-documentosEmpresa" role="tabpanel" aria-labelledby="pills-documentosEmpresa-tab" tabindex="0">
		<?php if ($this->cantidadContactosEmergencia >= 1) { ?>
			<div class="d-flex justify-content-end mb-2">
				<a href="/page/documentosempresa/manage?emp=<?php echo $this->content->id ?>" class="btn btn-primary">Agregar documentos </a>
			</div>
			<div class="content-table table-responsive">
		<table class=" table table-striped  table-hover table-administrator text-left">
			<thead>
				<tr>
					<td>Nombre del documento</td>
					<td>Archivo</td>
					<td>Fecha de creación</td>
					<td width="100"></td>
				</tr>
			</thead>
			<tbody>
				<?php foreach($this->listaDocumentos as $content){ ?>
				<?php $idDocumento =  $content->documento_empresa_id; ?>
					<tr>
						<td><?=$content->documento_empresa_nombre;?></td>
						<td><?=$content->documento_empresa_archivo;?></td>
						<td><?=$content->documento_empresa_fecha_creacion;?></td>
						<td class="text-right">
							<div>
								<a class="btn btn-azul btn-sm" href="/page/documentosempresa/manage?id=<?= $idDocumento ?>&emp=<?php echo $this->content->id ?>"  data-bs-toggle="tooltip" data-placement="top" title="Editar"><i class="fas fa-pen-alt"></i></a>
								<span  data-bs-toggle="tooltip" data-placement="top" title="Eliminar"><a class="btn btn-rojo btn-sm"  data-bs-toggle="modal" data-bs-target="#modaldoce<?= $idDocumento ?>"  ><i class="fas fa-trash-alt" ></i></a></span>
							</div>
							<!-- Modal -->
							<div class="modal fade text-left" id="modaldoce<?= $idDocumento ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
								        	<a class="btn btn-danger" href="/page/documentosempresa/delete?id=<?= $idDocumento ?>&csrf=<?= $this->csrf; ?>&csrf_section=<?= $this->csrf_section; ?><?php echo ''; ?>&emp=<?php echo $this->content->id ?>" >Eliminar</a>
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
				<a href="/page/documentosempresa/manage?emp=<?php echo $this->content->id ?>" class="btn btn-primary">Agregar documentos </a>
			</div>

			<div class="alert alert-info text-center" role="alert">
				La empresa aún no cuenta con registros
			</div>
		<?php }  ?>
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
</script>