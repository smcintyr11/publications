<div class="container-lg my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="/linkTypes/new" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="cur_sort" value="<?= $cur_sort ?>">
    <input type="hidden" name="rows" value="<?= $rows ?>">
    <input type="hidden" name="page" value="<?= $page ?>">
    <input type="hidden" name="filter" value="<?= $filter ?>">

    <div class="form-group row">
    <label for="linkType" class="col-sm-2 col-form-label font-weight-bold">Link Type:</label>
    <div class="col-sm-10">
      <input class="form-control" type="input" name="linkType" value="<?= set_value('linkType') ?>"/><br />
    </div>
    </div>

    <button class="btn btn-success m-1" type="submit" name="submit">Create Link Type</button>
    <a class="btn btn-info m-1" href="/linkTypes/index/<?= $cur_sort ?>/<?= $rows ?>/<?= $page ?>/<?= $filter ?>">Back to Link Types</a>
  </form>

</div>