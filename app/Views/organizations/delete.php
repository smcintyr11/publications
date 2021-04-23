<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <form class="form-group" action="/organizations/delete" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?php
      if ($dependentRecords) {
        echo ('<div class="alert alert-danger alert-dismissible fade show" role="alert">
        There are dependent records.  You are unable to delete this record.</div>');
      }
     ?>

    <div class="form-group row">
      <label for="OrganizationID" class="col-2 col-form-label font-weight-bold">Organization ID:</label>
      <div class="col-10">
        <input type="text" readonly class="form-control-plaintext" name="OrganizationID" id="OrganizationID" value="<?= $organization['OrganizationID'] ?>">
      </div>
    </div>
    <div class="form-group row">
      <label for="Organization" class="col-2 col-form-label font-weight-bold">Organization:</label>
      <div class="col-10">
        <input type="text" readonly class="form-control-plaintext" id="Organization" value="<?= $organization['Organization'] ?>">
      </div>
    </div>
    <?php
      if ($dependentRecords) {
        echo ('<div class="form-group row">
          <a class="btn btn-info m-1" href="/organizations/index/' . $page . '">Return to Organizations</a>
          </div>');
      } else {
        echo ('    <div class="form-group row">
              <label>Are you sure you wish to delete this organization?</label>
            </div>
            <div class="form-group row">
              <button class="btn btn-success m-1" type="submit" name="submit">Yes</button>
              <a class="btn btn-danger m-1" href="/organizations/index/' . $page . '">No</a>
            </div>');
      }
     ?>
  </form>

</div>
