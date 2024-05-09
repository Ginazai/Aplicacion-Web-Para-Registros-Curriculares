<div class="modal fade" id="view-profile-<?= $id ?>" tabindex="-1" aria-labelledby="view-profile-label-<?= $id ?>" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content glass">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="view-profile-label-<?= $id ?>"><?= $name . " " . $lastname ?>'s profile</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body glass border rounded-top-0">
      	<div class="card glass" style="background-color:transparent;">
			<ul class="list-group list-group-flush">
				<li class="list-group-item">Name: <?= $name . " " . $lastname ?></li>
				<li class="list-group-item">Email: <?= htmlentities($data['email']) ?></li>
				<li class="list-group-item">Headline: <?= htmlentities($data['headline']) ?></li>
				<li class="list-group-item">Summary: <?= htmlentities($data['summary']) ?></li>
				<li class="list-group-item list-group-item-secondary">Position:
					<ol id="position-<?= $id ?>" class="list-group list-group-numbered">
						<script type="application/javascript">
							position_data.map((elem) => {
								if(elem.profile_id == "<?= $id ?>"){
									$('#position-'+elem.profile_id).append
									("<li class='list-group-item border border-0'><b>"+ elem.year + ":</b> " + elem.description +"</li>");
								}
							});
						</script>
					</ol>
				</li>
				<li class="list-group-item list-group-item-secondary rounded-0 border-bottom-0">Education:
					<ol id="edu-<?= $id ?>" class="list-group list-group-numbered">
						<script type="application/javascript">
							edu_data.map((elem) => {
								if(elem.profile_id == "<?= $id ?>"){
									$('#edu-'+elem.profile_id).append
									("<li class='list-group-item border border-0'><b>"+ elem.year + ":</b> " + elem.name +"</li>");
								}
							});
						</script>
					</ol>
				</li>
			</ul>		
		</div>
      </div>
    </div>
  </div>
</div>
