var rescate;
$(document).ready(function() {
	console.log('Bienvenido a Sistema de Registro de Actas.');
	var url = window.location.search;
	url = url .split('&');
	url = url[0];
	getURL(url);
	//Initialize Select2 Elements
	$('.select2').select2({
		language: {
			noResults: function() {
		     	return "No hay elementos para seleccionar";        
		    }
		}
	});
	$('[data-toggle="popover"]').popover({
		container: 'body'
	}); 
});

function getURL(url) {
	if ( url == '?menu=general' ) {
		$('#option_1').addClass('active');
		$('#option_1_1').addClass('active');
		autocomplete_input('area','area_h',2);
		autocomplete_input('orden_i','orden_h',5);
		autocomplete_input('personal','',3);
		autocomplete_input('personal_a','',3);
		load_catalogo('municipio','select',10);
		question();
		frm_add_acta();
		frm_edit_acta();
	}
	if ( url == '?menu=list_acta' ) {
		$('#option_1').addClass('active');
		$('#option_1_2').addClass('active');
		getActas();
		frm_upload_file();
	}
	if ( url == '?menu=ordenes' ) {
		$('#option_2').addClass('active');
		getOrdenes();
	}
	if ( url == '?menu=reports' ) {
		$('#option_3').addClass('active');
	}
	if ( url == '?menu=aviso' ) {
		$('#option_4').addClass('active');
	}
	if ( url == '?menu=manual' ) {
		$('#option_5').addClass('active');
	}
	if ( url == '?menu=seguimiento' ) {
		frm_add_pr();//FORMUALRIO DEL PRESUNTO RESPONSABLE
	}
	return false; 
}

function remover_persona(id,tipo) {
	$('#'+tipo+'>li#'+id).remove();
	return false;
}

/*Buscar la orden de trabajo*/
function question() {
	$('#question').change(function(e){
		e.preventDefault();
		var q = $(this).val();	
		if (q == 1) {
			$('#orden').removeClass('hidden');
		}else if ( q == 2){
			$('#orden').addClass('hidden');
		}
	});
	return false;
}

/*LISTADO DE ORDENES DE TRABAJO */
function getOrdenes() {
	var tabla = $("#ordenes_trabajo").anexGrid({
	    class: 'table-striped table-bordered table-hover',
	    columnas: [
	    	{ leyenda: 'Acciones', style: 'width:100px;', columna: 'Sueldo' },
	    	{ leyenda: 'ID', style:'width:20px;'},
	        { leyenda: 'Clave de orden de trabajo', style: 'width:200px;', columna: 'clave'},
	        { leyenda: 'Fecha', columna: 'fecha', filtro: false },
	        { leyenda: 'Participantes', style: 'width:300px;', columna: 'Correo' },
	        { leyenda: 'Estado', style: 'width:120px;', columna: 'Sexo'},
	        
	    ],
	    modelo: [
	    	        { class:'',formato: function(tr, obj, valor){
	    	            return anexGrid_dropdown({
	                        contenido: '<i class="glyphicon glyphicon-cog"></i>',
	                        class: 'btn btn-primary ',
	                        target: '_blank',
	                        id: 'editar-' + obj.id,
	                        data: [
	                            { href: "javascript:open_modal('modal_ot_upload');", contenido: '<i class="glyphicon glyphicon-cloud"></i> Adjuntar documento' },
	                            { href: "javascript:open_modal('modal_add_obs');", contenido: '<i class="glyphicon glyphicon-comment"></i>Agregar observaciones' },
	                            { href: 'index.php?menu=detalle&ot='+obj.id, contenido: '<i class="glyphicon glyphicon-eye-open"></i>Ver detalle' },
	                        ]
	                    });
	    	        }},
	        { propiedad: 'id' },
	        { propiedad: 'clave' },
	        { propiedad: 'fecha' },
	        { propiedad: 'estado', class: '', },
	        { propiedad: 'participantes', class: '', },
	        
	        
	    ],
	    url: 'controller/puente.php?option=1',
	    filtrable: false,
	    columna: 'id',
	    columna_orden: 'DESC'
	});
	return tabla;
}

/*LISTADO DE ACTAS*/
function getActas() {
	load_catalogo('','json',10);
	
	var aux = [{'valor': '','contenido':'TODOS'}];
	$.each(rescate, function(i, val) {
		aux.push({'valor': val.id,'contenido':val.nombre});
	});
	var tabla = $("#lista_actas").anexGrid({
	    class: 'table-striped table-bordered table-hover',
	    columnas: [
	    	{ class:'text-center', leyenda: 'Acciones', style: 'width:30px;', columna: 'Sueldo' },
	    	{ class:'text-center', leyenda: 'ID', style:'width:20px;'},
	    	{ class:'text-center', leyenda: 'Clave', style: 'width:100px;', columna: 'a.clave',filtro:true},
	        { class:'text-center', leyenda: 'Tipo acta', style: 'width:100px;',
	        columna: 'a.t_actuacion',filtro:function(){
    	        return anexGrid_select({
    	            data: [
    	                    { valor: '', contenido: 'Todos' },
    	                    { valor: '1', contenido: 'INSPECCION' },
    	                    { valor: '2', contenido: 'VERIFICACION' },
    	                    { valor: '3', contenido: 'SUPERVISIÓN' },
    	                ]
    	            });
    	        }
	    	},
	        { class:'text-center', leyenda: 'Fecha',style: 'width:50px;', columna: 'fecha', filtro: false },
	        { class:'text-center', leyenda: 'Procedencia', style: 'width:100px;', columna: 'a.procedencia',filtro:function(){
	        	return anexGrid_select({
	                data: [
	                    { valor: '', contenido: 'Todos' },
	                    { valor: '1', contenido: 'SS' },
	                    { valor: '2', contenido: 'CPRS' },
	                ]
	            });
	        } },
	        { class:'ext-center', leyenda: 'Municipio', style: 'width:100px;', columna:'m.id',ordenable:false,filtro:function(){
	        	return anexGrid_select({
	        		//class:'select2',
    	            data: aux
    	        });
	        }},
	        { class:'text-center', leyenda: 'Descripcion', style: 'width:300px;', columna: 'a.comentarios',filtro:true},
	        
	    ],
	    modelo: [
	        { class:'',formato: function(tr, obj, valor){
	            return anexGrid_dropdown({
                    contenido: '<i class="glyphicon glyphicon-cog"></i>',
                    class: 'btn btn-primary ',
                    target: '_blank',
                    id: obj.id,
                    data: [
                        { href: 'index.php?menu=seguimiento&acta=1', contenido: '<i class="glyphicon glyphicon-road"></i> Dar seguimiento' },
                        { href: 'index.php?menu=general&acta='+obj.id, contenido: '<i class="glyphicon glyphicon-pencil"></i> Editar' },
                        { href: 'index.php?menu=cedula&acta='+obj.id, contenido: '<i class="glyphicon glyphicon-eye-open"></i> Ver cedula' },
                        { href: "javascript:open_modal('modal_upload_file',"+obj.id+");", 
                          contenido: '<i class="glyphicon glyphicon-cloud"></i> Adjuntar documento',
                        }
                    ]
                });
	        }},
	        { propiedad: 'id' },
	        { propiedad: 'clave' },
	        { propiedad: 't_actuacion' },
	        { propiedad: 'fecha' },
	        { propiedad: 'procedencia', class: '', },
	        { propiedad: 'n_municipio', class: '', },
	        { class:'text-justify', propiedad: 'comentarios',  },
	        
	    ],
	    url: 'controller/puente.php?option=6',
	    filtrable: true,
	    columna: 'id',
	    columna_orden: 'DESC'
	});
	return tabla;
}

function open_modal(modal,id) {
	$('#'+modal).modal('show');
	//agregar el ID del acta al modal
	$('[name="acta_id"]').val(id);
	return false;
}
//Autocompletado de informacion 
function autocomplete_input(input,hidden,option){
	//2.- areas, 3.- personal, 5.- oins
	$('#'+input).autocomplete({
		source: "controller/puente.php?option="+option,
		select:function(event,ui){
			if ( (input == 'personal' || input == 'personal_a') && hidden == '' ) {
				$("#"+input).val("");
				var lista;
				if ( input == 'personal' ) { lista = 'investigadores'; } 
				if ( input == 'personal_a' ) { lista = 'apoyo'; }
				$('#'+lista).append(
					'<li id="'+ui.item.id+'">'+
					    ui.item.value+
					    '<input type="hidden" name="'+lista+'[]" value="'+ui.item.id+'">'+
					    '<button type="button" class="btn btn-flat btn-danger btn-sm" onclick="remover_persona('+ui.item.id+','+"'investigadores')"+'">'+
					        '<i class="fa fa-minus"></i>'+
					    '</button> '+
					'</li>'
				);
			}else{
				$('#'+input).val(ui.item.value);
				$('#'+hidden).val(ui.item.id);
			}
			return false;
		}
	});
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
		time = 5000;
	}
	if ( estado == 'success') {
		icono = "fa-check";
		clase = "alert-success";
		msj = mensaje;
		edo = "Éxito!";
		time = 3000;
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
//Formulario de alta de acta 
function frm_add_acta() {
	$('#frm_add_acta').submit(function(e){
		e.preventDefault();
		var dataForm = $(this).serializeArray();
		$.ajax({
			url: 'controller/puente.php',
			type: 'POST',
			dataType: 'json',
			data: dataForm,
			async:false,
			cache:false,
		})
		.done(function(response) {
			alerta('div_alert',response.status,response.message,'');
			if(response.status == 'success'){
				setTimeout(function(){
					document.location.href = "index.php?menu=list_acta";
				},5000);
			}
		})
		.fail(function(jqXHR, textStatus, errorThrown) {
			alerta('div_alert','error',jqXHR.responseText,'');
		});
		
	});
	return false;
}
function load_catalogo(element,type,option){
	var result;
	if ( element != '' ) {
		$('#'+element).html('');
	}
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: option},
		async:false,
		cache:false,
	})
	.done(function(response) {
		if ( type == 'select') {
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
		alert('Ocurrio un error al cargar el catalogo de opcion: '+option);
	});		
}
// Setear un array de municipios 
function setInfo( json ) { rescate = json; }
//
function frm_upload_file() {
	$('#frm_upload_file').submit(function(e) {
		e.preventDefault();
		var dataForm = new FormData(document.getElementById("frm_upload_file"));
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
			alerta('upload_file',response.status,response.message,'modal_upload_file');
			setTimeout( function(){
				document.getElementById('frm_upload_file').reset();
			},5000 );
		})
		.fail(function(jqXHR,textStatus,errorThrown) {
			alerta('upload_file','error',jqXHR.responseText,'modal_upload_file');
		});
	});
	return false;
}
function frm_edit_acta() {
	$('#frm_edit_acta').submit(function(e) {
		e.preventDefault();
		var dataForm = $(this).serializeArray();
		$.ajax({
			url: 'controller/puente.php',
			type: 'POST',
			dataType: 'json',
			data: dataForm,
			async:false,
			cache:false,
		})
		.done(function(response) {
			alerta('div_alert',response.status,response.message,'');
			if(response.status == 'success'){
				setTimeout(function(){
					document.location.href = "index.php?menu=list_acta";
				},5000);
			}
		})
		.fail(function(jqXHR, textStatus, errorThrown) {
			alerta('div_alert','error',jqXHR.responseText,'');
		});
	});
}
function frm_add_pr() {//FORMUALRIO DEL PRESUNTO RESPONSABLE
	$('#frm_add_pr').submit( function(e){
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
			alerta('alert_pr',response.status,reponse.message,'');
		})
		.fail(function(jqXHR, textStatus, errorThrown) {
			alerta('alert_pr',response.status,reponse.message,'');
		});
		
	} );
	return false;
}