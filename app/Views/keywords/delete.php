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

  <form class="form-group" action="<?= base_url() ?>/keywords/delete" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateDRAlert($dependentRecords); ?>

    <?= MyFormGeneration::generateIDTextBox("keywordID",
      $keyword['KeywordID'], "Keyword ID"); ?>

    <?= MyFormGeneration::generateIDTextBox("keywordEnglish",
      $keyword['KeywordEnglish'], "Keyword (English)"); ?>

    <?= MyFormGeneration::generateIDTextBox("keywordFrench",
      $keyword['KeywordFrench'], "Keyword (French)"); ?>

    <?= MyFormGeneration::generateDeleteOptions($dependentRecords, 'keywords', 'keyword', $page); ?>

  </form>

</div>
