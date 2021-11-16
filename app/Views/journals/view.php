<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <div class="form-row">
    <div class="col-6">
      <a class="btn btn-info my-3" href="<?= base_url() ?>/journals/index/<?= $page ?>">Back to Journals</a>
    </div>
    <?php
      $version = "Created by " . $createdBy . " on " . $journal['Created'] . "<br>";
      if (is_null($client['Modified'])) {
        $version = $version . "Not modified";
      } else {
        $version = $version . "Modified by " . $modifiedBy . " on " . $journal['Modified'];
      }
      echo (MyFormGeneration::generateItalicText("Version", $version, 6, "right"));
      ?>
  </div>
  
  <h1><?= esc($title); ?></h1>

  <?= MyFormGeneration::generateIDTextBox("journalID",
    $journal['JournalID'], "Journal ID"); ?>

  <?= MyFormGeneration::generateIDTextBox("journal",
    $journal['Journal'], "Journal"); ?>

</div>
