<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <div class="form-row">
    <?php
      $version = "Created by " . $createdBy . " on " . $organization['Created'] . "<br>";
      if (is_null($organization['Modified'])) {
        $version = $version . "Not modified";
      } else {
        $version = $version . "Modified by " . $modifiedBy . " on " . $organization['Modified'];
      }
      echo (MyFormGeneration::generateItalicText("Version", $version, 12, "right"));
      ?>
  </div>
  
  <h1><?= esc($title); ?></h1>

  <form class="form-group" action="<?= base_url() ?>/organizations/delete" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateDRAlert($dependentRecords); ?>

    <?= MyFormGeneration::generateIDTextBox("organizationID",
      $organization['OrganizationID'], "Organization ID"); ?>

    <?= MyFormGeneration::generateIDTextBox("organization",
      $organization['Organization'], "Organization"); ?>

    <?= MyFormGeneration::generateDeleteOptions($dependentRecords, 'organizations', 'organization', $page); ?>

  </form>

</div>
