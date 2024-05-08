$(document).ready(function () {
	var name = $('.school').val();
	console.log(name);
	$.ajax({
		url: "php/school.php",
		type: "post",
		data: {term:name},
		dataType: 'json',
		success: function() {
			$('.school').autocomplete({
			source: data
		}); 
		}
	});
});