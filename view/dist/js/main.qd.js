var rescate;var url;
var estados = [{'valor': '','contenido':'TODOS'}];
var municipios = [{'valor': '','contenido':'TODOS'}];
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
		load_catalogo('','json',12);
		$.each(rescate, function(i, val) {
			estados.push({'valor': val.id,'contenido':val.nombre});
		});
		load_catalogo('','json',10);
		$.each(rescate, function(i, val) {
			municipios.push({'valor': val.id,'contenido':val.nombre});
		});
		getListadoQD();
		autocomplete_input('sp','sp_id');
		autocomplete_input('jefe_depto','jefe_depto_id');
		frm_asignar();
	}
	if ( url == '?menu=general' ) {
		var nivel = $('#nivel').val();
		$('#option_1').addClass('active');
		if ( nivel == 'ANALISTA' || nivel == 'JEFE' ) {
			$('#option_1_2').addClass('active');
			autocomplete_input('sp','sp_id');
			listado_qd();
			frm_asignar();
		}else{
			$('#option_1_1').addClass('active');
			frm_add_queja();
		}
		$('#d_ano').change(function(e) {
			if ( $('#d_ano').is(':checked')  ) {
				$('#d_quejoso').addClass('hidden');
			}else{
				$('#d_quejoso').removeClass('hidden');
			}
		});
		
		frm_add_opinion();
		frm_add_referencia();
		eventNormatividad();
		load_catalogo('t_ref','select',2);
		load_catalogo('procedencia','select',4);
		load_catalogo('t_tra','select',5);
		load_catalogo('t_ley','select','7A');

		load_catalogo('vias_r','select',9);
		load_catalogo('municipios','select',10);
		load_catalogo('estado','select',12);
		
		load_cargos();
		change_TR();
		autocomplete_input('sp','sp_id');
		frm_add_cargo();
		$('#pregunta').change(function(e) {
			e.preventDefault();			
			if( $(this).val() == '2' ){
				$('#cant_person').removeClass('hidden');
				$('#ind_presuntos').addClass('hidden');
			}else{
				$('#cant_person').addClass('hidden');
				$('#cantidad').val("");
				$('#ind_presuntos').addClass('hidden');
			}
		});
		//cuando eliga un tipo de asunto
		$('#t_asunto').change(function(e) {
			e.preventDefault();
			if ( $(this).val() == '1' ) {
				load_catalogo('procedencia','select',4);
			}
			if ( $(this).val() == '2' ) {
				load_catalogo('procedencia','select',4);
			}
		});
		//Preguntar si se desea agregar un semoviente 
		$('#add_semo').change(function(e) {
			e.preventDefault();
			if( $(this).val() == 1 ){
				$('#info_semo').removeClass('hidden');
			}else if ( $(this).val() == 2 ) {
				$('#info_semo').addClass('hidden');
				$('#t_animal').val('');
				$('#raza').val('');
				$('#edad').val('');
				$('#color').val('');
				$('#n_animal').val('');
				$('#inventario').val('');
			}else{
				alerta('div_alert','error','NO SE HA ENCONTRADO UNA ACCIÓN PARA LA OPCIÓN SELECCNADA.','');
			}
		});
		//cuando se pregunta si desea agregar una unidad
		$('#question_unidad').change(function(e) {
			e.preventDefault();
			if ($(this).val() === '1') {
				$('#data_unidad').removeClass('hidden');
			}else{
				$('#data_unidad').addClass('hidden');
			}
		});
		//Cambios del 07/01/2021
		//Cuando cambie el tipo de afectado cambie el formulario
		$('#procedencia_quejoso').change(function(e) {
			e.preventDefault();
			$('#area_1,#area_2,#area_3,#area_4').addClass('hidden');
			$('#area').html('');
			$('#area').append('<option value="">...</option>');
			if ($(this).val() == '1') {
				$('#area').append('<option value="3">Dirección General de Prevención y Reinserción Social</option>');
			}else if ($(this).val() == '2') {
				$('#area').append('<option value="1">Operativos Secretaría de Seguridad</option>');
				$('#area').append('<option value="2">Dirección de Policía de Tránsito</option>');
				$('#area').append('<option value="4">Personal Administrativo</option>');
			}
		});
		//Cuando seleccione procedencia quejoso
		eventOrganigrama();
	}
	if ( url == '?menu=reports' ) {
		$('#option_2').addClass('active');
		load_catalogo('municipio','select',10);
		//load_catalogo('t_ref','select',2);
		load_catalogo('estado','select',12);
		load_catalogo('procedencia','select',4);
		load_catalogo('t_tra','select',5);
		load_catalogo('t_ley','select','7A');
		load_catalogo('vias_r','select',9);
		frm_reportes();
	}
	if ( url == '?menu=aviso' ) {
		$('#option_3').addClass('active');
	}
	if ( url == '?menu=manual' ) {
		$('#option_4').addClass('active');
	}
	if ( url == '?menu=devoluciones' ) {
		$('#option_4').addClass('active');
		//getDevoluciones();
		tbl = applyDataTables('tbl_devoluciones');
	}
	if ( url == '?menu=m_queja' ) {
		load_catalogo('t_ley','select','7A');
		frm_edit_queja();
		frm_add_quejoso();
		eventNormatividad();
		eventOrganigrama();
		$('#procedencia').change(function(e) {
			e.preventDefault();
			$('#area_1,#area_2,#area_3,#area_4').addClass('hidden');
			$('#area').html('');
			$('#area').append('<option value="">...</option>');
			if ($(this).val() == '1') {
				$('#area').append('<option value="3">Dirección General de Prevención y Reinserción Social</option>');
			}else if ($(this).val() == '2') {
				$('#area').append('<option value="1">Operativos Secretaría de Seguridad</option>');
				$('#area').append('<option value="2">Dirección de Policía de Tránsito</option>');
				$('#area').append('<option value="4">Personal Administrativo</option>');
			}
		});
	}
	if ( url == '?menu=seguimiento' ) {
		load_catalogo('municipios','select',10);
		load_catalogo('cargo','select',22);
		load_catalogo('subdir','select',23);
		load_catalogo('region','select',25);
		load_catalogo('agrupamiento','select',24);
		load_catalogo('procedencia','select',4);
		load_catalogo('a_presunto','select',4);
		load_catalogo('penales','select',109);
		
		load_adsp();
		frm_add_unidad();
		change_procedencia();
		
		frm_add_presunto();
		frm_add_adsp();
	}
	if ( url == '?menu=expedientes' ) {
		tbl = applyDataTables('tbl_abogado');
	}
	if ( url == '?menu=enviar' ) {
		load_catalogo('t_ley','select','7A');
		frm_add_turno();
		autocomplete_input('oficio_envio','oficio_e_id');
		eventNormatividad();
		$('#question').change(function(e) {
			e.preventDefault();
			if ( $(this).val() !=3 ) {
				$('#div_conductas').removeClass('hidden');
			}else{
				$('#div_conductas').addClass('hidden');
			}
		});
	}
	if ( url == '?menu=turnado_multi' ) {
		load_catalogo('estado','select',12);
		load_catalogo('t_ley','select','7A');
		load_catalogo('dependencia_f','select',77);
		frm_add_turno_multi();
		autocomplete_input('sp','sp_id');
		autocomplete_input('sp_uai','sp_uai_id');
		autocomplete_input('persona','persona_id');
		autocomplete_input('expediente','expediente_id');
		autocomplete_input('oficio','oficio_id');
		
		$('#estado').change(function(e) {
			e.preventDefault();
			var e = $(this).val();
			//cuando es tramite
			if ( e == '1' || e == '9'  ) {
				$('#contenedor_0').removeClass('hidden');
				$('#contenedor_1,#contenedor_2,#contenedor_3,#contenedor_4,#contenedor_5').addClass('hidden');
			}
			//cuando es archivo o imporcedencia
			if ( e == '3' ) {
				$('#contenedor_1').removeClass('hidden');
				$('#contenedor_0,#contenedor_2,#contenedor_3,#contenedor_4,#contenedor_5').addClass('hidden');
			}
			//Cuando es respo
			if ( e == '7' ) {
				$('#contenedor_2').removeClass('hidden');
				$('#contenedor_0,#contenedor_1,#contenedor_3,#contenedor_4,#contenedor_5').addClass('hidden');
			}
			//Cuando es incompetencia
			if ( e == '4' ) {
				$('#contenedor_3').removeClass('hidden');
				$('#contenedor_0,#contenedor_2,#contenedor_1,#contenedor_4,#contenedor_5').addClass('hidden');
			}
			//Cuando es acumulado
			if ( e == '2' ) {
				$('#contenedor_4').removeClass('hidden');
				$('#contenedor_0,#contenedor_2,#contenedor_3,#contenedor_1,#contenedor_5').addClass('hidden');
			}
			//Cuando es reserva e improcedencia 
			if ( e == '10' || e == '11' ) {
				alerta('div_turno','error','NO CUENTAS CON EL PERFIL PARA REALIZAR TURNOS CON EL ESTADO SELECCIONADO.','');
				$('#btn_turnar').addClass('hidden');
			}else{
				$('#btn_turnar').removeClass('hidden');
			}
		});
	}
	if ( url == '?menu=tablero' ) {
		$('#option_3').addClass('active');	
		tablero_ctrl();
		tbl = applyDataTables('tablero_ctrl');
	}
	if ( url == '?menu=migracion' ) {
		$('#option_7').addClass('active');
		$('#modo').change(function(e) {
			e.preventDefault();
			if ( $(this).val() == 1  ) {
				$('#div_2').addClass('hidden');
				$('#div_1').removeClass('hidden');
				$('#div_2_1').addClass('hidden');
			}
			if ( $(this).val() == 2  ) {
				$('#div_1').addClass('hidden');
				$('#buscador_tbl').addClass('hidden');
				$('#div_2').removeClass('hidden');
				$('#div_2_1').addClass('hidden');
				
				$('#btn_migrate').addClass('hidden');
			}
		});
		$('#c_buscar').change(function(e) {
			e.preventDefault();
			if ( $(this).val() == 1 ) {
				$('#div_2_1').removeClass('hidden');
			}
		});
				
		autocomplete_input('sp','sp_id',3);
		autocomplete_input('destino','destino_id',3);
		
		$("#buscador").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#tbl_migrate tbody tr").filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		});
		frm_migracion('');

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
function getListadoQD() {
	var datos = {
	    class: 'table-striped table-bordered table-hover',
	    columnas: [
	    	{ leyenda: 'Acciones', class:'text-center', style: 'width:10px;',ordenable:true,columna:'id'},
	        { leyenda: 'ID', class:'text-center', style: 'width:10px;',ordenable:true,columna:'id'},
	        { leyenda: 'No. expediente',class:'text-center', columna:'q.cve_exp',ordenable:true,filtro:true},
	        { leyenda: 'Equipo de trabajo',class:'text-center', columna:'',ordenable:false,filtro:true,style: 'width:400px;'},
	        { leyenda: 'Estado',class:'text-center',  columna:'e.id',ordenable:false,filtro:function(){
	        	return anexGrid_select({
    	            data: estados
    	        });
	        }},
	        { leyenda: 'Procedencia',class:'text-center', style: 'width:100px;', columna:'',ordenable:false},
	        { leyenda: 'Fecha/Hora de hechos',class:'text-center', style: 'width:100px;', columna:'',ordenable:false},
	        { leyenda: 'Municipio',class:'text-center', style: 'width:100px;', columna:'m.id',ordenable:false,filtro:function(){
	        	return anexGrid_select({
    	            data: municipios
    	        });
	        }},
	        { leyenda: 'Probable conducta', class: 'text-center', columna:'',ordenable:false },
	        { leyenda: 'Fecha_apertura', class: 'text-center', columna:'',ordenable:false },
	        { leyenda: 'Contadores', class: 'text-center', columna:'',ordenable:false },
	        { leyenda: '', class: 'text-center', columna:'',ordenable:false },
	    ],
	    modelo: [
	    	{ class:'',formato: function(tr, obj, valor){
	    		var acciones = [];
	    		var nivel = $('#nivel').val();
	    		acciones = [
		    				{ href: "javascript:open_modal('modal_add_opinion',"+obj.id+");", contenido: '<i class="glyphicon glyphicon-cloud"></i> Agregar opinión' },
	                        { href: "javascript:open_modal('modal_upload_file',"+obj.id+");", contenido: '<i class="glyphicon glyphicon-cloud"></i> Adjuntar documento al expediente' },
	                        { href: "index.php?menu=cedula&queja="+obj.id, contenido: '<i class="glyphicon glyphicon-eye-open "></i>Ver cédula' },
	                        { href: 'index.php?menu=m_queja&queja='+obj.id, contenido: '<i class="fa fa-pencil"></i> Modificar' },
	                        //{ href: 'index.php?menu=turnar&queja='+obj.id, contenido: '<i class="fa fa-pencil"></i> Enviar' },
	                    ];
	                
	            if ( obj.jefe == 'NO ASIGNADO' ) {
	            	acciones.push({ href: "javascript:open_modal('modal_asignar',"+obj.id+");", contenido: '<i class="fa fa-user"></i> Asignar equipo de trabajo' });
	            }
	            if ( obj.edo_id != '7' ) {
	            	acciones.push({ href: "index.php?menu=enviar&queja="+obj.id, contenido: '<i class="fa fa-user"></i> Turnar a D. R. en A. I.' });
	            }
	            if(nivel == 'SECRETARIA'){
	    			acciones = [
	    				{ href: "index.php?menu=cedula&queja="+obj.id, contenido: '<i class="glyphicon glyphicon-eye-open "></i>Ver cédula' },
                    ];
	    		}
	            return anexGrid_dropdown({
                    contenido: '<i class="glyphicon glyphicon-cog"></i>',
                    class: 'btn btn-primary opciones',
                    target: '__blank',
                    id: 'editar-' + obj.id,
                    data: acciones
                });
	        }}, 
	        {class:'text-center', formato: function(tr, obj, valor){
	        	if ( obj.fase >= 0 && obj.fase <=70) { tr.addClass('bg-green'); }
	        	if ( obj.fase >= 71 && obj.fase <=88) { tr.addClass('bg-yellow'); }
	        	if ( obj.fase >= 89 && obj.fase <=90) { tr.addClass('bg-red'); }
	        	if ( obj.fase >= 91 ) { tr.addClass('bg-purple'); }
	            return obj.id;
	        }}, 
	        {class:'text-center', formato: function(tr, obj, valor){
	        	var fila = "";
	        	if (obj.multiple_id != '0' && obj.multiple_id != null) {
	        		fila = "<ol>"+
	        			"<li>"+obj.cve_exp+"</li>"+
	        			"<li> F-"+obj.multiple_id+"</li>"+
	        		"</ol>";
	        	}else{
	        		fila = obj.cve_exp;
	        	}
	        	
	            return fila;
	        }},
	        {class:'text-left', formato: function(tr, obj, valor){
	        	var personal = "", subd = "", jefe = "", abogado = "";
	        	if(obj.jefe != 'NO ASIGNADO' ){ jefe = obj.jefe; }else{ jefe = 'NO ASIGNADO' }
	        	if(obj.full_name != 'NO ASIGNADO' ){ abogado = obj.full_name; }else{ abogado = 'NO ASIGNADO' }
	        	personal = '<b>JEFE DE DEPTO.:</b> '+jefe+'<br>'+'<b>ABOGADO:</b> '+abogado;
	        	return personal;
	        }},
	        {class:'text-center', formato: function(tr, obj, valor){
	        	return obj.n_estado;
	        }}, 
	        {class:'text-center', formato: function(tr, obj, valor){
	        	return obj.procedencia;
	        }}, 
	        {class:'', formato: function(tr, obj, valor){
	            return obj.f_hechos+
	            	'\n'+
	            	obj.h_hechos;
	        }}, 
	        {class:'text-center', formato: function(tr, obj, valor){
	            return obj.municipio;
	        }}, 
	        {class:'text-center', formato: function(tr, obj, valor){
	        	return 'Conducta resumida';
	        }},
	        {class:'text-center', formato: function(tr, obj, valor){
	        	return obj.f_alta;
	        }},
	        {class:'text-center', formato: function(tr, obj, valor){
	        	var contadores = "";
	        	contadores += "<b>Desde alta:</b> "+obj.fase+" días <br>";
	        	contadores += "<b>Desde los hechos:</b> "+obj.resta+" días ";
	        	return contadores;
	        }},
	        {class:'text-center', formato: function(tr, obj, valor){
	        	var icono = "";
	        	var resta = parseInt(obj.resta);
	        	if( resta >= 1 && resta < 457 ){ icono = '<i class="fa fa-smile-o" style="font-size: 25px;"></i>'; }
	        	if( resta >= 457 && resta < 640 ){ icono = '<i class="fa  fa-exclamation-triangle" style="font-size: 25px;"></i>'; }
	        	if( resta >= 640 && resta < 823 ){ icono = '<i class="fa  fa-ban" style="font-size: 25px;"></i>'; }
	        	if( resta >= 823 && resta < 1095 ){ icono = '<i class="fa fa-thumbs-o-down" style="font-size: 25px;"></i>'; }
	        	//Agregar el icono del estatus
	        	if (obj.n_estado == 'ARCHIVO') {
	        		return icono+' <i class="fa fa-archive" style="font-size: 25px;"></i>';
	        	}else if (obj.n_estado == 'RESPONSABILIDADES') {
	        		return icono+' <i class="fa fa-balance-scale" style="font-size: 25px;"></i>';
	        	}else if (obj.n_estado == 'PRESCRITO') {
	        		return icono+' <i class="fa fa-file-o" style="font-size: 25px;"></i>';
	        	}else{
	        		return icono +"  ";
	        	}
	        	return icono;
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
	        { leyenda: 'ID', class:'text-center', style: 'width:10px;',ordenable:true,columna:'id'},
	        //{ leyenda: 'Número_Folio',class:'text-center',style: 'width:200px;',  columna:'q.cve_ref',ordenable:false,filtro:true},
	        { leyenda: 'No. expediente',class:'text-center', columna:'q.cve_exp',ordenable:true,filtro:true},
	        { leyenda: 'Adscripción',class:'text-center', columna:'a.nombre',ordenable:false,filtro:true},
	        { leyenda: 'Abogado_asignado',class:'text-center', columna:'pe.nombre',ordenable:false,filtro:true,style: 'width:400px;'},
	        { leyenda: 'Jefe_de_Depto.',class:'text-center', columna:'pe.nombre',ordenable:false,filtro:true,style: 'width:400px;'},
	        { leyenda: 'Estado',class:'text-center',  columna:'e.id',ordenable:false,filtro:function(){
	        	return anexGrid_select({
    	            data: estados
    	        });
	        }},
	        { leyenda: 'Fecha/Hora de hechos',class:'text-center', style: 'width:100px;', columna:'',ordenable:false},
	        { leyenda: 'Presunta(s)_Infracción(es)',class:'text-center', columna:'',ordenable:false, style: 'width:400px;' },
	        { leyenda: 'Municipio',class:'text-center', style: 'width:100px;', columna:'m.id',ordenable:false,filtro:function(){
	        	return anexGrid_select({
	        		//class:'select2',
    	            data: aux
    	        });
	        }},
	        //{ leyenda: 'Procedencia',class:'text-center', style: 'width:100px;', columna:'',ordenable:false},
	        { leyenda: 'Fase', class: 'text-center', style: 'width:10px;', columna:'',ordenable:false },
	        { leyenda: 'Condición', class: 'text-center', columna:'',ordenable:false },
	        { leyenda: 'Fecha_apertura', class: 'text-center', columna:'',ordenable:false },
	        { leyenda: 'Información_extraordinaria', class: 'text-center', columna:'',ordenable:false },
	    ],
	    modelo: [
	    	{ class:'',formato: function(tr, obj, valor){
	    		var acciones = [];
	    		var nivel = $('#nivel').val();
	    		if(nivel == 'DIRECTOR'){
                    if ( obj.n_estado == 'RESPONSABILIDADES' ) {
		    			acciones = [
		    				{ href: "javascript:open_modal('modal_add_opinion',"+obj.id+");", contenido: '<i class="glyphicon glyphicon-cloud"></i> Agregar opinión' },
	                        { href: "index.php?menu=seguimiento&queja="+obj.id, contenido: '<i class="fa fa-folder"></i> Datos de presuntos y quejosos' },
	                        { href: "javascript:open_modal('modal_upload_file',"+obj.id+");", contenido: '<i class="glyphicon glyphicon-cloud"></i> Adjuntar documento al expediente' },
	                        { href: "index.php?menu=cedula&queja="+obj.id, contenido: '<i class="glyphicon glyphicon-eye-open "></i>Ver cédula' },
	                        //{ href: 'index.php?menu=m_queja&queja='+obj.id, contenido: '<i class="fa fa-pencil"></i> Modificar' },
	                        //{ href: 'index.php?menu=turnar&queja='+obj.id, contenido: '<i class="fa fa-pencil"></i> Enviar' },
	                        { href: "javascript:open_modal('modal_asignar',"+obj.id+");", contenido: '<i class="fa fa-user"></i> Asignar a un abogado' },
	                    ];
                    }else{
		    			acciones = [
		    				{ href: "javascript:open_modal('modal_add_opinion',"+obj.id+");", contenido: '<i class="glyphicon glyphicon-cloud"></i> Agregar opinión' },
	                        { href: "index.php?menu=seguimiento&queja="+obj.id, contenido: '<i class="fa fa-folder"></i> Datos de presuntos y quejosos' },
	                        { href: "javascript:open_modal('modal_upload_file',"+obj.id+");", contenido: '<i class="glyphicon glyphicon-cloud"></i> Adjuntar documento al expediente' },
	                        { href: "index.php?menu=cedula&queja="+obj.id, contenido: '<i class="glyphicon glyphicon-eye-open "></i>Ver cédula' },
	                        //{ href: 'index.php?menu=m_queja&queja='+obj.id, contenido: '<i class="fa fa-pencil"></i> Modificar' },
	                        { href: 'index.php?menu=turnar&queja='+obj.id, contenido: '<i class="fa fa-pencil"></i> Enviar' },
	                        { href: "javascript:open_modal('modal_asignar',"+obj.id+");", contenido: '<i class="fa fa-user"></i> Asignar a un abogado' },
	                    ];
                    }
	    		}else if(nivel == 'SUBDIRECTOR'){
	    			if ( obj.n_estado == 'RESPONSABILIDADES' ) {
	    				acciones = [
		    				{ href: "index.php?menu=cedula&queja="+obj.id, contenido: '<i class="glyphicon glyphicon-eye-open "></i>Ver cédula' },
		    				{ href: "javascript:open_modal('modal_add_opinion',"+obj.id+");", contenido: '<i class="glyphicon glyphicon-cloud"></i> Agregar comentario' },
		    				{ href: "javascript:open_modal('modal_upload_file',"+obj.id+");", contenido: '<i class="glyphicon glyphicon-cloud"></i> Adjuntar documento al expediente' },
		    				{ href: "index.php?menu=seguimiento&queja="+obj.id, contenido: '<i class="fa fa-folder"></i> Datos de presuntos y quejosos' },
		    				{ href: "javascript:open_modal('modal_asignar',"+obj.id+");", contenido: '<i class="fa fa-user"></i> Asignar a un abogado' },
                    	];
	    			}else{
	    				acciones = [
		    				{ href: "index.php?menu=cedula&queja="+obj.id, contenido: '<i class="glyphicon glyphicon-eye-open "></i>Ver cédula' },
		    				{ href: "javascript:open_modal('modal_add_opinion',"+obj.id+");", contenido: '<i class="glyphicon glyphicon-cloud"></i> Agregar comentario' },
		    				{ href: "javascript:open_modal('modal_upload_file',"+obj.id+");", contenido: '<i class="glyphicon glyphicon-cloud"></i> Adjuntar documento al expediente' },
		    				{ href: "index.php?menu=seguimiento&queja="+obj.id, contenido: '<i class="fa fa-folder"></i> Datos de presuntos y quejosos' },
		    				{ href: 'index.php?menu=turnar&queja='+obj.id, contenido: '<i class="fa fa-pencil"></i> Enviar' },
		    				{ href: "javascript:open_modal('modal_asignar',"+obj.id+");", contenido: '<i class="fa fa-user"></i> Asignar a un abogado' },
	                    ];
	    			}
	    		}else if(nivel == 'SECRETARIA'){
	    			acciones = [
	    				{ href: "index.php?menu=cedula&queja="+obj.id, contenido: '<i class="glyphicon glyphicon-eye-open "></i>Ver cédula' },
                    ];
	    		}else if(nivel == 'JEFE' || nivel == 'ANALISTA'){
	    			acciones = [
	    				{ href: "javascript:open_modal('modal_add_opinion',"+obj.id+");", contenido: '<i class="glyphicon glyphicon-cloud"></i> Agregar comentario' },
	    				{ href: "index.php?menu=seguimiento&queja="+obj.id, contenido: '<i class="fa fa-folder"></i> Datos de presuntos y quejosos' },
                    ];
	    		}
	            return anexGrid_dropdown({
                    contenido: '<i class="glyphicon glyphicon-cog"></i>',
                    class: 'btn btn-primary opciones',
                    target: '__blank',
                    id: 'editar-' + obj.id,
                    data: acciones
                });
	        }}, 
	        {class:'text-center', formato: function(tr, obj, valor){
	            return obj.id;
	        }}, 
	        /*{class:'text-center', formato: function(tr, obj, valor){
	            return obj.cve_ref;
	        }},*/ 
	        {class:'text-center', formato: function(tr, obj, valor){
	        	var fila = "";
	        	if (obj.multiple_id != '0' && obj.multiple_id != null) {
	        		fila = "<ol>"+
	        			"<li>"+obj.cve_exp+"</li>"+
	        			"<li> F-"+obj.multiple_id+"</li>"+
	        		"</ol>";
	        	}else{
	        		fila = obj.cve_exp;
	        	}
	        	
	            return fila;
	        }},
	        {class:'text-center', formato: function(tr, obj, valor){
	        	return obj.n_area;
	        }}, 
	        {class:'text-center', formato: function(tr, obj, valor){
	        	return obj.full_name;
	        }}, 
	        {class:'text-center', formato: function(tr, obj, valor){
	        	if ( obj.jefe_name !== '') {
	        		return 'NO ASIGNADO';
	        	}else{
	        		return obj.jefe_name;
	        	}
	        }}, 
	        
	        {class:'text-center', formato: function(tr, obj, valor){
	        	if (obj.n_estado == 'ARCHIVO') {
	        		return obj.n_estado+"<br>"+'<i class="fa fa-archive" style="font-size: 25px;"></i>';
	        	}else if (obj.n_estado == 'RESPONSABILIDADES') {
	        		return obj.n_estado+"<br>"+'<i class="fa fa-balance-scale" style="font-size: 25px;"></i>';
	        	}else if (obj.n_estado == 'PRESCRITO') {
	        		return obj.n_estado+"<br>"+'<i class="fa fa-file-o" style="font-size: 25px;"></i>';
	        	}else{
	        		return obj.n_estado;
	        	}
	            
	        }}, 
	        {class:'', formato: function(tr, obj, valor){
	            return obj.f_hechos+
	            	'\n'+
	            	obj.h_hechos;
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
	        /*{class:'text-center', formato: function(tr, obj, valor){
	            return obj.procedencia;
	        }},*/
	        {class:'text-center', formato: function(tr, obj, valor){
	        	var fase = parseInt(obj.fase);

	        	var icono = '';
	        	var resta = parseInt(obj.resta);
	        	if( resta >= 1 && resta < 457 ){ icono = '<i class="fa fa-smile-o" style="font-size: 25px;"></i>'; }
	        	if( resta >= 457 && resta < 640 ){ icono = '<i class="fa  fa-exclamation-triangle" style="font-size: 25px;"></i>'; }
	        	if( resta >= 640 && resta < 823 ){ icono = '<i class="fa  fa-ban" style="font-size: 25px;"></i>'; }
	        	if( resta >= 823 && resta < 1095 ){ icono = '<i class="fa fa-thumbs-o-down" style="font-size: 25px;"></i>'; }

	        	//icono += obj.t_tramite+' Fecha de turnado: '+obj.f_turno+' ('+obj.f_reasignado+')';
	            if (fase >= 0 && fase < 70) {
					tr.addClass('bg-green');
	            	return 'DÍAS('+fase+')'+icono;
	            }
	            if (fase > 70 && fase < 88) {
	            	tr.addClass('bg-yellow');
	            	return 'DÍAS('+fase+')'+icono;
	            }
	            if (fase > 88 && fase <= 90) {
	            	tr.addClass('bg-red-active');
	            	return 'DÍAS('+fase+')'+icono;
	            }
	            if (fase > 90) {
	            	tr.addClass('bg-purple');
	            	return 'DÍAS('+fase+')'+icono;
	            }

	        }}, 
	        {class:'text-center', formato: function(tr, obj, valor){
	        	return obj.visto;
	        }},
	        {class:'text-center', formato: function(tr, obj, valor){
	        	return obj.f_alta;
	        }},
	        {class:'text-center', formato: function(tr, obj, valor){
	        	var lista = "";
	        	lista += "<ol align='left'>";
	        		//lista += "<li>Tipo de trámite: <br>"+obj.t_tramite+"</li>";
	        		lista += "<li>Fecha de turnado: <br>"+obj.f_turno+"</li>";
	        		lista += "<li>¿Reasignación?: <br>"+obj.f_reasignado+"</li>";
	        	lista += "</ol>";

	        	return lista;
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
		//location.reload();
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
	var clase, icono, msj,edo;
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
	if ( estado == 'success' ) {
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
	if ((div == 'div_turno' && estado == 'success') || div == 'div_alert') {
		setInterval(function(){
			location.href = 'index.php?menu=list_queja';
		},time);
	}
	return false;
}
//Funcion para abrir un modal
function open_modal(modal,value) {
	$('#'+modal).modal('show');
	//alert('Modal: '+modal+' valor: '+value);
	if ( modal == 'modal_upload_file' ) { $('#queja_id').val(value); }
	if ( modal == 'modal_add_opinion' ) { $('[name="queja_id"]').val(value); }
	if ( modal == 'modal_asignar' ){$('[name="queja_id"]').val(value);}
	if ( modal == 'modal_aviso' ){$('[id="conducta_id"]').val(value);}
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
	var data = $('#capitulos').val();
	//var data = $('#t_ley').val();
	var art = $('#art').val();
	var sec = $('#secciones').val();
	var fracciones = $('#fracciones').val();
	/*if( element == 'conductas' || element == 'art' || element == 'secciones' || element == 'fracciones' ){
		var data = $('#capitulos').val();
		//var data = $('#t_ley').val();
		var art = $('#art').val();
		var sec = $('#secciones').val();
		var fracciones = $('#fracciones').val();
	}
	else */if(element == 'procedencia'){
		var data = $('#t_asunto').val();
	}else if (element == 'capitulos'){
		var data = $('#t_ley').val();
	}/*else{
		var data = '0';
		var art = $('#art').val();
		var sec = $('#secciones').val();
		var fracciones = $('#fracciones').val();
	}*/
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
			if(element == 'art'){$('#art').select2();}
			$('#'+element).append('<option value="" >...</option>');
			$.each(response, function(index, val) {
				if(val.id !== null){
					$('#'+element).append('<option value="'+val.id+'">'+val.nombre+'</option>');
				}
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
	//Generar la clave del expediente
	if ( element == 't_tra' ) {
		generate_code(element);
	}		
}
// Setear un array de municipios 
function setInfo( json ) { rescate = json; }
//Autocompletado de informacion 
function autocomplete_input(input,hidden){
	var option ;
	if (input == 'expediente') {
		option = 12 
	}else if ( input == 'oficio' || input == 'oficio_envio' ){
		option = 10;
	}else{
		option = 3;
	}
	$('#'+input).autocomplete({
		source: "controller/puente.php?option="+option,
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
		if (valor == 2) {
			$('#estatal').removeClass('hidden');
			$('#cprs').addClass('hidden');
		}else if (valor == 1 || valor == 3) {
			$('#estatal').addClass('hidden');
			$('#cprs').removeClass('hidden');
		}else{
			$('#cprs').addClass('hidden');
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
		console.log(dataForm);
		$.ajax({
			url: 'controller/puente.php',
			type: 'POST',
			dataType: 'json',
			data: dataForm,
			async:false,
			cache:false,
		})
		.done(function(r) {
			alerta('edit_alert',r.status,r.message,'');
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
			console.log(response);
			if (response.status == 'error') {
				alerta('alert_reporte','error',response.message,'');
				tbl.rows().remove().draw();
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
			            val.procedencia,
			            val.d_hechos
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
			
			formulario +=  '<div id="frm_presunto_'+conta+'" class="row">'+
			    '<div class="col-md-12">'+
			        '<h3>FORMULARIO PRESUNTO RESPONSABLE '+conta+' <button class="btn btn-danger btn-flat" type="button" onclick="remove_frm('+"'frm_presunto_"+conta+"'"+')"> <i class="fa fa-trash"></i> </button> </h3>'+
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
			       		'<div class="col-md-3">'+
			       			'<div class="form-group">'+
			       				'<label>RFC</label>'+
			       				'<input type="text" id="rfc" name="rfc[]" class="form-control" value="">'+
			       			'</div>'+
			       		'</div>'+
			       		'<div class="col-md-3">'+
			       			'<div class="form-group">'+
			       				'<label>CURP</label>'+
			       				'<input type="text" id="curp" name="curp[]" class="form-control" value="">'+
			       			'</div>'+
			       		'</div>'+
			       		'<div class="col-md-3">'+
			       			'<div class="form-group">'+
			       				'<label>CUIP</label>'+
			       				'<input type="text" id="cuip" name="cuip[]" class="form-control" value="">'+
			       			'</div>'+
			       		'</div>'+
			       		'<div class="col-md-3">'+
			       			'<div class="form-group">'+
			       				'<label>Tipo de puesto</label>'+
			       				'<select id="t_puesto" name="t_puesto[]" class="form-control">'+
			       					'<option value="">...</option>'+
			       					'<option value="1">Administrativo</option>'+
			       					'<option value="2">Operativo</option>'+
			       				'</select>'+
			       			'</div>'+
			       		'</div>'+
			       	'</div>'+
 			        '<div class="row">'+
        	            '<div class="col-md-4">'+
        	            	'<div class="form-group">'+
        	            		'<label>Selecciona un cargo</label>'+
        		            	'<div class="input-group">'+
        		            		'<select id="cargo" name="cargo[]" class="form-control cargos">'+
        		            			'<option value="">...</option>'+
        		            		'</select>'+
        		            		'<span class="input-group-btn">'+
        		            			'<button onclick="open_modal('+"'modal_add_cargo'"+",''"+');" type="button" class="btn btn-info btn-flat" >'+
        		            				'<i class="fa fa-plus"></i>'+
        		            			'</button>'+
        		            		'</span>'+
        		            	'</div>'+
        	            	'</div>'+
        	            '</div>'+
			            '<div class="col-md-4">'+
			                '<div class="form-group">'+
			                    '<label for="ge">Seleccione el género</label>'+
			                    '<select id="ge" name="ge[]" class="form-control">'+
			                        '<option value="">...</option>'+
			                        '<option value="1">Hombre</option>'+
			                        '<option value="2">Mujer</option>'+
			                    '</select>'+
			                '</div>'+
			            '</div>'+
			            '<div class="col-md-4">'+
			                '<div class="form-group">'+
			                    '<label>Procedencia</label>'+
			                    '<select id="procedencia" name="pro[]" class="form-control">'+
			                        '<option value="">...</option>'+
			                        '<option value="1">CPRS</option>'+
			                        '<option value="2">Secretaría de Seguridad</option>'+
			                        '<option value="3">Administrativo</option>'+
			                    '</select>'+
			                '</div>'+
			            '</div>'+
			            			            
			        '</div>'+
			        
			        '<div id="cprs" class="">'+
			            
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
	load_cargos();
}
function remove_frm(frm) {
	$('#'+frm).remove();
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
//cargos para  formularios de multiple de adminision
function load_cargos() {
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: 22},
		async:false,
		cache:false,
	})
	.done(function(response) {
		var options = "";
		options += '<option value="" >...</option>';
		$.each(response, function(index, val) {
			options += '<option value="'+val.id+'">'+val.nombre+'</option>';
		});
		$('.cargos').html(options);
	})
	.fail(function() {
		alert('Ocurrio un error al cargar el catalogo de opcion: '+option)
	});
	return false;
}
function load_adsp() {
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: 102},
		async:false,
		cache:false,
	})
	.done(function(response) {
		var options = "";
		options += '<option value="" >...</option>';
		$.each(response, function(index, val) {
			options += '<option value="'+val.id+'">'+val.nombre+'</option>';
		});
		$('#a_presunto').html(options);
	})
	.fail(function() {
		alert('Ocurrio un error al cargar el catalogo de opcion: '+option)
	});
	return false;
}
// 
function frm_add_turno() {
	$('#frm_add_turno').submit(function(e) {
		e.preventDefault();
		var dataForm;
		dataForm = new FormData(document.getElementById("frm_add_turno"));
		console.log(dataForm);
		p_ajax = {
			url: 'controller/puente.php',
			type: 'POST',
			dataType: 'json',
			data: dataForm,
			async:false,
			cache:false,
			processData: false,
            contentType: false,
		};		
		$.ajax(p_ajax)
		.done(function(response) {
			alerta('div_turno',response.status, response.message,'');
		})
		.fail(function(jqXHR,textStatus,errorThrown) {
			alerta('div_turno','error',jqXHR.responseText,'');
		});	
	});//fin de submit
	return false;
}

/*//tablero de control
function tablero_ctrl() {
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '80'},
		async:false,
		cache:false,
	})
	.done(function(response) {
		$.each(response.edos, function(i, val) {
			var fila = "";
			fila += '<tr>';
				fila += '<td>'+val.n_estado+'</td>';
				fila += '<td>'+val.cuenta+'</td>';
				fila += '<td>';
					fila += '<button type="button" class="btn btn-success btn-flat" onclick="verExpedientes('+"'estado'"+','+val.estado+');"> <i class="fa fa-eye" ></i> </button>';
				fila += '</td>';
			fila += '</tr>';
			$('#tbl_edos').append(fila);
		});
		$.each(response.abogados, function(i, val) {
			var fila = "";
			fila += '<tr>';
				fila += '<td>'+val.full_name+'</td>';
				fila += '<td>'+val.cuenta+'</td>';
				fila += '<td>';
					fila += '<button type="button" class="btn btn-success btn-flat" onclick="verExpedientes('+"'abogado'"+','+val.id+');"> <i class="fa fa-eye" ></i> </button>';
				fila += '</td>';
			fila += '</tr>';
			$('#tbl_abogados').append(fila);
		});
	})
	.fail(function(jqXHR,textStatus,errorThrow) {
		console.log("Error: "+jqXHR.responseText);
	});
	applyDataTables('tbl_abogados');
	return false;
}*/
//tablero de control
function tablero_ctrl() {
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '80'},
		async:false,
		cache:false,
	})
	.done(function(response) {
		var suma = 0,fila = "";
		$.each(response.edos, function(i, val) {
			var fila = "";
			suma += parseInt(val.cuenta);
			fila += '<tr>';
				fila += '<td>'+val.n_estado+'</td>';
				fila += '<td>'+val.cuenta+'</td>';
				fila += '<td>';
					fila += '<button type="button" class="btn btn-success btn-flat" onclick="verExpedientes('+"'estado'"+','+val.estado+');"> <i class="fa fa-eye" ></i> </button>';
				fila += '</td>';
			fila += '</tr>';
			$('#tbl_edos').append(fila);
		});
		fila += '<tr>';
			fila += '<td class="text-right">TOTAL GENERAL</td>';
			fila += '<td colspan="2">'+suma+'</td>';
		fila += '</tr>';
		$('#tbl_edos').append(fila);
		var suma = 0,fila = "";
		$.each(response.abogados, function(i, val) {
			var fila = "";
			suma += parseInt(val.cuenta);
			fila += '<tr>';
				if (val.full_name != null) {
					fila += '<td>'+val.full_name+'</td>';
				}else{
					fila += '<td>SIN ASIGNAR</td>';
				}
				if (val.n_area != null) {
					fila += '<td>'+val.n_area+'</td>';
				}else{
					fila += '<td>SIN ASIGNAR</td>';
				}
				
				
				fila += '<td>'+val.cuenta+'</td>';
				if (val.full_name != null) {
					fila += '<td>';
						fila += '<button type="button" class="btn btn-success btn-flat" onclick="verExpedientes('+"'abogado'"+','+val.id+');"> <i class="fa fa-eye" ></i> </button>';
					fila += '</td>';
				}else{
					fila += '<td>SIN ASIGNACIONES</td>';
				}
				
			fila += '</tr>';
			$('#tbl_abogados').append(fila);
		});
		fila += '<tr>';
			fila += '<td></td>';
			fila += '<td class="text-right">TOTAL GENERAL</td>';
			fila += '<td>'+suma+'</td>';
			fila += '<td></td>';
		fila += '</tr>';
		$('#tbl_abogados').append(fila);	
		//Agregar los contadores de OINs
		var fila = "",suma = 0;
		console.log(response.c_oins);
		$.each(response.c_oins, function(i, val) {
			suma += parseInt(val.cuenta);
			var tipo = "";
			if (val.t_orden == "INS") {tipo="INSPECCIONES";}
			if (val.t_orden == "VER") {tipo="VERIFICACIONES";}
			if (val.t_orden == "SUP") {tipo="SUPERVICIONES";}
			if (val.t_orden == "INV") {tipo="INVESTIGACIONES";}
			if (val.t_orden == "AGE") {tipo="AGENTE ENCUBIERTO";}
			if (val.t_orden == "USI") {tipo="USUARIO SIMULADO";}
			fila += '<tr>';
				fila += '<td>'+tipo+'</td>';
				fila += '<td>'+val.cuenta+'</td>';
				fila += '<td>';
					fila += '<button type="button" class="btn btn-success btn-flat" onclick="load_detalle_oin(2);"> <i class="fa fa-eye" ></i> </button>';
				fila += '</td>';
			fila += '</tr>';

		});
		fila += '<tr>';
			fila += '<td class="text-right">TOTAL</td>';
			fila += '<td colspan="2">'+suma+'</td>';
		fila += '</tr>';
		$('#tbl_ordenes').append(fila);	
		//Agregar los contadores de las actas
		var suma = 0,fila = "";
		$.each(response.actas, function(i, val) {
			var fila = "", ta = "";
			suma += parseInt(val.cuenta);
			if (val.ta == 'INSPECCION'){ta = "INSPECCIÓN"}
			if (val.ta == 'VERIFICACION'){ta = "VERIFICACIÓN"}
			if (val.ta == 'SUPERVISION'){ta = "SUPERVISIÓN"}
			if (val.ta == 'INVESTIGACION'){ta = "INVESTIGACIÓN"}else{
				ta = val.ta;
			}
			fila += '<tr>';
				fila += '<td>'+ta+'</td>';
				fila += '<td>'+val.cuenta+'</td>';
				fila += '<td>';
					fila += '<button type="button" class="btn btn-success btn-flat" onclick="verActas('+"'"+val.ta+"'"+');"> <i class="fa fa-eye" ></i> </button>';
				fila += '</td>';
			fila += '</tr>';
			$('#tbl_actas').append(fila);
		});
		fila += '<tr>';
			fila += '<td class="text-right">TOTAL GENERAL</td>';
			fila += '<td colspan="2">'+suma+'</td>';
		fila += '</tr>';
		$('#tbl_actas').append(fila);	
	})
	.fail(function(jqXHR,textStatus,errorThrow) {
		console.log("Error: "+jqXHR.responseText);
	});
	applyDataTables('tbl_abogados');
	return false;
}

//CONSULAR LOS EXPEDIENTES DEL TABLERO DE CONTROL
function verExpedientes(tipo,num) {
	
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '81',valor:num,tipo:tipo},
		async:false,
		cache:false,
	})
	.done(function(response) {
		if ( $.fn.DataTable.isDataTable( '#tbl_general' ) ) {
			var t = $('#tbl_general').DataTable();
			t.destroy();
		}
		$('#tbl_general tbody').html("");
		$.each(response, function(i, val) {
			var fila = "";var c =0;
			c = i+1;
			fila += "<tr>";
				fila += "<td>"+c+"</td>";
				fila += "<td><a href='index.php?menu=cedula&queja="+val.id+"'>"+val.cve_exp+"</a></td>";
				fila += "<td>"+val.n_estado+"</td>";
				fila += "<td>"+val.n_procedencia+"</td>";
				fila += "<td>"+val.t_asunto+"</td>";
			fila += "</tr>";
			$('#tbl_general').append(fila);
		});
		
		applyDataTables('tbl_general');
	})
	.fail(function(jqXHR,textStatus,errorThrow) {
		console.log("error");
	});
	
	return false;
}
function frm_migracion() {
	$('#frm_migracion').submit(function(e) {
		e.preventDefault();
		if ( $.fn.DataTable.isDataTable( '#tbl_migrate' ) ) {
			var t = $('#tbl_migrate').DataTable();
			t.destroy();
		}
		$('#div_tbl').removeClass('hidden')
		if ($('#modo').val() == '1') {
			$('#btn_migrate').removeClass('hidden')
			$('#btn_change_edo').addClass('hidden')
		}else if($('#modo').val() == '2'){
			$('#btn_migrate').addClass('hidden')
			$('#btn_change_edo').removeClass('hidden')
		}
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
			//$('#buscador_tbl').removeClass('hidden');
			$('#tbl_migrate tbody').html("");
			$.each(response, function(i, val) {
				var fila = "";
				var name = "";
				if ( val.full_name == null ) {
					name = "SIN ASIGNAR";
				}else{
					name = val.full_name;
				}
				fila += "<tr>";
					fila += "<td>";
						fila += '<label>';
						    fila += '<input type="checkbox" class="minimal c_quejas" name="c_queja[]" value="'+val.id+'">';
						fila += '</label>';
					fila += "</td>";
					fila += "<td>"+val.id+"</td>";
					fila += "<td>"+val.cve_exp+"</td>";
					fila += "<td>"+name+"</td>";
					fila += "<td>"+val.nombre+"</td>";
				fila += "</tr>";
				$('#tbl_migrate tbody').append(fila);
			});
			
			$('input[type="checkbox"].minimal').iCheck({
			  checkboxClass: 'icheckbox_minimal-blue'
			});
			$('#check_master').on('ifChecked', function(event){
				var conta = 0 ;
				$('.c_quejas').iCheck('check');
				$('[name="c_queja[]"]:checked').each(function() {
					conta++;
				});
				$('#cuenta').text(conta);
			});
			$('#check_master').on('ifUnchecked', function(event){
				$('.c_quejas').iCheck('uncheck');
				$('#cuenta').text('0');
			});
			$(".c_quejas").on('ifChecked', function(event){

				var conta = 0 ;
				$('[name="c_queja[]"]:checked').each(function() {
					conta++;
				});
				$('#cuenta').text(conta);
			});
			$('.c_quejas').on('ifUnchecked', function(event){
				var conta = 0 ;
				$('[name="c_queja[]"]:checked').each(function() {
					conta++;
				});
				$('#cuenta').text(conta);
			});

		})
		.fail(function(jqXHR,textStatus,errorThrow) {
			alerta('alerta','error',jqXHR.responseText,'');
		});
		$('#tbl_migrate').dataTable({
			dom: 'Bfrtip',
			pageLength: -1,
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
	});

	return false;
}
function strarTransfer() {
	var inputs = [];
	var destino = $('#destino_id').val();
	$('[name="c_queja[]"]:checked').each(function() {
		inputs.push($(this).val());
	});
	if(inputs.length == 0){
		alerta('alerta','error','Debe seleccionar uno o más elementos','');
	}else{
		$.ajax({
			url: 'controller/puente.php',
			type: 'POST',
			dataType: 'json',
			data: {option: '84',quejas:inputs,destino:destino},
			async:false,
			cache:false
		})
		.done(function(response) {
			alerta('alerta',response.status,response.message,'');
		})
		.fail(function(jqXHR,textStatus,errorThrow) {
			console.log("Error");
		});
	}
	console.log( inputs.length );
	return false;
}
function generateFormsMigrate() {
	var inputs = [];
	$('[name="c_queja[]"]:checked').each(function(i,el) {
		inputs.push($(this).val());
	});
	if(inputs.length == 0){
		alerta('alerta','error','Debe seleccionar uno o más elementos','');
	}else{
		location.href = "index.php?menu=turnado_multi&quejas="+inputs;
	}
	return false;
}
function frm_add_turno_multi() {
	var inputs = [];
	$('#frm_add_turno_multi').submit(function(e) {
		e.preventDefault();

		var dataForm;
		if ( $('#estado').val() == '3' ) {
			dataForm = new FormData(document.getElementById("frm_add_turno_multi"));
			p_ajax = {
			url: 'controller/puente.php',
			type: 'POST',
			dataType: 'json',
			data: dataForm,
			async:false,
			cache:false,
			processData: false,
            contentType: false,
		};
		}else if($('#estado').val() == '8'){
			dataForm = $(this).serializeArray();
			p_ajax = {
				url: 'controller/puente.php',
				type: 'POST',
				dataType: 'json',
				data: dataForm,
				async:false,
				cache:false,
			};
		}else{
			dataForm = $(this).serialize();
			p_ajax = {
				url: 'controller/puente.php',
				type: 'POST',
				dataType: 'json',
				data: dataForm,
				async:false,
				cache:false,
			};
		}
		
		$.ajax(p_ajax)
		.done(function(response) {
			alerta('div_turno',response.status, response.message,'');
			if (response.status == 'success') {
				setTimeout(function() {
					//Modificar la url 
					var actual = $('#queja_id').val();
					$('#list_e li').each(function(i, el) {
						if (actual != $(this).prop('id')) {
							inputs.push($(this).prop('id'));
						}
					});
					location.href = "index.php?menu=turnado_multi&quejas="+inputs;
				}, 5000);
			}
		})
		.fail(function(jqXHR,textStatus,errorThrown) {
			alerta('div_turno',response.status,jqXHR.responseText,'');
		});
		
	});//fin de submit
	
	return false;
}
function frm_asignar() {
	$('#frm_asignar').submit(function(e) {
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
			alerta('m_asignar',response.status,response.message,'modal_asignar');
		})
		.fail(function(jqXHR,textStatus,errorThrown) {
			alerta('m_asignar',"error",jqXHR.responseText,'modal_asignar');
		});
	});
	return false;
}
//frm_add_cargo
function frm_add_cargo() {
	$('#frm_add_cargo').submit(function(e) {
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
			load_cargos();
			alerta('m_cargo',response.status,response.message,'modal_add_cargo');
		})
		.fail(function(jqXHR,textStatus,errorThrown) {
			alerta('m_cargo',"error",jqXHR.responseText,'modal_add_cargo');
		});
		
	});
	return false;
}
function frm_add_adsp() {
	$('#frm_add_adsp').submit(function(e) {
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
			load_adsp();
			alerta('m_adsp',response.status,response.message,'modal_add_adsp');
		})
		.fail(function(jqXHR,textStatus,errorThrown) {
			alerta('m_adsp',"error",jqXHR.responseText,'modal_add_adsp');
		});
	});
	return false;
}
function frm_add_opinion() {
	$('#frm_add_opinion').submit(function(e) {
		e.preventDefault();
		$.ajax({
			url: 'controller/puente.php',
			type: 'POST',
			dataType: 'json',
			data: $(this).serialize(),
			async:false,
			cache:false,
		})
		.done(function(response) {
			alerta('m_opinion',response.status,response.message,'modal_add_opinion');
		})
		.fail(function(jqXHR,textStatus,errorThrown) {
			alerta('m_opinion','error',jqXHR.responseText,'modal_add_opinion');
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
//funcion de eliminacio n de vias de recepcion asignadas a un expediente 
function deleteVia( via_id ) {
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '21', via:via_id},
		async:false,
		cache:false,
	})
	.done(function(response) {
		alerta('edit_alert',response.status,response.message,'');
		$('tr#'+via_id).hide('2500', function() {
			console.log('Via de recepcion eliminada');		
		});
	})
	.fail(function() {
		alerta('edit_alert',response.status,response.message,'');
	});
	return false;
}
//eliminación de quejosos 
function deleteQuejoso(quejoso_id) {
	$('#quejoso_'+quejoso_id).hide(3000);
	return false;
}
function deleteConducta(presunta,tipo) {
	if (tipo == 'PRINCIPAL') {
		open_modal('modal_aviso', presunta);
	}else{
		$.ajax({
			url: 'controller/puente.php',
			type: 'POST',
			dataType: 'json',
			data: {option: '14', presunta:presunta},
			async:false,
			cache:false,
		})
		.done(function(response) {
			alerta('edit_alert',response.status,response.message,'');
			$('tr#c_'+presunta).hide('2500', function() {
				console.log('Via de recepcion eliminada');		
			});
		})
		.fail(function() {
			alerta('edit_alert',response.status,response.message,'');
		});
	}
	
	return false;
}
// confirmar la eliminación de la conducta PRINCIPAL
function frm_aviso() {
	$('#frm_aviso').submit(function(e) {
		e.preventDefault();
		var presunta = $('#conducta_id').val();
		$.ajax({
			url: 'controller/puente.php',
			type: 'POST',
			dataType: 'json',
			data: {option: '14', presunta:presunta},
			async:false,
			cache:false,
		})
		.done(function(response) {
			alerta('edit_alert',response.status,response.message,'');
			$('tr#c_'+presunta).hide('2500', function() {
				console.log('Via de recepcion eliminada');		
			});
		})
		.fail(function() {
			alerta('edit_alert',response.status,response.message,'');
		});
	});
}
//eventos de los controles de catalogo de area del presunto responsable 
function eventOrganigrama() {
	$('#area').change(function(e) {
		e.preventDefault();
		$('#area_'+$(this).val()).removeClass('hidden');
		if ($(this).val() == 1) {$('#area_2,#area_3,#area_4').addClass('hidden');}
		if ($(this).val() == 2) {$('#area_1,#area_3,#area_4').addClass('hidden');}
		if ($(this).val() == 3) {$('#area_2,#area_1,#area_4').addClass('hidden');}
		if ($(this).val() == 4) {$('#area_2,#area_3,#area_1').addClass('hidden');}
	});
	return false;
}