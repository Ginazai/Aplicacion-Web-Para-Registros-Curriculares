$(document).ready(function () {
	$('#addPost').click(function (event) {
		event.preventDefault();
		var i = $(".position_field").length;
		console.log("position field lenght: " + i);
		if (i >= 9) {
			alert("Maximum entries exceeded");
		} else {
			console.log($('#year' + i));
			for(x = 0; x <= i.length; x++) {
				if($('"#year' + i + '"') != undefined){
					console.log("year exists");
				} else {
					$('#position_fields').append('\
					<div class="position_field">\
						<label for="year'+ i +'" class="form-label">Year:</label>\
						<input id="year'+ i +'" class="form-control col-6" maxlength="4" type="text" name="year'+ i +'">\
						<textarea class="form-control" name="desc' + i + '" rows="8" cols="80"></textarea>\
						<input class="col-6 form-control position_remove btn btn-sm btn-danger mt-2" type="button">\
							<span class="fas fa-trash"></span>\
						</input>\
					</div>');
				}
			}
		}
	});
	$(".position_remove").click(function() {
		var i = $(".position_field").length;
		console.log("removed clicked");
		$(this).parent().remove();
	});
});