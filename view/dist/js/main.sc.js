/*Acciones de la subdireccion de lo contencioso*/
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
		autocomplete_input('jefe','jefe_id',3);
		autocomplete_input('oficio_a','oficio_a_id',10);
		frm_add_responsable();
	}
	if ( url == '?menu=resolver' ) {
		$('#option_1').addClass('active');
		frm_add_resolucion();
		autocomplete_input('oficio','oficio_id',10);
	}
	if ( url == '?menu=demandar' ) {
		$('#option_1').addClass('active');
		frm_add_demanda();
		autocomplete_input('oficio','oficio_id',10);
	}
	if ( url == '?menu=list_demandas' ) {
		$('#option_1').addClass('active');
		autocomplete_input('oficio','oficio_id',10);
		frm_resolver_demanda();
		frm_add_apersonamiento();
	}
	if ( url == '?menu=reserva' ) {
		$('#option_1').addClass('active');
		autocomplete_input('oficio','oficio_id',10);
		frm_reserva();
	}
	if ( url == '?menu=improcedencia' ) {
		$('#option_1').addClass('active');
		frm_acuerdo_improcedencia();
	}
	if ( url == '?menu=list_reservas' ) {
		$('#option_1').addClass('active');
		frm_regresar_exp();
		frm_acuerdo_improcedencia();
		autocomplete_input('oficio','oficio_id',10);
	}
	if ( url == '?menu=reportes' ) {
		$('#option_2').addClass('active');
		acciones_frm();
		frm_reporte();
	}
	if ( url == '?menu=apersonamiento' ) {
		autocomplete_input('oficio','oficio_id',10);
		frm_apersonamiento();
	}
	
	return false; 
}
function getExpedientes() {
	var nivel = $('#nivel').val();	
	var tabla = $("#expedientes_sc").anexGrid({
	    class: 'table-striped table-bordered table-hover',
	    columnas: [
	    	{ leyenda: 'Acciones', style: 'width:10px;', columna: '' },
	    	{ leyenda: 'ID'},
	        { leyenda: 'Cve. Expediente', filtro:true, columna:'q.cve_exp'},
	        { leyenda: 'No. Oficio'},
	        { leyenda: 'Fojas'},
	        { leyenda: 'Remitente', columna:''},
	        { leyenda: 'Procedencia'},	        
	        { leyenda: 'Presunto(s)',columna:''},	        
	        { leyenda: 'Abogado responsable', filtro:false, columna:''},	        
	        { leyenda: 'Motivo', filtro:false, columna:''},	        
	        { leyenda: 'Dias trabajados', filtro:false, columna:''},	        
	    ],
	    modelo: [
	    	
	    	{ class:'',formato: function(tr, obj, valor){
	    		var ruta = "", demanda = { href: "#", contenido: '<i class="fa fa-edit"></i> ------' };
	    		ruta ="index.php?menu=resolver&exp="+obj.id;
	    		
	    		if(nivel == 'ANALISTA' ){
	    			links = [
	    				{ href: "index.php?menu=improcedencia&exp="+obj.queja_id, contenido: '<i class="fa fa-archive"></i>Acuerdo de improcedencia' },
	    				{ href: "index.php?menu=reserva&exp="+obj.queja_id, contenido: '<i class="fa fa-pause"></i>Poner en reserva' },
	    				{ href: "index.php?menu=list_reservas&exp="+obj.queja_id, contenido: '<i class="fa fa-eye"></i>Acuerdos de reserva' },
	    			];
	    		}else{
	    			if(obj.estado == '10'){
		    			links = [
				            { href: "javascript:open_modal('modal_add_responsable',"+obj.queja_id+",'queja_id');", contenido: '<i class="fa fa-user"></i>Asignar responsable' },
				            { href: "index.php?menu=cedula&exp="+obj.id, contenido: '<i class="fa fa-eye"></i>Cédula' },
				            { href: "index.php?menu=reserva&exp="+obj.queja_id, contenido: '<i class="fa fa-pause"></i>Poner en reserva' },
				            { href: "index.php?menu=list_reservas&exp="+obj.queja_id, contenido: '<i class="fa fa-eye"></i>Listar reserva' },
				            { href: "index.php?menu=demandar&exp="+obj.id, contenido: '<i class="fa fa-edit"></i> Registrar demandas' },
				            { href: "index.php?menu=list_demandas&exp="+obj.id, contenido: '<i class="fa fa-list"></i> Listado de demandas' },
				        ];
	    			}else if( obj.estado == '11' ){
		    			links = [
				            { href: "javascript:open_modal('modal_add_responsable',"+obj.queja_id+",'queja_id');", contenido: '<i class="fa fa-user"></i>Asignar responsable' },
				            { href: ruta, contenido: '<i class="fa fa-edit"></i> Registrar resolucion de la Comisión' },
				            { href: "index.php?menu=list_demandas&exp="+obj.id, contenido: '<i class="fa fa-list"></i> Listado de demandas' },
				            { href: "index.php?menu=improcedencia&exp="+obj.queja_id, contenido: '<i class="fa fa-book"></i>Acuerdo de improcedencia' },
				            { href: "index.php?menu=cedula&exp="+obj.id, contenido: '<i class="fa fa-eye"></i>Cédula' },
				            { href: "index.php?menu=demandar&exp="+obj.id, contenido: '<i class="fa fa-edit"></i> Registrar demandas' }
				        ];
	    			}else{
	    				if ( obj.autoridad == 'SC' ) {
			    			links = [
					            { href: "javascript:open_modal('modal_add_responsable',"+obj.queja_id+",'queja_id');", contenido: '<i class="fa fa-user"></i>Asignar responsable' },
					            { href: "index.php?menu=apersonamiento&queja_id="+obj.queja_id, contenido: '<i class="fa fa-edit"></i> Registrar seguimiento presencial' },
					            { href: ruta, contenido: '<i class="fa fa-edit"></i> Registrar resolucion de la Comisión' },
					            { href: "index.php?menu=demandar&exp="+obj.id, contenido: '<i class="fa fa-edit"></i> Registrar demanda' },
					            { href: "index.php?menu=list_demandas&exp="+obj.id, contenido: '<i class="fa fa-list"></i> Listado de demandas' },
					            { href: "index.php?menu=improcedencia&exp="+obj.queja_id, contenido: '<i class="fa fa-book"></i>Acuerdo de improcedencia' },
					            { href: "index.php?menu=reserva&exp="+obj.queja_id, contenido: '<i class="fa fa-pause"></i>Poner en reserva' },
					            { href: "index.php?menu=list_reservas&exp="+obj.queja_id, contenido: '<i class="fa fa-eye"></i>Listar reserva' },
					            { href: "index.php?menu=cedula&exp="+obj.id, contenido: '<i class="fa fa-eye"></i>Cédula' },
					        ];
	    				}else{
	    					links = [
					            { href: "javascript:open_modal('modal_add_responsable',"+obj.queja_id+",'queja_id');", contenido: '<i class="fa fa-user"></i>Asignar responsable' },
					            { href: "index.php?menu=apersonamiento&queja_id="+obj.queja_id, contenido: '<i class="fa fa-edit"></i> Registrar seguimiento presencial' },
					            { href: ruta, contenido: '<i class="fa fa-edit"></i> Registrar resolucion' },
					            { href: "index.php?menu=demandar&exp="+obj.id, contenido: '<i class="fa fa-edit"></i> Registrar demanda' },
					            { href: "index.php?menu=list_demandas&exp="+obj.id, contenido: '<i class="fa fa-list"></i> Listado de demandas' },
					            { href: "index.php?menu=cedula&exp="+obj.id, contenido: '<i class="fa fa-eye"></i>Cédula' },
					        ];
	    				}
	    			}
	    			
	    		}
	            return anexGrid_dropdown({
                    contenido: '<i class="glyphicon glyphicon-cog"></i>',
                    class: 'btn btn-primary ',
                    target: '_blank',
                    data: links
                });
	        }},
	        { formato: function(tr, obj, valor){
	        	if (obj.estado == 10) {
	        			if (obj.edo_tr == 'SI') {
	        				tr.addClass('bg-orange-active');
	        			}else{
	        				tr.addClass('bg-orange');
	        				tr.addClass('disabled');
	        			}
	        	}
	        	if (obj.estado == 11) {
	        		if (obj.edo_tr == 'SI') {
	        			tr.addClass('bg-maroon-active');
	        		}else{
	        			tr.addClass('bg-maroon');
	        			tr.addClass('disabled');
	        		}
	        	}
	        	if (obj.estado == '8' && obj.edo_tr == 'NA') {
	        		tr.addClass('bg-green-active');
	        	}else{
	        		tr.addClass('bg-teal disabled');
	        	}
            	return obj.id;
        	}},
	        { propiedad: 'cve_exp' },
	        { propiedad: 'sapa.oficio' },
	        { formato: function(tr, obj, valor){
            	return obj.sapa.fojas;
        	}},
	        { propiedad: 'sapa.remitente'},
	        { formato:function(tr,obj,valor){
	        	return 'ANÁLISIS E INTEGRACIÓN';
	        }},
	        { formato: function(tr, obj, valor){
	        	var lista = "";
	        	lista += '<ul>';
	        	$.each(obj.presuntos, function(i, p) {
	        		lista += '<li>'+p.nombre+'</li>';
	        	});
	        	lista += '</ul>';
	        	return lista;
	        }},
	        { formato: function(tr, obj, valor){
	        	if( obj.a_responsable == '' ){
	        		return 'AÚN SIN ASIGNAR';
	        	}else{
	        		return obj.a_responsable;
	        	}
	        }},
	        { formato: function(tr, obj, valor){
	        	if ( obj.motivo == null || obj.motivo == '') {
	        		return 'SIN MOTIVO';
	        	}else{
	        		return obj.motivo;
	        	}
	        }},
	        { formato: function(tr, obj, valor){
	        	return obj.f_cierre;
	        }},
	    ],
	    url: 'controller/puente.php?option=11',
	    filtrable: true,
	    columna: 'id',
	    columna_orden: 'DESC'
	});
	return tabla;
}
//Agregar un abogado responsable
function frm_add_responsable() {
	$('#frm_add_responsable').submit(function(e) {
		e.preventDefault();
		var dataForm = new FormData(document.getElementById("frm_add_responsable"));
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
			document.getElementById('frm_add_responsable').reset();
			alerta('div_responsable',response.status,response.message,'modal_add_responsable');
		})
		.fail(function(jqXHR,textStatus,errorThrow) {
			document.getElementById('frm_add_responsable').reset();
			$('#sp_id').val("");
			alerta('div_responsable','error',jqXHR.responseText,'modal_add_responsable');
		});
	});
}
//Agregar una resolucion al expediente
function frm_add_resolucion() {
	$('#frm_add_resolucion').submit(function(e) {
		e.preventDefault();
		var dataForm = $(this).serialize();
		var sp_id = $('#sp_id').val();
		if ( sp_id == '') {
			document.getElementById('frm_add_resolucion').reset();
			alerta('div_resolucion','error','Debe de seleccionar un elemento de la lista del buscador de servidores públicos.','');
		}else{
			$.ajax({
				url: 'controller/puente.php',
				type: 'POST',
				dataType: 'json',
				data: dataForm,
				async:false,
				cache:false,
			})
			.done(function(response) {
				document.getElementById('frm_add_resolucion').reset();
				alerta('div_resolucion',response.status,response.message,'');
			})
			.fail(function(jqXHR,textStatus,errorThrow) {
				document.getElementById('frm_add_resolucion').reset();
				alerta('div_resolucion','error',jqXHR.responseText,'');
			});
		}
	});
}
function open_modal( modal, val, name){
	if (name != '') {
		$('[name="'+name+'"]').val(val);
	}
	if (name == 'queja_id') {
		getClave(val,modal);
	}
	$('#'+modal).modal('show');
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
//formulario de demanda 
function frm_add_demanda() {
	$('#frm_add_demanda').submit(function(e) {
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
			document.getElementById('frm_add_demanda').reset();
			alerta('div_demanda',response.status,response.message,'');
		})
		.fail(function(jqXHR,textStatus,errorThrow) {
			alerta('div_demanda','error',jqXHR.responseText,'');
		});		
	});
	return false;
}
function resolverDemanda(demanda) {
	$('#modal_resolver_demanda').modal('show');
	$('#option').val('64');
	$('#dem_id').val(demanda);
	return false;
}
function frm_resolver_demanda() {
	$('#frm_resolver_demanda').submit(function(e) {
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
			document.getElementById('frm_resolver_demanda').reset();
			alerta('div_res_demanda',response.status,response.message,'modal_resolver_demanda');
			setTimeout(function(){
				location.reload();
			},3000);
		})
		.fail(function(jqXHR,textStatus,errorThrow) {
			alerta('div_res_demanda','error',jqXHR.responseText,'modal_resolver_demanda');
		});		
	});
	return false;
}
//function reserva
function frm_reserva() {
	$('#frm_reserva').submit(function(e) {
		e.preventDefault();
		var dataForm = new FormData(document.getElementById("frm_reserva"));
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
			document.getElementById('frm_reserva').reset();
			alerta('div_reserva',response.status,response.message,'');
			
		})
		.fail(function(jqXHR,textStatus,errorThrow) {
			alerta('div_reserva','error',jqXHR.responseText,'');
		});
	});
}
function frm_acuerdo_improcedencia() {
	$('#frm_acuerdo_improcedencia').submit(function(e) {
		e.preventDefault();
		var dataForm = new FormData(document.getElementById("frm_acuerdo_improcedencia"));
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
			document.getElementById('frm_acuerdo_improcedencia').reset();
			alerta('div_acuerdo',response.status,response.message,'');
			
		})
		.fail(function(jqXHR,textStatus,errorThrow) {
			alerta('div_acuerdo','error',jqXHR.responseText,'');
		});
	});
	return false;
}
function frm_regresar_exp() {
	$('#frm_regresar_exp').submit(function(e) {
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
			document.getElementById('frm_regresar_exp').reset();
			alerta('div_regresar',response.status,response.message,'modal_regresar_exp');
		})
		.fail(function(jqXHR,textStatus,errorThrow) {
			alerta('div_regresar','error',jqXHR.responseText,'modal_regresar_exp');
		});
	});
	return false;
}
function frm_reporte() {
	var tabla ,fila;
	$('#frm_reporte').submit(function(e) {
		e.preventDefault();
		var dataForm = $(this).serialize();
		fila = "";
		$.ajax({
			url: 'controller/puente.php',
			type: 'POST',
			dataType: 'json',
			data: dataForm,
			async:false,
			cache:false,
		})
		.done(function(response) {
			var conta = 0 ;
			$('#tbody_reporte').html("");
			$.each(response, function(i, val) {
				fila += "<tr>";
					fila += "<td>"+(++conta)+"</td>";
					fila += "<td>"+val.cve_exp+"</td>";
					fila += "<td>"+val.oficio+"</td>";
					fila += "<td>"+val.n_estado+"</td>";
					fila += "<td>"+val.e_procesal+"</td>";
					fila += "<td>";
						fila += "<a href='index.php?menu=cedula&exp="+val.queja_id+"'class='btn btn-success btn-flat' target='__blank'>";
							fila += "<i class='fa fa-eye'></i>";
						fila += "</a>";
					fila += "</td>";
				fila += "</tr>";
				$('#tbl_reporte').append(fila);
			});
			$('#tbl_reporte').DataTable();
		})
		.fail(function(jqXHR,textStatus,errorThrow) {
			alerta('div_reportes','error',jqXHR.responseText,'');
		});
		
	});
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

//Modal para modificar la resolucion de la demanda
function editResolucion(resolucion) {
	$('#modal_resolver_demanda').modal('show');
	$('#option').val('69');
	$('#resolucion').val(resolucion);
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '70',resolucion:resolucion},
		async:false,
		cache:false,
	})
	.done(function(response) {
		$('#f_res').val(response.f_resolucion);
		$('#comentario').val(response.comentario);
	})
	.fail(function(jqXHR,textStatus,errorThrow) {
		console.log("error");
	});
	
 	return false;
 } 
 function modal_apersonamiento(demanda) {
 	$('#modal_add_apersonamiento').modal('show');
 	$('#demanda_id').val(demanda);
 	return false;
 }

 function frm_add_apersonamiento(){
 	$('#frm_add_apersonamiento').submit(function(e) {
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
 			alerta('div_apersonamiento',response.status,response.message,'modal_add_apersonamiento');
 		})
 		.fail(function(jqXHR,textStatus,errorThrow) {
 			alerta('div_apersonamiento','error',jqXHR.responseText,'');
 		});
 		
 	});
 	return false;
 }
 // 
 function frm_apersonamiento() {
 	$('#frm_apersonamiento').submit(function(e) {
 		e.preventDefault();
 		var dataForm = $(this).serialize();
 		$.ajax({
 			url: 'controller/puente.php',
 			type: 'POST',
 			dataType: 'json',
 			data: dataForm,
 			cache:false,
 			async:false,
 		})
 		.done(function(response) {
 			alerta('apersonamiento',response.status, response.message, '');
 		})
 		.fail(function(jqXHR,textStatus,errorThrow) {
 			console.log("error");
 		});
 		
 	});
 	return false;
 }
 //recuperador de clave del expediente
function getClave(id,modal) {
	//var id = $('#id').val();
	var clave = "";
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		//dataType: 'json',
		data: {option: '98',id:id},
		async:false,
	})
	.done(function(response) {
		$('#etiqueta_'+modal).text(response);
	})
	.fail(function() {
		console.log("error");
	});
	return clave;
}
function acciones_frm() {
	$('#f_buscar').change(function(e) {
		e.preventDefault();
		if ( $(this).val() == '' ) {
			$('#campos_queja').addClass('hidden');
			$('#campos_res').addClass('hidden');
			$('#campos_dem').addClass('hidden');
			$('#campos_rdem').addClass('hidden');
		}
		if ( $(this).val() == 1 ){
			$('#campos_queja').removeClass('hidden');
			$('#campos_res').addClass('hidden');
			$('#campos_dem').addClass('hidden');
			$('#campos_rdem').addClass('hidden');
		}
		if ( $(this).val() == 2 ){
			$('#campos_queja').addClass('hidden');
			$('#campos_res').removeClass('hidden');
			$('#campos_dem').addClass('hidden');
			$('#campos_rdem').addClass('hidden');
		}
		if ( $(this).val() == 3 ){
			$('#campos_queja').addClass('hidden');
			$('#campos_res').addClass('hidden');
			$('#campos_dem').removeClass('hidden');
			$('#campos_rdem').addClass('hidden');
		}
		if ( $(this).val() == 4 ){
			$('#campos_queja').addClass('hidden');
			$('#campos_res').addClass('hidden');
			$('#campos_dem').addClass('hidden');
			$('#campos_rdem').removeClass('hidden');
		}
	});
	return false;
}