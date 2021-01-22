<div class="container-lg my-3 py-3">
    <h1><?= esc($title); ?></h1>

    <?= \Config\Services::validation()->listErrors(); ?>

    <form class="form-group" action="/clients/new" method="post">
      <?= csrf_field() ?>

      <input type="hidden" name="cur_sort" value="<?= $cur_sort ?>">
      <input type="hidden" name="rows" value="<?= $rows ?>">
      <input type="hidden" name="page" value="<?= $page ?>">
      <input type="hidden" name="filter" value="<?= $filter ?>">

      <label for="Client">Client</label>
      <input class="form-control" type="input" name="client" /><br />

      <button class="btn btn-success m-1" type="submit" name="submit">Create Client</button>
      <a class="btn btn-info m-1" href="/clients/index/<?= $cur_sort ?>/<?= $rows ?>/<?= $page ?>/<?= $filter ?>">Back to Clients</a>
    </form>

</div>
