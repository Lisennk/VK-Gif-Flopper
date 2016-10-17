$(document).ready(function() {
	$('#submit').click(function() {
		window.open('/app.php?url=' + $('#url').val());
	});
});