<!-- Edit Modal -->
<div class="modal fade rounded-0" id="edit-modal-<?= $id ?>" tabindex="-1" aria-labelledby="edit-modal-label-<?= $id ?>" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content glass">
	      <div class="modal-header">
	        <h1 class="modal-title fs-5" id="edit-modal-label-<?= $id ?>">Editing <?= $name . " " . $lastname . "'s" ?> profile</h1>
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	  <div class="modal-body glass border rounded-0">
			<form class="form-floating border-0" id="modal-edit-<?= $id ?>" name="modal-edit-<?= $id ?>" action="php/edit_entry.php?profile_id=<?= $id ?>" role="form" method="post"  style="background-color:transparent;">
	      <div class="row g-2">
	      	<div class="col-md">
		        <div class="form-floating">
		          <input id="edit-fname" type="text" class="form-control" name="edit-fname" placeholder="Name" value="<?= $name ?>">
		          <label for="edit-fname" class="form-label">Name</label>
		        </div>
		      </div>
		      <div class="col-md">
		        <div class="form-floating">
		          <input class="form-control" id="edit-lname" type="text" name="edit-lname" placeholder="Lastname" value="<?= $lastname ?>">
		           <label for="edit-lastName" class="form-label">Lastname</label>
		        </div>
	      </div>
	      </div>

	        <div class="col-sm-12 form-floating my-3">
	          <input id="edit-email" class="form-control" type="text" name="edit-email" placeholder="" value="<?= $email ?>">
	          <label for="edit-email" class="form-label">Email</label>
	        </div>

	        <div class="col-sm-12 form-floating my-3">
	          <input class="form-control" id="edit-headline" type="text" name="edit-headline" placeholder="" value="<?= $headline ?>">
	          <label for="edit-headline" class="form-label">Headline</label>
	        </div>

	        <div class="col-sm-12 form-floating my-3">
	          <textarea id="edit-summary" class="form-control" name="edit-summary"><?= $summary ?></textarea>
	          <label for="edit-summary" class="form-label">Summary</label>
	        </div>

	        <div class="col-sm-2 my-3">
	          <label for="editEdu_<?= $id ?>" class="form-label">Education</label>
	          <button class="form-control btn glass-btn-success btn-sm" type="button" id="editEdu_<?= $id ?>" name="add_education">+</button>
	        </div>

	        <div class="col-sm-12" id="edu_fields_edit_<?= $id ?>">
	        	<script type="application/javascript">
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
							});
	        	</script>
					</div>

		    <div class="col-sm-2 my-3">
		      <label for="editPost_<?= $id ?>" class="form-label">Position</label>
		      <button class="form-control btn glass-btn-success btn-sm" id="editPost_<?= $id ?>" type="button" name="addPost">+</button>
		    </div>

			  <div class="col-sm-12" id="position_fields_edit_<?= $id ?>">
			  	<script type="application/javascript">
			  		$(document).ready(function() {
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
			  	</script>
				</div>
		</form>
</div>
<div class="modal-footer float-left">
  <button type="button" class="btn glass-btn-danger" data-bs-dismiss="modal">Close</button>
  <button type="submit" form="modal-edit-<?= $id ?>" class="btn glass-btn-success">Edit</button>
</div>
</div>
</div>
</div>
<!-- Edit Modal -->