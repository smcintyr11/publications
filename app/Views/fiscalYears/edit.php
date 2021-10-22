<script type="text/javascript" src="/scripts/unique.js"></script>
<script type="text/javascript" src="/scripts/fiscalYearsEdit.js"></script>

<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="/fiscalYears/edit" method="post"  id="frmEditFiscalYear">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateIDTextBox("fiscalYearID",
      $fiscalYear['FiscalYearID'], "Fiscal Year ID"); ?>

    <?= MyFormGeneration::generateTextBox("fiscalYear",
      set_value('fiscalYear', $fiscalYear['FiscalYear']),
      "-- Enter the fiscal year (e.g. 2021 / 2022) --", "Fiscal Year"); ?>

    <button class="btn btn-success m-1" type="submit" name="submit">Save Fiscal Year</button>
    <a class="btn btn-info m-1" href="/fiscalYears/index/<?= $page ?>">Back to Fiscal Years</a>
  </form>
</div>

<script type="text/javascript">
$(document).ready(function(){
  // Add uniqueness checking to the link type
  $("#fiscalYear").change(function(){uniqueCheck("/fiscalYears/uniqueCheck", "#fiscalYear", <?= $fiscalYear['FiscalYearID'] ?>, "<?= $fiscalYear['FiscalYear'] ?>");});
});
</script>
