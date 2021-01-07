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
		actions_eprocesal();
		load_catalogo('cargo','select',22);
		autocomplete_input('jefe_sapa','jefe_sapa_id',3);
		autocomplete_input('analista','analista_id',3);
		autocomplete_input('n_oficio','n_oficio_id',10);
		
		autocomplete_input('oficio_a','oficio_a_id',10);
		catalogo_conductas();
		
		frm_add_seguimiento();
		//formularios nuevos
		frm_add_responsable();		
		frm_add_eprocesal();
		frm_add_culpable();
		
		frm_asignar();
		//para las sanciones 
		autocomplete_input('oficio_sa','oficio_sa_id',10);
		frm_add_sancion();
		frm_add_verificacion();
		eventNormatividad();
		load_catalogo('t_ley','select','7A');

	}
	if ( url == '?menu=estadistica' ) {
		$('#option_2').addClass('active');
		frm_estadistica();
	}
	if ( url == '?menu=acuse' ) {
		$('#option_3').addClass('active');
		autocomplete_input('oficio','oficio_id',10);
		frm_add_acuse();
		lista_acuses();
	}
	if ( url == '?menu=e_procesal' ) {
		//evento('autoridad','select');
		load_catalogo('cargo','select',22);
		autocomplete_input('jefe','jefe_id',3);
		autocomplete_input('analista','analista_id',3);
		autocomplete_input('oficio','oficio_id',10);
		frm_edo_procesal();
		catalogo_conductas();
		//eventos para cuando cambie de el valor de la autoridad
		$('#autoridad').change(function(e) {
			e.preventDefault();
			if ( $(this).val() == '3' ) {
				$('#motivo_sc').removeClass('hidden');
			}else{
				$('#motivo_sc').addClass('hidden');
			}
		});
	}
	if ( url == '?menu=modificar' ) {
		frm_edit_edo_procesal();
		autocomplete_input('jefe','jefe_id',3);
		autocomplete_input('analista','analista_id',3);	
		autocomplete_input('oficio','oficio_id',10);
		actions_eprocesal();
	}
	return false; 
}
function getExpedientes() {
	//alert('Todo bien hasta aqui ');
	var nivel = $('#nivel').val();
	var actions = [];
	var opt ;
	if (nivel == 'SUBDIRECTOR') {
		opt = 9;
	}else if (nivel == 'ANALISTA' || nivel == 'JEFE'){
		opt = 13;
	}
	
	var tabla = $("#expedientes").anexGrid({
	    class: 'table-striped table-bordered table-hover',
	    columnas: [
	    	{ leyenda: 'Acciones', style: 'width:10px;', columna: 'Sueldo' },
	    	{ leyenda: 'ID'},
	        { leyenda: 'Expediente', filtro:true, columna:'q.cve_ref'},
	        { leyenda: 'Procedencia'},
	        { leyenda: 'Presunto(s)'},
	        { leyenda: 'Origen de queja'},	        
	        { leyenda: 'Turnado a (Jefe de Depto.)', filtro:true, columna:'qr.jefatura'},	        
	        { leyenda: 'Asignado a (Abogado Analista)', filtro:true, columna:'qr.analista'},
            { leyenda: 'Edo. Procesal',columna:'qr.e_procesal',filtro:function () {
            	return anexGrid_select({
    	            data: [
    	                { valor: '', contenido: 'Todos' },
    	                { valor: '1', contenido: 'ENVIADO' },
    	                { valor: '4', contenido: 'RESUELTO' },
    	                { valor: '3', contenido: 'DEVUELTO' },
    	                { valor: '2', contenido: 'TRÁMITE' },
    	            ]
    	        });
            }},	        
            { leyenda: 'Autoridad destino',columna:'qr.autoridad',filtro:function () {
            	return anexGrid_select({
    	            data: [
    	                { valor: '', contenido: 'Todos' },
    	                { valor: '1', contenido: 'CHyJ' },
    	                { valor: '2', contenido: 'OIC' },
    	                { valor: '3', contenido: 'Subd. Cont' }
    	            ]
    	        });
            }},	        
	        { leyenda: 'Fecha de asignación', filtro:false, columna:''},	        
	        { leyenda: 'Días transcurridos', filtro:false, columna:''},
	        { leyenda: 'Días trabajados por abogado', filtro:false, columna:''},
	    ],
	    modelo: [
	    	
	    	{ class:'',formato: function(tr, obj, valor){
	    		//console.log(  ) ;
	    		var actions = [];
    			if( obj.jefe == null ){
    				actions.push({ href: "javascript:open_modal('modal_asignar',"+obj.queja_id+",'queja_id');", contenido: '<i class="fa fa-user-plus"></i> Asignar equipo de trabajo' });
    			}else{
					actions.push({ href: "javascript:open_modal('modal_add_culpable',"+obj.queja_id+",'queja_id');", contenido: '<i class="fa fa-plus"></i>Agregar responsable' });
					actions.push({ href: "javascript:open_modal('modal_add_seguimiento',"+obj.queja_id+",'queja_id');", contenido: '<i class="fa fa-plus"></i>Agregar seguimiento' });
					actions.push({ href: "javascript:open_modal('modal_add_eprocesal',"+obj.queja_id+",'queja_id');", contenido: '<i class="fa fa-book"></i>Definir estado procesal' });
					actions.push({ href: "index.php?menu=cedula&exp_id="+obj.queja_id, contenido: '<i class="fa fa-file-text-o"></i>Cédula' }); 
					actions.push({ href: "javascript:open_modal('modal_add_sancion',"+obj.queja_id+",'queja_id');", contenido: '<i class="fa fa-plus"></i> Registrar sanción' });
					if (nivel == 'SUBDIRECTOR') {
						actions.push({ href: "index.php?menu=modificar&queja_id="+obj.queja_id, contenido: '<i class="fa fa-edit"></i> Modificar' }); 
					}
    			}

				var valor = tabla.obtener(tr.data('fila'));
				//x = [];
				//Array.prototype.push.apply(actions, x);
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
	        
	        { propiedad: 'categoria'},
	        
	        { formato: function(tr, obj, valor){
	        	if (obj.jefe == null) {
	        		return "AÚN NO SE TURNA";
	        	}else{
	        		return obj.jefe;
	        	}
	        }},

	        { formato: function(tr, obj, valor){
	        	if (obj.analista == null) {
	        		return "AÚN NO SE TURNA";
	        	}else{
	        		return obj.analista;
	        	}
	        }},
	        { formato:function(tr,obj,valor){
	        	
	        	if(obj.e_procesal == 'ENVIADO'){tr.addClass('bg-green');}
	        	if(obj.e_procesal == 'RESUELTO'){tr.addClass('bg-yellow');}
	        	if(obj.e_procesal == 'DEVUELTO'){tr.addClass('bg-gray');}
	        	if (obj.e_procesal == null) {
	        		tr.addClass('bg-teal disabled');
	        		return 'AÚN NO SE REGISTRA';
	        	}else{
	        		//tr.addClass('bg-teal disabled');
	        		return obj.e_procesal;
	        	}
	        }},
	        { formato: function(tr, obj, valor){
	        	if (obj.autoridad == 'SC') {
	        		return "SUBDIRECCIÓN DE LO CONTENCIOSO ";
	        	}
	        	if (obj.autoridad == 'OIC') {
	        		return "ORGANO DE CONTROL INTERNO";
	        	}
	        	if (obj.autoridad == 'CHyJ') {
	        		return "COMISIÓN DE HONOR Y JUSTICIA";
	        	}
	        }},
	        { class:'text-center',formato: function(tr, obj, valor){
	        	return obj.f_sapa;
	        }},
	        { class:'text-center',formato: function(tr, obj, valor){
	        	return obj.dias_t;
	        }},
	        { class:'text-center',formato: function(tr, obj, valor){
	        	return obj.diast_analista;
	        }}
	    ],
	    url: 'controller/puente.php?option='+opt,
	    filtrable: true,
	    columna: 'id',
	    columna_orden: 'DESC'
	});
	return tabla;
}
//Listado de acuses 
function lista_acuses() {
	var table = $("#tbl_acuses").anexGrid({
	    class: 'table-striped table-bordered table-hover',
		    columnas: [
		    	{ leyenda: 'ID'},
		        { leyenda: 'NÚMERO DE OFICIO', filtro:false, columna:''},
		        { leyenda: 'FECHA DEL ACUSE', filtro:false, columna:''},
		        { leyenda: 'ASUNTO', filtro:false, columna:''},
		        { leyenda: 'COMENTARIO', filtro:false, columna:''},
		    ],
		    modelo: [
		        { propiedad: 'id' },
		        { formato: function(tr, obj, valor){
		        	return "<a href='controller/puente.php?option=16&doc="+obj.id+"'>"+obj.oficio+"</a>";
		        }},
		        { propiedad: 'f_acuse' },
		        { propiedad: 'asunto' },
		        { propiedad: 'comentario' },
		    ],
		    url: 'controller/puente.php?option=15',
		    filtrable: true,
		    columna: 'id',
		    columna_orden: 'DESC'
		});
	return table;
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
	//console.log(val);
	if (name != '') {
		$('[name="'+name+'"]').val(val);
	}
	if (name == 'queja_id') {
		getClave(val,modal);
	}

	if (modal == 'modal_add_verificacion') {
		load_catalogo('sanciones','select','1X');
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
	var result;
	
	var result, data=$('#capitulos').val(); 
	var art = $('#art').val();
	var sec = $('#secciones').val();
	var fracciones = $('#fracciones').val();
	if ( element == 'sanciones' ) { data = $('#v_queja_id').val(); }
	if ( element != '' ) {
		$('#'+element).html('');
	}
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: option, data:data,art:art,sec:sec,fra:fracciones },
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
			$('#frm_add_seguimiento').trigger('trigger');
			//document.getElementId("frm_add_seguimiento").reset();
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
		$('#conducta').select2({
			width: '100%'//necesidad de anular el cambio predeterminado
		});
	})
	.fail(function(jqXHR,textStatus,errorThrow) {
		console.log("Error: "+jqXHR.responseText);
	}) ;
	
}

//Formulario 
function frm_add_acuse() {
	$('#frm_add_acuse').submit(function(e) {
		e.preventDefault();
		var dataForm =  new FormData(document.getElementById("frm_add_acuse"));
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
			alerta('alerta',response.status,response.message,'');
			
			setTimeout(function(){
				lista_acuses();
				var frm = $('#frm_add_acuse');
				document.getElementById("frm_add_acuse").reset();
			}, 5000);
		})
		.fail(function(jqXHR,textStatus,errorThrow) {
			console.log("error");
		});
		
	});
	return false;
}
//Formulario para asignar al personal a cargo 
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
			alerta('m_responsable',response.status,response.message,'modal_add_responsable');
			document.getElementById('frm_add_responsable').reset();
			getExpedientes();
		})
		.fail(function(jqXHR,textStatus,errorThrow) {
			console.log("Error: "+jqXHR.responseText);
		});
		
	});
}
//Formulario para guardar el estado procesal del modal
function frm_add_eprocesal() {
	$('#frm_add_eprocesal').submit(function(e) {
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
			alerta('m_eprocesal',response.status, response.message,'modal_add_eprocesal');
		})
		.fail(function(jqXHR,textStatus,errorThrow) {
			console.log("Error: "+jqXHR.responseText);
		});
		
	});;
}
function frm_add_culpable() {
	$('#frm_add_culpable').submit(function(e) {
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
			alerta('m_culpable',response.status, response.message,'modal_add_culpable');
		})
		.fail(function(jqXHR,textStatus,errorThrow) {
			console.log("Error: "+jqXHR.responseText);
		});
		
	});;
}
//formulario para resultado de estadistica
function frm_estadistica() {
	$('#frm_estadistica').submit(function(e) {
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
			
			if ( $.fn.DataTable.isDataTable( '#tbl_estadistica' ) ){
				var t = $('#tbl_estadistica').DataTable();
				t.destroy();
			}
			$('#tbl_estadistica tbody').html("");
			var c = 0;
			$.each(response, function(i, val) {
				c = i+1;
				var tr = "";
				//Validar 
				if (val.cve_ref ==  null) { cve = "NO DEFINIDO";}else { cve = val.cve_ref }
				if (val.procedencia == null ) { procedencia = "NO DEFINIDO";}else { procedencia = val.procedencia }
				if (val.oficio == null ) { oficio = "NO DEFINIDO";}else { oficio = val.oficio }
				if (val.categoria == null ) { categoria = "NO DEFINIDO";}else { categoria = val.categoria }
				if (val.e_procesal == null ) { e_procesal = "NO DEFINIDO";}else { e_procesal = val.e_procesal }
				//if (val.autoridad == null ) { autoridad = "NO DEFINIDO";}else { autoridad = val.autoridad }
				if (val.f_disponibilidad == null ) { f_disponibilidad = "NO DEFINIDO";}else { f_disponibilidad = val.f_disponibilidad }
				if (val.n_estado == null ) { estado = "NO DEFINIDO";}else { estado = val.n_estado }
				var autoridad  = (val.autoridad == null) ? "NO DEFINIDO" :val.autoridad ;
				tr += "<tr>";
					tr += "<td>"+c+"</td>";
					tr += "<td>"+val.cve_exp+"</td>";
					tr += "<td>"+val.n_procedencia+"</td>";
					tr += "<td>"+oficio+"</td>";
					tr += "<td>"+categoria+"</td>";
					tr += "<td>"+e_procesal+"</td>";
					tr += "<td>"+autoridad+"</td>";
					tr += "<td>"+f_disponibilidad+"</td>";
					tr += "<td>"+f_disponibilidad+"</td>";
					tr += "<td>"+estado+"</td>";
				tr += "</tr>";
				$('#tbl_estadistica').append(tr);
			});
			var tbl = applyDataTables('tbl_estadistica');

		})
		.fail(function(jqXHR,textStatus,errorThrow) {
			alerta('estadistica','error', jqXHR.responseText,'');
		});
		//generar los tableros
		if ( $('#e_procesal').val() == '' ) {
			getContadoresByEdo('');
		}else{
			getContadoresByEdo($('#e_procesal').val());
		}
	});
	return false;
}
function getContadoresByEdo(edo) {
	$('#div_contadores').html('');

	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '99',edo:edo,fi: $('#f_ini').val(), ff:$('#f_fin').val() },
		async:false,
		cache: false,
	})
	.done(function(response) {
		$.each(response, function(i, val) {
			var contador = "",estado = '';
			
			contador +='<div class="col-md-3">';
			    contador +='<div class="info-box">';
			        contador +='<span class="info-box-icon bg-aqua"><i class="ion ion-ios-gear-outline fa-spin"></i></span>';
			        contador +='<div class="info-box-content">';
			            contador +='<span class="info-box-text">'+val.e_procesal+'</span>';
			            contador +='<span class="info-box-number">'+val.cuenta+'<small></small></span>';
			        contador +='</div>';
			    contador +='</div>';
			contador +='</div>';
			$('#div_contadores').append(contador);
		});
	})
	.fail(function() {
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
//Acciones del modal del estado procesal
function actions_eprocesal() {
	$('#e_procesal').change(function(e) {
		e.preventDefault();
		//alert($(this).val());
		if( $(this).val() == 3 ){
			$('#oficio').removeClass('hidden');
			$('#f_acuse').removeClass('hidden');
			$('#n_semana').removeClass('hidden');
			$('#fojas').removeClass('hidden');
			$('#t_doc').removeClass('hidden');
			$('#div_conducta').addClass('hidden');
			$('#div_auto').addClass('hidden');
			$('#resuelto').addClass('hidden');
			$('#div_normatividad').addClass('hidden');	
		}
		if( $(this).val() == 1 ){
			$('#oficio').removeClass('hidden');
			$('#f_acuse').removeClass('hidden');
			$('#n_semana').removeClass('hidden');
			$('#fojas').removeClass('hidden');
			$('#t_doc').removeClass('hidden');
			$('#div_conducta').removeClass('hidden');
			$('#div_auto').removeClass('hidden');
			$('#resuelto').addClass('hidden');	
			$('#div_normatividad').removeClass('hidden');
				
		}
		if( $(this).val() == 4 ){
			$('#oficio').addClass('hidden');
			$('#f_acuse').addClass('hidden');
			$('#n_semana').addClass('hidden');
			$('#fojas').addClass('hidden');
			$('#t_doc').addClass('hidden');
			$('#div_conducta').addClass('hidden');
			$('#div_auto').addClass('hidden');			
			$('#resuelto').removeClass('hidden');	
			$('#div_normatividad').addClass('hidden');		
		}

	});
	$('#autoridad').change(function(e) {
		e.preventDefault();
		if ( $(this).val() == '3' ) {
			$('#div_conducta').addClass('hidden');
			$('#div_motivo').removeClass('hidden');
		}else{
			$('#div_conducta').removeClass('hidden');
			$('#div_motivo').addClass('hidden');
		}
	});
	return false;
}
//Formulario para asigar 
function frm_asignar(){
	$('#frm_asignar').submit(function(e) {
		e.preventDefault();
		var dataForm = new FormData(document.getElementById("frm_asignar"));
		$.ajax({
			url: 'controller/puente.php',
			type: 'POST',
			dataType: 'json',
			data: dataForm,
			async:false,
			cache: false,
			processData: false,
            contentType: false,
		})
		.done(function(response) {
			alerta('m_asignar',response.status,response.message,'modal_asignar');
		})
		.fail(function(jqXHR,textStatus,errorThrow) {
			console.log("Error: "+jqXHR.responseText);
		});
		
	});
	return false;
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
function eventNormatividad(){
	$('#t_ley').change(function(e){
		e.preventDefault();
		load_catalogo( 'capitulos', 'select', '4X');
		load_catalogo( 'conducta', 'select', 8);
	});
	$('#capitulos').change(function(e){
		e.preventDefault();
		$('#art,#secciones,#fracciones').html('');
		load_catalogo( 'art', 'select', '7B');
		load_catalogo( 'conducta', 'select', 8);

	});
	$('#art').change(function(e){
		e.preventDefault();
		$('#secciones,#fracciones').html('');
		load_catalogo( 'secciones', 'select','7C');
		load_catalogo( 'conducta', 'select', 8);
	});
	$('#secciones').change(function(e){
		e.preventDefault();
		$('#fracciones').html('');
		load_catalogo( 'fracciones', 'select', '7D');
		load_catalogo( 'conducta', 'select', 8);
	});
	$('#fracciones').change(function(e){
		e.preventDefault();
		load_catalogo( 'conducta', 'select', 8);
	});
}