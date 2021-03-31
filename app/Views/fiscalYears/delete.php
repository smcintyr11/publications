<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <form class="form-group" action="/fiscalYears/delete" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <div class="form-group row">
      <label for="FiscalYearID" class="col-sm-2 col-form-label font-weight-bold">Fiscal Year ID:</label>
      <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" name="FiscalYearID" id="FiscalYearID" value="<?= $fiscalYear['FiscalYearID'] ?>">
      </div>
    </div>

    <div class="form-group row">
      <label for="FiscalYear" class="col-sm-2 col-form-label font-weight-bold">Fiscal Year:</label>
      <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" name="FiscalYear" id="FiscalYear" value="<?= $fiscalYear['FiscalYear'] ?>">
      </div>
    </div>

    <div class="form-group row">
      <label>Are you sure you wish to delete this fiscal year?</label>
    </div>

    <div class="form-group row">
      <button class="btn btn-success m-1" type="submit" name="submit">Yes</button>
      <a class="btn btn-danger m-1" href="/fiscalYears/index/<?= $page ?>">No</a>
    </div>
  </form>
</div>
