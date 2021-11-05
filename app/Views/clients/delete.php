<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <form class="form-group" action="<?= base_url() ?>/clients/delete" method="post">
    <br />
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateDRAlert($dependentRecords); ?>

    <?= MyFormGeneration::generateIDTextBox("clientID",
      $client['ClientID'], "Client / Publisher ID"); ?>

    <?= MyFormGeneration::generateIDTextBox("client",
      $client['Client'], "Client / Publisher"); ?>

    <?= MyFormGeneration::generateDeleteOptions($dependentRecords, 'clients', 'client', $page); ?>

  </form>

</div>
