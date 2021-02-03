<div class="container-lg my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="/fiscalYears/new" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="cur_sort" value="<?= $cur_sort ?>">
    <input type="hidden" name="rows" value="<?= $rows ?>">
    <input type="hidden" name="page" value="<?= $page ?>">
    <input type="hidden" name="filter" value="<?= $filter ?>">

    <label for="fiscalYear">Fiscal Year</label>
    <input class="form-control" type="input" name="fiscalYear" value="<?= set_value('fiscalYear') ?>"/><br />

    <button class="btn btn-success m-1" type="submit" name="submit">Create Fiscal Year</button>
    <a class="btn btn-info m-1" href="/fiscalYears/index/<?= $cur_sort ?>/<?= $rows ?>/<?= $page ?>/<?= $filter ?>">Back to Fiscal Years</a>
  </form>
</div>
