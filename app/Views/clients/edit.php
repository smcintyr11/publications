<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="/clients/edit" method="post">
    <br />
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateIDTextBox("clientID",
      $client['ClientID'], "Client / Publisher ID"); ?>


    <?= MyFormGeneration::generateTextBox("client",
      set_value('client', $client['Client']),
      "-- Enter the client or publisher name --", "Client / Publisher"); ?>

    <button class="btn btn-success m-1" type="submit" name="submit">Save Client / Publisher</button>
    <a class="btn btn-info m-1" href="/clients/index/<?= $page ?>">Back to Clients / Publishers</a>
  </form>
</div>
