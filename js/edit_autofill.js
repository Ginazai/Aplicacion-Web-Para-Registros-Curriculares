$(document).ready(function() {
	var i=0;
	edu_data.map((elem) => {
		var id=elem.profile_id;
		if(id == "<?= $id ?>"){
			var content = `
			<div class='edu_field row'>
				<div class='row g-2'>
					<div class='col-md form-floating'>
						<input class='form-control' id='edu_year_${i}' maxlength='4' type='text' name='Edit_edu_years[edu_year${i}]' value='${elem.year}' placeholder='Year'>
						<label for='edu_year${i}' class='form-label'>Year</label>
					</div>
					<div id='edu_school_${i}' class='col-md form-floating'>
						<input class='school form-control' type='text' name='Edit_edu_inst[edu_school${i}]' value='${elem.name}' placeholder='Institution'>
						<label for='edu_school${i}' class='form-label'>Institution</label>
					</div>
				</div>
				<div class='col-12' width='100%'>
					<button class='my-2 float-end remove-edu-field btn btn-sm btn glass-btn-danger' type='button'>
						<span class='fas fa-trash'></span> Eliminar
					</button>
				</div>
			</div>`;
		$(`#edu_fields_edit_${id}`).append(content);
		i+=1;
		}
	});
	var i=0;
	position_data.map((elem) => {
		var id=elem.profile_id;
		if(id == "<?= $id ?>"){
			var content = `
			<div class='position_field row g-2'>
				<div class='form-floating mt-3'>
					<input id='year${i}'  class='form-control mx-0' maxlength='4' type='number' name='Edit_years[year${i}]' placeholder='Year' value='${elem.year}'>
					<label for='year${i}' class='form-label'>Year</label>
				</div>
				<div class='form-floating'>
					<textarea class='form-control' name='Edit_descriptions[desc${i}]' placeholder='Description'>${elem.description}</textarea>
					<label for='desc${i}' class='form-label'>Description</label>
				</div>
				<div class='col-12' width='100%'>
					<button class='position_remove my-2 float-end btn btn-sm btn glass-btn-danger' type='button'>
					<span class='fas fa-trash'></span> Eliminar
					</button>
				</div>
			</div>`;
		$(`#position_fields_edit_${id}`).append(content);
		i+=1;
		}
	});
});