$(document).ready(function() {
	var url = window.location.search;
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
	}
	if ( url == '?menu=list_queja' ) {
		$('#option_1').addClass('active');
		$('#option_1_2').addClass('active');
		listado_qd();
	}
	if ( url == '?menu=reports' ) {
		$('#option_2').addClass('active');
	}
	if ( url == '?menu=aviso' ) {
		$('#option_3').addClass('active');
	}
	if ( url == '?menu=manual' ) {
		$('#option_4').addClass('active');
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
	var datos = {
	    class: 'table-striped table-bordered',
	    columnas: [
	        { leyenda: 'ID', class:'text-center', style: 'width:30px;',ordenable:true,columna:'id'},
	        { leyenda: 'No. Folio',class:'text-center', style: 'width:100px;', columna:'',ordenable:false,filtro:true},
	        { leyenda: 'No. expediente',class:'text-center', style: 'width:100px;', columna:'',ordenable:true,filtro:true},
	        { leyenda: 'Fecha de registro',class:'text-center', style: 'width:100px;', columna:'',ordenable:false},
	        { leyenda: 'Infracción(es)',class:'text-center', style: 'width:100px;', columna:'',ordenable:false},
	        { leyenda: 'Municipio',class:'text-center', style: 'width:100px;', columna:'',ordenable:false,filtro:function(){
	        	return anexGrid_select({
    	            data: [
    	                { valor: '', contenido: 'Todos' },
    	                { valor: '1', contenido: 'Abogado' },
    	                { valor: '2', contenido: 'Bombero' },
    	                { valor: '3', contenido: 'Doctor' },
    	                { valor: '4', contenido: 'Ingeniero Civil' },
    	                { valor: '5', contenido: 'Ingeniero de Sistemas' },
    	                { valor: '6', contenido: 'Músico' },
    	            ]
    	        });
	        }},
	        { leyenda: 'Procedencia',class:'text-center', style: 'width:100px;', columna:'',ordenable:false},
	        { leyenda: 'Seguimiento', class:'text-center', style: 'width:50px;'},
	        { leyenda: 'Adjuntar', class:'text-center', style: 'width:50px;'},
	        { leyenda: 'Cédula', class:'text-center', style: 'width:50px;'},
	        { leyenda: 'Modificar', class:'text-center', style: 'width:50px;'},
	    ],
	    modelo: [
	        {class:'text-center', formato: function(tr, obj, valor){
	            return obj.id;
	        }}, 
	        {class:'text-center', formato: function(tr, obj, valor){
	            return obj.id;
	        }}, 
	        {class:'text-center', formato: function(tr, obj, valor){
	            return obj.id;
	        }}, 
	        {class:'text-center', formato: function(tr, obj, valor){
	            return obj.id;
	        }}, 
	        {class:'text-center', formato: function(tr, obj, valor){
	            return obj.id;
	        }}, 
	        {class:'text-center', formato: function(tr, obj, valor){
	            return obj.id;
	        }}, 
	        {class:'text-center', formato: function(tr, obj, valor){
	            return obj.id;
	        }}, 
	        {class:'text-center', formato: function(tr, obj, valor){
	            return obj.id;
	        }}, 
	        {class:'text-center', formato: function(tr, obj, valor){
	            return obj.id;
	        }}, 
	        {class:'text-center', formato: function(tr, obj, valor){
	            return obj.id;
	        }}, 
	        {class:'text-center', formato: function(tr, obj, valor){
	            return obj.id;
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
	
	tabla.tabla().on('click', '', function(){
	    var obj = tabla.obtener($(this).val()); 
	    console.log(obj);
	});
	return false;
}