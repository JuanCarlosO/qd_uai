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
		frm_add_responsable();
		autocomplete_input('jefe','jefe_id',3);
		autocomplete_input('analista','analista_id',3);
		//para las sanciones 
		autocomplete_input('oficio_sa','oficio_sa_id',10);
		frm_add_sancion();
		frm_add_verificacion();

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
		autocomplete_input('oficioa','oficioa_id',10);
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
		$('#motivo').change(function(event) {
			event.preventDefault();
			var edo = "";
			if ($(this).val() == "ARCHIVO") {edo = 2;}
			if ($(this).val() == "IMPROCEDENCIA") {edo = 11;}
			if ($(this).val() == "INCOMPETENCIA") {edo = 3;}
			if ($(this).val() == "RESERVA") {edo = 10;}
			$('#estado_exp').val(edo);
		});
	}
	if ( url == '?menu=list_reservas' ) {
		$('#option_1').addClass('active');
		frm_regresar_exp();
		frm_acuerdo_improcedencia();
		autocomplete_input('oficio','oficio_id',10);
	}
	if ( url == '?menu=reportes' ) {
		$('#option_2').addClass('active');
		tablero_ctrl();		
		acciones_frm();
		frm_reporte();
	}
	if ( url == '?menu=tablero' ) {
		$('#option_3').addClass('active');
		tablero_sc();	
	}
	if ( url == '?menu=apersonamiento' ) {
		autocomplete_input('oficio','oficio_id',10);
		frm_apersonamiento();
	}
	if ( url == '?menu=add_acuse' ) {
		$('#option_1').addClass('active');
		frm_add_acuse();
		autocomplete_input('oficio','oficio_id',10);
		autocomplete_input('queja','queja_id',12);
	}
	if ( url == '?menu=modificar' ) {
		$('#option_1').addClass('active');
		frm_edit_sv();
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
	        { leyenda: 'Equipo de trabajo'},
	        { leyenda: 'Procedencia'},	        
	        { leyenda: 'Presunto(s)',columna:''},          
	        { leyenda: 'Días trabajados', filtro:false, columna:''},	        
	    ],
	    modelo: [
	    	{ class:'',formato: function(tr, obj, valor){
	    		var ruta = "", actions = [];
	    		if (obj.autoridad == null){
	    			actions.push({ href:"javascript:open_modal('modal_add_responsable',"+obj.queja_id+",'queja_id');", contenido: '<i class="fa fa-users"></i> Asignar equipo de trabajo' });
	    		}else{
					actions.push({ href: "index.php?menu=modificar&queja_id="+obj.queja_id, contenido: '<i class="fa fa-edit"></i>Modificar' });
					actions.push({ href:"index.php?menu=resolver&exp="+obj.id, contenido: '<i class="fa fa-pencil"></i> Registrar resolución de la Comisión' });
					actions.push({ href: "index.php?menu=reserva&exp="+obj.queja_id, contenido: '<i class="fa fa-pause"></i>Poner en reserva' });
					actions.push({ href: "index.php?menu=list_reservas&exp="+obj.queja_id, contenido: '<i class="fa fa-eye"></i>Listar reserva' });
					//actions.push({ href: "index.php?menu=apersonamiento&queja_id="+obj.queja_id, contenido: '<i class="fa fa-edit"></i> Registrar seguimiento presencial' });
					actions.push({ href: "index.php?menu=demandar&exp="+obj.id, contenido: '<i class="fa fa-pencil"></i> Registrar demanda' });
					actions.push({ href: "index.php?menu=list_demandas&exp="+obj.id, contenido: '<i class="fa fa-list"></i> Listado de demandas' });
					actions.push({ href: "index.php?menu=cedula&exp="+obj.id, contenido: '<i class="fa fa-eye"></i>Cédula' });
					actions.push({ href: "index.php?menu=improcedencia&exp="+obj.queja_id, contenido: '<i class="fa fa-book"></i> Concluir expediente' });
					actions.push({ href: "javascript:open_modal('modal_add_sancion',"+obj.queja_id+",'queja_id');", contenido: '<i class="fa fa-plus"></i> Registrar sanción' });
					actions.push({ href: "javascript:open_modal('modal_add_verificacion',"+obj.queja_id+",'queja_id');", contenido: '<i class="fa fa-plus"></i> Registrar verificación' });
	    		}																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																							
	            return anexGrid_dropdown({
                    contenido: '<i class="glyphicon glyphicon-cog"></i>',
                    class: 'btn btn-primary ',
                    data: actions
                });
	        }},
	        { formato: function(tr, obj, valor){
	        	if (obj.estado == 2){
	        		
	        		if (obj.edo_tr == 'SI') {
        				tr.addClass('bg-light-blue-active');
        			}else{
        				tr.addClass('bg-light-blue-active');
        				tr.addClass('disabled');
        			}
	        	}
	        	if (obj.estado == 3){
	        		
	        		if (obj.edo_tr == 'SI') {
        				tr.addClass('bg-purple-active');
        			}else{
        				tr.addClass('bg-purple-active');
        				tr.addClass('disabled');
        			}
	        	}
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
	        { formato: function(tr, obj, valor){
	        	var lista = "";
	        	lista += "<ul>";
		        	lista += "<li>"+obj.jefe+"</li>";
		        	lista += "<li>"+obj.abogado+"</li>";
	        	lista += "</ul>";
            	return lista;
        	}},
	        { formato: function(tr, obj, valor){
	        	if ( obj.procedencia == 'SS' ) {return 'SECRETARÍA DE SEGURIDAD';}
	        	if ( obj.procedencia == 'CPRS' ) {return obj.procedencia;}
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
	        { propiedad: 'f_cierre' },
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
			document.getElementById('frm_add_responsable').reset();
			alerta('div_responsable',response.status,response.message,'modal_add_responsable');
		})
		.fail(function(jqXHR,textStatus,errorThrow) {
			document.getElementById('frm_add_responsable').reset();
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
	if (modal == 'modal_situacion') {
		situacion_sc(val);
	}
	if (modal == 'modal_add_verificacion') {
		load_catalogo('sanciones','select','1X');
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
	if ( element == 'sanciones' ) { data = $('#v_queja_id').val(); }
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
		//if (response.sc.primer == '0' || typeof response.sc === undefined) {
		if (typeof response.sc.primer === 'undefined') {
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
		/*************EXPEDIENTES EN ARCHIVO IMPROCEDENCIA, RESERVA E INCOMPETENCIA **************/
		$.each(response.q_estados, function(i, val) {
			var fila = "";
			fila += "<tr class='text-center'>";
				fila += "<td>"+val.nombre+"</td>";
				fila += "<td>"+val.cuenta+"</td>";
				fila += "<td>";
					fila += "<button class='btn btn-success btn-flat' onclick='open_modal("+'"modal_situacion"'+","+val.estado+","+'"estado"'+");'><i class='fa fa-legal'></i></button>";
				fila +="</td>";
			fila += "</tr>";
			$('#tbl_sc tbody').append(fila);
		});
		
	})
	.fail(function(jqXHR,textStatus,errorThrow) {
		console.log("Error: "+jqXHR.responseText);
	});
	
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
///Tablero de control
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
			alerta('div_acuse',response.status,response.message,'');
		})
		.fail(function(jqXHR,textStatus,errorThrown) {
			alerta('div_acuse','error',jqXHR.responseText,'');
		});
	});
}
function frm_add_sancion() {
	$('#frm_add_sancion').submit(function(e) {
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
			alerta('a_sancion',response.status,response.message,'modal_add_sancion');
		})
		.fail(function(jqXHR,textStatus,errorThrow) {
			alerta('a_sancion','error',jqXHR.responseText,'modal_add_sancion');
		});
		
	});
}
function frm_edit_sv() {
	$('#frm_edit_sv').submit(function(e) {
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
			alerta('san_ver',response.status,response.message,'');
			setTimeout(function (argument) {
				location.reload();
			},5000);
		})
		.fail(function(jqXHR,textStatus,errorThrow) {
			alerta('san_ver','error',jqXHR.responseText,'');
		});
		
	});
}
function frm_add_verificacion() {
	$('#frm_add_verificacion').submit(function(e) {
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
			alerta('a_verificacion',response.status,response.message,'modal_add_verificacion');
		})
		.fail(function(jqXHR,textStatus,errorThrow) {
			alerta('a_verificacion','error',jqXHR.responseText,'modal_add_verificacion');
		});
		
	});
}