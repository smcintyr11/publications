<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <div class="form-row">
    <div class="col-6">
      <a class="btn btn-info my-3" href="<?= base_url() ?>/keywords/index/<?= $page ?>">Back to Keywords</a>
    </div>
    <?php
      $version = "Created by " . $createdBy . " on " . $keyword['Created'] . "<br>";
      if (is_null($keyword['Modified'])) {
        $version = $version . "Not modified";
      } else {
        $version = $version . "Modified by " . $modifiedBy . " on " . $keyword['Modified'];
      }
      echo (MyFormGeneration::generateItalicText("Version", $version, 6, "right"));
      ?>
  </div>

  <h1><?= esc($title); ?></h1>

  <?= MyFormGeneration::generateIDTextBox("keywordID",
    $keyword['KeywordID'], "Keyword ID"); ?>

  <?= MyFormGeneration::generateIDTextBox("keywordEnglish",
    $keyword['KeywordEnglish'], "Keyword (English)"); ?>

  <?= MyFormGeneration::generateIDTextBox("keywordFrench",
    $keyword['KeywordFrench'], "Keyword (French)"); ?>

</div>
