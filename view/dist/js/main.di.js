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
		tablero_ctrl();
	}
	if ( url == '?menu=m_queja' ) {
		$('#option_3').addClass('active');		
		load_catalogo('t_ley','select','7A');
		load_catalogo('vias_r','select',9);
		$('#vias_r').select2();
	}
	if ( url == '?menu=list_queja' ) {
		$('#option_3').addClass('active');		
		listado_qd();
		frm_upload_file();
		frm_add_opinion();
		autocomplete_input('sp','sp_id');
		frm_asignar();
	}
	if ( url == '?menu=turnar' ) {
		//load_catalogo('estado','select',12);
		load_catalogo('t_ley','select','7A');
		load_catalogo('dependencia_f','select',77);
		frm_add_turno();
		autocomplete_input('sp','sp_id');
		autocomplete_input('sp_uai','sp_uai_id');
		autocomplete_input('persona','persona_id');
		autocomplete_input('expediente','expediente_id');
		autocomplete_input('oficio','oficio_id');
		autocomplete_input('oficio_envio','oficio_e_id');
		
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
			if ( e == '8' ) {
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
	
	if ( url == '?menu=expedientes' ) {
		$('#option_2').addClass('active');		
		applyDataTables('tbl_abogado');
	}
	if ( url == '?menu=turnado_multi' ) {
		
		load_catalogo('estado','select',12);
		load_catalogo('t_ley','select',7);
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
			if ( e == '8' ) {
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
		frm_migracion();
	}
	if ( url == '?menu=seguimiento' ) {
		$('#option_3').addClass('active');	
		load_catalogo('municipios','select',10);
		load_catalogo('cargo','select',22);
		load_catalogo('color','select',112);
		
		load_catalogo('procedencia','select',4);
		load_catalogo('a_presunto','select',4);
		
		load_adsp();
		frm_add_unidad();
		change_procedencia();
		frm_add_quejoso();
		frm_add_presunto();
		frm_add_adsp();
	}

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
function change_procedencia() {
	$('#procedencia').change(function(e) {
		e.preventDefault();
		var valor = $(this).val();
		if (valor == 2) {
			$('#estatal').removeClass('hidden');
			$('#cprs').addClass('hidden');
			load_catalogo('subdir','select',23);
			load_catalogo('region','select',25);
			load_catalogo('agrupamiento','select',24);
		}else if (valor == 1 || valor == 3) {
			$('#estatal').addClass('hidden');
			$('#cprs').removeClass('hidden');
			load_catalogo('penales','select',109);
		}else{
			$('#cprs').addClass('hidden');
			$('#estatal').addClass('hidden');
		}
	});
	return false;
}
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
// Listado de expedientes 
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
	        { leyenda: 'Número_Folio',class:'text-center',style: 'width:200px;',  columna:'q.cve_ref',ordenable:false,filtro:true},
	        { leyenda: 'No. expediente',class:'text-center', columna:'q.cve_exp',ordenable:true,filtro:true},
	        { leyenda: 'Adscripción',class:'text-center', columna:'a.nombre',ordenable:false,filtro:true},
	        { leyenda: 'Jefe_Departamento',class:'text-center', columna:'pe.nombre',ordenable:false,filtro:true,style: 'width:400px;'},
	        { leyenda: 'Abogado_asignado',class:'text-center', columna:'pe.nombre',ordenable:false,filtro:true,style: 'width:400px;'},
	        { leyenda: 'Estado',class:'text-center',  columna:'e.id',ordenable:false,filtro:function(){
	        	return anexGrid_select({
    	            data: estados
    	        });
	        }},
	        { leyenda: 'Fecha/Hora de hechos',class:'text-center', style: 'width:100px;', columna:'',ordenable:false},
	        { leyenda: 'Fecha_apertura', class: 'text-center', columna:'',ordenable:false },
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
	        
	        { leyenda: 'Información_extraordinaria', class: 'text-center', columna:'',ordenable:false },
	    ],
	    modelo: [
	    	{ class:'',formato: function(tr, obj, valor){
	    		var acciones = [];
	    		var nivel = $('#nivel').val();
	    		if(nivel == 'DIRECTOR'){
	    			acciones = [
	    				{ href: "javascript:open_modal('modal_add_opinion',"+obj.id+");", contenido: '<i class="glyphicon glyphicon-cloud"></i> Agregar opinión' },
                        { href: "index.php?menu=seguimiento&queja="+obj.id, contenido: '<i class="fa fa-folder"></i> Datos de presuntos y quejosos' },
                        { href: "javascript:open_modal('modal_upload_file',"+obj.id+");", contenido: '<i class="glyphicon glyphicon-cloud"></i> Adjuntar documento al expediente' },
                        { href: "index.php?menu=cedula&exp="+obj.id, contenido: '<i class="glyphicon glyphicon-eye-open "></i>Ver cédula' },
                        //{ href: 'index.php?menu=m_queja&queja='+obj.id, contenido: '<i class="fa fa-pencil"></i> Modificar' },
                        { href: 'index.php?menu=turnar&queja='+obj.id, contenido: '<i class="fa fa-pencil"></i> Enviar' },
                        { href: "javascript:open_modal('modal_asignar',"+obj.turno_id+");", contenido: '<i class="fa fa-user"></i> Asignar a un abogado' },
                    ];
	    		}else{
	    			acciones = [
	    				{ href: "javascript:open_modal('modal_add_opinion',"+obj.id+");", contenido: '<i class="glyphicon glyphicon-cloud"></i> Adjuntar documento al expediente' },
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
	        {class:'text-center', formato: function(tr, obj, valor){
	            return obj.cve_ref;
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
	        {class:'text-center', formato: function(tr, obj, valor){
	        	return obj.n_area;
	        }}, 
	        {class:'text-center', formato: function(tr, obj, valor){
	        	if ( obj.jefe_name !== '') {
	        		return 'NO ASIGNADO';
	        	}else{
	        		return obj.jefe_name;
	        	}
	        }}, 
	        {class:'text-center', formato: function(tr, obj, valor){
	        	return obj.full_name;
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
	        {class:'text-center', formato: function(tr, obj, valor){
	        	return obj.f_alta;
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
				fila += '<td>'+val.full_name+'</td>';
				fila += '<td>'+val.n_area+'</td>';
				fila += '<td>'+val.cuenta+'</td>';
				fila += '<td>';
					fila += '<button type="button" class="btn btn-success btn-flat" onclick="verExpedientes('+"'abogado'"+','+val.id+');"> <i class="fa fa-eye" ></i> </button>';
				fila += '</td>';
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
		//console.log(response.c_oins);
		$.each(response.c_oins, function(i, val) {
			suma += parseInt(val.cuenta);
			var tipo = "";
			if (val.t_orden == "INS") {tipo="INSPECCIONES";}
			if (val.t_orden == "VER") {tipo="VERIFICACIONES";}
			if (val.t_orden == "SUP") {tipo="SUPERVISIONES";}
			if (val.t_orden == "INV") {tipo="INVESTIGACIONES";}
			if (val.t_orden == "AGE") {tipo="AGENTE ENCUBIERTO";}
			if (val.t_orden == "USI") {tipo="USUARIO SIMULADO";}
			fila += '<tr>';
				fila += '<td>'+tipo+'</td>';
				fila += '<td>'+val.cuenta+'</td>';
				fila += '<td>';
					fila += '<button type="button" class="btn btn-success btn-flat" onclick="load_detalle_oin('+"'"+val.t_orden+"'"+');"> <i class="fa fa-eye" ></i> </button>';
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
			if (val.ta == 'INVESTIGACION'){ta = "INVESTIGACIÓN"}
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
function load_catalogo(element,type,option){
	var result;
	if( element == 'conductas' || element == 'art' || element == 'secciones' || element == 'fracciones' ){
		var data = $('#t_ley').val();
		var art = $('#art').val();
		var sec = $('#secciones').val();
		var fracciones = $('#fracciones').val();
	}
	else if(element == 'procedencia'){
		var data = $('#t_asunto').val();
	}
	else{
		var data = '0';
		var art = $('#art').val();
		var sec = $('#secciones').val();
		var fracciones = $('#fracciones').val();
	}
	if ( element != '' ) {
		$('#'+element).html('');
	}
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: option, data:data,art:art,sec:sec,fra:fracciones},
		async:false,
		cache:false,
	})
	.done(function(response) {
		if ( type == 'select') {
			if( element == 'conductas' ){
				$('#conductas').attr('multiple', '');
				$('#conductas').select2();
			}
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
		console.log('Ocurrio un error al cargar el catalogo de opcion: '+option)
	});

	//Generar la clave del expediente
	if ( element == 't_tra' ) {
		generate_code(element);
	}
	if ( element == 't_ley' ) {
		$('#'+element).change(function(e){
			e.preventDefault();
			load_catalogo( 'art', 'select', '7B');
			load_catalogo( 'conductas', 'select', 8);
			//load_catalogo( 'conductas', 'select', 8);			
		});
	}
	if ( element == 'art' ) {
		$('#'+element).change(function(e){
			e.preventDefault();
			load_catalogo( 'secciones', 'select','7C');
			load_catalogo( 'conductas', 'select', 8);
		});
	}
	if ( element == 'secciones' ) {
		$('#'+element).change(function(e){
			e.preventDefault();
			load_catalogo( 'fracciones', 'select', '7D');
			load_catalogo( 'conductas', 'select', 8);
		});
	}
	if ( element == 'fracciones' ) {
		$('#'+element).change(function(e){
			e.preventDefault();
			load_catalogo( 'conductas', 'select', 8);
		});
	}
		
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
function frm_migracion() {
	$('#frm_migracion').submit(function(e) {
		e.preventDefault();

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
			$('#buscador_tbl').removeClass('hidden');
			$('#tbl_migrate tbody').html("");
			$.each(response, function(i, val) {
				var fila = "";

				fila += "<tr>";
					fila += "<td>";
						fila += '<label>';
						    fila += '<input type="checkbox" class="minimal c_quejas" name="c_queja[]" value="'+val.id+'">';
						fila += '</label>';
					fila += "</td>";
					fila += "<td>"+val.id+"</td>";
					fila += "<td>"+val.cve_exp+"</td>";
					if (val.full_name == null) {
						fila += "<td>NO ASIGNADO</td>";
					}else{
						fila += "<td>"+val.full_name+"</td>";
					}
					
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
		
	});

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
			var proce = ( val.n_procedencia != null ) ? val.n_procedencia : 'SIN PROCEDENCIA' ;
			fila += "<tr>";
				fila += "<td>"+c+"</td>";
				fila += "<td><a href='index.php?menu=cedula&exp="+val.id+"'>"+val.cve_exp+"</a></td>";
				fila += "<td>"+val.n_estado+"</td>";
				fila += "<td>"+proce+"</td>";
				fila += "<td>"+val.t_asunto+"</td>";
				fila += "<td>"+val.t_tramite+"</td>";
				fila += "<td>"+val.dias_t+"</td>";
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
        },
        "pageLength": 5
	});
	return tabla ;
}
//Autocompletado de informacion 
function autocomplete_input(input,hidden){
	var option ;
	if (input == 'expediente') {
		option = 12 
	}else if ( input == 'oficio' ){
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
/*cargar el detalle la OIN en el modal*/
function load_detalle_oin(tipo) {
	/*if (tipo == 1) { t = 'ins';}
	if (tipo == 2) { t = 'sup';}
	if (tipo == 3) { t = 'ver';}
	if (tipo == 4) { t = 'inv';}*/
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '85',tipo: tipo},
		async:false,
		cache:false,
	})
	.done(function(response) {
		if ( $.fn.DataTable.isDataTable( '#tbl_oin' ) ) {
			$('#tbl_oin').DataTable().destroy();
		}
		$('#tbl_oin tbody').html("");
		var c = 0 ;
		$.each(response, function(i, val) {		
			c++;	
			var fila = "",f_finish = "",estado = "", of = "", f_oficio = "";
			if (val.no_oficio == null) { of = "SIN NÚMERO DE OFICIO"; }else{ of = val.no_oficio; }
			if (val.fecha_oficio == null) { f_oficio = "SIN FECHA OFICIO"; }else{ of = val.no_oficio; }
			fila += "<tr>";
				fila += "<td>"+c+"</td>";
				fila += "<td>"+val.clave+"</td>";
				fila += "<td>"+of+"</td>";
				fila += "<td>"+val.f_creacion;+"</td>";
				if (val.nom_completo == null) {
					fila += "<td>ENLACE OPERATIVO NO ASIGNADO</td>";
				}else{
					fila += "<td>"+val.nom_completo+"</td>";
				}
				
				if (val.estatus == null) {
					fila += "<td>EN PROCESO</td>";
					f_finish = "";
				}else{
					if( val.estatus == 'Cumplida' ){estado == "Cumplida con resultado";}else{
						estado = val.estatus;
					}
					fila += "<td>"+val.estatus+"</td>";
					f_finish = val.f_creacion;
				}
				fila += "<td>"+ f_finish +"</td>";
			fila += "</tr>";
			$('#tbl_oin').append(fila);
			
		});
		applyDataTables('tbl_oin');
	})
	.fail(function(jqXHR,textStatus,errorThrown) {
		console.log("Error:");
	});
	
	$('#modal_detalle_oin').modal('toggle');
}
/*mostrar las actas por tipo de actuacion*/
function verActas(t_tra) {
	
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '86',ta:t_tra},
	})
	.done(function(response) {
		if ( $.fn.DataTable.isDataTable( '#tbl_actas_detalle' ) ) {
			$('#tbl_actas_detalle').DataTable().destroy();
		}
		$('#tbl_actas_detalle tbody').html("");
		$.each(response, function(i, val) {
			var fila = "", t_acta = "";
			if (val.t_actuacion == 'SUPERVISION') { t_acta = "SUPERVISIÓN"; }
			if (val.t_actuacion == 'VERIFICACION') { t_acta = "VERIFICACIÓN"; }
			if (val.t_actuacion == 'INVESTIGACION') { t_acta = "INVESTIGACIÓN"; }
			if (val.t_actuacion == 'INSPECCION') { t_acta = "INSPECCIÓN"; }
			fila += "<tr>";
				fila += "<td>"+val.id+"</td>";
				fila += "<td>"+val.clave+"</td>";
				fila += "<td>"+val.fecha+"</td>";
				fila += "<td>"+t_acta+"</td>";
				fila += "<td>"+val.procedencia+"</td>";
				fila += "<td>"+val.n_municipio+"</td>";
				fila += "<td>"+val.lugar+"</td>";
				fila += "<td>"+val.n_area+"</td>";
				fila += "<td>"+val.n_abogado+"</td>";
			fila += "</tr>";
			$('#tbl_actas_detalle tbody').append(fila);
		});
		$('#modal_detalle_actas').modal('toggle');
		applyDataTables('tbl_actas_detalle');
	})
	.fail(function(jqXHR,textStatus,errorThrown) {
		alerta('div_actuaciones','error',jqXHR.responseText,'');
	});
	
	return false;
}
function frm_add_turno() {
	$('#frm_add_turno').submit(function(e) {
		e.preventDefault();
		var dataForm;
		if ( $('#estado').val() == '3' ) {
			dataForm = new FormData(document.getElementById("frm_add_turno"));
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
		})
		.fail(function(jqXHR,textStatus,errorThrown) {
			alerta('div_turno',response.status,jqXHR.responseText,'');
		});	
	});//fin de submit
	
	return false;
}
/*
if (nivel == 'SECRETARIA') {
	acciones = [
		{ href: "javascript:open_modal('modal_add_opinion',"+obj.id+");", contenido: '<i class="glyphicon glyphicon-cloud"></i> Adjuntar documento al expediente' },
        { href: "index.php?menu=seguimiento&queja="+obj.id, contenido: '<i class="fa fa-folder"></i> Datos de presuntos y quejosos' },
        { href: "javascript:open_modal('modal_upload_file',"+obj.id+");", contenido: '<i class="glyphicon glyphicon-cloud"></i> Adjuntar documento al expediente' },
        { href: "javascript:open_modal('modal_add_opinion',"+obj.id+");", contenido: '<i class="glyphicon glyphicon-cloud"></i> Adjuntar documento al expediente' },
        { href: "index.php?menu=cedula&queja="+obj.id, contenido: '<i class="glyphicon glyphicon-eye-open "></i>Ver cédula' },
        { href: 'index.php?menu=m_queja&queja='+obj.id, contenido: '<i class="fa fa-pencil"></i> Modificar' },
    ];
}else if(nivel == 'JEFE'){
	acciones = [
		{ href: "javascript:open_modal('modal_add_opinion',"+obj.id+");", contenido: '<i class="glyphicon glyphicon-cloud"></i> Adjuntar documento al expediente' },
        { href: "index.php?menu=seguimiento&queja="+obj.id, contenido: '<i class="fa fa-folder"></i> Datos de presuntos y quejosos' },
        { href: "javascript:open_modal('modal_upload_file',"+obj.id+");", contenido: '<i class="glyphicon glyphicon-cloud"></i> Adjuntar documento al expediente' },
        { href: "index.php?menu=cedula&queja="+obj.id, contenido: '<i class="glyphicon glyphicon-eye-open "></i>Ver cédula' },
        { href: "javascript:open_modal('modal_asignar',"+obj.turno_id+");", contenido: '<i class="fa fa-user"></i> Asignar a un abogado' },
    ];
}else if(nivel == 'SUBDIRECTOR' || ){
	
}else{
	acciones = [
        { href: "index.php?menu=seguimiento&queja="+obj.id, contenido: '<i class="fa fa-folder"></i> Datos de presuntos y quejosos' },
        { href: "javascript:open_modal('modal_upload_file',"+obj.id+");", contenido: '<i class="glyphicon glyphicon-cloud"></i> Adjuntar documento al expediente' },
        { href: "index.php?menu=cedula&queja="+obj.id, contenido: '<i class="glyphicon glyphicon-eye-open "></i>Ver cédula' },
        { href: 'index.php?menu=m_queja&queja='+obj.id, contenido: '<i class="fa fa-pencil"></i> Modificar' },
        { href: 'index.php?menu=turnar&queja='+obj.id, contenido: '<i class="fa fa-pencil"></i> Enviar' },
       // { href: "javascript:open_modal('modal_asignar',"+obj.turno_id+");", contenido: '<i class="fa fa-user"></i> Asignar a un abogado' },
    ];
	
}
*/
function open_modal(modal,value) {
	$('#'+modal).modal('show');
	if ( modal == 'modal_upload_file' || modal == 'modal_add_opinion' ) { $('[name="queja_id"]').val(value); }
	if ( modal == 'modal_asignar' ){$('#turno_id').val(value);}
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
// Setear un array de municipios 
function setInfo( json ) { rescate = json; }
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