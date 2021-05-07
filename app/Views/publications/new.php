<script type="text/javascript" src="/scripts/publicationNew.js"></script>

<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?php
    $errorList = \Config\Services::validation()->listErrors();
    $count = count(\Config\Services::validation()->getErrors());
    if ($count > 0) {
      echo ('<div class="alert alert-warning" role="alert">');
      echo ($errorList);
      echo ('</div>');
    }
  ?>

  <form class="form-group" action="/publications/new" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateMultilineTextBox("primaryTitle",
      set_value('primaryTitle'),
      "-- Enter the primary title --", "Primary Title", 3); ?>

    <?= MyFormGeneration::generateLookupTextBox("reportType",
      set_value('reportType'), "-- Enter a report type --", "Report Type",
      MyFormGeneration::generateNewButtonURL("reportTypes"), "reportTypeID",
      set_value('reportTypeID')); ?>

    <button class="btn btn-success m-1" type="submit" name="submit">Create Publication</button>
    <a class="btn btn-info m-1" href="/publications/index/<?= $page ?>">Back to Publications</a>
  </form>
</div>
