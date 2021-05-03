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

    <div class="form-group row">
      <label for="primaryTitle" class="col-2 col-form-label font-weight-bold">Primary Title:</label>
      <div class="col-10">
        <input class="form-control" type="input" name="primaryTitle" value="<?= set_value('primaryTitle') ?>"/><br />
      </div>
    </div>
    <div class="form-group row">
      <label for="reportTypeID" class="col-2 col-form-label font-weight-bold">Report Type:</label>
      <div class="col-10">
        <select class="form-control" id="reportTypeID" name="reportTypeID" value="<?= set_value('reportTypeID') ?>">
          <option value=''>-- Select a report type --</option>
          <?php
            foreach ($reportTypes as $reportType) {
              echo ('<option value="' . $reportType->ReportTypeID . '"');
              if ($reportType->ReportTypeID == set_value('reportTypeID')) {
                echo (' selected="selected"');
              }
              echo('>' . $reportType->ReportType . '</option>');
            }
          ?>
        </select><br />
      </div>
    </div>    

    <button class="btn btn-success m-1" type="submit" name="submit">Create Publication</button>
    <a class="btn btn-info m-1" href="/publications/index/<?= $page ?>">Back to Publications</a>
  </form>
</div>
