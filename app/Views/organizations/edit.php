<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="/organizations/edit" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateIDTextBox("organizationID",
      $organization['OrganizationID'], "Organization ID"); ?>

    <?= MyFormGeneration::generateTextBox("organization",
      set_value('organization', $organization['Organization']),
      "-- Enter the organization name --", "Organization"); ?>

    <button class="btn btn-success m-1" type="submit" name="submit">Save Organization</button>
    <a class="btn btn-info m-1" href="/organizations/index/<?= $page ?>">Back to Organizations</a>
  </form>
</div>
