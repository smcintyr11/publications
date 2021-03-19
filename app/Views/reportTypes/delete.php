<div class="container-lg my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <form class="form-group" action="/reportTypes/delete" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <div class="form-group row">
      <label for="ReportTypeID" class="col-sm-2 col-form-label font-weight-bold">Report Type ID:</label>
      <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" name="ReportTypeID" id="ReportTypeID" value="<?= $reportType['ReportTypeID'] ?>">
      </div>
    </div>
    <div class="form-group row">
      <label for="reportType" class="col-sm-2 col-form-label font-weight-bold">Report Type:</label>
      <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" id="ReportType" value="<?= $reportType['ReportType'] ?>">        
      </div>
    </div>
    <div class="form-group row">
      <label for="Abbreviation" class="col-sm-2 col-form-label font-weight-bold">Abbreviation:</label>
      <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" id="Abbreviation" value="<?= $reportType['Abbreviation'] ?>">
      </div>
    </div>

    <div class="form-group row">
      <label>Are you sure you wish to delete this report type?</label>
    </div>
    <div class="form-group row">
      <button class="btn btn-success m-1" type="submit" name="submit">Yes</button>
      <a class="btn btn-danger m-1" href="/reportTypes/index/<?= $page ?>">No</a>
    </div>
  </form>

</div>
