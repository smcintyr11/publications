<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <form class="form-group" action="/clients/delete" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <div class="form-group row">
      <label for="ClientID" class="col-sm-2 col-form-label font-weight-bold">Client ID:</label>
      <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" name="ClientID" id="ClientID" value="<?= $client['ClientID'] ?>">
      </div>
    </div>
    <div class="form-group row">
      <label for="Client" class="col-sm-2 col-form-label font-weight-bold">Client:</label>
      <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" id="Client" value="<?= $client['Client'] ?>">
      </div>
    </div>
    <div class="form-group row">
      <label>Are you sure you wish to delete this client?</label>
    </div>
    <div class="form-group row">
      <button class="btn btn-success m-1" type="submit" name="submit">Yes</button>
      <a class="btn btn-danger m-1" href="/clients/index/<?= $page ?>">No</a>
    </div>
  </form>

</div>
