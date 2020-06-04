$(document).ready(function() {
	url = window.location.search;
	url = url.split('&');
	getURL(url[0]);
});
//Detectando la URL
function getURL(url) {
	if ( url == '?menu=general' ) {
		$('#option_1').addClass('active');		
		tablero_ctrl();
	}
	if ( url == '?menu=expedientes' ) {
		$('#option_2').addClass('active');		
		applyDataTables('tbl_abogado');
	}
	if ( url == '?menu=turnado_multi' ) {
		
		load_catalogo('estado','select',12);
		load_catalogo('t_ley','select',7);
		load_catalogo('dependencia_f','select',77);
		frm_add_turno_multi();
		autocomplete_input('sp','sp_id');
		autocomplete_input('sp_uai','sp_uai_id');
		autocomplete_input('persona','persona_id');
		autocomplete_input('expediente','expediente_id');
		autocomplete_input('oficio','oficio_id');
		
		$('#estado').change(function(e) {
			e.preventDefault();
			var e = $(this).val();
			//cuando es tramite
			if ( e == '1' || e == '9'  ) {
				$('#contenedor_0').removeClass('hidden');
				$('#contenedor_1,#contenedor_2,#contenedor_3,#contenedor_4,#contenedor_5').addClass('hidden');
			}
			//cuando es archivo o imporcedencia
			if ( e == '3' ) {
				$('#contenedor_1').removeClass('hidden');
				$('#contenedor_0,#contenedor_2,#contenedor_3,#contenedor_4,#contenedor_5').addClass('hidden');
			}
			//Cuando es respo
			if ( e == '8' ) {
				$('#contenedor_2').removeClass('hidden');
				$('#contenedor_0,#contenedor_1,#contenedor_3,#contenedor_4,#contenedor_5').addClass('hidden');
			}
			//Cuando es incompetencia
			if ( e == '4' ) {
				$('#contenedor_3').removeClass('hidden');
				$('#contenedor_0,#contenedor_2,#contenedor_1,#contenedor_4,#contenedor_5').addClass('hidden');
			}
			//Cuando es acumulado
			if ( e == '2' ) {
				$('#contenedor_4').removeClass('hidden');
				$('#contenedor_0,#contenedor_2,#contenedor_3,#contenedor_1,#contenedor_5').addClass('hidden');
			}
			//Cuando es reserva e improcedencia 
			if ( e == '10' || e == '11' ) {
				alerta('div_turno','error','NO CUENTAS CON EL PERFIL PARA REALIZAR TURNOS CON EL ESTADO SELECCIONADO.','');
				$('#btn_turnar').addClass('hidden');
			}else{
				$('#btn_turnar').removeClass('hidden');
			}
		});
	}
	if ( url == '?menu=migracion' ) {
		$('#option_7').addClass('active');
		$('#modo').change(function(e) {
			e.preventDefault();
			if ( $(this).val() == 1  ) {
				$('#div_2').addClass('hidden');
				$('#div_1').removeClass('hidden');
				$('#div_2_1').addClass('hidden');
			}
			if ( $(this).val() == 2  ) {
				$('#div_1').addClass('hidden');
				$('#buscador_tbl').addClass('hidden');
				$('#div_2').removeClass('hidden');
				$('#div_2_1').addClass('hidden');
				
				$('#btn_migrate').addClass('hidden');
			}
		});
		$('#c_buscar').change(function(e) {
			e.preventDefault();
			if ( $(this).val() == 1 ) {
				$('#div_2_1').removeClass('hidden');
			}
		});
				
		autocomplete_input('sp','sp_id',3);
		autocomplete_input('destino','destino_id',3);
		
		$("#buscador").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#tbl_migrate tbody tr").filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		});
		frm_migracion();
	}

	return false; 
}
//Creador de alertas automatico
function alerta(div,estado,mensaje,modal)
{
	var clase,icono,msj,edo;
	if ( estado == 'error' ) {
		icono = "fa-times";
		clase = "alert-danger";
		msj = mensaje;
		edo = "Error!";
		if (url == '?menu=reports') {
			time = 15000;
		}else{
			time = 5000;
		}
	}
	if ( estado == 'success') {
		icono = "fa-check";
		clase = "alert-success";
		msj = mensaje;
		edo = "Éxito!";
		time = 10000;
	}
	var contenedor = 
	'<div class="row">'+
		'<div class="col-md-12">'+
			'<div class="alert '+clase+' ">'+
				'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'+
				'<h4><i class="icon fa '+icono+'"></i> '+edo+'</h4>'+
				'<p> '+msj+' </p>'+
			'</div>'+
		'</div>'+
	'</div>';
	$('#'+div).html(contenedor);
	location.href = "#"+div;
	setTimeout(function() {
		$('#'+div).html("");
		if ( modal != '' ) {
			$('#'+modal).modal('hide');
		}
	},time);
	return false;
}
//tablero de control
function tablero_ctrl() {
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '80'},
		async:false,
		cache:false,
	})
	.done(function(response) {
		var suma = 0,fila = "";
		$.each(response.edos, function(i, val) {
			var fila = "";
			suma += parseInt(val.cuenta);
			fila += '<tr>';
				fila += '<td>'+val.n_estado+'</td>';
				fila += '<td>'+val.cuenta+'</td>';
				fila += '<td>';
					fila += '<button type="button" class="btn btn-success btn-flat" onclick="verExpedientes('+"'estado'"+','+val.estado+');"> <i class="fa fa-eye" ></i> </button>';
				fila += '</td>';
			fila += '</tr>';
			$('#tbl_edos').append(fila);
		});
		fila += '<tr>';
			fila += '<td class="text-right">TOTAL GENERAL</td>';
			fila += '<td colspan="2">'+suma+'</td>';
		fila += '</tr>';
		$('#tbl_edos').append(fila);
		var suma = 0,fila = "";
		$.each(response.abogados, function(i, val) {
			var fila = "";
			suma += parseInt(val.cuenta);
			fila += '<tr>';
				fila += '<td>'+val.full_name+'</td>';
				fila += '<td>'+val.cuenta+'</td>';
				fila += '<td>';
					fila += '<button type="button" class="btn btn-success btn-flat" onclick="verExpedientes('+"'abogado'"+','+val.id+');"> <i class="fa fa-eye" ></i> </button>';
				fila += '</td>';
			fila += '</tr>';
			$('#tbl_abogados').append(fila);
		});
		fila += '<tr>';
			fila += '<td class="text-right">TOTAL GENERAL</td>';
			fila += '<td>'+suma+'</td>';
			fila += '<td></td>';
		fila += '</tr>';
		$('#tbl_abogados').append(fila);	
		//Agregar los contadores de OINs
		var fila = "",suma = 0;
		console.log(response.c_oins);
		$.each(response.c_oins, function(i, val) {
			suma += parseInt(val.cuenta);
			var tipo = "";
			if (val.t_orden == "INS") {tipo="INSPECCIONES";}
			if (val.t_orden == "VER") {tipo="VERIFICACIONES";}
			if (val.t_orden == "SUP") {tipo="SUPERVICIONES";}
			if (val.t_orden == "INV") {tipo="INVESTIGACIONES";}
			if (val.t_orden == "AGE") {tipo="AGENTE ENCUBIERTO";}
			if (val.t_orden == "USI") {tipo="USUARIO SIMULADO";}
			fila += '<tr>';
				fila += '<td>'+tipo+'</td>';
				fila += '<td>'+val.cuenta+'</td>';
				fila += '<td>';
					fila += '<button type="button" class="btn btn-success btn-flat" onclick="load_detalle_oin('+"'"+val.t_orden+"'"+');"> <i class="fa fa-eye" ></i> </button>';
				fila += '</td>';
			fila += '</tr>';

		});
		fila += '<tr>';
			fila += '<td class="text-right">TOTAL</td>';
			fila += '<td colspan="2">'+suma+'</td>';
		fila += '</tr>';
		$('#tbl_ordenes').append(fila);		
		//Agregar los contadores de las actas
		var suma = 0,fila = "";
		$.each(response.actas, function(i, val) {
			var fila = "", ta = "";
			suma += parseInt(val.cuenta);
			if (val.ta == 'INSPECCION'){ta = "INSPECCIÓN"}
			if (val.ta == 'VERIFICACION'){ta = "VERIFICACIÓN"}
			if (val.ta == 'SUPERVISION'){ta = "SUPERVISIÓN"}
			if (val.ta == 'INVESTIGACION'){ta = "INVESTIGACIÓN"}else{
				ta = val.ta;
			}
			fila += '<tr>';
				fila += '<td>'+ta+'</td>';
				fila += '<td>'+val.cuenta+'</td>';
				fila += '<td>';
					fila += '<button type="button" class="btn btn-success btn-flat" onclick="verActas('+"'"+val.ta+"'"+');"> <i class="fa fa-eye" ></i> </button>';
				fila += '</td>';
			fila += '</tr>';
			$('#tbl_actas').append(fila);
		});
		fila += '<tr>';
			fila += '<td class="text-right">TOTAL GENERAL</td>';
			fila += '<td colspan="2">'+suma+'</td>';
		fila += '</tr>';
		$('#tbl_actas').append(fila);	
	})
	.fail(function(jqXHR,textStatus,errorThrow) {
		console.log("Error: "+jqXHR.responseText);
	});
	applyDataTables('tbl_abogados');
	return false;
}
function generateFormsMigrate() {
	var inputs = [];
	$('[name="c_queja[]"]:checked').each(function(i,el) {
		inputs.push($(this).val());
	});
	if(inputs.length == 0){
		alerta('alerta','error','Debe seleccionar uno o más elementos','');
	}else{
		location.href = "index.php?menu=turnado_multi&quejas="+inputs;
	}
	return false;
}
function load_catalogo(element,type,option){
	var result;
	if( element == 'conductas' ){
		var data = $('#t_ley').val();
	}else{
		var data = '0';
	}
	if ( element != '' ) {
		$('#'+element).html('');
	}
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: option, data:data},
		async:false,
		cache:false,
	})
	.done(function(response) {
		if ( type == 'select') {
			if( element == 'conductas' ){
				$('#conductas').attr('multiple', '');
				$('#conductas').select2();
			}
			$('#'+element).append('<option value="" >...</option>');
			$.each(response, function(index, val) {
				$('#'+element).append('<option value="'+val.id+'">'+val.nombre+'</option>');
			});
			result = false;
		}else{
			result = response;
			setInfo(result);
		}
	})
	.fail(function() {
		alert('Ocurrio un error al cargar el catalogo de opcion: '+option)
	});

	//Generar la clave del expediente
	if ( element == 't_tra' ) {
		generate_code(element);
	}
	if ( element == 't_ley' ) {
		$('#'+element).change(function(e){
			e.preventDefault();
			load_catalogo( 'conductas', 'select', 8);
		});
	}
		
}
function strarTransfer() {
	var inputs = [];
	var destino = $('#destino_id').val();
	$('[name="c_queja[]"]:checked').each(function() {
		inputs.push($(this).val());
	});
	if(inputs.length == 0){
		alerta('alerta','error','Debe seleccionar uno o más elementos','');
	}else{
		$.ajax({
			url: 'controller/puente.php',
			type: 'POST',
			dataType: 'json',
			data: {option: '84',quejas:inputs,destino:destino},
			async:false,
			cache:false
		})
		.done(function(response) {
			alerta('alerta',response.status,response.message,'');
		})
		.fail(function(jqXHR,textStatus,errorThrow) {
			console.log("Error");
		});
	}
	console.log( inputs.length );
	return false;
}
function frm_migracion() {
	$('#frm_migracion').submit(function(e) {
		e.preventDefault();

		$('#div_tbl').removeClass('hidden')
		if ($('#modo').val() == '1') {
			$('#btn_migrate').removeClass('hidden')
			$('#btn_change_edo').addClass('hidden')
		}else if($('#modo').val() == '2'){
			$('#btn_migrate').addClass('hidden')
			$('#btn_change_edo').removeClass('hidden')
		}
		var dataForm = $(this).serialize();
		$.ajax({
			url: 'controller/puente.php',
			type: 'POST',
			dataType: 'json',
			data: dataForm,
			async:false,
			cache:false,
		})
		.done(function(response) {
			$('#buscador_tbl').removeClass('hidden');
			$('#tbl_migrate tbody').html("");
			$.each(response, function(i, val) {
				var fila = "";

				fila += "<tr>";
					fila += "<td>";
						fila += '<label>';
						    fila += '<input type="checkbox" class="minimal c_quejas" name="c_queja[]" value="'+val.id+'">';
						fila += '</label>';
					fila += "</td>";
					fila += "<td>"+val.id+"</td>";
					fila += "<td>"+val.cve_exp+"</td>";
					fila += "<td>"+val.full_name+"</td>";
					fila += "<td>"+val.nombre+"</td>";
				fila += "</tr>";
				$('#tbl_migrate tbody').append(fila);
			});
			
			$('input[type="checkbox"].minimal').iCheck({
			  checkboxClass: 'icheckbox_minimal-blue'
			});
			$('#check_master').on('ifChecked', function(event){
				var conta = 0 ;
				$('.c_quejas').iCheck('check');
				$('[name="c_queja[]"]:checked').each(function() {
					conta++;
				});
				$('#cuenta').text(conta);
			});
			$('#check_master').on('ifUnchecked', function(event){
				$('.c_quejas').iCheck('uncheck');
				$('#cuenta').text('0');
			});
			$(".c_quejas").on('ifChecked', function(event){

				var conta = 0 ;
				$('[name="c_queja[]"]:checked').each(function() {
					conta++;
				});
				$('#cuenta').text(conta);
			});
			$('.c_quejas').on('ifUnchecked', function(event){
				var conta = 0 ;
				$('[name="c_queja[]"]:checked').each(function() {
					conta++;
				});
				$('#cuenta').text(conta);
			});

		})
		.fail(function(jqXHR,textStatus,errorThrow) {
			alerta('alerta','error',jqXHR.responseText,'');
		});
		
	});

	return false;
}
//CONSULAR LOS EXPEDIENTES DEL TABLERO DE CONTROL
function verExpedientes(tipo,num) {
	
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '81',valor:num,tipo:tipo},
		async:false,
		cache:false,
	})
	.done(function(response) {
		if ( $.fn.DataTable.isDataTable( '#tbl_general' ) ) {
			var t = $('#tbl_general').DataTable();
			t.destroy();
		}
		$('#tbl_general tbody').html("");
		$.each(response, function(i, val) {
			var fila = "";var c =0;
			c = i+1;
			fila += "<tr>";
				fila += "<td>"+c+"</td>";
				fila += "<td><a href='index.php?menu=cedula&exp="+val.id+"'>"+val.cve_exp+"</a></td>";
				fila += "<td>"+val.n_estado+"</td>";
				fila += "<td>"+val.n_procedencia+"</td>";
				fila += "<td>"+val.t_asunto+"</td>";
				fila += "<td>"+val.t_tramite+"</td>";
				fila += "<td>"+val.dias_t+"</td>";
			fila += "</tr>";
			$('#tbl_general').append(fila);
		});
		
		
		applyDataTables('tbl_general');
	})
	.fail(function(jqXHR,textStatus,errorThrow) {
		console.log("error");
	});
	
	return false;
}
//Funcion que permite aplicar DataTables en Español
function applyDataTables(t) {
	var tabla = $('#'+t).DataTable({
		dom: 'Bfrtip',
		buttons:{
		    buttons: [
		        { extend: 'pdf', className: 'btn btn-flat btn-warning',text:' <i class="fa  fa-file-pdf-o"></i>PDF' },
		        { extend: 'excel', className: 'btn btn-success btn-flat',text:' <i class="fa fa-file-excel-o"></i>Excel' }
		    ]
		},
		"language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "pageLength": 5
	});
	return tabla ;
}
//Autocompletado de informacion 
function autocomplete_input(input,hidden){
	var option ;
	if (input == 'expediente') {
		option = 12 
	}else if ( input == 'oficio' ){
		option = 10;
	}else{
		option = 3;
	}
	$('#'+input).autocomplete({
		source: "controller/puente.php?option="+option,
		select:function(event,ui){
			$('#'+hidden).val(ui.item.id);
		}
	});
	return false;
}
function frm_add_turno_multi() {
	var inputs = [];
	$('#frm_add_turno_multi').submit(function(e) {
		e.preventDefault();

		var dataForm;
		if ( $('#estado').val() == '3' ) {
			dataForm = new FormData(document.getElementById("frm_add_turno_multi"));
			p_ajax = {
			url: 'controller/puente.php',
			type: 'POST',
			dataType: 'json',
			data: dataForm,
			async:false,
			cache:false,
			processData: false,
            contentType: false,
		};
		}else if($('#estado').val() == '8'){
			dataForm = $(this).serializeArray();
			p_ajax = {
				url: 'controller/puente.php',
				type: 'POST',
				dataType: 'json',
				data: dataForm,
				async:false,
				cache:false,
			};
		}else{
			dataForm = $(this).serialize();
			p_ajax = {
				url: 'controller/puente.php',
				type: 'POST',
				dataType: 'json',
				data: dataForm,
				async:false,
				cache:false,
			};
		}
		
		$.ajax(p_ajax)
		.done(function(response) {
			alerta('div_turno',response.status, response.message,'');
			if (response.status == 'success') {
				setTimeout(function() {
					//Modificar la url 
					var actual = $('#queja_id').val();
					$('#list_e li').each(function(i, el) {
						if (actual != $(this).prop('id')) {
							inputs.push($(this).prop('id'));
						}
					});
					location.href = "index.php?menu=turnado_multi&quejas="+inputs;
				}, 5000);
			}
		})
		.fail(function(jqXHR,textStatus,errorThrown) {
			alerta('div_turno',response.status,jqXHR.responseText,'');
		});
		
	});//fin de submit
	
	return false;
}
/*cargar el detalle la OIN en el modal*/
function load_detalle_oin(tipo) {
	/*if (tipo == 1) { t = 'ins';}
	if (tipo == 2) { t = 'sup';}
	if (tipo == 3) { t = 'ver';}
	if (tipo == 4) { t = 'inv';}*/
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '85',tipo: tipo},
		async:false,
		cache:false,
	})
	.done(function(response) {
		if ( $.fn.DataTable.isDataTable( '#tbl_oin' ) ) {
		  $('#tbl_oin').DataTable().destroy();
		}
		$('#tbl_oin tbody').html("");
		var c = 0 ;
		$.each(response, function(i, val) {		
			c++;	
			var fila = "",f_finish = "",estado = "";
			fila += "<tr>";
				fila += "<td>"+c+"</td>";
				fila += "<td>"+val.clave+"</td>";
				fila += "<td>"+val.no_oficio+"</td>";
				fila += "<td>"+val.fecha_oficio+"</td>";
				fila += "<td>"+val.nom_completo+"</td>";
				if (val.estatus == null) {
					fila += "<td>EN PROCESO</td>";
					f_finish = "";
				}else{
					if( val.estatus == 'Cumplida' ){estado == "Cumplida con resultado";}else{
						estado = val.estatus;
					}
					fila += "<td>"+val.estatus+"</td>";
					f_finish = val.f_creacion;
				}
				fila += "<td>"+ f_finish +"</td>";
			fila += "</tr>";
			$('#tbl_oin').append(fila);
			
		});
		applyDataTables('tbl_oin');
	})
	.fail(function(jqXHR,textStatus,errorThrown) {
		console.log("Error:");
	});
	
	$('#modal_detalle_oin').modal('toggle');
}
/*mostrar las actas por tipo de actuacion*/
function verActas(t_tra) {
	
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '86',ta:t_tra},
	})
	.done(function(response) {
		if ( $.fn.DataTable.isDataTable( '#tbl_actas_detalle' ) ) {
			$('#tbl_actas_detalle').DataTable().destroy();
		}
		$('#tbl_actas_detalle tbody').html("");
		$.each(response, function(i, val) {
			var fila = "";
			fila += "<tr>";
				fila += "<td>"+val.id+"</td>";
				fila += "<td>"+val.clave+"</td>";
				fila += "<td>"+val.fecha+"</td>";
				fila += "<td>"+val.t_actuacion+"</td>";
				fila += "<td>"+val.procedencia+"</td>";
				fila += "<td>"+val.n_municipio+"</td>";
				fila += "<td>"+val.lugar+"</td>";
				fila += "<td>"+val.n_area+"</td>";
				fila += "<td>"+val.n_abogado+"</td>";
			fila += "</tr>";
			$('#tbl_actas_detalle tbody').append(fila);
		});
		$('#modal_detalle_actas').modal('toggle');
		applyDataTables('tbl_actas_detalle');
	})
	.fail(function(jqXHR,textStatus,errorThrown) {
		alerta('div_actuaciones','error',jqXHR.responseText,'');
	});
	
	return false;
}