var tbl ;
var total_general=0; // Permite sumar A. Policiales + A. Especiales
$(document).ready(function() {
	url = window.location.search;
	url = url.split('&');
	getURL(url[0]);
});
//Detectando la URL
function getURL(url) {
	var date = new Date();
	if ( url == '?menu=general' ) {
		$('#option_1').addClass('active');
		
		
		autocomplete_input('clave','clave_id',12);
		frm_coincidencias();
	}
	if ( url == '?menu=reportes' ) {
		$('#option_2').addClass('active');
	}if ( url == '?menu=reportes' ) {
		$('#option_2').addClass('active');
	}if ( url == '?menu=di' ) {
		$('#option_3').addClass('active');
		$('#year').change(function(e) {
			e.preventDefault();
			dashboard($(this).val());
			dashboard_a($(this).val());
			contador_actas($(this).val());
			e_tipo($(this).val());
		});
		dashboard('');
		dashboard_a('');
		contador_actas('');
		e_tipo('');
	}
	if(url == '?menu=dr'){
		//verExpedientes();
		tablero_ctrl();
		tablero_sc();
	}
	
	return false; 
}
//Tablero de control
function dashboard(year) {
	var fila = "", total_general = 0;
	$('#dash tbody tr').remove("");
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '71',y:year},
		async:false,
		cache:false,
	})
	.done(function(response) {
		var suma = 0,suma_e = 0;
		$.each(response['qdp'], function(i, val) {
			var fila = "";
			suma += parseInt(val.total);
			fila += "<tr>";
				fila += "<td>"+val.nombre+"</td>";
				fila += "<td>"+val.total+"</td>";
				fila += "<td>";
					fila += "<button class='btn btn-success btn-flat' onclick='getListadoEstado("+val.estado+")'>";
						fila += "<i class='fa fa-eye'></i> Ver expedientes";	
					fila += "</button>";
				fila += "</td>";
			fila += "</tr>";
			$('#dash').append(fila);

		});
		fila += "<tr>";
			fila += "<td class='text-right'>TOTAL GENERAL:</td>";
			fila += "<td colspan='2'>"+suma+"</td>";
		fila += "</tr>";
		total_general += parseInt(suma);
		$('#dash').append(fila);
		var fila = "";
		$('#dash_especiales tbody').html('');
		$.each(response['qdnp'], function(i, val) {
			var fila = "";
			suma_e += parseInt(val.total);
			fila += "<tr>";
				fila += "<td>"+val.nombre+"</td>";
				fila += "<td>"+val.total+"</td>";
				fila += "<td>";
					fila += "<button class='btn btn-success btn-flat' onclick='getListadoEstadoNP("+val.estado+")'>";
						fila += "<i class='fa fa-eye'></i> Ver expedientes";	
					fila += "</button>";
				fila += "</td>";
			fila += "</tr>";
			$('#dash_especiales').append(fila);
		});
		fila += "<tr>";
			fila += "<td class='text-right'>TOTAL GENERAL:</td>";
			fila += "<td colspan='2'>"+suma_e+"</td>";
		fila += "</tr>";
		total_general += parseInt(suma_e);
		$('#dash_especiales').append(fila);
		$('#suma').text(total_general);
	})
	.fail(function(jqXHR,textStatus,errorThrow) {
		console.log("error");
	});
	
	return false;
}

//cONTADOR DE EXPEDIENTES POR TIPO  
function e_tipo(year) {
	var fila = "", total_general = 0;
	
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '115',y:year},
		async:false,
		cache:false,
	})
	.done(function(response) {
		$('#tbl_tipos_qdp tbody').html('');
		$('#tbl_tipos_especial tbody').html('');
		var suma = 0,suma_e = 0;
		$.each(response['policial'], function(i, val) {
			var fila = "";
			suma += parseInt(val.cuenta);
			fila += "<tr>";
				fila += "<td>"+val.nombre+"</td>";
				fila += "<td>"+val.cuenta+"</td>";
				fila += "<td>";
					fila += "<button class='btn btn-success btn-flat' onclick='getListadoTipo("+val.t_tramite+",1)'>";
						fila += "<i class='fa fa-eye'></i> Ver expedientes";	
					fila += "</button>";
				fila += "</td>";
			fila += "</tr>";
			$('#tbl_tipos_qdp tbody').append(fila);
		});
		fila += "<tr>";
			fila += "<td class='text-right'>TOTAL GENERAL:</td>";
			fila += "<td colspan='2'>"+suma+"</td>";
		fila += "</tr>";
		total_general += parseInt(suma);
		$('#tbl_tipos_qdp tbody').append(fila);
		var fila = "";
		suma = 0;
		$.each(response['especial'], function(i, val) {
			var fila = "";
			suma += parseInt(val.cuenta);
			fila += "<tr>";
				fila += "<td>"+val.nombre+"</td>";
				fila += "<td>"+val.cuenta+"</td>";
				fila += "<td>";
					fila += "<button class='btn btn-success btn-flat' onclick='getListadoTipo("+val.t_tramite+",2)'>";
						fila += "<i class='fa fa-eye'></i> Ver expedientes";	
					fila += "</button>";
				fila += "</td>";
			fila += "</tr>";
			$('#tbl_tipos_especial tbody').append(fila);
		});
		fila += "<tr>";
			fila += "<td class='text-right'>TOTAL GENERAL:</td>";
			fila += "<td colspan='2'>"+suma+"</td>";
		fila += "</tr>";
		total_general += parseInt(suma);
		$('#tbl_tipos_especial tbody').append(fila);
		
		
	})
	.fail(function(jqXHR,textStatus,errorThrow) {
		console.log("error");
	});
	
	return false;
}
function getListadoTipo(tramite, asunto ) {
	$('#qd_estado').removeClass('hidden');
	$('#actas').addClass('hidden');
	$('#div_oins').addClass('hidden');
	$('#tbl_ee tbody').html("");
	var y = $('#year').val();
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '116', y:y, t_tramite:tramite, t_asunto:asunto},
		async:false,
		cache:false
	})
	.done(function(response) {
		if ( ! $.fn.DataTable.isDataTable( '#tbl_ee' ) ) 
		{
			tbl = applyDataTables('tbl_ee');
		}else
		{
			tbl.rows().remove().draw();
		}
		$.each(response, function(i, val) {
			var fila = "";
			fila += "<ul>";
						fila += "<li><label>Fecha:</label>"+val.f_hechos+"</li>";
						fila += "<li><label>Hora: </label>"+val.h_hechos+"</li>";
					fila += "</ul>";
			var cve_link = '<a href="index.php?menu=cedula&exp='+val.id+'">'+val.cve_exp+'</a>';
			tbl.row.add( [
	            (++i),
	            cve_link,
	            val.t_asunto,
	            val.n_tramite ,
	            val.n_procedencia,
	            fila
	        ] ).draw( false );
			
		});
	})
	.fail(function() {
		console.log("error");
	});
	
}
//Autocompletado de informacion 
function autocomplete_input(input,hidden,option){
	$('#'+input).autocomplete({
		source: "controller/puente.php?option="+option,
		select:function(event,ui){
			if (input == 'clave') {
				window.open('index.php?menu=cedula&exp='+ui.item.id,'_blank');
			}else{
				$('#'+hidden).val(ui.item.id);
			}
		}
	});
	return false;
}
//Funcion que permite aplicar DataTables en Español
function applyDataTables(t) {
	var tabla = $('#'+t).DataTable({
		dom: 'Bfrtip',
		buttons:{
		    buttons: [
		        //{ extend: 'pdf', className: 'btn btn-flat btn-warning',text:' <i class="fa  fa-file-pdf-o"></i>PDF' },
		        { extend: 'excel', className: 'btn btn-success btn-flat',text:' <i class="fa fa-file-excel-o"></i>Excel' }
		    ]
		},
		"language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        }
	});
	return tabla ;
}
function getListadoEstado(estado) {
	$('#qd_estado').removeClass('hidden');
	$('#actas').addClass('hidden');
	$('#div_oins').addClass('hidden');
	$('#tbl_ee tbody').html("");
	var year = $('#year').val();
	
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '72',y:year,e:estado},
		async:false,
		cache:false,
	})
	.done(function(response) {
		if ( ! $.fn.DataTable.isDataTable( '#tbl_ee' ) ) 
		{
			tbl = applyDataTables('tbl_ee');
		}else
		{
			tbl.rows().remove().draw();
		}
		$.each(response, function(i, val) {
			var fila = "";
			fila += "<ul>";
						fila += "<li><label>Fecha:</label>"+val.f_hechos+"</li>";
						fila += "<li><label>Hora: </label>"+val.h_hechos+"</li>";
					fila += "</ul>";
			var cve_link = '<a href="index.php?menu=cedula&exp='+val.id+'">'+val.cve_exp+'</a>';
			tbl.row.add( [
	            (++i),
	            cve_link,
	            val.t_asunto,
	            val.n_tramite ,
	            val.n_procedencia,
	            fila
	        ] ).draw( false );
			
		});

	})
	.fail(function(jqXHR,textStatus,errorThrow) {
		console.log("error");
	});	
}
function getListadoEstadoNP(estado) {
	$('#qd_estado').removeClass('hidden');
	$('#actas').addClass('hidden');
	$('#div_oins').addClass('hidden');
	$('#tbl_ee tbody').html("");
	var year = $('#year').val();
	
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '72B',y:year,e:estado},
		async:false,
		cache:false,
	})
	.done(function(response) {
		if ( ! $.fn.DataTable.isDataTable( '#tbl_ee' ) ) 
		{
			tbl = applyDataTables('tbl_ee');
		}else
		{
			tbl.rows().remove().draw();
		}
		$.each(response, function(i, val) {
			var fila = "";
			fila += "<ul>";
						fila += "<li><label>Fecha:</label>"+val.f_hechos+"</li>";
						fila += "<li><label>Hora: </label>"+val.h_hechos+"</li>";
					fila += "</ul>";
			var cve_link = '<a href="index.php?menu=cedula&exp='+val.id+'">'+val.cve_exp+'</a>';
			tbl.row.add( [
	            (++i),
	            cve_link,
	            val.t_asunto,
	            val.n_tramite ,
	            val.n_procedencia,
	            fila
	        ] ).draw( false );
			
		});

	})
	.fail(function(jqXHR,textStatus,errorThrow) {
		console.log("error");
	});	
}

//Tablero de control de las actuaciones
function dashboard_a(year) {
	var fila = "";
	$('#dash_a tbody tr').remove("");
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '73',y:year},
		async:false,
		cache:false,
	})
	.done(function(response) {
		var suma = 0;
		$.each(response, function(i, val) {
			var fila = "";
			suma += parseInt(val.total);
			var t_acta = "";
			if (val.nombre == 'SUPERVISION') { t_acta = "SUPERVISIÓN"; }
			if (val.nombre == 'VERIFICACION') { t_acta = "VERIFICACIÓN"; }
			if (val.nombre == 'INVESTIGACION') { t_acta = "INVESTIGACIÓN"; }
			if (val.nombre == 'INSPECCION') { t_acta = "INSPECCIÓN"; }
			fila += "<tr>";
				fila += "<td>"+t_acta+"</td>";
				fila += "<td>"+val.total+"</td>";
				fila += "<td>";
					fila += "<button class='btn btn-success btn-flat' onclick='getListadoAcutaciones("+'"'+val.nombre+'"'+")'>";
						fila += "<i class='fa fa-eye'></i> Ver Actas";	
					fila += "</button>";
				fila += "</td>";
			fila += "</tr>";
			$('#dash_a').append(fila);
		});
		fila += "<tr>";
			fila += "<td class='text-right'>TOTAL GENERAL:</td>";
			fila += "<td colspan='2'>"+suma+"</td>";
		fila += "</tr>";
		$('#dash_a').append(fila);
	})
	.fail(function(jqXHR,textStatus,errorThrow) {
		console.log("error");
	});
	
	return false;
}
function getListadoAcutaciones(actuacion) {
	$('#actas').removeClass('hidden');
	$('#qd_estado').addClass('hidden');
	$('#div_oins').addClass('hidden');
	//$('#tbl_actas tbody').html("");
	var year = $('#year').val();
	
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '74',y:year,a:actuacion},
		async:false,
		cache:false,
	})
	.done(function(response) {
		if ( ! $.fn.DataTable.isDataTable( '#tbl_actas' ) ) 
		{
			tbl = applyDataTables('tbl_actas');
		}
		else
		{
			tbl.rows().remove().draw();
		}
		$.each(response, function(i, val) {
			tbl.row.add( [
	            (++i),
	            val.clave,
	            val.elaboro,
	            val.fecha ,
	            val.procedencia
	        ] ).draw( false );
			
		});

	})
	.fail(function(jqXHR,textStatus,errorThrow) {
		console.log("error");
	});	
}
//
function frm_coincidencias() {
	$('#frm_coincidencias').submit(function(e) {
		e.preventDefault();
		$('#actas').addClass('hidden');
		$('#qd_estado').removeClass('hidden');
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
			if ( ! $.fn.DataTable.isDataTable( '#tbl_ee' ) ) 
			{
				tbl = applyDataTables('tbl_ee');
			}
			else
			{
				tbl.rows().remove().draw();
			}
			$.each(response, function(i, val) {
				var fila = "";
				fila += "<ul>";
					fila += "<li><label>Fecha:</label>"+val.f_hechos+"</li>";
					fila += "<li><label>Hora: </label>"+val.h_hechos+"</li>";
				fila += "</ul>";
				var cve_link = '<a href="index.php?menu=cedula&exp='+val.id+'">'+val.cve_exp+'</a>';
				tbl.row.add( [
		            (++i),
		            cve_link,
		            val.t_asunto,
		            val.n_tramite ,
		            val.n_procedencia,
		            fila
		        ] ).draw( false );
			});			
		})
		.fail(function(jqXHR,textStatus,errorThrow) {
			console.log("Error:"+jqXHR.responseText);
		});
		
	});
	return false;
}
function contador_actas(year) {
	$('#tbl_ordenes tbody').html("");
	var suma = 0;
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '87',y:year},
		async:false,
		cache:false,
	})
	.done(function(response) {
		console.log(response);
		$.each(response, function(i, val) {
			var fila = "", t_orden = "";
			suma += parseInt(val.cuenta);
			if (val.t_orden == 'INS') { t_orden = 'INSPECCIÓN'; }
			if (val.t_orden == 'VER') { t_orden = 'VERIFICACIÓN'; }
			if (val.t_orden == 'SUP') { t_orden = 'SUPERVISIÓN'; }
			if (val.t_orden == 'INV') { t_orden = 'INVESTIGACIÓN'; }
			if (val.t_orden == 'AGE') { t_orden = 'AGENTE ENCUBIERTO'; }
			if (val.t_orden == 'USI') { t_orden = 'USUARIO SIMULADO'; }
			fila += '<tr>';
				fila += '<td>'+t_orden+'</td>';
				fila += '<td>'+val.cuenta+'</td>';
				fila += '<td>'
					fila += '<button class="btn btn-flat btn-success" onclick="verOIN('+"'"+val.t_orden+"'"+')"> <i class="fa fa-eye"></i> Mostrar </button>';
				fila += '</td>';
			fila += '</tr>';
			$('#tbl_ordenes tbody').append(fila)
		});
		var fila = "";
		fila += '<tr>';
			fila += '<td>TOTAL: </td>';
			fila += '<td colspan="2">'+suma+'</td>';
			
		fila += '</tr>';
		$('#tbl_ordenes tbody').append(fila)
	})
	.fail(function(jqXHR,textStatus,errorThrow) {
		console.log("error");
	});
	
	return false;
}
function verOIN(tipo) {
	//alert('Buscar: '+tipo);
	var y = $('#year').val();
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '88',t:tipo,y:y},
		async:false,
		cache:false,
	})
	.done(function(response) {
		console.log(response);
		if ( $.fn.DataTable.isDataTable( '#tbl_oins' ) ) 
		{
			$('#tbl_oins').DataTable().destroy();
		}
		$('#qd_estado, #actas').addClass('hidden');
		$('#div_oins').removeClass('hidden');
		$('#tbl_oins tbody').html("");
		var c = 1;
		$.each(response, function(i, val) {
			var fila = "", t_orden = "", enlace = ""; 
			if (val.t_orden == 'INS') { t_orden = 'INSPECCIÓN'; }
			if (val.t_orden == 'VER') { t_orden = 'VERIFICACIÓN'; }
			if (val.t_orden == 'SUP') { t_orden = 'SUPERVISIÓN'; }
			if (val.t_orden == 'INV') { t_orden = 'INVESTIGACIÓN'; }
			if (val.t_orden == 'AGE') { t_orden = 'AGENTE ENCUBIERTO'; }
			if (val.t_orden == 'USI') { t_orden = 'USUARIO SIMULADO'; }
			if (val.nom_completo == null) {
				enlace = "NO SE REGISTRO NOMBRE DEL ENLACE OPERATIVO.";
			}else{
				enlace = val.nom_completo;
			}
			fila += '<tr>';
				fila += '<td>'+c+'</td>';
				fila += '<td>'+val.clave+'</td>';
				fila += '<td>'+(( val.no_oficio == null ) ? 'NO SE ENCONTRO EL NÚMERO DE OFICIO': val.no_oficio )+'</td>';
				fila += '<td>'+enlace+'</td>';
				//fila += '<td>'+val.estatus+'</td>';
				fila += '<td>'+val.f_creacion+'</td>';
			fila += '</tr>';
			$('#tbl_oins').append(fila);
			c++;
		});
		applyDataTables('tbl_oins');
	})
	.fail(function(jqXHR,textStatus,errorThrow) {
		console.log("Error: "+jqXHR.responseText);
	});
	
	return false;
}
//CONSULAR LOS EXPEDIENTES DEL TABLERO DE CONTROL
function verExpedientes(num) {
	
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '79',tipo:num},
		async:false,
		cache:false,
	})
	.done(function(response) {
		$('#tbl_general tbody').html("");
		$.each(response, function(i, val) {
			var fila = "";var c =0;
			c = i+1;
			fila += "<tr>";
				fila += "<td>"+c+"</td>";
				fila += "<td><a href='index.php?menu=cedula&exp="+val.id+"' target='__blank'>"+val.cve_exp+"</a></td>";
				fila += "<td>"+val.oficio+"</td>";
				fila += "<td>"+val.n_procedencia+"</td>";
				fila += "<td>"+val.e_procesal+"</td>";
			fila += "</tr>";
			$('#tbl_general').append(fila);
		});
		
	})
	.fail(function(jqXHR,textStatus,errorThrow) {
		console.log("error");
	});
	
	return false;
}

function tablero_ctrl() {
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '78'},
		async:false,
		cache:false,
	})
	.done(function(response) {
		/*************EXPEDIENTES EN ARCHIVO IMPROCEDENCIA, RESERVA E INCOMPETENCIA **************/
		$.each(response.q_estados, function(i, val) {
			var fila = "";
			fila += "<tr class='text-center'>";
				fila += "<td>"+val.nombre+"</td>";
				fila += "<td>"+val.cuenta+"</td>";
				fila += "<td>";
					fila += "<button class='btn btn-success btn-flat' onclick='verListadoByEdo("+val.estado+")'><i class='fa fa-legal'></i></button>";
				fila +="</td>";
			fila += "</tr>";
			$('#tbl_sc tbody').append(fila);
		});
		//Agregar el contenido de SAPA
		$('#tbl_sapa tbody').append('<tr class="text-center">'+
			'<td>EXPEDIENTES EN CHyJ</td>'+
			'<td>'+response.sapa.chyj+'</td>'+
			'<td>'+
				'<button type="button" onclick="verExpedientes(3);" class="btn btn-success btn-flat"> <i class="fa fa-eye"></i> </button>'+
			'</td>'+
		'</tr>');
		$('#tbl_sapa tbody').append('<tr class="text-center">'+
			'<td>EXPEDIENTES ENVIADOS A LA SUBD. DE LO CONTENCIOSO</td>'+
			'<td>'+response.sapa.sc+'</td>'+
			'<td>'+
				'<button type="button" onclick="verExpedientes(4);" class="btn btn-success btn-flat"> <i class="fa fa-eye"></i> </button>'+
			'</td>'+
		'</tr>');
	})
	.fail(function(jqXHR,textStatus,errorThrow) {
		console.log("Error: "+jqXHR.responseText);
	});
	
	return false;
}
function open_modal(modal,valor,input) {
	$('#'+modal).modal('show');
	$('#'+input).val(valor);
	if (modal == 'modal_situacion') {
		situacion_sc(valor);
	}
	return false;
}
function situacion_sc(estado) {
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option:'104',edo: estado},
		async:false,
		cache:false,
	})
	.done(function(response) {
		$('#td_con').text(response.acuerdos_con);
		$('#td_sin').text(response.acuerdos_sin);
		//resoluciones
		var sum_san = 0;
		$.each(response.sanciones, function(i, val) {
			if (val.sancion == 'SANCIONADO') {
				sum_san = sum_san + parseInt(val.cuenta);
				$('#con_sancion').text(val.cuenta);
			}else{
				sum_san = sum_san + parseInt(val.cuenta);
				$('#sin_sancion').text(val.cuenta);
			}
			console.log(sum_san);
		});
		$('#suma_res').text(sum_san);
	})
	.fail(function(jqXHR,textStatus,errorThrown) {
		console.log("error");
	});
	
	return false;
}
//Tablero de control de la SC 
function tablero_sc() {
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '105'},
		async:false,
		cache:false,
	})
	.done(function(response) {
		$('#cuenta_chyj').text(response.cuenta);
	})
	.fail(function(jqXHR,textStatus,errorThrown) {
		console.log("error");
	});
	return false;
}
//
function cargarTablas() {
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '78'},
		async:false,
		cache:false,
	})
	.done(function(response) {
		$('#por_demanda tbody').html('');
		$('#res_prim_dem tbody').html('');
		$('#res_rr_dem tbody').html('');
		$.each(response.demandas, function(i, val) {
			if (val.t_demanda == 'RECURSO DE REVISION') { t_demanda = 'RECURSO DE REVISIÓN'}else{ t_demanda = val.t_demanda }
			$('#por_demanda').append(
				'<tr class="text-center">'+
					'<td>'+t_demanda+'</td>'+
					'<td>'+val.cuenta+'</td>'+
					'<td>'+
						'<button class="btn btn-success btn-flat" onclick="verExpByDemanda('+"'"+val.t_demanda+"'"+');">'+
							'<i class="fa fa-eye"></i>'+
						'</button>'+
					'</td>'+
				'</tr>'
			);
		});
		$.each(response.res_primer_d, function(i, val) {
			$('#res_prim_dem').append(
				'<tr class="text-center">'+
					'<td>'+val.resultado+'</td>'+
					'<td>'+val.cuenta+'</td>'+
					'<td>'+
						'<button type="button" onclick="verExpByEdoDem('+"'"+val.resultado+"', 1"+');" class="btn btn-success btn-flat"> <i class="fa fa-eye"></i> </button>'+
					'</td>'+
				'</tr>'
			);
		});
		$.each(response.res_rr_d, function(i, val) {
			$('#res_rr_dem').append(
				'<tr class="text-center">'+
					'<td>'+val.resultado+'</td>'+
					'<td>'+val.cuenta+'</td>'+
					'<td>'+
						'<button type="button" onclick="verExpByEdoDem('+"'"+val.resultado+"', 2"+');" class="btn btn-success btn-flat"> <i class="fa fa-eye"></i> </button>'+
					'</td>'+
				'</tr>'
			);
		});
		$.each(response.res_chyj, function(i, val) {
			$('#tbl_res_chyj').append(
				'<tr class="text-center">'+
					'<td>'+val.sancion+'</td>'+
					'<td>'+val.cuenta+'</td>'+
					'<td>'+
						'<button type="button" onclick="verExpByResCom('+"'"+val.sancion+"'"+');" class="btn btn-success btn-flat"> <i class="fa fa-eye"></i> </button>'+
					'</td>'+
				'</tr>'
			);
		});
		
		$('#cuenta_apersona').text(response.apersona.cuenta);
		
	})
	.fail(function(jqXHR,textStatus,errorThrown) {
		console.log("error");
	});
	
	return false;
}

function verExpByDemanda(t_demanda) {
	var td ;
	if (t_demanda == 'PRIMER DEMANDA') {td = 1;}
	if (t_demanda == 'RECURSO DE REVISION') {td = 2;}
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '106', td:td},
		async:false,
		cache:false,
	})
	.done(function(response) {
		$('#tbl_global tbody').html('');
		var fila = "";
		$.each(response, function(index, val) {
			var jefe = "";
			if (val.name_jefe !== null) {
				jefe = val.name_jefe;
			}else{
				jefe = "NO ASIGNADO";
			}
			fila += "<tr>";
				fila += "<td>"+val.id+"</td>";
				fila += "<td><a href='index.php?menu=cedula&exp="+val.id+"'>"+val.cve_exp+"</a></td>";
				fila += "<td>"+( val.f_chyj == null ? 'NO SE REGISTRO' : val.f_chyj )+"</td>";
				fila += "<td>"+( val.f_trijaem == null ? 'NO SE REGISTRO' : val.f_trijaem )+"</td>";
				fila += "<td>"+jefe+"</td>";
				fila += "<td>"+val.name_abogado+"</td>";
				fila += "<td>"+val.u_oficio+"</td>";
				fila += "<td>"+( val.f_acuerdo == null ? 'NO SE REGISTRO' : val.f_acuerdo )+"</td>";
				fila += "<td>"+( val.asunto == null ? 'NO SE REGISTRO' : val.asunto )+"</td>";
				fila += "<td>";
				if (typeof val.apersona == 'object') {
					fila += "<ol>";
					$.each(val.apersona, function(ii, value) {
						fila += "<li >";
							fila += "<ul type='none'>";
								fila += "<li><b>Oficio: </b> "+value.oficio+"</li>";
								fila += "<li><b>Fecha Of: </b> "+value.f_oficio+"</li>";
								fila += "<li><b>Fecha aper: </b> "+( value.f_apersonamiento == null ? 'NO SE REGISTRO' : value.f_apersonamiento )+"</li>";
							fila += "</ul>";
						fila +="</li>";
					});
					fila += "</ol>";
				}else{
					fila += "SIN APERSONAMIENTOS ";
				}
				
				fila += "</td>";
			fila += "</tr>";
			$('#tbl_global').append(fila);
		});
	})
	.fail(function(jqXHR,textStatus,errorThrown) {
		console.log("error");
	});
	return false;
}
//Ver expedientes por resolicion de la comision
function verExpByResCom(res) {
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '107', r:res},
		async:false,
		cache:false,
	})
	.done(function(response) {
		$('#tbl_global tbody').html('');
		var fila = "";
		$.each(response, function(index, val) {
			var jefe = "";
			if (val.name_jefe !== null) {
				jefe = val.name_jefe;
			}else{
				jefe = "NO ASIGNADO";
			}
			fila += "<tr>";
				fila += "<td>"+val.id+"</td>";
				fila += "<td><a href='index.php?menu=cedula&exp="+val.id+"'>"+val.cve_exp+"</a></td>";
				fila += "<td>"+( val.f_chyj == null ? 'NO SE REGISTRÓ' : val.f_chyj )+"</td>";
				fila += "<td>"+( val.f_trijaem == null ? 'NO SE REGISTRÓ' : val.f_trijaem )+"</td>";
				fila += "<td>"+jefe+"</td>";
				fila += "<td>"+val.name_abogado+"</td>";
				fila += "<td>"+val.u_oficio+"</td>";
				fila += "<td>"+( val.f_acuerdo == null ? 'NO SE REGISTRO' : val.f_acuerdo )+"</td>";
				fila += "<td>"+( val.asunto == null ? 'NO SE REGISTRO' : val.asunto )+"</td>";
				fila += "<td>";
				if (typeof val.apersona == 'object') {
					fila += "<ol>";
					$.each(val.apersona, function(ii, value) {
						fila += "<li >";
							fila += "<ul type='none'>";
								fila += "<li><b>Oficio: </b> "+value.oficio+"</li>";
								fila += "<li><b>Fecha Of: </b> "+value.f_oficio+"</li>";
								fila += "<li><b>Fecha aper: </b> "+( value.f_apersonamiento == null ? 'NO SE REGISTRO' : value.f_apersonamiento )+"</li>";
							fila += "</ul>";
						fila +="</li>";
					});
					fila += "</ol>";
				}else{
					fila += "SIN APERSONAMIENTOS ";
				}
				
				fila += "</td>";
			fila += "</tr>";
			$('#tbl_global').append(fila);
		});
	})
	.fail(function(jqXHR,textStatus,errorThrown) {
		console.log("error");
	});
	return false;
}
//Mostrar los expedientes que cuenten con la primer demanda en alguno de los estatus 
function verExpByEdoDem(tipo,dema) {
		$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '108', edo:tipo, d:dema},
		async:false,
		cache:false,
	})
	.done(function(response) {
		$('#tbl_global tbody').html('');
		var fila = "";
		$.each(response, function(index, val) {
			var jefe = "";
			if (val.name_jefe !== null) {
				jefe = val.name_jefe;
			}else{
				jefe = "NO ASIGNADO";
			}
			fila += "<tr>";
				fila += "<td>"+val.id+"</td>";
				fila += "<td><a href='index.php?menu=cedula&exp="+val.id+"'>"+val.cve_exp+"</a></td>";
				fila += "<td>"+( val.f_chyj == null ? 'NO SE REGISTRO' : val.f_chyj )+"</td>";
				fila += "<td>"+( val.f_trijaem == null ? 'NO SE REGISTRO' : val.f_trijaem )+"</td>";
				fila += "<td>"+jefe+"</td>";
				fila += "<td>"+val.name_abogado+"</td>";
				fila += "<td>"+val.u_oficio+"</td>";
				fila += "<td>"+( val.f_acuerdo == null ? 'NO SE REGISTRO' : val.f_acuerdo )+"</td>";
				fila += "<td>"+( val.asunto == null ? 'NO SE REGISTRO' : val.asunto )+"</td>";
				fila += "<td>";
				if (typeof val.apersona == 'object') {
					fila += "<ol>";
					$.each(val.apersona, function(ii, value) {
						fila += "<li >";
							fila += "<ul type='none'>";
								fila += "<li><b>Oficio: </b> "+value.oficio+"</li>";
								fila += "<li><b>Fecha Of: </b> "+value.f_oficio+"</li>";
								fila += "<li><b>Fecha aper: </b> "+( value.f_apersonamiento == null ? 'NO SE REGISTRO' : value.f_apersonamiento )+"</li>";
							fila += "</ul>";
						fila +="</li>";
					});
					fila += "</ol>";
				}else{
					fila += "SIN APERSONAMIENTOS ";
				}
				
				fila += "</td>";
			fila += "</tr>";
			$('#tbl_global').append(fila);
		});
	})
	.fail(function(jqXHR,textStatus,errorThrown) {
		console.log("error");
	});
	return false;
}
function verListadoByEdo(estado) {
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option:'113', edo:estado},
		async:false,
		cache:false,
	})
	.done(function(response) {
		$('#tbl_sapa_res tbody').html("");
		$.each(response, function(i, val) {
			var fila = "";
			fila += '<tr>';
				fila += '<td>'+val.id+'</td>';
				fila += '<td> <a href="index.php?menu=cedula&exp='+val.id+'" target="__blank">'+val.cve_exp+'</a></td>';
				fila += '<td>'+val.jefe+'</td>';
				fila += '<td>'+val.analista+'</td>';
				fila += '<td>'+val.oficio+'</td>';
				fila += '<td>'+val.f_acuerdo+'</td>';
				fila += '<td>'+val.comentario+'</td>';
			fila += '</tr>';
			$('#tbl_sapa_res').append(fila);
		});
	})
	.fail(function(jqXHR,textStatus,errorThrown) {
		console.log("Error: "+jqXHR.responseText);
	});
	return false;
}