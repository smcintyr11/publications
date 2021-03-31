<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="/clients/edit" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <div class="form-group row">
      <label for="clientID" class="col-2 col-form-label font-weight-bold">Client ID:</label>
      <div class="col-10">
        <input type="text" readonly class="form-control-plaintext" name="clientID" id="clientID" value="<?= $client['ClientID'] ?>">
      </div>
    </div>

    <div class="form-group row">
      <label for="client" class="col-2 col-form-label font-weight-bold">Client:</label>
      <div class="col-10">
        <input class="form-control" type="input" name="client" id="client" value="<?= set_value('client', $client['Client']) ?>"/><br />
      </div>
    </div>

    <button class="btn btn-success m-1" type="submit" name="submit">Save Client</button>
    <a class="btn btn-info m-1" href="/clients/index/<?= $page ?>">Back to Clients</a>
  </form>
</div>
