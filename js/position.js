$(document).ready(function () {
	i=0;
	$('#addPost').click(function (event) {
		event.preventDefault();
		$('#position_fields').append('\
			<div class="position_field row g-2">\
			<div class="col-md">\
				<div class="form-floating">\
					<input id="year'+ i +'" class="form-control mx-0" maxlength="4" type="number" name="year'+ i +'" placeholder="Year">\
					<label for="year'+ i +'">Year</label>\
				</div>\
			</div>\
			<div class="col-md">\
				<div class="form-floating">\
					<textarea id="desc'+ i +'" class="form-control" name="desc' + i + '" placeholder="Description"></textarea>\
					<label for="desc'+ i +'">Description</label>\
				</div>\
			</div>\
			<div class="col-12" width="100%">\
				<button class="position_remove my-2 float-end btn btn-sm btn glass-btn-danger" type="button">\
					<span class="fas fa-trash"></span> Eliminar\
				</button>\
			</div>\
		</div>');
		i+=1;
	});
	$("#position_fields").on("click", ".position_remove", function() {
		$(this).parent().parent().remove();
	});
});