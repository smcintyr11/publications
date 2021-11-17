<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <div class="form-row">
    <?php
      $version = "Created by " . $createdBy . " on " . $keyword['Created'] . "<br>";
      if (is_null($keyword['Modified'])) {
        $version = $version . "Not modified";
      } else {
        $version = $version . "Modified by " . $modifiedBy . " on " . $keyword['Modified'];
      }
      echo (MyFormGeneration::generateItalicText("Version", $version, 12, "right"));
      ?>
  </div>

  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="<?= base_url() ?>/keywords/edit" method="post">
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
    <a class="btn btn-info m-1" href="<?= base_url() ?>/keywords/index/<?= $page ?>">Back to Keywords</a>
  </form>
</div>
