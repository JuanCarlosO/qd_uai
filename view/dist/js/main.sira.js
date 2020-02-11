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
		question();
	}
	if ( url == '?menu=list_acta' ) {
		$('#option_1').addClass('active');
		$('#option_1_2').addClass('active');
		getActas();
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
		console.log('Modulo de presuntos responsables');
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
	                            { href: '#', contenido: '<i class="glyphicon glyphicon-cloud"></i> Adjuntar documento' },
	                            { href: '#', contenido: '<i class="glyphicon glyphicon-comment"></i>Agregar observaciones' },
	                            { href: '#', contenido: '<i class="glyphicon glyphicon-eye-open"></i>Ver detalle' },
	                            { href: '#', contenido: '<i class="glyphicon glyphicon-bullhorn"></i>Ver conversación' }
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
	var tabla = $("#lista_actas").anexGrid({
	    class: 'table-striped table-bordered table-hover',
	    columnas: [
	    	{ class:'text-center', leyenda: 'Acciones', style: 'width:30px;', columna: 'Sueldo' },
	    	{ class:'text-center', leyenda: 'ID', style:'width:20px;'},
	    	{ class:'text-center', leyenda: 'Clave', style: 'width:100px;', columna: 'clave'},
	        { class:'text-center', leyenda: 'Tipo acta', style: 'width:100px;', columna: 'clave'},
	        { class:'text-center', leyenda: 'Fecha',style: 'width:50px;', columna: 'fecha', filtro: false },
	        { class:'text-center', leyenda: 'Procedencia', style: 'width:100px;', columna: 'Correo' },
	        { class:'text-center', leyenda: 'Municipio', style: 'width:100px;', columna: ''},
	        { class:'text-center', leyenda: 'Descripcion', style: 'width:300px;', columna: 'clave',filtro:true},
	        
	    ],
	    modelo: [
	        { class:'',formato: function(tr, obj, valor){
	            return anexGrid_dropdown({
                    contenido: '<i class="glyphicon glyphicon-cog"></i>',
                    class: 'btn btn-primary ',
                    target: '_blank',
                    id: 'editar-' + obj.id,
                    data: [
                        { href: 'index.php?menu=seguimiento&acta=1', contenido: '<i class="glyphicon glyphicon-road"></i> Dar seguimiento' },
                        { href: 'index.php?menu=general&acta=1', contenido: '<i class="glyphicon glyphicon-pencil"></i> Editar' },
                        { href: 'index.php?menu=cedula&acta=1', contenido: '<i class="glyphicon glyphicon-eye-open"></i> Ver cedula' },
                        { href: 'index.php?menu=adjuntar&acta=1', 
                          contenido: '<i class="glyphicon glyphicon-cloud"></i> Adjuntar documento',
                          attr:[
                          	'data-toggle="modal" data-target="#modal_adjuntar_"'
                          ]
                        }
                    ]
                });
	        }},
	        { propiedad: 'id' },
	        { propiedad: 'no_acta' },
	        { propiedad: 't_acta' },
	        { propiedad: 'fecha' },
	        { propiedad: 'procedencia', class: '', },
	        { propiedad: 'municipio', class: '', },
	        { class:'text-justify', propiedad: 'descripcion',  },
	        
	    ],
	    url: 'controller/puente.php?option=2',
	    filtrable: true,
	    columna: 'id',
	    columna_orden: 'DESC'
	});
	return tabla;
}

