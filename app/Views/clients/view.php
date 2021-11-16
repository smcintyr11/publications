<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">

  <div class="form-row">
    <div class="col-6">
      <a class="btn btn-info my-3" href="<?= base_url() ?>/clients/index/<?= $page ?>">Back to Clients</a>
    </div>
    <?php
      $version = "Created by " . $createdBy . " on " . $client['Created'] . "<br>";
      if (is_null($client['Modified'])) {
        $version = $version . "Not modified";
      } else {
        $version = $version . "Modified by " . $modifiedBy . " on " . $client['Modified'];
      }
      echo (MyFormGeneration::generateItalicText("Version", $version, 6, "right"));
      ?>
  </div>

  <h1><?= esc($title); ?></h1>

  <?= MyFormGeneration::generateIDTextBox("clientID",
    $client['ClientID'], "Client / Publisher ID"); ?>

  <?= MyFormGeneration::generateIDTextBox("client",
    $client['Client'], "Client / Publisher"); ?>

</div>
