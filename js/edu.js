$(document).ready(function() {
	j=0;
	function createEdu(parent_div) {
		console.log("pressed add edu");
		$(parent_div).append('\
		<div class="edu_field row">\
			<div class="row g-2">\
				<div class="col-md">\
					<div class="form-floating">\
						<input class="form-control" id="edu_year'+ j +'" maxlength="4" type="text" name="edu_year'+ j +'" placeholder="Year">\
						<label for="edu_year'+ j +'" class="form-label">Year</label>\
					</div>\
				</div>\
				<div class="col-md">\
					<div class="form-floating">\
						<input class="school form-control" type="text" name="edu_school' + j + '" rows="1" cols="60" placeholder="Institution">\
						<label for="edu_school'+ j +'" class="form-label">Institution</label>\
					</div>\
				</div>\
			</div>\
			<div class="col-12" width="100%">\
				<button class="my-2 float-end remove-edu-field btn btn-sm btn glass-btn-danger" type="button">\
					<span class="fas fa-trash"></span> Eliminar\
				</button>\
			</div>\
		</div>');
		j+=1;
	}
	const edu_fields = edit_fields_id.map((id) => {
		$("#editEdu_" + id).on("click",function(){createEdu("#edu_fields_edit_"+id);});
		$("#edu_fields_edit_"+id).on("click", ".remove-edu-field", function(e) {
			e.preventDefault();
			$(this).parent().parent().remove();
		});
	});
	$('.school').autocomplete({
		source: "school.php" 
	});
	$("#addEdu").on("click",function(){createEdu("#edu_fields");});
	$("#edu_fields").on("click", ".remove-edu-field", function(e) {
		e.preventDefault();
		$(this).parent().parent().remove();
	});
});