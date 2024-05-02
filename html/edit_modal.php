<!-- Edit Modal -->
<div class="modal glass fade" id="add-modal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-modal-label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content glass">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="add-modal-label">Editing Profile</h1>
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
		<form method="post" class="needs-validation add-form m-auto shadow rounded-3">
          <div class="row">

            <div class="col-6">
              <label for="firstName" class="form-label">First name:</label>
              <input id="firstName" type="text" class="form-control" name="first_name" placeholder="" value="<?= $fn ?>">
            </div>

            <div class="col-sm-6">
              <label for="lastName" class="form-label">Last name:</label>
              <input class="form-control" id="lastName" type="text" name="last_name" placeholder="" value="<?= $ln ?>">
            </div>

            <div class="col-sm-12">
              <label for="email" class="form-label">Email:</label>
              <input id="email" class="form-control" type="text" name="email" placeholder="" value="<?= $em ?>">
            </div>

            <div class="col-sm-12">
              <label for="headline" class="form-label">Headline:</label>
              <input class="form-control" id="headline" type="text" name="headline" placeholder="" value="<?= $he ?>">
            </div>

            <div class="col-sm-12">
              <label for="summary" class="form-label">Summary:</label>
              <textarea id="summary" class="form-control" name="summary" rows="8" cols="80"><?= $su ?></textarea>
            </div>

            <div class="col-sm-2">
              <label for="addEdu" class="form-label">Education:</label>

              <input class="form-control btn btn-success btn-sm" id="addEdu" type="submit" name="add_education" value="+">
            </div>

            <div class="col-sm-12" id="edu_fields">
				<script type="text/javascript" src="js/edu.js"></script>
				<script type="text/javascript" src="js/ajax.js"></script>

				<?php
				$edu_query = "SELECT * FROM education WHERE profile_id = :pid";
				$edu_stmt = $pdo->prepare($edu_query);
				$edu_stmt->execute(array(
				':pid' => $_GET['profile_id']));
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
          <input class="form-control btn btn-success btn-sm" id="addPost" type="submit" name="addPost" value="+">
        </div>

      <div class="col-sm-12" id="position_fields">
		<script type="text/javascript" src="js/position.js"></script>
		<?php
		$str_query = "SELECT * FROM position WHERE profile_id = :pid";
		$stmt = $pdo->prepare($str_query);
		$stmt->execute(array(
		':pid' => $_GET['profile_id']));
		$i = 0;
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$year = $row['year'];
			$desc = $row['description'];
			echo "<div class='position_field'><label for='year{$i}' class='form-label'>Year:</label><input id='year{$i}' class='form-control col-6' maxlength='4' type='text' name='year{$i}' value='{$year}'><textarea class='form-control' name='desc{$i}' rows='8' cols='80'>{$desc}</textarea><button class='col-6 form-control position_remove btn btn-sm btn-danger mt-2' type='button'><span class='fas fa-trash'></span></button></div>";
			$i++;
		}
		?>
		</div>
		<div class="row justify-content-around m-auto mt-5">
			<input class="col-6 mb-3 btn btn-primary" type="submit" name="add" value="Update">
			<input class="col-6 btn btn-danger" type="submit" name="cancel" value="Cancel">
		</div>
		<br>
		</form>
      </div>
      <div class="modal-footer float-left">
        <button type="button" class="btn glass-btn-success" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn glass-btn-danger">Edit</button>
      </div>
    </div>
  </div>
</div>
<!-- Edit Modal -->

