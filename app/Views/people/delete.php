<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <div class="form-row">
    <?php
      $version = "Created by " . $createdBy . " on " . $person['Created'] . "<br>";
      if (is_null($person['Modified'])) {
        $version = $version . "Not modified";
      } else {
        $version = $version . "Modified by " . $modifiedBy . " on " . $person['Modified'];
      }
      echo (MyFormGeneration::generateItalicText("Version", $version, 12, "right"));
      ?>
  </div>
  
  <h1><?= esc($title); ?></h1>

  <form class="form-group" action="<?= base_url() ?>/people/delete" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateDRAlert($dependentRecords); ?>

    <?= MyFormGeneration::generateIDTextBox("personID",
      $person['PersonID'], "Person ID"); ?>

    <?= MyFormGeneration::generateIDTextBox("displayName",
      $person['DisplayName'], "Display Name"); ?>

    <?= MyFormGeneration::generateIDTextBox("firstName",
      $person['FirstName'], "First Name"); ?>

    <?= MyFormGeneration::generateIDTextBox("lastName",
      $person['LastName'], "Last Name"); ?>

    <?= MyFormGeneration::generateIDTextBox("organization",
      $person['Organization'], "Organization"); ?>

    <?= MyFormGeneration::generateDeleteOptions($dependentRecords, 'people', 'person', $page); ?>

  </form>
</div>
