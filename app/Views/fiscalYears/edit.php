<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="/fiscalYears/edit" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <div class="form-group row">
      <label for="fiscalYearID" class="col-2 col-form-label font-weight-bold">Fiscal Year ID:</label>
      <div class="col-10">
        <input type="text" readonly class="form-control-plaintext" name="fiscalYearID" id="fiscalYearID" value="<?= $fiscalYear['FiscalYearID'] ?>">
      </div>
    </div>

    <div class="form-group row">
      <label for="fiscalYear" class="col-2 col-form-label font-weight-bold">Fiscal Year:</label>
      <div class="col-10">
        <input class="form-control" type="input" name="fiscalYear" id="fiscalYear" value="<?= set_value('fiscalYear', $fiscalYear['FiscalYear']) ?>"/><br />
      </div>
    </div>

    <button class="btn btn-success m-1" type="submit" name="submit">Save Fiscal Year</button>
    <a class="btn btn-info m-1" href="/fiscalYears/index/<?= $page ?>">Back to Fiscal Years</a>
  </form>
</div>
