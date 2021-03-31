<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="/costCentres/edit" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <div class="form-group row">
      <label for="costCentreID" class="col-sm-2 col-form-label font-weight-bold">Cost Centre ID:</label>
      <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" name="costCentreID" id="costCentreID" value="<?= $costCentre['CostCentreID'] ?>">
      </div>
    </div>

    <div class="form-group row">
      <label for="costCentre" class="col-sm-2 col-form-label font-weight-bold">Cost Centre:</label>
      <div class="col-sm-10">
        <input class="form-control" type="input" name="costCentre" id="costCentre" value="<?= set_value('costCentre', $costCentre['CostCentre']) ?>"/><br />
      </div>
    </div>

    <div class="form-group row">
      <label for="description" class="col-sm-2 col-form-label font-weight-bold">Description:</label>
      <div class="col-sm-10">
        <input class="form-control" type="input" name="description" id="description" value="<?= set_value('description', $costCentre['Description']) ?>"/><br />
      </div>
    </div>

    <button class="btn btn-success m-1" type="submit" name="submit">Save Cost Centre</button>
    <a class="btn btn-info m-1" href="/costCentres/index/<?= $page ?>">Back to Cost Centres</a>
  </form>
</div>
