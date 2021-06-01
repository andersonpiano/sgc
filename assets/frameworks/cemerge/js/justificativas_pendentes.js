$(document).ready(function(){

		
	$(".btn-justificativas-pendentes").click(function(){
		
		//var setor = $(this).attr('id');
//		dt_pendentes.reload();
		$("#modal_justificativas_pendentes").modal();
		
		
	});
	$data_ini = document.getElementById('data_plantao_inicio').value;
	$data_fim = document.getElementById('data_plantao_fim').value;

	var dt_pendentes = $("#dt_pendentes").DataTable({

		"oLanguage": DATATABLE_PTBR,
		"autoWidth": false,
		//"processing": true,
        //scrollCollapse: true,
		//"serverSide": false,
		//paging: true,
		"pageLength": 12,
		dom: 'frtip',
		buttons: [
			{
				extend: 'print',
				customize: function ( win ) {
                    $(win.document.body)
                        .css( 'font-size', '10pt' )
 
                    $(win.document.body).find( 'table' )
                        .addClass( 'compact' )
                        .css( 'font-size', 'inherit' );
                },
				title: '<p style="text-align: center;"><img src="/sgc/assets/frameworks/cemerge/images/logo.png" alt="Cemerge" /></p>',
				messageTop: '<center><h2>Justificativas pendentes</h2></center>',
                text: ' Imprimir',
				//messageBottom: '<table style="margin: 15px 0 10px; width: 99%; height: 30px;"> 	<tbody> 		<tr> 			<td><strong>&nbsp;TOTAL DE PROVENTOS: R$ 5.000,00</strong></td> 			<td style="text-align: right;"><strong>TOTAL DE DESCONTOS: R$ 500,00</strong></td> 			<td style="text-align: right;"><strong>VALOR LIQUIDO: R$ 4.500,00 </strong></td> 		</tr> 	</tbody> </table> <p><span style="font-size: 10pt;"><strong>RECEBI A IMPORTÂNCIA ACIMA DISCRIMINADA, REFERENTE A PRESTAÇÃO DE SERVIÇOS MÉDICOS EM REGIME COOPERATIVISTA E NA QUALIDADE DE ASSOCIADO.</strong></span></p> <table style="margin: 5px 0 10px; width: 99%; height: 30px;"> 	<tbody> 		<tr> 			<td style="text-align: left;">FORTALEZA-CE&nbsp; ____/____/____&nbsp;</td><td style="text-align: center;">&nbsp;______________________________________________</td> 		</tr> 	</tbody> </table>',
				className: 'btn btn-success text-center fa fa-print',
				exportOptions: {
                    columns: [ 0, 1, 2, 3],
                }
            }],
		"ajax": {
			"url": "/sgc/admin/justificativas/ajax_justificativas_pendentes_coordenador/"+$data_ini+'/'+$data_fim,
			"method": "POST",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false },
			{ targets: "dt-center", className: "dt-center" },
		],
		/*"drawCallback": function() {
			active_btn_profissional();
		},*/
		select: true,
	});

})
