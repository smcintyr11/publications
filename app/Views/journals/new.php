<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="/journals/new" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateTextBox("journal",
      set_value('journal'), "-- Enter the journal name --", "Journal"); ?>

    <button class="btn btn-success m-1" type="submit" name="submit">Create Journal</button>
    <a class="btn btn-info m-1" href="/journals/index/<?= $page ?>">Back to Journals</a>
  </form>

</div>
