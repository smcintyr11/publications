<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="/statuses/new" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateTextBox("status",
      set_value('status'), "-- Enter the status --", "Status"); ?>

    <?= MyFormGeneration::generateNumberTextBox("expectedDuration",
      set_value('expectedDuration'), "-- Enter the expected duration in days --", "Expected Duration"); ?>

    <button class="btn btn-success m-1" type="submit" name="submit">Create Status</button>
    <a class="btn btn-info m-1" href="/statuses/index/<?= $page ?>">Back to Statuses</a>
  </form>

</div>
