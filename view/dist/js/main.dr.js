$(document).ready(function() {
	url = window.location.search;
	url = url.split('&');
	getURL(url[0]);
});
//Detectando la URL
function getURL(url) {
	if ( url == '?menu=general' ) {
		$('#option_1').addClass('active');	
		autocomplete_input('oficio_dr','oficio_dr_id',10);
		frm_add_acuse();
		frm_turnar();
		if ( $('#nivel').val() == 'ANALISTA' ) {
			getCorrespondencia();
		}else{
			tablero_ctrl();
		}
	}
	if ( url == '?menu=list_exp' ) {
		$('#option_2').addClass('active');
		frm_add_acuse();		
		getCorrespondencia();
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
		//Agregar contenido a la SC
		if (response.sc.primer == '0') {
			var des = 'disabled';
		}else{ var desactiva = ''; }
		$('#tbl_sc tbody').append('<tr class="text-center">'+
			'<td>EXPEDIENTES CON UNA DEMANDA</td>'+
			'<td>'+response.sc.primer+'</td>'+
			'<td>'+
				'<button type="button" onclick="verExpedientes(2);" class="btn btn-success btn-flat" '+des+'> <i class="fa fa-eye"></i> </button>'+
			'</td>'+
		'</tr>');
		if (response.sc.rec_rev == '0') {
			var desactiva = 'disabled';
		}else{ var desactiva = ''; }
		$('#tbl_sc tbody').append('<tr class="text-center">'+
			'<td>EXPEDIENTES EN RECURSO DE REVISIÓN</td>'+
			'<td>'+response.sc.rec_rev+'</td>'+
			'<td>'+
				'<button type="button" onclick="verExpedientes(1);" class="btn btn-success btn-flat"'+desactiva+'> <i class="fa fa-eye"></i> </button>'+
			'</td>'+
		'</tr>');
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
				fila += "<td><a href='index.php?menu=cedula&exp="+val.id+"'>"+val.cve_exp+"</a></td>";
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
	        { leyenda: 'Número de oficio', style: 'width:200px;', columna: ''},
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
	    			options = [
                        { href: "javascript:open_modal('modal_turnar','"+obj.oficio+"','oficio_inv');", contenido: '<i class="fa fa-mail-forward"></i> Turnar a S.A.P.A.' },
                        { href: "controller/puente.php?option=4A&o="+obj.oficio, contenido: '<i class="fa fa-eye"></i> Ver acuse' },
                    ];
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
	        		fila += "<li>"+val.cve_exp+"</li>";
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
			alerta('div_acuse',response.status,response.message,'modal_add_acuse');
			getCorrespondencia();
		})
		.fail(function(jqXHR,textStatus,errorThrown) {
			alerta('div_acuse','error',jqXHR.responseText,'modal_add_acuse');
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
	$('#frm_turnar').submit(function(e) {
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