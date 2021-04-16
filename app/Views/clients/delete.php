<?php
  use App\Libraries\MyFormGeneration;
 ?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <form class="form-group" action="/clients/delete" method="post">
    <br />
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateIDTextBox("clientID",
      $client['ClientID'], "Client / Publisher ID"); ?>

    <?= MyFormGeneration::generateIDTextBox("client",
      $client['Client'], "Client / Publisher"); ?>

    <div class="form-group row">
      <label>Are you sure you wish to delete this client?</label>
    </div>
    <div class="form-group row">
      <button class="btn btn-success m-1" type="submit" name="submit">Yes</button>
      <a class="btn btn-danger m-1" href="/clients/index/<?= $page ?>">No</a>
    </div>
  </form>

</div>
