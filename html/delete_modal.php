<div class="modal fade" id="delete-modal-<?= $id ?>" tabindex="-1" aria-labelledby="delete-modal-label-<?= $id ?>" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content glass">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="delete-modal-label-<?= $id ?>">Delete Profile</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body glass rounded-0" style="background-color: transparent !important;">
        Are you sure you want to delete <b><?= $name . " " . $lastname . "'s" ?></b> profile?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn glass-btn-danger" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn glass-btn-success"><a class="text-white" href="php/delete.php?profile_id=<?= $id ?>" style="text-decoration: none !important;">Confirm</a></button>
      </div>
    </div>
  </div>
</div>