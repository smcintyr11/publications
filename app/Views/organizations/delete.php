<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <form class="form-group" action="/organizations/delete" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateDRAlert($dependentRecords); ?>

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

    <?= MyFormGeneration::generateDeleteOptions($dependentRecords, 'organizations', 'organization', $page); ?>
    
  </form>

</div>
