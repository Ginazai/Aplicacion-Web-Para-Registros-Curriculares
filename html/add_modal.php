<!-- Add Modal -->
<div class="modal glass fade" id="add-modal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-modal-label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content glass">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="add-modal-label">Adding Profile</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body glass border rounded-0">
        <?php 
				if (isset($_SESSION['error'])) {
					echo('<div style="color: red;" class="text-center">'.$_SESSION['error'].'</div>');
					unset($_SESSION['error']);
				}
				if (isset($_SESSION['succes'])) {
					echo('<div style="color: green;" class="text-center">'.$_SESSION['succes'].'</div>');
					unset($_SESSION['succes']);
				}
				?>
				<form id="form-add" name="form-add" action="" class="form-control border-0" style="background-color: transparent !important;">
					<div class="row g-2">
						<div class="col-md">
							<div class="form-floating">
			          <input id="firstName" type="text" class="form-control" name="first_name" placeholder="Name">
			          <label for="firstName" class="form-label">Name</label>
			        </div>
			      </div>
			      <div class="col-md">
			        <div class="form-floating">
			          <input class="form-control" id="lastName" type="text" name="last_name" placeholder="Lastname">
			          <label for="lastName" class="form-label">Last name</label>
			        </div>
			       </div>
	      	</div>

	        <div class="form-floating my-3">
	          <input id="email" class="form-control" type="text" name="email" placeholder="Email address">
	          <label for="email" class="form-label">Email</label>
	        </div>

	        <div class="form-floating my-3">
	          <input class="form-control" id="headline" type="text" name="headline" placeholder="Headline">
	          <label for="headline" class="form-label">Headline</label>
	        </div>

	        <div class="form-floating my-3">
	          <textarea id="summary" class="form-control" name="summary" rows="8" cols="80" placeholder="Summary"></textarea>
	           <label for="summary" class="form-label">Summary</label>
	        </div>

	        <div class="col-sm-2">
	          <label for="addEdu" class="form-label">Education</label>
	          <input class="form-control btn btn-success btn-sm" id="addEdu" type="submit" name="add_education" value="+">
	        </div>

	        <div class="col-sm-12" id="edu_fields">
						<script type="text/javascript" src="js/edu.js"></script>
						<script type="text/javascript" src="js/ajax.js"></script>
					</div>

	        <div class="col-sm-2">
	          <label for="addPost" class="form-label">Position</label>
	          <input class="form-control btn btn-success btn-sm" id="addPost" type="submit" name="addPost" value="+">
	        </div>

	        <div class="col-sm-12" id="position_fields">
						<script type="text/javascript" src="js/position.js"></script>
					</div>
				</form>
      </div>
      <div class="modal-footer float-left">
        <button type="button" class="btn glass-btn-success" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn glass-btn-danger">Add</button>
      </div>
    </div>
  </div>
</div>
<!-- Add Modal -->
