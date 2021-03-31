<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="/reportTypes/edit" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <div class="form-group row">
      <label for="reportTypeID" class="col-2 col-form-label font-weight-bold">Report Type ID:</label>
      <div class="col-10">
        <input type="text" readonly class="form-control-plaintext" name="reportTypeID" id="reportTypeID" value="<?= $reportType['ReportTypeID'] ?>">
      </div>
    </div>
    <div class="form-group row">
      <label for="reportType" class="col-2 col-form-label font-weight-bold">Report Type:</label>
      <div class="col-10">
        <input class="form-control" type="input" name="reportType" value="<?= set_value('reportType', $reportType['ReportType']) ?>"/><br />
      </div>
    </div>
    <div class="form-group row">
      <label for="abbreviation" class="col-2 col-form-label font-weight-bold">Abbreviation:</label>
      <div class="col-10">
        <input class="form-control" type="input" name="abbreviation" value="<?= set_value('abbreviation', $reportType['Abbreviation']) ?>"/><br />
      </div>
    </div>

    <button class="btn btn-success m-1" type="submit" name="submit">Save Report Type</button>
    <a class="btn btn-info m-1" href="/reportTypes/index/<?= $page ?>">Back to Report Types</a>
  </form>
</div>
