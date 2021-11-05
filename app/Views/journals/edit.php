<script type="text/javascript" src="<?= base_url() ?>/scripts/unique.js"></script>

<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="<?= base_url() ?>/journals/edit" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateIDTextBox("journalID",
      $journal['JournalID'], "Journal ID"); ?>

    <?= MyFormGeneration::generateTextBox("journal",
      set_value('journal', $journal['Journal']),
      "-- Enter the journal name --", "Journal"); ?>

    <button class="btn btn-success m-1" type="submit" name="submit">Save Journal</button>
    <a class="btn btn-info m-1" href="<?= base_url() ?>/journals/index/<?= $page ?>">Back to Journals</a>
  </form>
</div>


<script type="text/javascript">
$(document).ready(function(){
  // Add uniqueness checking to the link type
  $("#journal").change(function(){uniqueCheck("<?= base_url() ?>/journals/uniqueCheck", "#journal", <?= $journal['JournalID'] ?>, "<?= $journal['Journal'] ?>");});
});
</script>
