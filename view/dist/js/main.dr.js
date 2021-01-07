$(document).ready(function() {
	url = window.location.search;
	url = url.split('&');
	getURL(url[0]);
});
//Detectando la URL
function getURL(url) {
	if ( url == '?menu=general' ) {
		$('#option_1').addClass('active');	
		if ( $('#nivel').val() == 'ANALISTA' ) {
			getCorrespondencia();
		}else{
			tablero_sc();
			tablero_ctrl();
		}
		autocomplete_input('oficio_dr','oficio_dr_id',10);
	}
	if ( url == '?menu=list_exp' ) {
		$('#option_2').addClass('active');
		frm_send_sapa();	
		getCorrespondencia();
		frm_add_acuse();
	}
	return false; 
}
//tablero de control
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
		console.log(response);
		//Agregar contenido a la SC
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

		$('#tbl_xxx tbody').append('<tr class="text-center">'+
			'<td>EXPEDIENTES EN CHyJ</td>'+
			'<td>'+response.sapa.chyj+'</td>'+
			'<td>'+
				'<button type="button" onclick="verExpedientes(3);" class="btn btn-success btn-flat"> <i class="fa fa-eye"></i> </button>'+
			'</td>'+
		'</tr>');
		$('#tbl_xxx tbody').append('<tr class="text-center">'+
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
		$('#tbl_res_chyj tbody').html('');
		$.each(response.demandas, function(i, val) {
			var t_demanda = val.t_demanda;
			if (t_demanda == 'RECURSO DE REVISION') {
				t_demanda = 'RECURSO DE REVISIÓN';
			}
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
//Recuperar los oficios de los expedientes turnados
function getCorrespondencia() {
	var tabla = $("#correspondencia").anexGrid({
	    class: 'table-striped table-bordered table-hover',
	    columnas: [
	    	{ leyenda: 'Acciones', style: 'width:100px;', columna: 'Sueldo' },
	    	{ leyenda: 'ID', style:'width:20px;'},
	        { leyenda: 'Número de oficio (D.I.)', style: 'width:200px;', columna: ''},
	        { leyenda: 'Fecha/Hora de alta', columna: 'fecha', filtro: false },
	        { leyenda: 'Expedientes adjuntos', style: 'width:300px;', columna: '' },
	        { leyenda: 'Estado', style: 'width:120px;', columna: ''},
	        { leyenda: 'Fecha de recepción', style: 'width:120px;', columna: ''},
	        { leyenda: 'Fecha de acuse', style: 'width:120px;', columna: ''},
	        { leyenda: 'Fecha de turnado a S.A.P.A', style: 'width:120px;', columna: ''},
	    ],
	    modelo: [
	    	
	    	{ class:'',formato: function(tr, obj, valor){
	    		var options; 
	    		if (obj.f_acuse != 'SIN ACUSE') {
	    			if (obj.f_sapa == 'SIN TURNAR') {
		    			options = [
	                        { href: "javascript:open_modal('modal_send_sapa','"+obj.oficio+"','oficio_inv');", contenido: '<i class="fa fa-mail-forward"></i> Turnar a S.A.G.' },
	                        { href: "controller/puente.php?option=4A&o="+obj.oficio, contenido: '<i class="fa fa-eye"></i> Ver acuse' },
	                    ];
	    			}else{
	    				options = [
	                        { href: "controller/puente.php?option=4A&o="+obj.oficio, contenido: '<i class="fa fa-eye"></i> Ver acuse' },
	                    ];
	    			}
	    			
	    		}else{
	    			options = [
                        { href: "javascript:open_modal('modal_add_acuse',"+"'"+obj.oficio+"'"+",'oficio');", contenido: '<i class="glyphicon glyphicon-cloud"></i> Alta de acuse' },
                    ];
	    		}
	            return anexGrid_dropdown({
                    contenido: '<i class="glyphicon glyphicon-cog"></i>',
                    class: 'btn btn-primary ',
                    attr: [],
                    id: 'editar',
                    data: options
                });
	        }},
	    	
	        { class:'', formato:function (tr, obj, valor) {
	        	if (obj.t_tramite == 'ENVIADO') {
	        		tr.addClass('bg-aqua');
	        	}else{
	        		tr.addClass('bg-green');
	        	}
	        	return obj.id;
	        }},
	        { propiedad: 'oficio' },
	        { propiedad: 'fecha' },
	        { class:'', formato:function (tr, obj, valor) {
	        	var fila = "";
	        	fila += "<ul>";
	        	$.each(obj.claves, function(i, val) {
	        		var enlace = "";
	        		enlace = '<a target="_blank" class="link" href="index.php?menu=cedula&exp_id='+val.id+'">'+val.cve_exp+'</a>';
	        		fila += "<li>"+enlace+"</li>";
	        	});
	        	fila += "</ul>";
	        	return fila;
	        }},
	        { propiedad: 't_tramite'},
	        { propiedad: 'f_oficio'},
	        { propiedad: 'f_acuse'},
	        { propiedad: 'f_sapa'},
	        
	    ],
	    url: 'controller/puente.php?option=14',
	    filtrable: false,
	    columna: 'id',
	    columna_orden: 'DESC'
	});
	return tabla;
}

function open_modal(modal,valor,input) {
	$('#'+modal).modal('show');
	$('#'+input).val(valor);
	if (modal == 'modal_situacion') {
		situacion_sc(valor);
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
function frm_add_acuse() {
	$('#frm_add_acuse').submit(function(e) {
		e.preventDefault();
		var dataForm = new FormData(document.getElementById("frm_add_acuse"));
		$.ajax({
			url: 'controller/puente.php',
			type: 'POST',
			dataType: 'json',
			data: dataForm,
			async:false,
			cache:false,
			processData: false,
            contentType: false,
		})
		.done(function(response) {
			alerta('div_acuse',response.status,response.message,'modal_send_sapa');
			getCorrespondencia();
		})
		.fail(function(jqXHR,textStatus,errorThrown) {
			alerta('div_acuse','error',jqXHR.responseText,'modal_send_sapa');
		});
	});
	return false;
}
//Enviar a sapa
function sendSAPA(oficio){
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '90',oficio:oficio},
		async:false,
		cache:false,
	})
	.done(function(response) {
		alerta('alerta',response.status,response.message,'');
	})
	.fail(function(jqXHR,textStatus,errorThrown) {
		console.log("Error: ".jqXHR.responseText);
	});
	
	return false;
}
//Autocompletado de informacion 
function autocomplete_input(input,hidden,option){
	$('#'+input).autocomplete({
		source: "controller/puente.php?option="+option,
		select:function(event,ui){
			$('#'+hidden).val(ui.item.id);
		}
	});
	return false;
}
function frm_turnar() {
	$('#frm_turnar ').submit(function(e) {
		e.preventDefault();
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
			alerta('div_turnar',response.status,response.message,'modal_turnar');
		})
		.fail(function(jqXHR,textStatus,errorThrown) {
			console.log("Error: ".jqXHR.responseText);
		});
	});
	return false;
}
//
function frm_send_sapa() {
	$('#frm_send_sapa').submit(function(e) {
		e.preventDefault();
		var dataForm = new FormData(document.getElementById("frm_send_sapa"));
		$.ajax({
			url: 'controller/puente.php',
			type: 'POST',
			dataType: 'json',
			data: dataForm,
			async:false,
			cache:false,
			processData: false,
            contentType: false,
		})
		.done(function(response) {
			alerta('div_sapa',response.status,response.message,'modal_send_sapa');
			getCorrespondencia();
		})
		.fail(function(jqXHR,textStatus,errorThrown) {
			alerta('div_acuse','error',jqXHR.responseText,'modal_send_sapa');
		});
	});
}
//
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
				fila += "<td>"+( val.f_acuerdo == null ? 'NO SE REGISTRÓ' : val.f_acuerdo )+"</td>";
				fila += "<td>"+( val.asunto == null ? 'NO SE REGISTRÓ' : val.asunto )+"</td>";
				fila += "<td>";
				if (typeof val.apersona == 'object') {
					fila += "<ol>";
					$.each(val.apersona, function(ii, value) {
						fila += "<li >";
							fila += "<ul type='none'>";
								fila += "<li><b>Oficio: </b> "+value.oficio+"</li>";
								fila += "<li><b>Fecha Of: </b> "+value.f_oficio+"</li>";
								fila += "<li><b>Fecha aper: </b> "+( value.f_apersonamiento == null ? 'NO SE REGISTRÓ' : value.f_apersonamiento )+"</li>";
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
				fila += "<td>"+( val.f_chyj == null ? 'NO SE REGISTRÓ' : val.f_chyj )+"</td>";
				fila += "<td>"+( val.f_trijaem == null ? 'NO SE REGISTRÓ' : val.f_trijaem )+"</td>";
				fila += "<td>"+jefe+"</td>";
				fila += "<td>"+val.name_abogado+"</td>";
				fila += "<td>"+val.u_oficio+"</td>";
				fila += "<td>"+( val.f_acuerdo == null ? 'NO SE REGISTRÓ' : val.f_acuerdo )+"</td>";
				fila += "<td>"+( val.asunto == null ? 'NO SE REGISTRÓ' : val.asunto )+"</td>";
				fila += "<td>";
				if (typeof val.apersona == 'object') {
					fila += "<ol>";
					$.each(val.apersona, function(ii, value) {
						fila += "<li >";
							fila += "<ul type='none'>";
								fila += "<li><b>Oficio: </b> "+value.oficio+"</li>";
								fila += "<li><b>Fecha Of: </b> "+value.f_oficio+"</li>";
								fila += "<li><b>Fecha aper: </b> "+( value.f_apersonamiento == null ? 'NO SE REGISTRÓ' : value.f_apersonamiento )+"</li>";
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
				fila += "<td>"+( val.f_chyj == null ? 'NO SE REGISTRÓ' : val.f_chyj )+"</td>";
				fila += "<td>"+( val.f_trijaem == null ? 'NO SE REGISTRÓ' : val.f_trijaem )+"</td>";
				fila += "<td>"+jefe+"</td>";
				fila += "<td>"+val.name_abogado+"</td>";
				fila += "<td>"+val.u_oficio+"</td>";
				fila += "<td>"+( val.f_acuerdo == null ? 'NO SE REGISTRÓ' : val.f_acuerdo )+"</td>";
				fila += "<td>"+( val.asunto == null ? 'NO SE REGISTRÓ' : val.asunto )+"</td>";
				fila += "<td>";
				if (typeof val.apersona == 'object') {
					fila += "<ol>";
					$.each(val.apersona, function(ii, value) {
						fila += "<li >";
							fila += "<ul type='none'>";
								fila += "<li><b>Oficio: </b> "+value.oficio+"</li>";
								fila += "<li><b>Fecha Of: </b> "+value.f_oficio+"</li>";
								fila += "<li><b>Fecha aper: </b> "+( value.f_apersonamiento == null ? 'NO SE REGISTRÓ' : value.f_apersonamiento )+"</li>";
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