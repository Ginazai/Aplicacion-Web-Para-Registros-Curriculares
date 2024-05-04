$(document).ready(function() {
	$('#addEdu').click(function(e) {
		e.preventDefault();
		var j = $('.edu_field').length;
		console.log(".edu_field length: " + j);
		if (j >= 9) {
			alert("Maximum number of institutions exceeded");
		} else {
			// var checking = $('.edu_field').children().children();
			// ($('.edu_field') != undefined) ? console.log(checking.attr('id')) : console.log('it does not exist');
			
			// (checking.attr('id') == 'edu_year' + j) ? console.log('edu_year' + j + 'exists') 
			// : 
			$('#edu_fields').append('\
				<div class="edu_field row">\
					<div class="col-6">\
						<label for="edu_year'+ j +'" class="form-label">Year:</label>\
						<input class="form-control" id="edu_year'+ j +'" maxlength="4" type="text" name="edu_year'+ j +'">\
					</div>\
					<div class="col-6">\
						<label for="edu_school'+ j +'" class="form-label">Institution:</label>\
						<input class="school form-control" type="text" name="edu_school' + j + '" rows="1" cols="60">\
					</div>\
					<div class="col-12" width="100%">\
						<button class="my-2 float-end remove-edu-field btn btn-sm btn glass-btn-danger" type="button">\
							<span class="fas fa-trash"></span> Eliminar\
						</button>\
					</div>\
				</div>');

			 
		}
	});
	$('.school').autocomplete({
		source: "school.php" 
	});
	$("#edu_fields").on("click", ".remove-edu-field", function(e) {
		e.preventDefault();
		console.log("removed clicked");
		$(this).parent().parent().remove();
	});
});