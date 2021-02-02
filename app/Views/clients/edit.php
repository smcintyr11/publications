<div class="container-lg my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="/clients/edit" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="cur_sort" value="<?= $cur_sort ?>">
    <input type="hidden" name="rows" value="<?= $rows ?>">
    <input type="hidden" name="page" value="<?= $page ?>">
    <input type="hidden" name="filter" value="<?= $filter ?>">

    <div class="form-group row">
      <label for="clientID" class="col-sm-2 col-form-label font-weight-bold">Client ID:</label>
      <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" name="clientID" id="clientID" value="<?= $client['ClientID'] ?>">
      </div>
    </div>

    <label for="client">Client</label>
    <input class="form-control" type="input" name="client" id="client" value="<?= set_value('client', $client['Client']) ?>"/><br />

    <button class="btn btn-success m-1" type="submit" name="submit">Save Client</button>
    <a class="btn btn-info m-1" href="/clients/index/<?= $cur_sort ?>/<?= $rows ?>/<?= $page ?>/<?= $filter ?>">Back to Clients</a>
  </form>
</div>
