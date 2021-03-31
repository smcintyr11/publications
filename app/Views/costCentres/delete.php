<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <form class="form-group" action="/costCentres/delete" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <div class="form-group row">
      <label for="CostCentreID" class="col-sm-2 col-form-label font-weight-bold">Cost Centre ID:</label>
      <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" name="CostCentreID" id="CostCentreID" value="<?= $costCentre['CostCentreID'] ?>">
      </div>
    </div>

    <div class="form-group row">
      <label for="CostCentre" class="col-sm-2 col-form-label font-weight-bold">Cost Centre:</label>
      <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" name="CostCentre" id="CostCentre" value="<?= $costCentre['CostCentre'] ?>">
      </div>
    </div>

    <div class="form-group row">
      <label for="Description" class="col-sm-2 col-form-label font-weight-bold">Description:</label>
      <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" name="Description" id="CostCentre" value="<?= $costCentre['Description'] ?>">
      </div>
    </div>

    <div class="form-group row">
      <label>Are you sure you wish to delete this cost centre?</label>
    </div>

    <div class="form-group row">
      <button class="btn btn-success m-1" type="submit" name="submit">Yes</button>
      <a class="btn btn-danger m-1" href="/costCentres/index/<?= $page ?>">No</a>
    </div>
  </form>
</div>
