<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <div class="form-row">
    <?php
      $version = "Created by " . $createdBy . " on " . $journal['Created'] . "<br>";
      if (is_null($journal['Modified'])) {
        $version = $version . "Not modified";
      } else {
        $version = $version . "Modified by " . $modifiedBy . " on " . $journal['Modified'];
      }
      echo (MyFormGeneration::generateItalicText("Version", $version, 12, "right"));
      ?>
  </div>
  
  <h1><?= esc($title); ?></h1>

  <form class="form-group" action="<?= base_url() ?>/journals/delete" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateDRAlert($dependentRecords); ?>

    <?= MyFormGeneration::generateIDTextBox("journalID",
      $journal['JournalID'], "Journal ID"); ?>

    <?= MyFormGeneration::generateIDTextBox("journal",
      $journal['Journal'], "Journal"); ?>

    <?= MyFormGeneration::generateDeleteOptions($dependentRecords, 'journals', 'journal', $page); ?>

  </form>

</div>
