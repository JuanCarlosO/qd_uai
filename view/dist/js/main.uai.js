var tbl ;
$(document).ready(function() {
	url = window.location.search;
	url = url.split('&');
	getURL(url[0]);
});
//Detectando la URL
function getURL(url) {
	var date = new Date();
	if ( url == '?menu=general' ) {
		$('#option_1').addClass('active');
		$('#year').change(function(e) {
			e.preventDefault();
			dashboard($(this).val());
			dashboard_a($(this).val());
		});
		dashboard('');
		dashboard_a('');
		autocomplete_input('clave','clave_id',12);
	}
	if ( url == '?menu=reportes' ) {
		$('#option_2').addClass('active');
	}
	
	return false; 
}
//Tablero de control
function dashboard(year) {
	var fila = "";
	$('#dash tbody tr').remove("");
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '71',y:year},
		async:false,
		cache:false,
	})
	.done(function(response) {
		
		$.each(response, function(i, val) {
			var fila = "";
			fila += "<tr>";
				fila += "<td>"+val.nombre+"</td>";
				fila += "<td>"+val.total+"</td>";
				fila += "<td>";
					fila += "<button class='btn btn-success btn-flat' onclick='getListadoEstado("+val.estado+")'>";
						fila += "<i class='fa fa-eye'></i> Ver expedientes";	
					fila += "</button>";
				fila += "</td>";
			fila += "</tr>";
			$('#dash').append(fila);
		});
	})
	.fail(function(jqXHR,textStatus,errorThrow) {
		console.log("error");
	});
	
	return false;
}
//Autocompletado de informacion 
function autocomplete_input(input,hidden,option){
	$('#'+input).autocomplete({
		source: "controller/puente.php?option="+option,
		select:function(event,ui){
			if (input == 'clave') {
				window.open('index.php?menu=cedula&exp_id='+ui.item.id,'_blank');
			}else{
				$('#'+hidden).val(ui.item.id);
			}
		}
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
function getListadoEstado(estado) {
	$('#qd_estado').removeClass('hidden');
	$('#actas').addClass('hidden');
	$('#tbl_ee tbody').html("");
	var year = $('#year').val();
	
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '72',y:year,e:estado},
		async:false,
		cache:false,
	})
	.done(function(response) {
		if ( ! $.fn.DataTable.isDataTable( '#tbl_ee' ) ) 
		{
			tbl = applyDataTables('tbl_ee');
		}else
		{
			tbl.rows().remove().draw();
		}
		$.each(response, function(i, val) {
			var fila = "";
			fila += "<ul>";
						fila += "<li><label>Fecha:</label>"+val.f_hechos+"</li>";
						fila += "<li><label>Hora: </label>"+val.h_hechos+"</li>";
					fila += "</ul>";
			var cve_link = '<a href="index.php?menu=cedula&exp_id='+val.id+'">'+val.cve_exp+'</a>';
			tbl.row.add( [
	            (++i),
	            cve_link,
	            val.t_asunto,
	            val.n_tramite ,
	            val.n_procedencia,
	            fila
	        ] ).draw( false );
			
		});

	})
	.fail(function(jqXHR,textStatus,errorThrow) {
		console.log("error");
	});	
}
//Tablero de control de las actuaciones
function dashboard_a(year) {
	var fila = "";
	$('#dash_a tbody tr').remove("");
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '73',y:year},
		async:false,
		cache:false,
	})
	.done(function(response) {
		
		$.each(response, function(i, val) {
			var fila = "";
			fila += "<tr>";
				fila += "<td>"+val.nombre+"</td>";
				fila += "<td>"+val.total+"</td>";
				fila += "<td>";
					fila += "<button class='btn btn-success btn-flat' onclick='getListadoAcutaciones("+'"'+val.nombre+'"'+")'>";
						fila += "<i class='fa fa-eye'></i> Ver Actas";	
					fila += "</button>";
				fila += "</td>";
			fila += "</tr>";
			$('#dash_a').append(fila);
		});
	})
	.fail(function(jqXHR,textStatus,errorThrow) {
		console.log("error");
	});
	
	return false;
}
function getListadoAcutaciones(actuacion) {
	$('#actas').removeClass('hidden');
	$('#qd_estado').addClass('hidden');
	//$('#tbl_actas tbody').html("");
	var year = $('#year').val();
	
	$.ajax({
		url: 'controller/puente.php',
		type: 'POST',
		dataType: 'json',
		data: {option: '74',y:year,a:actuacion},
		async:false,
		cache:false,
	})
	.done(function(response) {
		if ( ! $.fn.DataTable.isDataTable( '#tbl_actas' ) ) 
		{
			tbl = applyDataTables('tbl_actas');
		}
		else
		{
			tbl.rows().remove().draw();
		}
		$.each(response, function(i, val) {
			tbl.row.add( [
	            (++i),
	            val.clave,
	            val.elaboro,
	            val.fecha ,
	            val.procedencia
	        ] ).draw( false );
			
		});

	})
	.fail(function(jqXHR,textStatus,errorThrow) {
		console.log("error");
	});	
}