$(document).ready(function () {
	i=0;
	function createPositionEdit(parent_elem) {
		var elem_len = $(parent_elem).children().length;
		if(elem_len>=10){alert("Maximun amount of position rows reached");}
		if(elem_len>0){i=elem_len-1;}
		$(parent_elem).append('\
			<div class="position_field row g-2">\
			<div class="col-12 mt-3">\
				<div class="form-floating">\
					<input id="year'+ i +'" class="form-control mx-0" maxlength="4" type="number" name="Edit_years[year'+ i +']" placeholder="Year">\
					<label for="year'+ i +'">Year</label>\
				</div>\
			</div>\
			<div class="col-12">\
				<div class="form-floating">\
					<textarea id="desc'+ i +'" class="form-control" name="Edit_descriptions[desc' + i + ']" placeholder="Description"></textarea>\
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
	}
	function createPositionAdd(parent_elem) {
		if($(parent_elem).children().length>=9){alert("Maximun amount of position rows reached");}
		$(parent_elem).append('\
			<div class="position_field row g-2">\
			<div class="col-12 mt-3">\
				<div class="form-floating">\
					<input id="year'+ i +'" class="form-control mx-0" maxlength="4" type="number" name="Add_years[year'+ i +']" placeholder="Year">\
					<label for="year'+ i +'">Year</label>\
				</div>\
			</div>\
			<div class="col-12">\
				<div class="form-floating">\
					<textarea id="desc'+ i +'" class="form-control" name="Add_descriptions[desc' + i + ']" placeholder="Description"></textarea>\
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
	}
	const map_positions = edit_fields_id.map((id) => {
		console.log("created #editPost_"+id);
		$("#editPost_"+id).on("click", function() {createPositionEdit("#position_fields_edit_"+id);});
		$("#position_fields_edit_"+id).on("click", ".position_remove", function(e) {
			e.preventDefault();
			$(this).parent().parent().remove();
		});
	});
	$("#addPost").on("click", function() {createPositionAdd("#position_fields");});
	$("#position_fields").on("click", ".position_remove", function(e) {
		e.preventDefault();
		$(this).parent().parent().remove();
	});
});