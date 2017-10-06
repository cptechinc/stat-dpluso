<script>
	$(function() {
		$('#table').DataTable({
			pageLength: 15,
			columnDefs: [
				<?php foreach($array as $colnumber) : ?>
					{
						targets: [<?php echo $colnumber; ?>],
						orderable: false
					},
				<?php endforeach; ?>
			]
		});
	});
</script>
