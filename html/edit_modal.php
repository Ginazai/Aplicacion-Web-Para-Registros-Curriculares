<?php
require_once "pdo.php";

$get_data = $pdo->prepare("SELECT * FROM profile"); 
$get_data->execute();

$profiles = $get_data->fetchAll();

if($profiles&&$get_data->rowCount()>0){
	foreach($profiles as $profile){
		$id=$profile['profile_id'];
		$name=$profile['first_name'];
		$lastname=$profile['last_name'];
		$email=$profile['email'];
		$headline=$profile['headline'];
		$summary=$profile['summary'];
?>
<!-- Edit Modal -->
<script type="application/javascript">var edit_field = [];</script>
<div class="modal glass fade rounded-0" id="edit-modal-<?= $id ?>" tabindex="-1" aria-labelledby="edit-modal-label-<?= $id ?>" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content glass">
	      <div class="modal-header">
	        <h1 class="modal-title fs-5" id="edit-modal-label-<?= $id ?>">Editing Profile</h1>
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
			<form class="form-control border-0" id="modal-edit-<?= $id ?>" name="modal-edit-<?= $id ?>" action="" role="form" method="post"  style="background-color:transparent;">

	      <div class="row g-2">
	      	<div class="col-md">
		        <div class="form-floating">
		          <input id="firstName" type="text" class="form-control" name="first_name" placeholder="Name" value="<?= $name ?>">
		          <label for="firstName" class="form-label">Name</label>
		        </div>
		      </div>
		      <div class="col-md">
		        <div class="form-floating">
		          <input class="form-control" id="lastName" type="text" name="last_name" placeholder="Lastname" value="<?= $lastname ?>">
		           <label for="lastName" class="form-label">Lastname</label>
		        </div>
	      </div>
	      </div>

	        <div class="col-sm-12">
	          <label for="email" class="form-label">Email:</label>
	          <input id="email" class="form-control" type="text" name="email" placeholder="" value="<?= $email ?>">
	        </div>

	        <div class="col-sm-12">
	          <label for="headline" class="form-label">Headline:</label>
	          <input class="form-control" id="headline" type="text" name="headline" placeholder="" value="<?= $headline ?>">
	        </div>

	        <div class="col-sm-12">
	          <label for="summary" class="form-label">Summary:</label>
	          <textarea id="summary" class="form-control" name="summary" rows="8" cols="80"><?= $summary ?></textarea>
	        </div>

	        <div class="col-sm-2">
	          <label for="addEdu" class="form-label">Education:</label>

	          <button class="form-control btn glass-btn-success btn-sm" type="button" id="editEdu" name="add_education">+</button>
	        </div>

	        <div class="col-sm-12" id="edu_fields_edit">
						<?php
						$edu_query = "SELECT * FROM education WHERE profile_id = :pid";
						$edu_stmt = $pdo->prepare($edu_query);
						$edu_stmt->execute(array(
						':pid' => $id));
						$j = 0;
						while ($edu_row = $edu_stmt->fetch(PDO::FETCH_ASSOC)) {
							$inst = $edu_row['institution_id'];
							$inst_year = $edu_row['year'];

							$inst_query = "SELECT * FROM institution WHERE institution_id = :iid";
							$inst_stmt = $pdo->prepare($inst_query);
							$inst_stmt->execute(array(
								':iid' => $inst
							));
							while ($inst_row = $inst_stmt->fetch(PDO::FETCH_ASSOC)) {
								$inst_name = $inst_row['name'];
								echo "<div class='edu_field row'><div class='col-6'><label for='edu_year{$j}' class='form-label'>Year:</label><input class='form-control' id='edu_year{$j}' maxlength='4' type='text' name='edu_year{$j}' value='{$inst_year}'></div><div class='col-6'><label for='edu_school{$j}' class='form-label'>Institution:</label><input class='school form-control' type='text' name='edu_school{$j}' rows='1' cols='60' value='{$inst_name}'></div><div class='col-12'><button class='edu_rm form-control btn btn-sm btn-danger mt-3' type='button'><span class='fas fa-trash'></span></button></div></div>";
								$j++;
							}
						}
						?>
					</div>

		    <div class="col-sm-2">
		      <label for="addPost" class="form-label">Position:</label>
		      <input class="form-control btn glass-btn-success btn-sm" id="addPost" type="button" name="addPost" value="+">
		    </div>

			  <div class="col-sm-12" id="position_fields">
					<?php
					$str_query = "SELECT * FROM position WHERE profile_id = :pid";
					$stmt = $pdo->prepare($str_query);
					$stmt->execute(array(
					':pid' => $id));
					$i = 0;
					while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
						$year = $row['year'];
						$desc = $row['description'];
						?>
						<div class='position_field_edit_<?= $id ?>'>
							<label for='year{$i}' class='form-label'>Year:</label>
							<input id='year{$i}' class='form-control col-6' maxlength='4' type='text' name='year{$i}' value='{$year}'>
							<textarea class='form-control' name='desc{$i}' rows='8' cols='80'>{$desc}</textarea>
							<button class='my-2 float-end remove-edu-field btn btn-sm btn glass-btn-danger' type='button'>
							<span class='fas fa-trash'></span> Eliminar
							</button>
						</div>
						<script type="application/javascript">
							var temp_field = "position_field_edit_<?= $id ?>";
							edit_fields.push(temp_field);
						</script>
						<?php
						$i++;
					}
					?>
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
<?php 
	}
}
?>