<div class="container-lg my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="/costCentres/new" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="cur_sort" value="<?= $cur_sort ?>">
    <input type="hidden" name="rows" value="<?= $rows ?>">
    <input type="hidden" name="page" value="<?= $page ?>">
    <input type="hidden" name="filter" value="<?= $filter ?>">

    <div class="form-group row">
    <label for="costCentre" class="col-sm-2 col-form-label font-weight-bold">Cost Centre:</label>
    <div class="col-sm-10">
      <input class="form-control" type="input" name="costCentre" value="<?= set_value('costCentre') ?>"/><br />
    </div>
    </div>

    <div class="form-group row">
    <label for="description" class="col-sm-2 col-form-label font-weight-bold">Description:</label>
    <div class="col-sm-10">
      <input class="form-control" type="input" name="description" value="<?= set_value('description') ?>"/><br />
    </div>
    </div>

    <button class="btn btn-success m-1" type="submit" name="submit">Create Cost Centre</button>
    <a class="btn btn-info m-1" href="/costCentres/index/<?= $cur_sort ?>/<?= $rows ?>/<?= $page ?>/<?= $filter ?>">Back to Cost Centres</a>
  </form>
</div>
