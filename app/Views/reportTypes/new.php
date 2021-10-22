<script type="text/javascript" src="/scripts/unique.js"></script>

<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="/reportTypes/new" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateTextBox("reportType",
      set_value('reportType'), "-- Enter the report type --", "Report Type"); ?>

    <?= MyFormGeneration::generateTextBox("abbreviation",
      set_value('abbreviation'), "-- Enter the abbreviation for the report type (e.g. JJ) --", "Abbreviation"); ?>

    <button class="btn btn-success m-1" type="submit" name="submit">Create Report Type</button>
    <a class="btn btn-info m-1" href="/reportTypes/index/<?= $page ?>">Back to Report Types</a>
  </form>

</div>

<script type="text/javascript">
$(document).ready(function(){
  // Add uniqueness checking to the link type
  $("#reportType").change(function(){uniqueCheck("/reportTypes/uniqueCheckRT", "#reportType", null);});
  $("#abbreviation").change(function(){uniqueCheck("/reportTypes/uniqueCheckAB", "#abbreviation", null);});
});
</script>
