var rescate;
$(document).ready(function() {
	url = window.location.search;
	url = url.split('&');
	getURL(url[0]);
});
//Detectando la URL
function getURL(url) {
	if ( url == '?menu=general' ) {
		$('#option_1').addClass('active');
		getExpedientes();
		frm_add_seguimiento();
		
	}
	if ( url == '?menu=estadistica' ) {
		$('#option_2').addClass('active');
		frm_estadistica();
	}
	if ( url == '?menu=e_procesal' ) {
		//evento('autoridad','select');
		load_catalogo('cargo','select',22);
		autocomplete_input('jefe','jefe_id',3);
		autocomplete_input('analista','analista_id',3);
		autocomplete_input('oficio','oficio_id',10);
		frm_edo_procesal();
		catalogo_conductas();
	}
	if ( url == '?menu=e_e_procesal' ) {
		frm_edit_edo_procesal();
		autocomplete_input('oficio','oficio_id',10);
		$('#e_procesal').change(function (e) {
			e.preventDefault();
			if ( $(this).val() == 3 ) {
				$('#motivo').removeClass('hidden');
			}else{
				$('#motivo').addClass('hidden');
			}
		})
	}
	return false; 
}
function getExpedientes() {
	var nivel = $('#nivel').val();
	var actions = [];
	
	var tabla = $("#expedientes").anexGrid({
	    class: 'table-striped table-bordered table-hover',
	    columnas: [
	    	{ leyenda: 'Acciones', style: 'width:10px;', columna: 'Sueldo' },
	    	{ leyenda: 'ID'},
	        { leyenda: 'Clave', filtro:true, columna:'q.cve_exp'},
	        { leyenda: 'Procedencia'},
	        { leyenda: 'Presuntos'},
	        { leyenda: 'Oficio', filtro:true, columna:'qr.oficio'},
	        { leyenda: 'Origen de queja'},	        
	        { leyenda: 'Edo. Procesal',columna:'qr.e_procesal',filtro:function () {
	        	return anexGrid_select({
		            data: [
		                { valor: '', contenido: 'Todos' },
		                { valor: '1', contenido: 'ENVIADO' },
		                { valor: '2', contenido: 'TRÁMITE' },
		                { valor: '3', contenido: 'DEVUELTO' },
		            ]
		        });
	        }},	        
	        { leyenda: 'Turnado a (Jefe de Depto.)', filtro:true, columna:'qr.jefatura'},	        
	        { leyenda: 'Asignado a (Abogado Analista)', filtro:true, columna:'qr.analista'},	        
	        { leyenda: 'Días transcurridos', filtro:false, columna:''},	        
	    ],
	    modelo: [
	    	
	    	{ class:'',formato: function(tr, obj, valor){
	    		if (nivel == 'ANALISTA') {
					actions = [
			            { href: "javascript:open_modal('modal_add_seguimiento',"+obj.id+",'qd_res');", contenido: '<i class="fa fa-plus"></i>Agregar seguimiento' },
			        ];
				}else{
					actions = [
			            { href: "index.php?menu=e_e_procesal&exp="+obj.queja_id, contenido: '<i class="fa fa-edit"></i> Editar Edo. Procesal' },
			            { href: "index.php?menu=e_procesal&exp="+obj.queja_id, contenido: '<i class="fa fa-pencil"></i>Edo. Procesal' },
			            { href: "index.php?menu=cedula&exp_id="+obj.queja_id, contenido: '<i class="fa fa-file-text-o"></i>Cédula' },
			            { href: "javascript:open_modal('modal_add_seguimiento',"+obj.id+",'qd_res');", contenido: '<i class="fa fa-plus"></i>Agregar seguimiento' },
			        ];
				}
	            return anexGrid_dropdown({
                    contenido: '<i class="glyphicon glyphicon-cog"></i>',
                    class: 'btn btn-primary ',
                    target: '_blank',
                    id: 'editar',
                    data: actions
                });
	        }},
	    	
	        { propiedad: 'id' },
	        { propiedad: 'cve_exp' },
	        { propiedad: 'n_procedencia' },
	        { formato: function(tr, obj, valor){
	        	var lista = "";
	        	lista += "<ol>";
            	for (var i = 0; i < obj.presuntos.length  ; i++) {
            		lista += "<li>"+obj.presuntos[i].nombre+"</li>";
            	}
            	lista += "</ol>";
            	return lista;
        	}},
	        { propiedad: 'oficio'},
	        { propiedad: 'categoria'},
	        { formato:function(tr,obj,valor){
	        	
	        	if(obj.e_procesal == 'ENVIADO'){tr.addClass('bg-green');}
	        	if(obj.e_procesal == 'TRAMITE'){tr.addClass('bg-yellow');}
	        	if(obj.e_procesal == 'DEVUELTO'){tr.addClass('bg-gray');}
	        	return obj.e_procesal;
	        }},
	        { formato: function(tr, obj, valor){
	        	if (obj.n_jefatura == null) {
	        		return "Aún no se turna";
	        	}else{
	        		return obj.n_jefatura;
	        	}
	        }},

	        { formato: function(tr, obj, valor){
	        	if (obj.n_analista == null) {
	        		return "Aún no se turna";
	        	}else{
	        		return obj.n_analista;
	        	}
	        }},

	        { class:'text-center',formato: function(tr, obj, valor){
	        	return obj.dias_t;
	        }}
	    ],
	    url: 'controller/puente.php?option=9',
	    filtrable: true,
	    columna: 'id',
	    columna_orden: 'DESC'
	});
	return tabla;
}
function evento(element,type){
	$('#'+element).change(function(e){
		e.preventDefault();
		if ( $(this).val() == "3" ) {
			$('#ctrl_interno').removeClass('hidden');
		}else{
			$('#ctrl_interno').addClass('hidden');
		}
	});
	
	return false;
}
function open_modal( modal, val, name){
	if (name != '') {
		$('[name="'+name+'"]').val(val);
	}
	$('#'+modal).modal('show');
	return false;
}
//Guardar el estado procesal
function frm_edo_procesal(){
	$('#frm_edo_procesal').submit(function(e) {
		e.preventDefault();
		var dataForm = $(this).serialize();
		console.log('Preparando la info para enviar al modelo.')
		$.ajax({
			url: 'controller/puente.php',
			type: 'POST',
			dataType: 'json',
			data: dataForm,
			async:false,
			cache:false
		})
		.done(function(response) {
			alerta('div_edo',response.status,response.message,'');
		})
		.fail(function(jqXHR,textStatus,errorThrow) {
			alerta('div_edo','error',jqXHR.responseText,'');
		});
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
//Carga de catalogos 
function load_catalogo(element,type,option){
	var result, data='0'; 
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
}
// Setear un array de municipios 
function setInfo( json ) { rescate = json; }
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
//eliminar al responable del expediente
function delete_responsable(id) {
	$('#div_tbl_res').addClass('hidden');
	$('#field_res').removeClass('hidden');

	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option:'55',pr:id},
		async:false,
		cache:false,
	})
	.done(function(response) {
		alert(response.message);
		$('#nombre').attr('required', '');
		$('#ap_pat').attr('required', '');
		$('#ap_mat').attr('required', '');
		$('#genero').attr('required', '');
		$('#cargo').attr('required', '');
		$('#adscripcion').attr('required', '');
		$('#presunto_id').val("");
	})
	.fail(function(jqXHR,textStatus,errorThrow) {
		console.log("Error al tratar de elimiar al responsable: "+jqXHR.responseText );
	});
	
	return false;
}
//Formulario para actualizar el estado Procesal
function frm_edit_edo_procesal() {
	$('#frm_edit_edo_procesal').submit(function(e) {
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
			alerta('div_edo',response.status,response.message,'');
		})
		.fail(function(jqXHR,textStatus,errorThrow) {
			console.log("Error al tratar de elimiar al responsable: "+jqXHR.responseText );
		});
		
	});
	return false;
}
//El analista de DR guarda una observación del expediente 
function frm_add_seguimiento() {
	$('#frm_add_seguimiento').submit(function(e) {
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
			alerta('div_seguimiento',response.status,response.message,'modal_add_seguimiento');
			document.getElementId("frm_add_seguimiento").reset();
		})
		.fail(function(jqXHR,textStatus,errorThrow) {
			console.log("Error: "+jqXHR.responseText);
		});
		
	});
	return false;
}
//agregar una nueva conducta
function add_conducta() {
	var conducta = prompt('Escriba la nueva conducta:');
	if (conducta != "") {
		conducta = conducta.toUpperCase();
		$.ajax({
			url: 'controller/puente.php',
			type: 'POST',
			dataType: 'json',
			data: {option: '60',nombre:conducta},
			async:false,
			cache:false,
		})
		.done(function(response) {
			$('#conducta').html("");
			$('#conducta').append('<option value="">...</option>');
			$.each(response, function(i, con) {
				$('#conducta').append('<option value="'+con.id+'">'+con.nombre+'</option>');
			});
			catalogo_conductas();
		})
		.fail(function(jqXHR,textStatus,errorThrow) {
			console.log("Error: "+jqXHR.responseText);
		});
	}else{
		console.log('No tienen conducta');
	}
}
// Consultar las conducta
function catalogo_conductas() {
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '59'},
		async:false,
		cache:false,
	})
	.done(function(response) {

		$('#conducta').html("");
		$('#conducta').append('<option value="">...</option>');
		$.each(response, function(i, con) {
			$('#conducta').append('<option value="'+con.id+'">'+con.nombre+'</option>');
		});
	})
	.fail(function(jqXHR,textStatus,errorThrow) {
		console.log("Error: "+jqXHR.responseText);
	});
	
}
//Formulario de estadistica
function frm_estadistica() {
	$('#frm_estadistica').submit(function(e) {
		e.preventDefault();
		var dataForm = $(this).serialize();
		alert(dataForm);
	});
}