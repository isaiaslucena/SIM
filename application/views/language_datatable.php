
<script type="text/javascript">
	$('#<?php echo $datatablename;?>').DataTable({
		<?php if ($datatablename == 'table_clients' ) { ?>
			"columns": [
				{ "searchable": false },
				null,
				{ "searchable": false },
				{ "searchable": false },
				{ "searchable": false },
				{ "searchable": false },
				{ "searchable": false }
			],
		<?php } else if ($datatablename == 'table_report_radios') { ?>
			columnDefs: [
				{ type: 'date-euro', targets: 3 }
			],
			"paging": false,
			"searching": false,
			"order": [[ 3, "desc" ]],
		<?php } else if ($datatablename == 'table_report_all_user') { ?>
			columnDefs: [
				{ type: 'date-euro', targets: 3 }
			],
			"order": [[ 3, "asc" ]],
		<?php } ?>
		"language" : {
			"sEmptyTable": "Nenhum registro encontrado",
			"sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
			"sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
			"sInfoFiltered": "(Filtrados de _MAX_ registros)",
			"sInfoPostFix": "",
			"sInfoThousands": ".",
			"sLengthMenu": "_MENU_ resultados por página",
			"sLoadingRecords": "Carregando...",
			"sProcessing": "Processando...",
			"sZeroRecords": "Nenhum registro encontrado",
			"sSearch": "Pesquisar",
			"oPaginate": {
				"sNext": "Próximo",
				"sPrevious": "Anterior",
				"sFirst": "Primeiro",
				"sLast": "Último"
			},
			"oAria": {
				"sSortAscending": ": Ordenar colunas de forma ascendente",
				"sSortDescending": ": Ordenar colunas de forma descendente"
			}
		}

	});
</script>