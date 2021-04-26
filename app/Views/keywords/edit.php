<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="/keywords/edit" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateIDTextBox("keywordID",
      $keyword['KeywordID'], "Keyword ID"); ?>

    <?= MyFormGeneration::generateTextBox("keywordEnglish",
      set_value('keywordEnglish', $keyword['KeywordEnglish']),
      "-- Enter the keyword in English --", "Keyword (English)"); ?>

    <?= MyFormGeneration::generateTextBox("keywordFrench",
      set_value('keywordFrench', $keyword['KeywordFrench']),
      "-- Enter the keyword in French --", "Keyword (French)"); ?>

    <button class="btn btn-success m-1" type="submit" name="submit">Save Keyword</button>
    <a class="btn btn-info m-1" href="/keywords/index/<?= $page ?>">Back to Keywords</a>
  </form>
</div>
