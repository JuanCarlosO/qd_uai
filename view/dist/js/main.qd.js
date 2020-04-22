var rescate;var url;
$(document).ready(function() {
	
	url = window.location.search;
	url = url.split('&');
	getURL(url[0]);
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
	$('[data-mask]').inputmask();
});

function getURL(url) {
	if ( url == '?menu=list_queja' ) {
		$('#option_1').addClass('active');
		$('#option_1_2').addClass('active');
		listado_qd();
		frm_upload_file();
	}
	if ( url == '?menu=general' ) {
		$('#option_1').addClass('active');
		$('#option_1_1').addClass('active');
		frm_add_queja();
		frm_add_referencia();
		load_catalogo('t_ref','select',2);
		load_catalogo('procedencia','select',4);
		load_catalogo('t_tra','select',5);
		load_catalogo('t_ley','select',7);
		load_catalogo('vias_r','select',9);
		load_catalogo('municipios','select',10);
		load_catalogo('estado','select',12);
		change_TR();
		autocomplete_input('sp','sp_id');
		$('#pregunta').change(function(e) {
			e.preventDefault();
			if( $(this).val() == '2' ){
				$('#cant_person').removeClass('hidden');
				
			}else{
				$('#cant_person').addClass('hidden');
				$('#cantidad').val("");
			}
		});

	}
	if ( url == '?menu=reports' ) {
		$('#option_2').addClass('active');
		load_catalogo('municipio','select',10);
		load_catalogo('t_ref','select',2);
		load_catalogo('estado','select',12);
		load_catalogo('procedencia','select',4);
		load_catalogo('t_tra','select',5);
		load_catalogo('t_ley','select',7);
		load_catalogo('vias_r','select',9);
		frm_reportes();
	}
	if ( url == '?menu=aviso' ) {
		$('#option_3').addClass('active');
	}
	if ( url == '?menu=manual' ) {
		$('#option_4').addClass('active');
	}
	if ( url == '?menu=m_queja' ) {
		console.log('Edición de la queja');
		load_catalogo('t_ley','select',7);
		autocomplete_input('sp','sp_id');	
		frm_edit_queja();
	}
	if ( url == '?menu=seguimiento' ) {
		load_catalogo('municipios','select',10);
		load_catalogo('cargo','select',22);
		load_catalogo('subdir','select',23);
		load_catalogo('region','select',25);
		load_catalogo('agrupamiento','select',24);
		
		frm_add_unidad();
		change_procedencia();
		frm_add_quejoso();
		frm_add_presunto();
	}
	if ( url == '?menu=expedientes' ) {
		tbl = applyDataTables('tbl_abogado');
	}
	if ( url == '?menu=turnar' ) {
		load_catalogo('estado','select',12);
		load_catalogo('t_ley','select',7);
		autocomplete_input('persona','persona_id');
		$('#estado').change(function(e) {
			e.preventDefault();
			var e = $(this).val();
			//cuando es tramite
			if ( e == '1' || e == '9'  ) {
				$('#contenedor_1,#contenedor_2,#contenedor_3,#contenedor_4,#contenedor_5').addClass('hidden');
			}
			//cuando es archivo o imporcedencia
			if ( e == '3' || e == '11'  ) {
				$('#contenedor_1').removeClass('hidden');
				$('#contenedor_2,#contenedor_3,#contenedor_4,#contenedor_5').addClass('hidden');
			}
			//Cuando es respo
			if ( e == '8' ) {
				$('#contenedor_2').removeClass('hidden');
				$('#contenedor_1,#contenedor_3,#contenedor_4,#contenedor_5').addClass('hidden');
			}
			//Cuando es incompetencia
			if ( e == '4' ) {
				$('#contenedor_3').removeClass('hidden');
				$('#contenedor_2,#contenedor_1,#contenedor_4,#contenedor_5').addClass('hidden');
			}
			//Cuando es acumulado
			if ( e == '2' ) {
				$('#contenedor_4').removeClass('hidden');
				$('#contenedor_2,#contenedor_3,#contenedor_1,#contenedor_5').addClass('hidden');
			}
			//Cuando es reserva
			if ( e == '10'  ) {
				$('#contenedor_5').removeClass('hidden');
				$('#contenedor_2,#contenedor_3,#contenedor_4,#contenedor_1').addClass('hidden');
			}
		});
	}
	
	return false; 
}

function add_aver_prev() {
	var html = "";
	html += '<div class="row">'+
                '<div class="col-md-4">'+
                    '<div class="form-group">'+
                        '<label>Origen de la investigación</label>'+
                        '<select id="origen" name="origen[]" class="form-control">'+
                            '<option value="">...</option>'+
                            '<option value="1">FGJEM</option>'+
                            '<option value="2">TRIBUNAL</option>'+
                            '<option value="3">CODHEM</option>'+
                            '<option value="4">UAI</option>'+
                            '<option value="5">SS</option>'+
                            '<option value="6">MUNICIPAL</option>'+
                        '</select>'+
                    '</div>'+
                '</div>'+
                '<div class="col-md-4">'+
                    '<div class="form-group">'+
                        '<label>Tipo de trámite</label>'+
                        '<select id="tramite_prev" name="tramite_prev[]" class="form-control">'+
                            '<option value="">...</option>'+
                            '<option value="1">Acta Circunstanciada</option>'+
                            '<option value="2">Averiguación Previa</option>'+
                            '<option value="3">Noticia Criminal</option>'+
                            '<option value="4">Carpeta de Investigación</option>'+
                            '<option value="5">Causa Penal</option>'+
                            '<option value="6">Expediente</option>'+
                        '</select>'+
                    '</div>'+
                '</div>'+
                '<div class="col-md-4">'+
                    '<div class="form-group">'+
                        '<label>Clave</label>'+
                        '<input type="text" name="clave_prev[]" class="form-control" value="">'+
                    '</div>'+
                '</div>'+
            '</div>';
	$('#otras_aver').append(html);
}
function listado_qd() {
	
	load_catalogo('','json',10);
	
	var aux = [{'valor': '','contenido':'TODOS'}];
	$.each(rescate, function(i, val) {
		aux.push({'valor': val.id,'contenido':val.nombre});
	});

	load_catalogo('','json',12);
	var estados = [{'valor': '','contenido':'TODOS'}];
	$.each(rescate, function(i, val) {
		estados.push({'valor': val.id,'contenido':val.nombre});
	});

	var datos = {
	    class: 'table-striped table-bordered table-hover',
	    columnas: [
	    	{ leyenda: 'Acciones', class:'text-center', style: 'width:10px;',ordenable:true,columna:'id'},
	        { leyenda: 'ID', class:'text-center', style: 'width:20px;',ordenable:true,columna:'id'},
	        { leyenda: 'No. Folio',class:'text-center', style: 'width:100px;', columna:'q.cve_ref',ordenable:false,filtro:true},
	        { leyenda: 'No. expediente',class:'text-center', style: 'width:100px;', columna:'q.cve_exp',ordenable:true,filtro:true},
	        { leyenda: 'Estado',class:'text-center', style: 'width:70px;', columna:'e.id',ordenable:false,filtro:function(){
	        	return anexGrid_select({
    	            data: estados
    	        });
	        }},
	        { leyenda: 'Fecha/Hora de hechos',class:'text-center', style: 'width:100px;', columna:'',ordenable:false},
	        { leyenda: 'Infracción(es)',class:'text-center', style: 'width:100px;', columna:'',ordenable:false},
	        { leyenda: 'Municipio',class:'text-center', style: 'width:100px;', columna:'m.id',ordenable:false,filtro:function(){
	        	return anexGrid_select({
	        		//class:'select2',
    	            data: aux
    	        });
	        }},
	        { leyenda: 'Procedencia',class:'text-center', style: 'width:100px;', columna:'',ordenable:false},
	        { leyenda: 'Fase', class: 'text-center', style: 'width:10px;', columna:'',ordenable:false },
	    ],
	    modelo: [
	    	{ class:'',formato: function(tr, obj, valor){
	            return anexGrid_dropdown({
                    contenido: '<i class="glyphicon glyphicon-cog"></i>',
                    class: 'btn btn-primary opciones',
                    target: '__blank',
                    id: 'editar-' + obj.id,
                    data: [
                        { href: "index.php?menu=seguimiento&queja="+obj.id, contenido: '<i class="fa fa-folder"></i> Dar seguimiento' },
                        { href: "javascript:open_modal('modal_upload_file',"+obj.id+");", contenido: '<i class="glyphicon glyphicon-cloud"></i> Adjuntar documento al expediente' },
                        { href: "index.php?menu=cedula&queja="+obj.id, contenido: '<i class="glyphicon glyphicon-eye-open "></i>Ver cédula' },
                        { href: 'index.php?menu=m_queja&queja='+obj.id, contenido: '<i class="fa fa-pencil"></i> Modificar' },
                        { href: 'index.php?menu=turnar&queja='+obj.id, contenido: '<i class="fa fa-pencil"></i> Turnar' },
                        
                    ]
                });
	        }}, 
	        {class:'text-center', formato: function(tr, obj, valor){
	            return obj.id;
	        }}, 
	        {class:'text-center', formato: function(tr, obj, valor){
	            return obj.cve_ref;
	        }}, 
	        {class:'text-center', formato: function(tr, obj, valor){
	            return obj.cve_exp;
	        }}, 
	        {class:'text-center', formato: function(tr, obj, valor){
	            return obj.n_estado;
	        }}, 
	        {class:'text-center', formato: function(tr, obj, valor){
	            return '<ol>'+
	            	'<li>'+obj.f_hechos+'</li>'+
	            	'<li>'+obj.h_hechos+'</li>'+
	            	''+
	            '</ol>';
	        }}, 
	        {class:'text-justify', formato: function(tr, obj, valor){
	        	
	        	var lista = '<ol>';
	        	$.each(obj.conductas, function(i, v) {
	        		lista += '<li>'+v.nombre+'</li>';
	        	});
	            lista += '</ol>';
	            return lista;
	        }}, 
	        {class:'text-center', formato: function(tr, obj, valor){
	            return obj.municipio;
	        }}, 
	        {class:'text-center', formato: function(tr, obj, valor){
	            return obj.procedencia;
	        }},
	        {class:'text-center', formato: function(tr, obj, valor){
	        	var fase = parseInt(obj.fase);
	            if (fase >= 0 && fase < 70) {
					tr.addClass('bg-green');
	            	return 'INVESTIGAR ('+fase+')';
	            }
	            if (fase > 70 && fase < 88) {
	            	tr.addClass('bg-yellow');
	            	return 'COMPLEMENTO DE INVESTIGACIÓN('+fase+')';
	            }
	            if (fase > 88 ) {
	            	tr.addClass('bg-red-active');
	            	return 'DETERMINACIÓN ('+fase+')';
	            }
	        }}, 
	    ],
	    url: 'controller/puente.php?option=1',
	    columna: 'id',
	    columna_orden: 'DESC',
	    ordenable: true,
	    type:'POST',
	    paginable:true,
	    limite:[25,50,100,200,500],
	    filtrable:true
	    
	};
	var tabla = $("#lista_qd").anexGrid(datos);
	
	return false;
}
//Formulario de alta de queja
function frm_add_queja() {
	$('#frm_add_queja').submit(function(e) {
		e.preventDefault();
		var dataForm = $(this).serializeArray();
		var r = smart_ajax(dataForm);
		console.log(r);
		alerta('div_alert',r.status,r.message,'');
	});
}
//Formulario de alta de tipo de referencia
function frm_add_referencia() {
	$('#frm_add_referencia').submit(function(e) {
		e.preventDefault();
		var a = smart_ajax( $(this).serialize() );
		if ( a.status == 'error' ) {
			alerta('am_ref',a.status,a.message,'modal_add_referencia');
		}else if ( a.status == 'success' ){
			alerta('am_ref',a.status,a.message,'modal_add_referencia');
		}
		document.getElementById('frm_add_referencia').reset();
	});
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
//Funcion para abrir un modal
function open_modal(modal,value) {
	$('#'+modal).modal('show');
	if ( modal == 'modal_upload_file' ) {
		$('#queja_id').val(value);
	}
	return false;
}
//Funcion de accion para el formulario de adjuntar documento al expediente.
function frm_upload_file() {
	$('#frm_upload_file').submit(function(e) {
		e.preventDefault();
		//alerta('upload_file','success','Mi mensaje de alerta','modal_upload_file');
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
		})
		.fail(function(jqXHR,textStatus,errorThrown) {
			alerta('upload_file','error',jqXHR.responseText,'modal_upload_file');
		});
		
	});
}
//ajax mejorado para formularios
function smart_ajax(dataForm){
	var result ;
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: dataForm,
		async:false,
		cache:false,
	})
	.done(function(response) {
		result = response;
	})
	.fail(function(jqXHR,textStatus,errorThrown ) {
		result = {'status':'error','message':jqXHR.responseText};
	});
	return result;
}
//
function change_TR(){
	$('#t_ref').change(function(){
		if( $(this).val() == 0 ){
			$('#elements_tr').addClass('hidden');
		}else{
			$('#elements_tr').removeClass('hidden');
		}
	});
	return false;
}
//
function load_tipos_tramite(element,type){
	$('#'+element).html('');
	$.post('controller/puente.php', {option: '5'}, function(data, textStatus, xhr) {
		if ( type == 'select') {
			$('#'+element).append('<option value="" >...</option>');
			$.each(data, function(index, val) {
				$('#'+element).append('<option value="'+val.id+'">'+val.nombre+'</option>');
			});
		}
	},'json');
	//Generar la clave del expediente
	if ( element == 't_tra' ) {
		generate_code(element);
	}
	return false;
}
//Generador de clave del expediente
function generate_code(element) {
	$('#'+element).change(function(e) {
		e.preventDefault();
		if ( $(this).val() != '' ) {
			var t_tra = $('#t_tra').val();
			$.post('controller/puente.php', {option:'6',tt:t_tra }, function(data, textStatus, xhr) {
				$('#cve_exp').val(data);
			});
		}
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
// Setear un array de municipios 
function setInfo( json ) { rescate = json; }
//Autocompletado de informacion 
function autocomplete_input(input,hidden){
	$('#'+input).autocomplete({
		source: "controller/puente.php?option=3",
		select:function(event,ui){
			$('#'+hidden).val(ui.item.id);
		}
	});
	return false;
}
// Funciuon para eliminar turnos 
function delete_turno(turno){
	var resp = confirm('¿Realmente desea eliminar este turno?');
	if (resp) {
		$.ajax({
			url: 'controller/puente.php',
			type: 'POST',
			dataType: 'json',
			data: {option: '13',turno:turno},
			async:false,
			cache:false,
		})
		.done(function(response) {
			if (response.status == 'success') {
				$('li#'+turno).hide(2000);
			}else{
				alert(response.message);
			}
		})
		.fail(function() {
			console.log("error");
		});
	}
	return false;
}
// Funciuon para eliminar presuntas conductas de un expediente 
function delete_conducta(presunta){
	var resp = confirm('¿Realmente desea eliminar esta presuta conducta?');
	if (resp) {
		$.ajax({
			url: 'controller/puente.php',
			type: 'POST',
			dataType: 'json',
			data: {option: '14',presunta:presunta},
			async:false,
			cache:false,
		})
		.done(function(response) {
			if (response.status == 'success') {
				$('#presunta_'+presunta).hide(2000);
			}else{
				alert(response.message);
			}
		})
		.fail(function() {
			console.log("error");
		});
	}
	return false;
}
//Funcion que permite almacenar la unidad implicada
function frm_add_unidad() {
	$('#frm_add_unidad').submit(function(e) {
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
			alerta('am_unidad',response.status,response.message,'modal_add_unidad');
			setTimeout(function(){
				window.location.reload();
			},3000);
		})
		.fail(function(jqXHR,textStatus,errorThrown) {
			alerta('am_unidad','error',jqXHR.responseText,'modal_add_unidad');
		});
	});
	return false;
}
//Funcion para agregar un presunto responsable
function frm_add_presunto() {
	$('#frm_add_presunto').submit(function(e) {
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
			alerta('am_presunto',response.status,response.message,'modal_add_presunto');
			setTimeout(function(){
				window.location.reload();
			},3000);
		})
		.fail(function(jqXHR,textStatus,errorThrown) {
			alerta('am_presunto','error',jqXHR.responseText,'modal_add_presunto');
		});
		
	});
	return false;
}
//Funcion para agregar un quejoso
function frm_add_quejoso() {
	$('#frm_add_quejoso').submit(function(e) {
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
			alerta('am_quejoso',response.status,response.message,'modal_add_quejoso');
			setTimeout(function(){
				window.location.reload();
			},3000);
		})
		.fail(function(jqXHR,textStatus,errorThrown) {
			alerta('am_quejoso','error',jqXHR.responseText,'modal_add_quejoso');
		});
		
	});
	return false;
}
//funcion del cambio de procedencia 
function change_procedencia() {
	$('#procedencia').change(function(e) {
		e.preventDefault();
		var valor = $(this).val();
		if (valor == 1 ) {
			$('#estatal').removeClass('hidden');
			$('#cprs').addClass('hidden');
		}else if (valor == '' ) {
			$('#estatal').addClass('hidden');
			$('#cprs').addClass('hidden');
		}else{
			$('#cprs').removeClass('hidden');
			$('#estatal').addClass('hidden');
		}
	});
	return false;
}
//Funcion para eliminar los archivos 
function del_file(file) {
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '19',file:file},
		async:false,
		cache:false,
	})
	.done(function(response) {
		$('#file_'+file).hide(3000);
	})
	.fail(function(jqXHR,textStatus,errorThrown) {
		alerta('am_quejoso','error',jqXHR.responseText,'modal_add_quejoso');
	});	
	return false;
}
//Funcion para eliminar la via de recepcion 
function delete_via(via) {
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '21',via:via},
		async:false,
		cache:false,
	})
	.done(function(response) {
		$('#via_'+via).hide(3000);
	})
	.fail(function(jqXHR,textStatus,errorThrown) {
		alerta('div_alert','error',jqXHR.responseText,'');
	});	
	return false;
}
//Formulario para editar 
function frm_edit_queja() {
	$('#frm_edit_queja').submit(function(e) {
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
		.done(function(r) {
			alerta('div_alert',r.status,r.message,'');
			setTimeout(function(){
				window.location.reload();
			},3000);
		})
		.fail(function(jqXHR,textStatus,errorThrown) {
			alerta('am_quejoso','error',jqXHR.responseText,'modal_add_quejoso');
		});
	});
	return false;
}
//Eliminar presunto
function delete_presunto(presunto,queja){
	var resp = confirm('¿Realmente desea eliminar este presunto responsable?');
	if (resp) {
		$.ajax({
			url: 'controller/puente.php',
			type: 'POST',
			dataType: 'json',
			data: {option: '26',presunto:presunto,queja_id:queja},
			async:false,
			cache:false,
		})
		.done(function(response) {
			if (response.status == 'success') {
				$('#tr_pres_'+presunto).hide(2000);
			}else{
				alert(response.message);
			}
		})
		.fail(function() {
			console.log("error");
		});
	}
	return false;
}
//Eliminar unidad
function delete_unidad(unidad,queja){
	var resp = confirm('¿Realmente desea eliminar este unidad implicada?');
	if (resp) {
		$.ajax({
			url: 'controller/puente.php',
			type: 'POST',
			dataType: 'json',
			data: {option: '27',unidad:unidad,queja_id:queja},
			async:false,
			cache:false,
		})
		.done(function(response) {
			if (response.status == 'success') {
				$('#tr_uni_'+unidad).hide(2000);
			}else{
				alert(response.message);
			}
		})
		.fail(function() {
			console.log("error");
		});
	}
	return false;
}
//Eliminar quejoso
function delete_quejoso(quejoso,queja){
	var resp = confirm('¿Realmente desea eliminar este quejoso?');
	if (resp) {
		$.ajax({
			url: 'controller/puente.php',
			type: 'POST',
			dataType: 'json',
			data: {option: '28',quejoso:quejoso,queja_id:queja},
			async:false,
			cache:false,
		})
		.done(function(response) {
			if (response.status == 'success') {
				$('#tr_que_'+quejoso).hide(2000);
			}else{
				alert(response.message);
			}
		})
		.fail(function() {
			console.log("error");
		});
	}
	return false;
}
//Generador de reportes
function frm_reportes(){
	var tbl;
	$('#frm_reportes').submit(function(e) {
		e.preventDefault();
		var dataForm = $(this).serializeArray();
		
		$.ajax({
			url: 'controller/puente.php',
			type: 'POST',
			dataType: 'json',
			data: dataForm,
			async:false,
			cache: false,
		})
		.done(function(response) {
			if (response.status == 'error') {
				alerta('alert_reporte','error',response.message,'');
			}else{
				if ( ! $.fn.DataTable.isDataTable( '#reporte' ) ) {
					tbl = applyDataTables('reporte');
				}else{

					tbl.rows().remove().draw();
				}
				var counter = 1;
				$.each(response, function(i, val) {
					var conductas ;
					if (val.conductas.length == 0 ) {
						conductas = "NO SE ENCONTRARON CONDUCTAS.";
					}else{
						conductas = "";
						//Crear ciclo para las conductas
						$.each(val.conductas, function(i, con) {
							conductas += "<li>"+con.nombre+"</li>";
						});
					}
					tbl.row.add( [
			            val.id,
			            val.cve_ref,
			            val.cve_exp,
			            val.n_estado,
			            val.f_hechos +' / '+ val.h_hechos,
			           	'<ol>'+conductas+'</ol>',
			            val.municipio ,
			            val.procedencia
			        ] ).draw( false );
				});
			}			
		})
		.fail(function(jqXHR,textStatus,errorThrown) {
			alerta('am_quejoso','error',jqXHR.responseText,'');
		});
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
        }
	});
	return tabla ;
}
//Sin datos 
function cero()
{
	alert('No existen datos por consultar.');
	return false;
}
function addPresuntos() {
	var cantidad = $('#cantidad').val();
	if (cantidad <=30 && cantidad >= 1) {
		$('#mult_presuntos').removeClass('hidden');
		$('#formularios').html("");
		var conta = 1;
		for (var i = 0; i < cantidad; i++) {
			var formulario = "";
			
			formulario +=  '<div class="row">'+
			    '<div class="col-md-12">'+
			        '<h3>FORMULARIO PRESUNTO RESPONSABLE '+conta+'</h3>'+
			        '<div class="row">'+
			            '<div class="col-md-4">'+
			                '<div class="form-group">'+
			                    '<label for="n_ref">Nombre </label>'+
			                    '<input type="text" id="nombre" name="nombre[]" value="" required="" placeholder="Nombre(s)" maxlength="50" class="form-control">'+
			                '</div>'+
			            '</div>'+
			            '<div class="col-md-4">'+
			                '<div class="form-group">'+
			                    '<label for="ap_pat">Apellido paterno</label>'+
			                    '<input type="text" id="ap_pat" name="ap_pat[]" value="" required="" placeholder="Nombre de la nueva referencia" maxlength="50" class="form-control">'+
			                '</div>'+
			            '</div>'+
			            '<div class="col-md-4">'+
			                '<div class="form-group">'+
			                    '<label for="ap_mat">Apellido materno</label>'+
			                    '<input type="text" id="ap_mat" name="ap_mat[]" value="" required="" placeholder="Apellido materno" maxlength="50" class="form-control">'+
			                '</div>'+
			            '</div>'+
			        '</div>'+
			        '<div class="row">'+
			            '<div class="col-md-4">'+
			                '<div class="form-group">'+
			                    '<label for="ge">Seleccione el genero</label>'+
			                    '<select id="ge" name="ge[]" class="form-control">'+
			                        '<option value="">...</option>'+
			                        '<option value="1">Hombre</option>'+
			                        '<option value="2">Mujer</option>'+
			                    '</select>'+
			                '</div>'+
			            '</div>'+
			            '<div class="col-md-4">'+
			                '<div class="form-group">'+
			                    '<label for="cargo">Seleccione el cargo</label>'+
			                    '<select id="cargo" name="cargo[]" class="form-control">'+
			                        '<option value=""></option>'+
			                    '</select>'+
			                '</div>'+
			            '</div>'+
			            '<div class="col-md-4">'+
			                '<div class="form-group">'+
			                    '<label>Municipio</label>'+
			                    '<select id="mun" name="mun[]" class="form-control">'+
			                        '<option value="">...</option>'+
			                    '</select>'+
			                '</div>'+
			            '</div>'+
			        '</div>'+
			        '<div class="row">'+
			            '<div class="col-md-4">'+
			                '<div class="form-group">'+
			                    '<label>Procedencia</label>'+
			                    '<select id="procedencia" name="pro[]" class="form-control">'+
			                        '<option value="">...</option>'+
			                        '<option value="1">ESTATAL</option>'+
			                        '<option value="2">CPRS</option>'+
			                    '</select>'+
			                '</div>'+
			            '</div>'+
			        '</div>'+
			        '<div id="estatal" class="">'+
			            '<div class="row">'+
			                '<div class="col-md-3">'+
			                    '<div class="form-group">'+
			                        '<label>Adscripción</label>'+
			                        '<input type="text" name="adscripcion[]" class="form-control">'+
			                    '</div>'+
			                '</div>'+
			                '<div class="col-md-3">'+
			                    '<div class="form-group">'+
			                        '<label>Subdirección</label>'+
			                        '<select id="subdir" name="subdir[]" class="form-control">'+
			                            '<option value="">...</option>'+
			                        '</select>'+
			                    '</div>'+
			                '</div>'+
			                '<div class="col-md-3">'+
			                    '<div class="form-group">'+
			                        '<label>Region</label>'+
			                        '<select id="region" name="region[]" class="form-control">'+
			                            '<option value="">...</option>'+
			                        '</select>'+
			                    '</div>'+
			                '</div>'+
			                '<div class="col-md-3">'+
			                    '<div class="form-group">'+
			                        '<label>Agrupamiento</label>'+
			                        '<select id="agrupamiento" name="agrupamiento[]" class="form-control">'+
			                            '<option value="">...</option>'+
			                        '</select>'+
			                    '</div>'+
			                '</div>'+
			            '</div>'+
			        '</div>'+
			        '<div id="cprs" class="">'+
			            '<div class="row">'+
			                '<div class="col-md-4">'+
			                    '<div class="form-group">'+
			                        '<label>Agencia</label>'+
			                        '<input type="text" name="agencia[]" class="form-control">'+
			                    '</div>'+
			                '</div>'+
			                '<div class="col-md-4">'+
			                    '<div class="form-group">'+
			                        '<label>Fiscalia</label>'+
			                        '<input type="text" name="fiscalia[]" class="form-control">'+
			                    '</div>'+
			                '</div>'+
			                '<div class="col-md-4">'+
			                    '<div class="form-group">'+
			                        '<label>Mesa</label>'+
			                        '<input type="text" name="mesa[]" class="form-control">'+
			                    '</div>'+
			                '</div>'+
			            '</div>'+
			            '<div class="row">'+
			                '<div class="col-md-4">'+
			                    '<div class="form-group">'+
			                        '<label>Turno</label>'+
			                        '<input type="text" id="turno" name="turno[]" class="form-control" value="" >'+
			                    '</div>'+
			                '</div>'+
			            '</div>'+
			        '</div>'+
			        '<div class="row">'+
			            '<div class="col-md-12">'+
			                '<div class="form-group">'+
			                    '<label>Media filiación</label>'+
			                    '<textarea name="media[]" class="form-control" style="resize: vertical; max-height: 250px;"></textarea>'+
			                '</div>'+
			            '</div>'+
			        '</div>'+
			    '</div>'+
			'</div>';
			$('#formularios').append(formulario);
			conta++;
			load_municicios('mun[]');
		}
	}else{
		alert('NO');
	}
}
function load_municicios(name) {
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: 10},
		async:false,
		cache:false,
	})
	.done(function(response) {
		var options = "";
		options += '<option value="" >...</option>';
		$.each(response, function(index, val) {
			options += '<option value="'+val.id+'">'+val.nombre+'</option>';
		});
		$('[name="'+name+'"]').html(options);
	})
	.fail(function() {
		alert('Ocurrio un error al cargar el catalogo de opcion: '+option)
	});
	return false;
}