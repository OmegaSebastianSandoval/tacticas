<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script type="text/javascript">
	function verificar_conexion() {
		var online = navigator.onLine;
		if (online === false) {
			document.getElementById('sin_conexion').style.display = '';
			document.getElementById('sin_conexion2').style.display = '';
		} else {
			document.getElementById('sin_conexion').style.display = 'none';
			document.getElementById('sin_conexion2').style.display = 'none';
		}
	}

	function total_horas(i) {
		var j = 0;
		var horas = 0;
		var total_horas = 0;
		var total_incapacidad = 0;
		var e = 0;
		var no = 0;

		for (j = 0; j <= 31; j++) {
			if (document.getElementById('horas_' + i + '_' + j)) {
				horas = document.getElementById('horas_' + i + '_' + j).value;
				e = document.getElementById('loc_' + i + '_' + j);
				loc = e.options[e.selectedIndex].value;
				if (loc != 'DESCANSO' && loc != 'VACACIONES' && loc != 'FALTA' && loc != 'PERMISO') {
					total_horas += Number(horas);
				}
				if (loc == 'INCAPACIDAD') {
					total_incapacidad += Number(horas);
				}
			}
		}

		var valor_hora = document.getElementById('valor_hora' + i).value;
		document.getElementById('total_horas' + i).innerHTML = total_horas;
		document.getElementById('incap' + i).innerHTML = total_incapacidad;
		document.getElementById('total' + i).innerHTML = (total_horas * valor_hora).toFixed(2);
	}

	function actualizar_filtro() {
		var e;

		$('#filtro_0 option[value!=""]').remove();

		for (i = 0; i <= 100; i++) {
			if (document.getElementById('loc_' + i + '_G')) {
				e = document.getElementById('loc_' + i + '_G');
				loc = e.options[e.selectedIndex].value;
				if (loc != "") {
					if (!$("#filtro_0 option[value='" + loc + "']").length > 0) {
						$("#filtro_0").append('<option value="' + loc + '">' + loc + '</option>');
					}
				}
			}
		}
	}


	function guardar_hora(i, j) {

		verificar_conexion();
		var general = "0";
		if (j == "G") {
			j = "0";
			general = "1";
		}
		var fecha = document.getElementById('fecha_' + i + '_' + j).value;
		var horas = document.getElementById('horas_' + i + '_' + j).value;
		var e = document.getElementById('loc_' + i + '_' + j);
		if (general == "1") {
			e = document.getElementById('loc_' + i + '_G');
		}
		var loc = e.options[e.selectedIndex].value;
		var cedula = document.getElementById('cedula' + i).value;
		var planilla = document.getElementById('planilla').value;
		var tipo = document.getElementById('tipo').value;

		/* $('#consulta_horas' + j).load('mod_nomina/consulta.php', {
			fecha: fecha,
			horas: horas,
			loc: loc,
			cedula: cedula,
			planilla: planilla,
			tipo: tipo,
			general: general
		}); */
		if (loc == '' && horas >= 1 || loc != '' && horas === '' || loc != '' && horas == 0 || loc == '' && horas == 0) {
			return
		} else {
			$.post("/page/planilla/guardarhoras", {
				"fecha": fecha,
				"horas": horas,
				"loc": loc,
				"cedula": cedula,
				"planilla": planilla,
				"tipo": tipo,
				"general": general
			}, function(res) {
				//	console.log(res);
			})

			total_horas(i);


			if (loc != 'DESCANSO' && loc != 'VACACIONES' && loc != 'PERMISO' && loc != 'FALTA' && loc != 'INCAPACIDAD') {
				if (general == "1" || j > 0) {
					llenar_fila(loc, i, j);
				}
			}


			if (general == "1") {
				actualizar_filtro();
			}
		}


	}

	function guardar_hora_pendiente(i, j) {

		verificar_conexion();
		console.log(i);
		console.log(j);
		var general = "0";
		if (j == "G") {
			j = "0";
			general = "1";
		}
		var fecha = document.getElementById('fechapend_' + i + '_' + j).value;
		var horas = document.getElementById('horas_' + i + '_' + j).value;
		var e = document.getElementById('loc_' + i + '_' + j);
		if (general == "1") {
			e = document.getElementById('loc_' + i + '_G');
		}
		var loc = e.options[e.selectedIndex].value;
		var cedula = document.getElementById('cedula' + i).value;
		var planilla = document.getElementById('planilla').value;
		var tipo = document.getElementById('tipo').value;
		var pendiente = 'pendiente' + j

		console.log(pendiente);




		if (loc == '' && horas >= 1 || loc != '' && horas === '' || loc != '' && horas == 0 || loc == '' && horas == 0) {
			return
		} else {
			$.post("/page/planilla/guardarhoraspendientes", {
				"fecha": fecha,
				"horas": horas,
				"loc": loc,
				"cedula": cedula,
				"planilla": planilla,
				"tipo": tipo,
				"general": general,
				"pendiente": pendiente
			}, function(res) {
				//	console.log(res);
			})
			total_horas(i);


			if (loc != 'DESCANSO' && loc != 'VACACIONES' && loc != 'PERMISO' && loc != 'FALTA' && loc != 'INCAPACIDAD') {
				if (general == "1" || j > 0) {
					llenar_fila(loc, i, j);
				}
			}


			if (general == "1") {
				actualizar_filtro();
			}
		}

		/* 		total_horas(i);


				if (loc != 'DESCANSO' && loc != 'VACACIONES' && loc != 'PERMISO' && loc != 'FALTA' && loc != 'INCAPACIDAD') {
					if (general == "1" || j > 0) {
						llenar_fila(loc, i, j);
					}
				}


				if (general == "1") {
					actualizar_filtro();
				} */

	}

	function llenar_fila(loc, i, j) {
		//console.log(loc);
		var aux = 0;
		if (loc == "") {
			aux = Number(j) - 1;
			loc = $('#loc_' + i + '_' + aux).val();
		}

		j = Number(j);
		var k = 0;
		var e;
		var valor = "";
		var w = "";
		var loc2 = "";
		//console.log("loc:"+loc+" i:"+i+" j:"+j);
		for (k = j + 1; k <= 31; k++) {
			if (document.getElementById('loc_' + i + '_' + k)) {
				//console.log("ENTRO");
				//console.log("loc:"+loc+" i:"+i+" j:"+k);
				//e = document.getElementById('loc_'+i+'_'+k);
				//valor = e.options[e.selectedIndex].value;						
				valor = document.getElementById('horas_' + i + '_' + k).value;
				w = document.getElementById('w_' + k).value;
				loc2 = $('#loc_' + i + '_' + k).val();

				if (valor == "" || valor == 0 || loc2 == "") {
					if (w != 'DOMINGO') {
						$('#loc_' + i + '_' + k).val(loc);
					}
				}
			} //for
		} //for
	}

	function filtrar(j) {
		var e = document.getElementById('filtro_' + j);
		var valor = e.options[e.selectedIndex].value;
		var e2;
		var valor2;

		for (k = 1; k <= 200; k++) {
			if (document.getElementById('loc_' + k + '_' + j)) {
				e2 = document.getElementById('loc_' + k + '_' + j);
				valor2 = e2.options[e2.selectedIndex].value;
				if (valor == valor2) {
					document.getElementById('fila_' + k).style.display = '';
				} else {
					document.getElementById('fila_' + k).style.display = 'none';
				}
				if (valor == "") {
					document.getElementById('fila_' + k).style.display = '';
				}
			}
		}
		limpiar_filtros(j);
	}

	function limpiar_filtros(j) {
		for (k = 1; k <= 30; k++) {
			if (document.getElementById('filtro_' + k)) {
				if (j != k) {
					e = document.getElementById('filtro_' + k);
					e.selectedIndex = 0;
				}
			}
		}
	}

	function filtrar_0() {
		var j = 0;
		var e = document.getElementById('filtro_' + j);
		var valor = e.options[e.selectedIndex].value;
		var e2;
		var valor2;
		var valores = "";

		for (var i = 0, iLen = e.options.length; i < iLen; i++) {
			opt = e.options[i];
			if (opt.selected) {
				valores = valores + opt.value + ',';
			}
		}

		j = 'G';
		for (k = 1; k <= 200; k++) {
			if (document.getElementById('loc_' + k + '_' + j)) {
				e2 = document.getElementById('loc_' + k + '_' + j);
				valor2 = e2.options[e2.selectedIndex].value;
				if (valores.indexOf(valor2) != -1) {
					document.getElementById('fila_' + k).style.display = '';
				} else {
					document.getElementById('fila_' + k).style.display = 'none';
				}
				if (valor2 == "") {
					document.getElementById('fila_' + k).style.display = 'none';
				}
				if (valor == "") {
					document.getElementById('fila_' + k).style.display = '';
				}
			}
		}
		limpiar_filtros(j);
	}

	/* function verificar_planilla() {
		var total = 0;

		for (var i = 1; i <= 200; i++) {
			for (var j = 1; j <= 31; j++) {
				var inputHoras = document.getElementById('horas_' + i + '_' + j);
				var selectLoc = document.getElementById('loc_' + i + '_' + j);

				if (inputHoras && selectLoc) {
					var horas = inputHoras.value;
					var loc = selectLoc.options[selectLoc.selectedIndex].value;

					if (horas > 0 && loc === "") {
						document.getElementById('casilla_' + i + '_' + j).style.backgroundColor = '#FFCCCC';
						total++;
					} else {
						document.getElementById('casilla_' + i + '_' + j).style.backgroundColor = '#E7F6F9';
					}
				}
			}
		}

		if (total > 0) {
			Swal.fire({
				icon: 'info',
				title: 'Oops...',
				text: 'Tiene ' + total + ' casillas por llenar',
			});
		}
	} */

	function verificar_planilla() {
		var i = 0;
		var j = 0;
		var horas = 0;
		var e;
		var loc = "";
		var total = 0;
		console.log(i);
		console.log(j);

		for (i = 1; i <= 200; i++) {
			for (j = 1; j <= 31; j++) {

				if (document.getElementById('horas_' + i + '_' + j)) {
					console.log(i);
					console.log(j);
					horas = document.getElementById('horas_' + i + '_' + j).value;
					e = document.getElementById('loc_' + i + '_' + j);
					loc = e.options[e.selectedIndex].value;

					if (horas > 0 && loc == "") {
						document.getElementById('casilla_' + i + '_' + j).style.backgroundColor = '#FFCCCC';
						total++;
					}
					/* else {
						document.getElementById('casilla_' + i + '_' + j).style.backgroundColor = 'red';
					} */
				}
			}
		}
		if (total > 0) {
			alert("Tiene " + total + " casillas por llenar");


			/* 	// Muestra el modal utilizando jQuery
			$('#modalFaltantes').modal('show');

			let txtfaltantes = document.getElementById('txtfaltantes');
			txtfaltantes.innerText = "Faltan " + total + " casillas por llenar"
 */
		}
	}

	function guardar_neta(i) {
		var viaticos = 0;
		var prestamos = 0;
		var prestamos2 = 0;
		var decimo = 0;
		var cedula = document.getElementById('cedula' + i).value;
		var planilla = document.getElementById('planilla').value;

		viaticos = document.getElementById('viaticos' + i).value;
		prestamos = document.getElementById('prestamos' + i).value;
		prestamos2 = document.getElementById('prestamos_financiera' + i).value;
		decimo = document.getElementById('decimo' + i).value;
		neta = Number(viaticos) + Number(prestamos) + Number(decimo);

		/* $('#consulta_neta').load('mod_nomina/consulta_neta.php', {
			cedula: cedula,
			planilla: planilla,
			viaticos: viaticos,
			prestamos: prestamos,
			prestamos2: prestamos2,
			decimo: decimo,
			neta: neta
		}); */
		$.post("/page/planilla/consultaneta", {

			"cedula": cedula,
			"planilla": planilla,
			"viaticos": viaticos,
			"prestamos": prestamos,
			"prestamos2": prestamos2,
			"decimo": decimo,
			"neta": neta
		}, function(res) {
			console.log(res);
		})

		total_horas(i);
	}

	function total_neta() {
		var i = 1;
		var viaticos = 0;
		var prestamos = 0;
		var prestamos2 = 0;
		var decimo = 0;
		var neta = 0;
		var total_neta1 = 0;
		var total_viaticos = 0;
		var total_prestamos = 0;
		var total_prestamos2 = 0;
		var total_decimo = 0;
		var total_bruta = 0;
		var seguridad_social = 0;
		var seguro_educativo = 0;

		for (i = 1; i <= 500; i++) {
			if (document.getElementById('viaticos' + i)) {
				total_bruta = document.getElementById('total_bruta' + i).value;
				seguridad_social = document.getElementById('seguridad_social' + i).value;
				seguro_educativo = document.getElementById('seguro_educativo' + i).value;

				viaticos = document.getElementById('viaticos' + i).value;
				prestamos = document.getElementById('prestamos' + i).value;
				prestamos2 = document.getElementById('prestamos_financiera' + i).value;
				decimo = document.getElementById('decimo' + i).value;
				neta = Number(total_bruta) + Number(viaticos) - Number(prestamos) - Number(prestamos2) - Number(seguridad_social) - Number(seguro_educativo);
				document.getElementById('neta' + i).innerHTML = neta.toFixed(2);

				total_viaticos += Number(viaticos);
				total_prestamos += Number(prestamos);
				total_prestamos2 += Number(prestamos2);
				total_decimo += Number(decimo);
				total_neta1 += Number(neta);
			}
		}
		document.getElementById('total_viaticos').innerHTML = total_viaticos.toFixed(2);
		document.getElementById('total_prestamos').innerHTML = total_prestamos.toFixed(2);
		document.getElementById('total_prestamos2').innerHTML = total_prestamos2.toFixed(2);
		document.getElementById('total_decimo').innerHTML = total_decimo.toFixed(2);
		document.getElementById('total_neta1').innerHTML = total_neta1.toFixed(2);
	}
</script>