jQuery(document).ready(function($) {

	$('#irpf').change(function () {

		$('body').trigger('update_checkout');
		});
	});