<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <form class="form-group" action="/journals/delete" method="post">
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
