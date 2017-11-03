<script type="text/javascript">
	var dttable = $('#<?php echo $datatablename;?>').DataTable({
		<?php if ($datatablename == 'table_clients' ) { ?>
			"columns": [
				{"searchable": false},
				{"searchable": true},
				{"searchable": false},
				{"searchable": false},
				{"searchable": false},
				{"searchable": false},
				{"searchable": false}
			],
		<?php } else if ($datatablename == 'table_report_radios') { ?>
			columnDefs: [
				{type: 'date-euro', targets: 3}
			],
			"paging": false,
			"searching": false,
			"order": [[ 3, "desc" ]],
		<?php } else if ($datatablename == 'table_report_all_user') { ?>
			columnDefs: [
				{ type: 'date-euro', targets: 3 }
			],
			"order": [[ 3, "asc" ]],
		<?php } else if ($datatablename == 'table_users') { ?>
			"columns": [
				{ "searchable": false },
				null,
				{ "searchable": false },
				{ "searchable": false },
				null,
				{ "searchable": false }
			],
			"order": [[ 1, "asc" ]],
		<?php } else if ($datatablename == 'table_rec_radios') { ?>
			"autoWidth": false,
			"order": [
				[0, "asc"],
				[1, "asc"]
			],
			pagination: false,
			"columnDefs": [
				{"searchable": true, "width": "10%", "visible": false, "targets": 0},
				{"searchable": true, "width": "10%", "targets": 1},
				{"searchable": false, "width": "65%", "targets": 2},
				{"searchable": false, "width": "15%", "targets": 3}
			],
			"drawCallback": function(settings) {
				var api = this.api();
				var rows = api.rows({page:'current'}).nodes();
				var last = null;
				api.column(0, {page:'current'}).data().each(function (group, i) {
					if (last !== group) {
						$(rows).eq(i).before('<tr class="group"><td colspan="5">'+group+'</td></tr>');
						last = group;
					}
				})
			},
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