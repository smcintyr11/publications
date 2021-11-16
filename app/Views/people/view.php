<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <div class="form-row">
    <div class="col-6">
      <a class="btn btn-info my-3" href="<?= base_url() ?>/people/index/<?= $page ?>">Back to People</a>
    </div>
    <?php
      $version = "Created by " . $createdBy . " on " . $person['Created'] . "<br>";
      if (is_null($person['Modified'])) {
        $version = $version . "Not modified";
      } else {
        $version = $version . "Modified by " . $modifiedBy . " on " . $person['Modified'];
      }
      echo (MyFormGeneration::generateItalicText("Version", $version, 6, "right"));
      ?>
  </div>

  <h1><?= esc($title); ?></h1>

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

</div>
