<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="<?= base_url() ?>/keywords/new" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateTextBox("keywordEnglish",
      set_value('keywordEnglish'), "-- Enter the keyword in English --", "Keyword (English)"); ?>

    <?= MyFormGeneration::generateTextBox("keywordFrench",
      set_value('keywordFrench'), "-- Enter the keyword in French --", "Keyword (French)"); ?>

    <button class="btn btn-success m-1" type="submit" name="submit">Create Keyword</button>
    <a class="btn btn-info m-1" href="<?= base_url() ?>/keywords/index/<?= $page ?>">Back to Keywords</a>
  </form>

</div>
