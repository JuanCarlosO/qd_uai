var rescate;
$(document).ready(function() {
	console.log('Bienvenido a Sistema de Registro de Actas.');
	$('[data-mask]').inputmask();
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
		getDashboard();
	}
	if ( url == '?menu=ordenes' ) {
		$('#option_2').addClass('active');
		getOrdenes();
		frm_ot_upload();
	}
	if ( url == '?menu=reports' ) {
		//alert('vamos a reportar')
		$('#option_3').addClass('active');
		load_catalogo('municipio','select',10);
		frm_reportes();
	}
	if ( url == '?menu=aviso' ) {
		$('#option_4').addClass('active');
	}
	if ( url == '?menu=manual' ) {
		$('#option_5').addClass('active');
	}
	if ( url == '?menu=seguimiento' ) {
		frm_add_pr();//FORMUALRIO DEL PRESUNTO RESPONSABLE
		getPresuntos();
		frm_add_quejoso();getQuejosos();
		frm_add_vehiculo();
		frm_add_animal();
		getAutos();getAnimales();frm_add_arma();
		getDocumentos();
		//Catalogos
		load_catalogo('submarca','select',40);//Marcas de los vehiculos
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
                    id: 'editar',
                    data: [
                        { href: "javascript:open_modal('modal_ot_upload',"+obj.id+");", contenido: '<i class="glyphicon glyphicon-cloud"></i> Adjuntar documento' },
                        { href: "javascript:open_modal('modal_add_obs');", contenido: '<i class="glyphicon glyphicon-comment"></i>Agregar observaciones' },
                        { href: 'index.php?menu=detalle&ot='+obj.id, contenido: '<i class="glyphicon glyphicon-eye-open"></i>Ver detalle' },
                    ]
                });
	        }},
	    	
	        { propiedad: 'id' },
	        { propiedad: 'clave' },
	        { propiedad: 'f_creacion' },
	        { propiedad: 'id'},
	        { propiedad: 'estatus'}
	        
	        
	    ],
	    url: 'controller/puente.php?option=8',
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
                        { href: 'index.php?menu=seguimiento&acta='+obj.id, contenido: '<i class="glyphicon glyphicon-road"></i> Dar seguimiento' },
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
	if (modal == 'modal_ot_upload') {
		$('[name="oin_id"]').val(id);
	}else{
		$('[name="acta_id"]').val(id);
	}
	
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
			alerta('div_pr',response.status,response.message,'modal_add_pr');
			setTimeout(function(){
				document.getElementById('frm_add_pr').reset();
				getPresuntos();
			},3000);
		})
		.fail(function(jqXHR, textStatus, errorThrown) {
			alerta('div_pr','error',jqXHR.responseText,'modal_add_pr');
		});
		
	} );
	return false;
}
function getPresuntos() {
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '34',acta:$('acta_id').val()},
		async:false,
		cache:false,
	})
	.done(function(response) {
		$('#tbl_pr>tbody').html("");
		var ap,am;
		$.each(response, function(i, val) {
			if(val.ap_pat == null ){
				ap = "S/A"
			}else{
				ap = val.ap_pat;
			}
			if(val.ap_mat == null ){
				am = "S/A"
			}else{
				am = val.ap_mat;
			}

			$('#tbl_pr').append(
				'<tr>'+
					'<td>'+(++i)+'</td>'+
					'<td>'+val.nombre+' '+ap+' '+am+'</td>'+
					'<td>'+val.procedencia+'</td>'+
					'<td>'+
						'<button class="btn btn-danger btn-flat" onclick="delete_pr('+val.id+')">'+
							'<i class="fa fa-trash "></i>'+
						'</button>'+
					'</td>'+
				'</tr>'
			);
		});
	})
	.fail(function(jqXHR, textStatus, errorThrown) {
		console.log("Error: "+jqXHR.responseText);
	});
	return false;
}
//Eliminar un presunto responsable 
function delete_pr(element){
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '35',e:element},
		async:false,
		cache:false,
	})
	.done(function(response) {
		alerta('alert_pr',response.status,response.message,'');
		if(response.status == 'success'){
			setTimeout(function(){
				getPresuntos();
			},3000);
		}
	})
	.fail(function(jqXHR, textStatus, errorThrown) {
		console.log("error");
	});
	
	return false;
}
//formulario para agregar quejoso 
function frm_add_quejoso(){
	$('#frm_add_quejoso').submit( function(e){
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
			alerta('modal_quejoso',response.status,response.message,'modal_add_quejoso');
			setTimeout(function(){
				document.getElementById('frm_add_quejoso').reset();
				getPresuntos();
			},3000);
		})
		.fail(function(jqXHR, textStatus, errorThrown) {
			alerta('modal_quejoso','error',jqXHR.responseText,'modal_add_quejoso');
		});
		
	} );
	return false;
}

function getQuejosos(){
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '37',acta:$('#acta_id').val()},
		async:false,
		cache:false,
	})
	.done(function(response) {
		$('#tbl_quejoso>tbody').html("");
		var ap,am;
		$.each(response, function(i, val) {
			if(val.ap_pat == null ){
				ap = "S/A"
			}else{
				ap = val.ap_pat;
			}
			if(val.ap_mat == null ){
				am = "S/A"
			}else{
				am = val.ap_mat;
			}

			$('#tbl_quejoso').append(
				'<tr>'+
					'<td>'+(++i)+'</td>'+
					'<td>'+val.nombre+' '+ap+' '+am+'</td>'+
					'<td>'+val.genero+'</td>'+
					'<td>'+
						'<button class="btn btn-danger btn-flat" onclick="delete_quejoso('+val.id+')">'+
							'<i class="fa fa-trash "></i>'+
						'</button>'+
					'</td>'+
				'</tr>'
			);
		});
	})
	.fail(function(jqXHR, textStatus, errorThrown) {
		console.log("Error: "+jqXHR.responseText);
	});
	return false;
}
//Eliminar un quejoso
function delete_pr(element){
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '38',e:element},
		async:false,
		cache:false,
	})
	.done(function(response) {
		alerta('alert_quejoso',response.status,response.message,'');
		if(response.status == 'success'){
			setTimeout(function(){
				getQuejosos();
			},3000);
		}
	})
	.fail(function(jqXHR, textStatus, errorThrown) {
		console.log("error");
	});
	
	return false;
}
//AGREGAR UN VEHICULO 
function frm_add_vehiculo() {
	$('#frm_add_vehiculo').submit(function (e) {
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
			alerta('div_auto',response.status,response.message,'modal_add_vehiculo');
			if(response.status == 'success'){
				setTimeout(function(){
					//getAutos();
					return false;
				},3000);
			}
		})
		.fail(function(jqXHR, textStatus, errorThrown) {
			alerta('div_auto','error', jqXHR.responseText, '');
		});
		
	});
	return false;
}
//Recuperar los autos implicados 
function getAutos() {
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '41',acta:$('#acta_id').val()},
		async:false,
		cache:false,
	})
	.done(function(response) {
		$('#tbl_autos>tbody').html("");
		var ap,am;
		$.each(response, function(i, val) {
			
			$('#tbl_autos').append(
				'<tr>'+
					'<td>'+(++i)+'</td>'+
					'<td>'+
						'<ol>'+
							'<li>'+val.n_marca+'</li>'+
							'<li>'+val.n_submarca+'</li>'+
							'<li>'+val.t_auto+'</li>'+
							'<li>'+val.modelo+'</li>'+
							'<li>'+val.color+'</li>'+
						'</ol>'+
					'</td>'+
					'<td>'+
						'<ol>'+
							'<li>NIV: '+val.niv+'</li>'+
							'<li>PLACAS: '+val.placa+'</li>'+
							'<li>PLACAS: '+val.n_inventario+'</li>'+
						'</ol>'+
					'</td>'+
					'<td>'+
						'<button class="btn btn-danger btn-flat" onclick="delete_auto('+val.id+')">'+
							'<i class="fa fa-trash "></i>'+
						'</button>'+
					'</td>'+
				'</tr>'
			);
		});
	})
	.fail(function(jqXHR, textStatus, errorThrown) {
		console.log("Error: "+jqXHR.responseText);
	});
	return false;
}
//Eliminar un vehiculo
function delete_auto(element){
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '42',e:element,acta:$('#acta_id').val()},
		async:false,
		cache:false,
	})
	.done(function(response) {
		alerta('alert_autos',response.status,response.message,'');
		if(response.status == 'success'){
			setTimeout(function(){
				getAutos();
			},3000);
		}
	})
	.fail(function(jqXHR, textStatus, errorThrown) {
		alerta('alert_autos','error', jqXHR.responseText, '');
	});
	return false;
}
//recuperar marcas de vehiculos 
function getMarcas() {
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '37'},
		async:false,
		cache:false,
	})
	.done(function(response) {
		$('#tbl_quejoso>tbody').html("");
		var ap,am;
		$.each(response, function(i, val) {
			if(val.ap_pat == null ){
				ap = "S/A"
			}else{
				ap = val.ap_pat;
			}
			if(val.ap_mat == null ){
				am = "S/A"
			}else{
				am = val.ap_mat;
			}

			$('#tbl_quejoso').append(
				'<tr>'+
					'<td>'+(++i)+'</td>'+
					'<td>'+val.nombre+' '+ap+' '+am+'</td>'+
					'<td>'+val.genero+'</td>'+
					'<td>'+
						'<button class="btn btn-danger btn-flat" onclick="delete_quejoso('+val.id+')">'+
							'<i class="fa fa-trash "></i>'+
						'</button>'+
					'</td>'+
				'</tr>'
			);
		});
	})
	.fail(function(jqXHR, textStatus, errorThrown) {
		console.log("Error: "+jqXHR.responseText);
	});
	return false;
}

function frm_add_animal() {
	$('#frm_add_animal').submit(function(e) {
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
			alerta('div_animal',response.status,response.message,'modal_add_animal');
			if(response.status == 'success'){
				setTimeout(function(){
					getAnimales();
					return false;
				},3000);
			}
		})
		.fail(function(jqXHR, textStatus, errorThrown) {
			alerta('div_animal','error', jqXHR.responseText, '');
		});
	});
	return false;
}

function getAnimales() {
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '44',acta:$('#acta_id').val()},
		async:false,
		cache:false,
	})
	.done(function(response) {
		$('#tbl_animales>tbody').html("");
		var ap,am;
		$.each(response, function(i, val) {
			
			$('#tbl_animales').append(
				'<tr>'+
					'<td>'+(++i)+'</td>'+
					'<td>'+
						'<ol>'+
							'<li> TIPO: '+val.tipo+'</li>'+
							'<li> RAZA: '+val.raza+'</li>'+
							'<li> NOMBRE: '+val.nombre+'</li>'+
							'<li> EDAD: '+val.edad+'</li>'+
							'<li> COLOR: '+val.color+'</li>'+
						'</ol>'+
					'</td>'+
					'<td>'+val.inv+'</td>'+
					'<td>'+
						'<button class="btn btn-danger btn-flat" onclick="delete_animal('+val.id+')">'+
							'<i class="fa fa-trash "></i>'+
						'</button>'+
					'</td>'+
				'</tr>'
			);
		});
	})
	.fail(function(jqXHR, textStatus, errorThrown) {
		console.log("Error: "+jqXHR.responseText);
	});
	return false;
}
function delete_animal(element){
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '45',e:element,acta:$('#acta_id').val()},
		async:false,
		cache:false,
	})
	.done(function(response) {
		alerta('alert_animales',response.status,response.message,'');
		if(response.status == 'success'){
			setTimeout(function(){
				getAnimales();
			},3000);
		}
	})
	.fail(function(jqXHR, textStatus, errorThrown) {
		alerta('alert_animales','error', jqXHR.responseText, '');
	});
	return false;
}
//METODOS DE LAS ARMAS 
function frm_add_arma() {
	$('#frm_add_arma').submit(function(e) {
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
			alerta('div_armas',response.status,response.message,'modal_add_arma');
			if(response.status == 'success'){
				setTimeout(function(){
					getArmas();
					return false;
				},3000);
			}
		})
		.fail(function(jqXHR, textStatus, errorThrown) {
			alerta('div_armas','error', jqXHR.responseText, '');
		});
	});
	return false;
}
function getArmas() {
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '46',acta:$('#acta_id').val()},
		async:false,
		cache:false,
	})
	.done(function(response) {
		$('#tbl_armas>tbody').html("");
		
		$.each(response, function(i, val) {
			
			$('#tbl_armas').append(
				'<tr>'+
					'<td>'+(++i)+'</td>'+
					'<td>'+
						val.tipo+
					'</td>'+
					'<td>'+
						'<ol>'+
							
							'<li> MATRICULA: '+val.matricula+'</li>'+
							'<li> INVENTARIO: '+val.inv+'</li>'+
						'</ol>'+
					'</td>'+
					'<td>'+
						'<button class="btn btn-danger btn-flat" onclick="delete_arma('+val.id+')">'+
							'<i class="fa fa-trash "></i>'+
						'</button>'+
					'</td>'+
				'</tr>'
			);
		});
	})
	.fail(function(jqXHR, textStatus, errorThrown) {
		console.log("Error: "+jqXHR.responseText);
	});
	return false;
}
function delete_arma(element){
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '48',e:element,acta:$('#acta_id').val()},
		async:false,
		cache:false,
	})
	.done(function(response) {
		alerta('alert_armas',response.status,response.message,'');
		if(response.status == 'success'){
			setTimeout(function(){
				getArmas();
			},3000);
		}
	})
	.fail(function(jqXHR, textStatus, errorThrown) {
		alerta('alert_arma','error', jqXHR.responseText, '');
	});
	return false;
}
//METODOS DE LOS ARCHIVOS 
function getDocumentos(){
	//console.log($('#acta_id').val());
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '49',acta:$('#acta_id').val()},
		async:false,
		cache:false,
	})
	.done(function(response) {
		$('#tbl_docs>tbody').html("");
		
		$.each(response, function(i, val) {
			
			$('#tbl_docs').append(
				'<tr>'+
					'<td>'+(++i)+'</td>'+
					'<td>'+
						val.nombre+
					'</td>'+
					'<td>'+
						val.comentarios+
					'</td>'+
					'<td>'+
						'<a href="#" class="btn btn-default btn-flat" onclick="view_docs('+val.id+')">'+
							'<i class="fa fa-eye "></i>'+
						'</a>'+
					'</td>'+
					'<td>'+
						'<button class="btn btn-danger btn-flat" onclick="delete_doc('+val.id+')">'+
							'<i class="fa fa-trash "></i>'+
						'</button>'+
					'</td>'+
				'</tr>'
			);
		});
	})
	.fail(function() {
		console.log("error");
	});
	return false;
}
function delete_doc(element){
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '50',e:element,acta:$('#acta_id').val()},
		async:false,
		cache:false,
	})
	.done(function(response) {
		alerta('alert_docs',response.status,response.message,'');
		if(response.status == 'success'){
			setTimeout(function(){
				getDocumentos();
			},3000);
		}
	})
	.fail(function(jqXHR, textStatus, errorThrown) {
		alerta('alert_docs','error', jqXHR.responseText, '');
	});
	return false;
}
//GENERADOR DE REPORTE
function frm_reportes() {
	$('#frm_reportes').submit(function(e) {
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
			if ( ! $.fn.DataTable.isDataTable( '#tbl_reporte_actas' ) ) {
				tbl = applyDataTables('reporte');
			}else{

				tbl.rows().remove().draw();
			}
			$('#tbl_reporte_actas>tbody').html();
			$.each(response, function(i, val) {
				$('#tbl_reporte_actas').append(
					'<tr>'+
						'<td>'+(++i)+'</td>'+
						'<td>'+val.clave+'</td>'+
						'<td>'+val.t_actuacion+'</td>'+
						'<td>'+val.fecha+'</td>'+
						'<td>'+val.procedencia+'</td>'+
						'<td>'+val.n_municipio+'</td>'+
						'<td>'+val.comentarios+'</td>'+
					'</tr>'
				);
			});
			applyDataTables('tbl_reporte_actas');
		})
		.fail(function(jqXHR, textStatus, errorThrown) {
			console.log("error");
		});
		
	});
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
        }
	});
	return tabla ;
}
function frm_ot_upload (){
	$('#frm_ot_upload').submit(function(e) {
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
			alerta('div_doc_oin', response.status, response.message, 'modal_ot_upload');
		})
		.fail(function(jqXHR, textStatus, errorThrown) {
			alerta('div_doc_oin', 'error', jqXHR.responseText, 'modal_ot_upload');
		});
	});
	return false;
}
function getDashboard() {
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '57'},
		async:false,
		cache:false,
	})
	.done(function(response) {

		$.each(response, function(i, val) {
			console.log();
			if(val.t_actuacion == 'INSPECCION'){
				$('#c_ins').text(val.cuenta);
			}
			if(val.t_actuacion == 'VERIFICACION'){
				$('#c_ver').text(val.cuenta);
			}
			if(val.t_actuacion == 'SUPERVISION'){
				$('#c_sup').text(val.cuenta);
			}
		});
	})
	.fail(function(jqXHR, textStatus, errorThrown) {
		console.log("Error: "+jqXHR.responseText);
	});

	return false;
}