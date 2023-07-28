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

		$.post("/page/planilla/guardarhoras", {
			"fecha": fecha,
			"horas": horas,
			"loc": loc,
			"cedula": cedula,
			"planilla": planilla,
			"tipo": tipo,
			"general": general
		}, function(res) {
			console.log(res);
		})

		total_horas(i);

		<?php if ($this->tipo == 1) { ?>
			if (loc != 'DESCANSO' && loc != 'VACACIONES' && loc != 'PERMISO' && loc != 'FALTA' && loc != 'INCAPACIDAD') {
				if (general == "1" || j > 0) {
					llenar_fila(loc, i, j);
				}
			}
		<?php } ?>

		if (general == "1") {
			actualizar_filtro();
		}

	}

	function llenar_fila(loc, i, j) {
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

	function verificar_planilla() {
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
}

</script>